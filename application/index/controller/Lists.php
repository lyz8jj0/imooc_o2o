<?php
/**
 * Created by PhpStorm.
 * User: lixinyu
 * Date: 2018/4/9
 * Time: 下午1:36
 */
namespace app\index\controller;
class Lists extends Base
{
    /**
     * 商品列表页
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $firstCatIds = [];
        //思路 首先需要一级栏目
        $categorys = model("Category")->getNormalCategoryByParentId();
        foreach ($categorys as $v) {
            $firstCatIds[] = $v->id;
        }
        $id = input('id', 0, 'intval');
        $data = [];
        //区分是一级分类还是二级分类
        if (in_array($id, $firstCatIds)) { //一级分类
            $categoryParentId = $id;
            $data['category_id'] = $id;
        } elseif ($id) { //二级分类
            //获取二级分类的数据
            $category = model('Category')->get($id);
            if (!$category || $category->status != 1) {
                $this->error('数据不合法');
            }
            $categoryParentId = $category->parent_id;
            $data['se_category_id'] = $id;
        } else { //0
            $categoryParentId = 0;
        }
        $secategorys = [];
        if($categoryParentId){
            $secategorys = model('Category')->getNormalCategoryByParentId($categoryParentId);
        }
        //根据条件来查询商品列表数据
        $deals = model('Deal')->getDealByCondition($data);
        return $this->fetch('', [
            'categorys' => $categorys,
            'secategorys' => $secategorys,
            'id' => $id,
            'categoryParentId' => $categoryParentId,
            'deals' => $deals
        ]);
    }
}
