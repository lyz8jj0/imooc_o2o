<?php
namespace app\common\model;

use think\Exception;

class Deal extends BaseModel
{
    /**
     * 商户中心团购列表
     *
     * @param $bisId
     *
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getDealByBisId($bisId)
    {
        $order = [
            'id' => 'desc'
        ];
        $data = [
            'bis_id' => $bisId,
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate(10);
        return $result;
    }

    /**
     * 跟据传过来的值获取团购列表
     *
     * @param array $data
     *
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getNormalDeals($data = [])
    {
        $data['status'] = 1;
        $order = ['id' => 'desc'];
        $result = $this->where($data)
            ->order($order)
            ->paginate(10);
        return $result;
    }

    /**
     * 根据分类 以及 城市来获取 商品数据
     *
     * @param     $id     (分类)
     * @param     $cityId (城市)
     * @param int $limit  (条数)
     *
     * @return false|\PDOStatement|string|\think\Collection
     * @throws Exception
     */
    public function getNormalDealByCategoryCityId($id, $cityId, $limit = 10)
    {
        $data = [
            'end_time' => ['gt', time()], //结束时间大于当前时间
            'category_id' => $id,
            'city_id' => $cityId,
            'status' => 1,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        $result = $this->where($data)
            ->order($order);
        if ($limit) {
            $result = $result->limit($limit);
        }
        return $result->select();
    }
}
