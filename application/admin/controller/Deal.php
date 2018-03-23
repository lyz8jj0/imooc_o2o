<?php
namespace app\admin\controller;

use think\Controller;

class Deal extends Controller
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
        $data = input('get.');
        $sdata = [];
        if (!empty($data['start_time']) && !empty($data['end_time']) && strtotime($data['end_time']) > strtotime($data['start_time'])) {
            $sdata['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
        }
        if (!empty($data['category_id'])) {
            $sdata['category_id'] = $data['category_id'];
        }
        if (!empty($data['city_id'])) {
            $sdata['city_id'] = $data['city_id'];
        }
        if (!empty($data['name'])) {
            $sdata['name'] = ['like', '%' . $data['name'] . '%'];
        }
        $deals = $this->obj->getNormalDeals($sdata);
        $categoryArrs = $cityArrs = [];
        $catgorys = model("Category")->getNormalCategoryByParentId();
        foreach ($catgorys as $k) {
            $categoryArrs[$k->id] = $k->name;
        }
        $citys = model("City")->getNormalCitys();
        foreach ($citys as $k) {
            $cityArrs[$k->id] = $k->name;
        }
        return $this->fetch('', [
            'categorys' => $catgorys,
            'citys' => $citys,
            'deals' => $deals,
            'category_id' => empty($data['category_id']) ? '' : $data['category_id'],
            'city_id' => empty($data['city_id']) ? '' : $data['city_id'],
            'name' => empty($data['name']) ? '' : $data['name'],
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'cityArrs' => $cityArrs,
            'categoryArrs' => $categoryArrs
        ]);
    }
}

