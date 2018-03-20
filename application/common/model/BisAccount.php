<?php
namespace app\common\model;
class BisAccount extends BaseModel
{
    /**
     * 更新登陆时间
     *
     * @param $data
     * @param $id
     *
     * @return false|int
     */
    public function updateById($data, $id)
    {
        //allowField 过渡data数组中非数据表中的数据
        return $this->allowField(true)->save($data, ['id' => $id]);
    }
}
