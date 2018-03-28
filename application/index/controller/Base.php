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
        //用户数据
        $this->getCity($citys);
        $this->assign('citys', $citys);
        $this->assign('city', $this->city);
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
}
