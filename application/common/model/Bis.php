<?php
namespace app\common\model;

use think\Exception;

class Bis extends BaseModel
{
    /**
     * 通过状态获取商家数据
     *
     * @param int $status
     *
     * @throws Exception
     */
    public function getBisByStatus($status = 0)
    {
        $order = [
            'id' => 'desc'
        ];
        $data = [
            'status' => $status,
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }
}
