<?php

namespace Modules\Core\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Route;
use Module;
use Theme;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * app实例
     * 
     * @var mixed|\Illuminate\Foundation\Application
     */    
    protected $app;

    /**
     * 主题名称
     * 
     * @var string
     */
    protected $theme;

    /**
     * 本地语言
     * 
     * @var string
     */    
    protected $locale;

    /**
     * view 实例
     * 
     * @var object
     */
    protected $view;

    /**
     * view 数据
     * 
     * @var array
     */
    protected $viewData = [];


    /**
     * 基础控制器，所有的控制器都要继承自此控制器
     */
    public function __construct()
    {
        // 初始化
        $this->__init();

        // 解析当前路由数据
        $this->currentRouteAction();

        // 注册主题和模块view
        $this->registerThemeViews();

        // 注册模块命名空间view
        $this->registerNamespaces();

        // 设置当前语言
        $this->setLocaleLanguage();        
    }

    // 初始化
    protected function __init()
    {
        // app实例
        $this->app = app();

        // view实例
        $this->view   = $this->app->make('view');
        
        // 默认主题
        $this->theme  = 'default';
        
        // 默认语言 中文简体
        $this->locale = 'zh-Hans';
    }

    /**
     * 解析当前路由数据
     * 
     * @return void
     */
    protected function currentRouteAction()
    {
        // 获取当前路由数据
        $action = Route::getCurrentRoute()->getAction();

        // 从路由信息中获取模块名称
        $this->app->singleton('current.module',function() use ($action) {
            return is_array($action['module']) ? end($action['module']) : $action['module'];
        });

        // 从路由信息中获取动作类型，如： admin,api,front
        $this->app->singleton('current.type',function() use ($action) {
            return is_array($action['type']) ? end($action['type']) : $action['type'];
        });

        // 从路由中获取控制器    
        $this->app->singleton('current.controller',function() use ($action) {
            return strtolower(substr($action['controller'], strrpos($action['controller'], "\\") + 1, strrpos($action['controller'], "@")-strlen($action['controller'])-10));
        });

        // 从路由信息中获取模块名称
        $this->app->singleton('current.action',function() use ($action) {
            return strtolower(substr($action['controller'], strpos($action['controller'], "@") + 1));
        });                        

        //$this->view->share('current', $this->current);
    }

    /**
     * 注册主题模板和模块的views，实现在主题和模块中寻址
     * 
     * @return void
     */
    protected function registerThemeViews()
    {

        // 注册主题模板，实现view在主题中寻址
        $this->app->singleton('current.theme',function() {
            return Theme::active($this->theme);
        });  

        $this->view->addLocation($this->app['current.theme']->path.'/views/'.strtolower($this->app['current.module']));


        // 注册当前模块的views，实现view在模块中寻址
        $modulePath = Module::getModulePath($this->app['current.module']);


        $this->view->addLocation(Module::getModulePath($this->app['current.module']) . '/Resources/views/'.strtolower($this->app['current.type']));
         
    }

    /**
     * 注册资源命名空间，按照命名空间寻址
     * 
     * @return void
     */
    protected function registerNamespaces()
    {
        foreach (Module::getOrdered() as $module) {

            // 模型名称和路径
            $name = $module->getLowerName();
            $path = $module->getPath();

            // 注册命名空间
            $this->view->addNamespace($name, [$this->app['current.theme']->path.'/views/'.$name, $path . '/Resources/views/'.$this->app['current.type']]);
        }
    }

    /**
     * 设置本地语言
     * 
     * @return void 
     */
    protected function setLocaleLanguage()
    {
        
        if ( $this->locale ) {

            // 设置默认语言       
            app()->setLocale($this->locale);
        }
    }


    /**
     * 传入参数, 支持链式
     * 
     * @param  string|array $key 参数名
     * @param  mixed $value 参数值
     * @return $this
     */
    public function with($key, $value=null)
    {
        if (is_array($key)) {
            $this->viewData = array_merge($this->viewData, $key);
        } else {
            $this->viewData[$key] = $value;
        }

        return $this;
    }

    /**
     * 模板变量赋值，魔术方法
     *
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function __set($key, $value)
    {
        $this->viewData[$key] = $value;
    }

    /**
     * 取得模板显示变量的值
     * 
     * @access protected
     * @param string $name 模板显示变量
     * @return mixed
     */
    public function __get($key)
    {
        return $this->viewData[$key];
    }

    /**
     * 显示View
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view($view = null, $data = [], $mergeData = [])
    {
        if ( empty($view) ) {

            // 默认view为: controller/action
            $view = $this->app['current.controller'].'.'.$this->app['current.action'];
        }

        // 转换模板数据
        $data = ($data instanceof Arrayable) ? $data->toArray() : $data;

        // 合并模板数据
        $data = array_merge($this->viewData, $data);

        // 生成 view
        return $this->view->make($view, $data, $mergeData);
    }


    /**
     * 消息提示
     * 
     * @param  array  $msg 消息内容
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function message(array $msg)
    {
        //如果请求为ajax，则输出json数据
        if ( \Request::expectsJson() )
        {
            return response()->json($msg);
        }
        
        // 返回view
        return $this->with($msg)->view('core::msg');  
    }

    /**
     * 消息提示：success
     * 
     * @param  mixed  $msg  消息内容
     * @param  string  $url  跳转路径
     * @param  integer $time 跳转或者消息提示时间
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function success($msg, $url='', $time=2)
    {
        $msg = is_array($msg) ? $msg : ['content'=>$msg];

        $msg = array_merge($msg, [
            'state' => true,
            'type'  => 'success',
            'url'   => $url,
            'time'  => $time
        ]);

        return $this->message($msg);
    }


    /**
     * 消息提示：error
     * 
     * @param  mixed  $msg  消息内容
     * @param  integer $time 跳转或者消息提示时间
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function error($msg, $time=5)
    {
        $msg = is_array($msg) ? $msg : ['content'=>$msg];

        $msg = array_merge($msg, [
            'state' => false,
            'type'  => 'error',
            'time'  => $time
        ]);

        return $this->message($msg);
    }
}