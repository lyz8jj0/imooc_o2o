<?php
namespace app\common\model;

use think\Exception;

class BisLocation extends BaseModel
{
    /**
     * 通过不同门店的bis_id获取本店的商户门店列表
     *
     * @param int $is_main
     *
     * @return \think\Paginator
     * @throws Exception
     */
    public function getBisLocationByIsMain($bisId)
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
}
