<?php
/**
 * Created by PhpStorm.
 * User: lixinyu
 * Date: 2018/3/20
 * Time: 下午3:05
 */
namespace app\bis\controller;
class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}
