<?php
/**
 * Created by PhpStorm.
 * User: lixinyu
 * Date: 2018/3/20
 * Time: 下午3:17
 */
namespace app\bis\controller;

use think\Controller;

class Base extends Controller
{
    public $account;

    public function _initialize()
    {
        //判定用户是否登录
        $isLogin = $this->isLogin();
        if (!$isLogin) {
            return $this->redirect('login/index');
        }
    }

    //判定是否登录
    public function isLogin()
    {
        //获取session
        $user = $this->getLoginUser();
        if ($user && $user->id) {
            return true;
        }
        return false;
    }

    //获取session值
    public function getLoginUser()
    {
        //如果不存在话去获取 ，存在的话直接返回
        if (!$this->account) {
            $this->account = session('bisAccount', '', 'bis');
        }
        return $this->account;
    }
}
