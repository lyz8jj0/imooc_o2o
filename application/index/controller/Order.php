<?php
/**
 * Created by PhpStorm.
 * User: lixinyu
 * Date: 2018/4/9
 * Time: 下午4:19
 */
namespace app\index\controller;
class Order extends Base
{
    /**
     * 订单确认
     */
    public function index()
    {
        $this->success('抢购成功', 'index/index');
    }

    public function confirm()
    {
        if (!$this->getLoginUser()) {
            $this->error('请登录', 'user/login');
        }
        //
        $id = input('get.id', 0, 'intval');
        if (!$id) {
            $this->error('参数不合法');
        }
        $count = input('get.count', 1, 'intval');
        $deal = model('Deal')->find($id);
        if (!$deal || $deal->status != 1) {
            $this->error('商品不存在');
        }
        $deal = $deal->toArray();
        return $this->fetch('', [
            'controller' => 'pay', //引入pay.css样式
            'count' => $count,
            'deal' => $deal,
        ]);
    }
}
