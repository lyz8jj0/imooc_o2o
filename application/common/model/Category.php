<?php
namespace app\common\model;

use think\Exception;
use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;

    //增加分类保存
    public function add($data)
    {
        $data['status'] = 1;
//        $data['create_time'] = time();
        return $this->save($data);
    }

    /**
     * 添加分类
     *
     * @return |array
     * @throws \Exception
     */
    public function getNormalFirstCategory()
    {
        $data = [
            'status' => 1,
            'parent_id' => 0
        ];
        $order = [
            'id' => 'desc',
        ];
        $result = $this->where($data)
            ->order($order)
            ->select();
        return $result;
    }

    /**
     * 获取一级分类
     *
     * @return |array
     * @throws \Exception
     */
    public function getNormalFirstCategorys($parentId = 0)
    {
        $data = [
            'parent_id' => $parentId,
            'status' => ['neq', -1],
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate(10);
        return $result;
    }

    /**
     * 获取一级生活服务
     *
     * @return |array()
     * @throws Exception
     */
    public function getNormalCategoryByParentId($parent_id = 0)
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
