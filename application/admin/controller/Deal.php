<?php
namespace app\admin\controller;
class Deal extends Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model("Deal");
    }

    /**
     * 团购商品列表页
     *
     * @return mixed
     */
    public function index()
    {
        $sdata = [];
        $deals = $this->obj->getNormalDeals($sdata);
        $categoryArrs = [];
        $catgorys = model("Category")->getNormalCategoryByParentId();
        foreach ($catgorys as $v) {
            $categoryArrs[$v->id] = $v->name;
        }
        $cityArrs = [];
        $citys = model("City")->getNormalCitys();
        foreach ($citys as $v) {
            $cityArrs[$v->id] = $v->name;
        }
        return $this->fetch('', [
            'deals' => $deals,
            'categoryArrs' => $categoryArrs,
            'cityArrs' => $cityArrs
        ]);
    }

    /**
     * 商家团购提交申请列表
     *
     * @return mixed
     */
    public function apply()
    {
        $deals = model('Deal')->getDealByStatus();
        return $this->fetch('', [
            'deals' => $deals
        ]);
    }

    /**
     * 平台团购列表下的商品详情页
     *
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        $id = input('get.id');
        $deal = model('Deal')->get(['id' => $id]);
        $citys = model('City')->getNormalCityByParentId();
        $categorys = model('Category')->getNormalCategoryByParentId();
        return $this->fetch('', [
            'citys' => $citys,
            'categorys' => $categorys,
            'deal' => $deal,
        ]);
    }
}

