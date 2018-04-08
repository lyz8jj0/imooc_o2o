<?php
namespace app\common\model;

use think\Exception;
use think\Model;

class City extends Model
{
    /**
     * 下拉框中的城市列表
     *
     * @param int $parent_id (默认查询的是一级城市)
     *
     * @return false|\PDOStatement|string|\think\Collection
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

    /**
     * 获取所有二级城市
     *
     * @return false|\PDOStatement|string|\think\Collection
     * @throws Exception
     */
    public function getNormalCitys()
    {
        $data = [
            'status' => 1,
            'parent_id' => ['gt', 0],
        ];
        $order = ['id' => 'desc'];
        return $this->where($data)
            ->order($order)
            ->select();
    }
}
