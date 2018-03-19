<?php
/**
 * BaseModel 公共的model层
 */
namespace app\common\model;

use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;

    //增加分类保存
    public function add($data)
    {
        $data['status'] = 0;
        $this->save($data); //保存信息
        return $this->id;  //返回主键id
    }
}
