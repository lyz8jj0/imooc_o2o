<?php
namespace app\common\model;
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
        $order = ['id'=>'desc'];
        $result = $this->where($data)
            ->order($order)
            ->paginate(10);
        return $result;
    }
}
