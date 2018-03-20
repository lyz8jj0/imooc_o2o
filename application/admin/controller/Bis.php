<?php
namespace app\admin\controller;

use think\Controller;
use think\Exception;

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
    public function index()
    {
        $bis = $this->obj->getBisByStatus(1);
        return $this->fetch('', [
            'bis' => $bis
        ]);
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

    /**
     *商家入驻申请单条记录详情
     *
     * @return mixed
     * @throws Exception
     */
    public function detail()
    {
        $id = input('get.id');
        //获取一级城市的数据
        $citys = model('City')->getNormalCityByParentId();
        //获取一级栏目的数据
        $categorys = model('Category')->getNormalCategoryByParentId();
        //获取商户数据(三个表)
        $bisData = model('Bis')->get($id);
        $locationData = model('BisLocation')->get(['bis_id' => $id, 'is_main' => 1]);
        $accountData = model('BisAccount')->get(['bis_id' => $id, 'is_main' => 1]);
        return $this->fetch('', [
            'citys' => $citys,
            'categorys' => $categorys,
            'bisData' => $bisData,
            'locationData' => $locationData,
            'accountData' => $accountData,
        ]);
    }

    /**
     * 修改状态
     */
    public function status()
    {
        $data = input('get.');
        $res = $this->obj->save(['status' => $data['status']], ['id' => $data['id']]);
        $location = model('BisLocation')->save(['status' => $data['status']],
            ['bis_id' => $data['id'], 'is_main' => 1]);
        $account = model('BisAccount')->save(['status' => $data['status']], ['bis_id' => $data['id'], 'is_main' => 1]);
        if ($res && $location && $account) {
            //发送邮件
            //  \phpmailer\Email::send($data['email'], $title, $content);
            //status 1(通过)   status 2(不通过)    status -1(删除)
            $this->success('状态更新成功');
        } else {
            $this->error('状态更新失败');
        }
    }
}

