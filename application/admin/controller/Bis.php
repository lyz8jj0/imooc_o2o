<?php
namespace app\admin\controller;

use think\Controller;

class Bis extends Controller
{
    private $obj;

    /**
     * 控制器初始化
     */
    public function _initialize()
    {
        $this->obj = model("Bis");
    }

    /**
     * 入驻申请列表
     *
     * @return mixed
     */
    public function apply()
    {
        $bis = $this->obj->getBisByStatus();
        return $this->fetch('', [
            'bis' => $bis
        ]);
    }
}

