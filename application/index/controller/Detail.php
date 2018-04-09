<?php
namespace app\index\controller;
class Detail extends Base
{
    /**
     * 商品详情页
     *
     * @param $id
     *
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($id)
    {
        if (!intval($id)) {
            $this->error('ID不合法');
        }
        //根据id查询商品的数据
        $deal = model('Deal')->get($id);
        //根据商户id获取商家信息
        $bisId = $deal->bis_id;
        $bisLocation = model('bis')->get(['id' => $bisId]);
        if (!$deal || $deal->status != 1) {
            $this->error('该商品不存在');
        }
        //获取分类信息
        $category = model('Category')->get($deal->category_id);
        //获取分店信息
        $locations = model('BisLocation')->getNormalLocationsInId($deal->location_ids);
        $flag = 0;
        //判断如果还没到团购开始时候则显示距开始时间还有时间
        if ($deal->start_time > time()) {
            $flag = 1;
            $dtime = $deal->start_time - time();
            $timedata = '';
            $d = floor($dtime / (3600 * 24));//0.6  0    1.2  1  tp5的一个向下取整函数
            if ($d) {
                $timedata .= $d . "天";
            }
            $h = floor($dtime % (3600 * 24) / 3600);
            if ($h) {
                $timedata .= $h . "小时";
            }
            $m = floor($dtime % (3600 * 24) % 3600 / 60);
            if ($m) {
                $timedata .= $m . "分";
            }
            $this->assign(['timedata' => $timedata]);
        }
        return $this->fetch('', [
            'deal' => $deal,
            'title' => $deal->name,
            'category' => $category,
            'locations' => $locations,
            'overplus' => $deal->total_count - $deal->buy_count, //团购剩余数量
            'flag' => $flag,
            'bisLocation' => $bisLocation
        ]);
    }
}
