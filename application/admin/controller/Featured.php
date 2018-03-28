<?php
namespace app\admin\controller;

use think\Controller;
use think\Validate;

class Featured extends Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model("Featured");
    }

    public function index()
    {
        //获取推荐位类别
        $types = config('featured.featured_type');
        $type = input('get.type', 0, 'intval');
        //获取列表数
        $results = $this->obj->getFeaturedByType($type);
        return $this->fetch('', [
            'types' => $types,
            'results' => $results,
            'type' => $type
        ]);
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

//    public function status()
//    {
//        //获取值
//        $data = input('get.');
//        //tp5 validate 做严格验证
//        $validate = Validate('Featured');
//        if (!$validate->scene('status')->check($data)) {
//            $this->error($validate->getError());
//        }
//        $res = $this->obj->save(['status' => $data['status']], ['id' => $data['id']]);
//        if ($res) {
//            $this->success('更新成功');
//        } else {
//            $this->error('更新失败');
//        }
//    }
}
