<?php
namespace app\index\controller;
class Index extends Base
{
    public function index()
    {
        //return [1,2];
        //获取首页大图相关数据
        $homePigPictureInfo = model('Featured')->getFeaturedByType(0);
        //获取广告位相关的数据
        $homeRightPictureInfo = model('Featured')->getFeaturedByType(1);
        return $this->fetch('', [
            'homePigPictureInfo' => $homePigPictureInfo,
            'homeRightPictureInfo' => $homeRightPictureInfo,
        ]);
    }
}
