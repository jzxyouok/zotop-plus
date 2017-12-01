<?php
use Illuminate\Routing\Router;

// Media 模块后台路由
$router->group(['prefix' =>'media','module'=>'media'], function (Router $router) {
    
    // 首页
    $router->get('/{folder_id?}', 'MediaController@index')->name('media.index')->middleware('allow:media.index');

    // 文件夹
    $router->group(['prefix' =>'folder'], function (Router $router) {
        $router->any('create/{parent_id?}/{method?}','FolderController@create')->name('media.folder.create')->middleware('allow:media.folder.create');
        $router->any('rename{$id}','FolderController@rename')->name('media.folder.rename')->middleware('allow:media.folder.rename');
        $router->any('edit/{$id}','FolderController@edit')->name('media.folder.edit')->middleware('allow:media.folder.edit');
        $router->any('delete/{$id}','FolderController@delete')->name('media.folder.delete')->middleware('allow:media.folder.delete');
    });

    // 文件管理
    $router->group(['prefix' =>'file'], function (Router $router) {
        $router->any('editor','FileController@editor')->name('media.file.editor')->middleware('allow:media.file.editor');
        $router->any('create','FileController@create')->name('media.file.create')->middleware('allow:media.file.create');
        $router->any('delete','FileController@delete')->name('media.file.delete')->middleware('allow:media.file.delete');
        $router->any('copy','FileController@copy')->name('media.file.copy')->middleware('allow:media.file.copy');
        $router->any('rename','FileController@rename')->name('media.file.rename')->middleware('allow:media.file.rename');
        $router->any('upload/{type?}','FileController@upload')->name('media.file.upload')->middleware('allow:media.file.upload');
    });


});