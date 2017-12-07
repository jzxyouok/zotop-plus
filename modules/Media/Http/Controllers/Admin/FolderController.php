<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Media\Models\Folder;

class FolderController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('media::media.title');
        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create(Request $request, $parent_id=0)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            $folder = new Folder;
            $folder->fill($request->all());
            $folder->parent_id = $parent_id;
            $folder->save();

            return $this->success(trans('core::master.created'), $request->referer());       
        }

        $this->title = trans('media::media.create');
        $this->folder = Folder::findOrNew(0);

        return $this->view();
    }

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit(Request $request, $id)
    {      
        // 保存数据
        if ($request->isMethod('POST')) {

            $folder = Folder::findOrFail($id);
            $folder->fill($request->all());
            $folder->save();

            return $this->success(trans('core::master.updated'), $request->referer());       
        }

        $this->title = trans('media::media.edit');
        $this->id    = $id;
        $this->folder = Folder::findOrFail($id);

        return $this->view();
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function delete(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);

        if ($folder->delete()) {
            return $this->success(trans('core::master.deleted'), $request->referer());
        }

        return $this->error($folder->error);  
    }
}
