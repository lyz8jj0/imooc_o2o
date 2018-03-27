<?php
/**
 * BaseModel 公共的model层
 */
namespace app\common\model;

use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;

    public function add($data)
    {
        $data['status'] = 0;
        $this->save($data); //保存信息
        return $this->id;  //返回主键id
    }

    /**
     * 更新
     *
     * @param $data (要更新的内容)
     * @param $id   (更新的主键id)
     *
     * @return false|int
     */
    public function updateById($data, $id)
    {
        return $this->allowField(true)->save($data, ['id' => $id]);
    }
}
