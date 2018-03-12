<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function test()
    {
        \Map::getLngLat('河北科技大学');
        return 'singwa';
    }

    public function map()
    {
        $res = \Map::staticimage('河北科技大学');
        return $res;
    }

    public function weclome()
    {
        return '欢迎来到o2o主后台首页！';
    }
}
