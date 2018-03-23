<?php
namespace app\api\controller;

use think\Controller;

class Category extends Controller
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model("Category");
    }

    /**
     * 商户注册页面下拉框的所属分类
     *
     * @return array
     */
    public function getCategorysByParentId()
    {
        $id = input('post.id', 0, 'intval');
        if (!$id) {
            $this->error('ID不合法');
        }
        //通过id获取二级分类
        $categorys = $this->obj->getNormalCategoryByParentId($id);
        if (!$categorys) {
            return show(0, 'error');
        }
        return show(1, 'success', $categorys);
    }
}

