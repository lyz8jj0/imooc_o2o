<?php
namespace app\common\model;

use think\Exception;
use think\Model;

class City extends Model
{
    /**
     * 商户入驻申请下的所属城市(二级分类)
     *
     * @return |array()
     * @throws Exception
     */
    public function getNormalCityByParentId($parent_id = 0)
    {
        $data = [
            'status' => 1,
            'parent_id' => $parent_id,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->order($order)
            ->select();
    }
}
