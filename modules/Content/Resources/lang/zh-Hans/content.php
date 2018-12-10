<?php

return [
    'title'              => '内容',
    'description'        => '内容管理',
    'root'               => '内容管理',
    'position'           => '位置',

    'create'             => '新建',
    'create.model'       => '新建:0',
    'edit'               => '编辑',
    'edit.model'         => '编辑:0',
    'duplicate'          => '复制',
    'duplicate.model'    => '复制:0',   
    'children'           => '下级',
    'stick'              => '置顶',
    'unstick'            => '取消置顶',
    'destroy'            => '永久删除',
    'view'               => '查看',
    'preview'            => '预览',
    'sort'               => '排序',
    'up'                 => '上级',
    
    'delete.confirm'     => '您确定要 永久删除 嘛？',
    'delete.notempty'    => '该内容下面尚有子内容，禁止删除',
    'move.help'          => '移动内容到此处',
    'move.unchange'      => '[ :0 ] 已经在当前位置',
    'move.forbidden'     => '不能将 [ :0 ] 移动到自身或其子内容下',   
    'sort.help'          => '调整 [ :0 ] 排列到选中的内容之前',
    
    'title.label'        => '标题',
    'user.label'         => '编辑',

    'slug.label'         => '别名',
    'slug.help'          => '',
    'slug.regex'         => '只允许英文（小写）字母、数字和中线（-），且必须以英文或者数字开头结尾',

    'status.label'       => '状态',
    'status.publish'     => '发布',
    'status.draft'       => '草稿',
    'status.pending'     => '待审核',
    'status.fail'        => '未通过审核',
    'status.trash'       => '回收站',
    'status.future'      => '定时发布',

    'status.future.help' => '设置定时发布的时间',

    'updated_at.label'   => '最后修改',
    'created_at.label'   => '创建时间',
    'publish_at.label'   => '发布时间',
];
