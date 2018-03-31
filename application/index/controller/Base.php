<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;

class Base extends Controller
{
    public $city = '';
    public $account = '';

    /**
     * 初始化
     *
     * @throws Exception
     */
    public function _initialize()
    {
        //城市数据
        $citys = model('City')->getNormalCitys();
        $this->getCity($citys);
        //获取首页分类的数据
        $cats = $this->getRecommendCats();
        $this->assign('citys', $citys);
        $this->assign('city', $this->city);
        $this->assign('cats', $cats);
        $this->assign('user', $this->getLoginUser());
    }

    /**
     * @param $citys
     *
     * @throws Exception
     */
    public function getCity($citys)
    {
        foreach ($citys as $v) {
            $city = $v->toArray();//将对象转换成数组
            if ($v['is_default'] == 1) {
                $defaultuname = $city['uname'];
                break;//终止foreach
            }
        }
        $defaultuname = $defaultuname ? $defaultuname : 'nanchang';
        if (session('cityname', '', 'o2o') && !input('get.city')) {
            $cityuname = session('cityname', '', 'o2o');
        } else {
            $cityuname = input('get.city', $defaultuname, 'trim');
            session('cityname', $cityuname, 'o2o');
        }
        $this->city = model('City')->where(['uname' => $cityuname])->find();
    }

    //获取session值
    public function getLoginUser()
    {
        //如果不存在话去获取 ，存在的话直接返回
        if (!$this->account) {
            $this->account = session('o2o_user', '', 'o2o');
        }
        return $this->account;
    }

    /**
     * 获取首页推荐当中的商品分类数据(Recommend推荐)
     */
    public function getRecommendCats()
    {
        $parentIds = $sedcatArr = $recomCats = [];
        $cats = model('Category')->getNormalRecommendCategoryByParentId(0, 5);
        foreach ($cats as $v) {
            $parentIds[] = $v->id;
        }
        //获取二级分类的数据
        $seCats = model('Category')->getNormalCategoryIdParentId($parentIds);
        foreach ($seCats as $v) {
            $sedcatArr[$v->parent_id][] = [
                'id' => $v->id,
                'name' => $v->name,
            ];
        }
        foreach ($cats as $v) {
            //$recomCats 代表一级和二级数据，[]第一个参数是 一级分类的name，第二个参数是此一级分类下面的所有二级分类数据
            $recomCats[$v->id] = [
                $v->name,
                empty($sedcatArr[$v->id]) ? [] : $sedcatArr[$v->id]
            ];
        }
//      dump($parentIds);
//        echo"<hr>";
//      dump($sedcatArr);
//      echo"<hr>";
//      dump($recomCats);
//      die;
        return $recomCats;
    }
}
