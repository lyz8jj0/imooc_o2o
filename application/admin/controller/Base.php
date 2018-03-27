<?php
/**
 * Created by PhpStorm.
 * User: lixinyu
 * Date: 2018/3/27
 * Time: 下午1:52
 */
namespace app\admin\controller;

use think\Controller;

class Base extends Controller
{
    /**
     * 公共的修改状态
     */
    public function status()
    {
        //获取值
        $data = input('get.');
        //做严格验证
        if (empty($data['id'])) {
            $this->error('id不合法');
        }
        if (!is_numeric($data['status'])) {
            $this->error('status不合法');
        }
        $model = request()->controller();
        $res = model($model)->save(['status' => $data['status']], ['id' => $data['id']]);
        if ($res) {
            $this->success('更新成功');
        } else {
            $this->error('更新失败');
        }
    }
}
