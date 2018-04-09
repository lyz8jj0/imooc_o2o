<?php
namespace app\admin\controller;

use think\Controller;
use think\Exception;

class Store extends Controller
{
    public function _initialize()
    {
        $this->obj = model('BisLocation');
    }

    /**
     * 门店列表
     *
     * @return mixed
     */
    public function index()
    {
        $store = $this->obj->getStoreByStatus(1);
        return $this->fetch('', [
            'store' => $store
        ]);
    }

    /**
     * 门店入驻申请
     *
     * @return mixed
     */
    public function apply()
    {
        $store = $this->obj->getStoreByStatus();
        return $this->fetch('', [
            'store' => $store
        ]);
    }

    public function dellist()
    {
        $store = $this->obj->getStoreByStatus(-1);
        return $this->fetch('', [
            'store' => $store
        ]);
    }

    /**
     * 修改状态
     */
    public function status()
    {
        $data = input('get.');
        $res = $this->obj->save(['status' => $data['status']], ['id' => $data['id']]);
        if ($res) {
            $this->success('状态更新成功');
        } else {
            $this->error('状态更新失败');
        }
    }

    /**
     * 门店入驻申请详情数据
     *
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        $data = input('get.id');
        $store = $this->obj->get($data);
        $citys = model('City')->getNormalCityByParentId();
        $categorys = model('Category')->getNormalCategoryByParentId();
        $category_info = $this->getCategoryInfo($data);
        return $this->fetch('', [
            'store' => $store,
            'citys' => $citys,
            'categorys' => $categorys,
            'category_info' => $category_info,
        ]);
    }

    /**
     * 获取点击详情对应id的所属分类的二级分类信息
     *
     * @param $id
     *
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function getCategoryInfo($id)
    {
        $locationData = model('BisLocation')->get(['id' => $id]);
        $category_path_all = $locationData['category_path'];
        $category_path = explode(',', $category_path_all);
        //循环遍历 将所属分类中的主分类去掉
        foreach ($category_path as $k => $v) {
            if ($k == 0) {
                unset($category_path[$k]);
            }
        }
        $category_path = array_values($category_path); //设置数组从零开始
        //通过二级分类的id来查询二级分类的名字
        foreach ($category_path as $k => $v) {
            $category_path_name[] = model('Category')->get($category_path[$k])['name'];
        }
        foreach ($category_path_name as $k => $v) {
            foreach ($category_path as $kk => $vv) {
                if ($k == $kk) {
                    $category_info[$vv] = $v;
                }
            }
        }
        return $category_info;
    }
}

