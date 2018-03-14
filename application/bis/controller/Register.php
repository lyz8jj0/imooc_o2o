<?php
namespace app\bis\controller;

use think\Controller;

class Register extends Controller
{
    /**
     *  获取一级城市分类数据
     *
     * @return mixed
     */
    public function index()
    {
        $citys = model('City')->getNormalCityByParentId();
        $categorys = model('Category')->getNormalCategoryByParentId();
        return $this->fetch('', [
            'citys' => $citys,
            'categorys' => $categorys,
        ]);
    }
}
