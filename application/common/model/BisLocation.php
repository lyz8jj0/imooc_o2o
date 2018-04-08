<?php
namespace app\common\model;

use think\Exception;

class BisLocation extends BaseModel
{
    /**
     * 通过不同门店对应的bis_id获取本店的商户门店列表
     *
     * @param int $is_main
     *
     * @return \think\Paginator
     * @throws Exception
     */
    public function getBisLocationByBisId($bisId)
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
     * 团购商品添加里面的门店列表
     *
     * @param $bisId
     *
     * @return false|\PDOStatement|string|\think\Collection
     * @throws Exception
     */
    public function getNormalLocationByBisId($bisId)
    {
        $data = [
            'bis_id' => $bisId,
            'status' => 1,
        ];
        $result = $this->where($data)
            ->order('id', 'desc')
            ->select();
        return $result;
    }

    /**
     *  获取o2o平台下的门店入驻申请信息
     *
     * @param $status (0为申请时的状态)
     *
     * @throws Exception
     */
    public function getStoreByStatus($status = 0)
    {
        $data = [
            'status' => $status,
        ];
        $order = [
            'id' => 'desc',
        ];
        $result = $this->where($data)
            ->order($order)
            ->select();
        return $result;
    }
}
