<?php
/**
 * Created by PhpStorm.
 * User: lixinyu
 * Date: 2018/3/20
 * Time: 下午3:05
 */
namespace app\bis\controller;
class Deal extends Base
{
    /**
     * @return mixed 商户中心的 团购列表页
     */
    public function index()
    {
        $bisId=$this->getLoginUser()->bis_id;
        $deal = model('Deal')->getDealByBisId($bisId);
        return $this->fetch('',[
            'deal' => $deal
        ]);
    }

    /**
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function add()
    {
        $bisId = $this->getLoginUser()->bis_id;
        if (request()->isPost()) {
            //走插入逻辑
            $data = input('post.');
            //严格校验提交的数据
            $location = model('BisLocation')->get($data['location_ids'][0]);
            $deals = [
                'bis_id' => $bisId,
                'name' => $data['name'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'se_category_id' => empty($data['se_category_id']) ? '' : implode(',', $data['se_category_id']),
                'city_id' => $data['city_id'],
                'location_ids' => empty($data['location_ids']) ? '' : implode(',', $data['location_ids']),
                'start_time' => strtotime($data['start_time']),
                'end_time' => strtotime($data['end_time']),
                'total_count' => $data['total_count'],
                'origin_price' => $data['origin_price'],
                'coupons_begin_time' => $data['coupons_begin_time'],
                'coupons_end_time' => $data['coupons_end_time'],
                'notes' => $data['notes'],
                'description' => $data['description'],
                'bis_account_id' => $this->getLoginUser()->id,
                'current_price' => $data['current_price'],
                'xpoint' => $location->xpoint,
                'ypoint' => $location->ypoint,
            ];
            $id = model('Deal')->add($deals);
            if ($id) {
                $this->success('添加成功', url('deal/index'));
            } else {
                $this->error('添加失败');
            }
        } else {
            //获取一级城市的数据
            $citys = model('City')->getNormalCityByParentId();
            //获取一级栏目的数据
            $categorys = model('Category')->getNormalCategoryByParentId();
            return $this->fetch('', [
                'citys' => $citys,
                'categorys' => $categorys,
                'bislocations' => model('BisLocation')->getNormalLocationByBisId($bisId)
            ]);
        }
    }
}
