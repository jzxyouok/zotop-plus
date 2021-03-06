<?php
/**
 * 全局导航
 */
\Filter::listen('global.navbar', function($navbar){
    
    // 站点名称
    $navbar['core.sitename'] = [
        'text'   => config('site.name'),
        'href'   => route('site.config.base'),
        'class'  => 'sitename',
        'active' => Route::is('site.*')
    ];

    return $navbar;
}, 0);

/**
 * 快捷方式
 */
\Filter::listen('global.start', function($navbar) {
  
    //站点设置
    $navbar['config-site'] = [
        'text' => trans('site::config.title'),
        'href' => route('site.config.base'),
        'icon' => 'fa fa-cog bg-success text-white',
        'tips' => trans('site::config.description'),
    ];

    return $navbar;

}, 99);

/**
 * 全局工具
 */
\Filter::listen('global.tools', function($tools) {
        
    // 网站首页
    $tools['viewsite'] = [
        'icon'   => 'fa fa-home',
        'text'   => trans('site::site.view'),
        'title'  => trans('site::site.view.tips', [config('site.name')]),
        'href'   => config('site.url') ?: route('index'),
        'target' => '_blank',
    ];

    return $tools;
}, 1);

/**
 * 全局工具
 */
\Filter::listen('module.manage', function($manage, $module) {
    
    if (strtolower($module->name) == 'site') {
        $manage['site_config'] = [
            'text'  => trans('site::config.title'),
            'href'  => route('site.config.base'),
            'icon'  => 'fa fa-cog',
            'class' => '',
        ];
    }
    
    return $manage;         

}, 1);

/**
 * 模板选择器
 */
\Form::macro('template', function($attrs) {
    $value  = $this->getValue($attrs);
    $name   = $this->getAttribute($attrs, 'name');
    $id     = $this->getIdAttribute($name, $attrs);
    $button = $this->getAttribute($attrs, 'button', trans('site::field.template.select'));
    $select = route('site.view.select', [
        'theme'  => config('site.theme'),
        'module' => $this->getAttribute($attrs, 'module', app('current.module')),
    ]);


    return $this->toHtmlString(
        $this->view->make('site::field.template')->with(compact('id', 'name', 'value', 'button', 'select', 'attrs'))->render()
    );
});

