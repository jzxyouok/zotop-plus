<?php

namespace Modules\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Traits\ModuleConfig;

class ConfigController extends AdminController
{
    use ModuleConfig;

    /**
     * 基本配置
     *
     * @return Response
     */
    public function base(Request $request)
    {
        // 保存数据
        if ( $request->isMethod('POST') ) {

            // 表单验证
            $this->validate($request, [
                'name' => 'required',
                'url'  => 'url'
            ],[],[
                'name' => trans('site::config.name.label'),
                'url'  => trans('site::config.url.label')
            ]);

            // 写入配置组
            $this->save('module.site', $request->all());

            return $this->success(trans('core::master.saved'),$request->referer());
        }

        $this->title = trans('site::config.base');

        return $this->view();
    }

    /**
     * 搜索优化
     *
     * @return Response
     */
    public function seo(Request $request)
    {
        // 保存数据
        if ( $request->isMethod('POST') ) {

            // 写入配置组
            $this->save('module.site', $request->all());

            return $this->success(trans('core::master.saved'));
        }

        $this->title = trans('site::config.seo');

        return $this->view();
    }
     
}