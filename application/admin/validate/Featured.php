<?php
namespace app\admin\validate;

use think\Validate;

class Featured extends Validate
{
    protected $rule = [
        ['title', 'require', '标题必须存在'],
        ['image', 'require', '图片必须存在'],
        ['url', 'require', 'url必须存在'],
        ['description', 'require', '描述必须存在'],
        ['status', 'number|in:-1,0,1', '状态必须是数字|状态范围不合法'],
        ['id', 'number'],
    ];
    /**场景设置**/
    protected $scene = [
        'add' => ['title', 'image', 'url', 'description',],
        'status' => ['status', 'id']
    ];
}
