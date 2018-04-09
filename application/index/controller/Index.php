<?php
namespace app\index\controller;
class Index extends Base
{
    public function index()
    {
        //return [1,2];
        //获取首页大图相关数据
        $homePigPictureInfo = model('Featured')->getFeatured(0);
        //获取广告位相关的数据
        $homeRightPictureInfo = model('Featured')->getFeatured(1);
        //商品分类，数据-美食 推荐的数据
        $datas = model('Deal')->getNormalDealByCategoryCityId(1, $this->city->id);
        //获取4个子分类
        $meshicates = model('Category')->getNormalRecommendCategoryByParentid(1, 4);
        return $this->fetch('', [
            'homePigPictureInfo' => $homePigPictureInfo,
            'homeRightPictureInfo' => $homeRightPictureInfo,
            'datas' => $datas,
            'meishicates' => $meshicates,
        ]);
    }
}
