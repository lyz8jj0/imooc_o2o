<?php
namespace app\admin\controller;

use think\Controller;
use think\Validate;

class Featured extends Controller
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model("Featured");
    }

    public function index()
    {
        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {
            //入库的逻辑
            $data = input('post.');
            $validate = validate('Featured');
            $result = $validate->scene('add')->check($data);
            if (!$result) {
                $this->error($validate->getError());
            }
            //数据需要进行严格校验validate
            $id = model("Featured")->add($data);
            if ($id) {
                $this->success('添加成功');
            } else {
                $this->error('添加失败');
            }
        } else {
            //获取推荐位类别
            $types = config('featured.featured_type');
            return $this->fetch('', [
                'types' => $types
            ]);
        }
    }
}

