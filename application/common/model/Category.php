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
     * 集团后台 添加分类页面
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
     * 集团后台 生活分类列表 （获取一级分类）
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
     * 获取详情下的所属分类(二级分类) 通过parentId
     *
     * @param int $parent_id 默认查询没有父类的分类也就是一级分类
     *
     * @return false|\PDOStatement|string|\think\Collection
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

    /**
     * 获取首页推荐当中的一级分类数据展示
     *
     * @param int $id    (0为一级分类)
     * @param int $limit (展示默认5条)
     *
     * @return false|\PDOStatement|string|\think\Collection
     * @throws Exception
     */
    public function getNormalRecommendCategoryByParentId($id = 0, $limit = 5)
    {
        $data = [
            'parent_id' => $id,
            'status' => 1,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = $this->where($data)
            ->order($order);
        if ($limit) {
            $result = $result->limit($limit);
        }
        return $result->select();
    }

    /**
     * 获取首页推荐当中的二级分类数据展示
     *
     * @param $ids (通过一级分类来获取二级分类)
     *
     * @return false|\PDOStatement|string|\think\Collection
     * @throws Exception
     */
    public function getNormalCategoryIdParentId($ids)
    {
        $data = [
            'parent_id' => ['in', $ids],
            'status' => 1,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = $this->where($data)
            ->order($order)
            ->select();
        return $result;
    }
}
