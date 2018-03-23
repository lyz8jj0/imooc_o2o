<?php
/**
 * Created by PhpStorm.
 * User: lixinyu
 * Date: 2018/3/20
 * Time: 下午3:36
 */
namespace app\bis\controller;

use think\Exception;

class Location extends Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('BisLocation');
    }

    public function weclome()
    {
        return '欢迎来到o2o商户中心首页！';
    }

    /**
     *  列表页
     *
     * @return mixed
     */
    public function index()
    {
        //通过session获取本店bisId
        $bisId = $this->getLoginUser()->bis_id;
        print_r($bisId);
        $bisLocation = $this->obj->getBisLocationByIsMain($bisId);
        return $this->fetch('', [
            'bisLocation' => $bisLocation
        ]);
    }

    /**
     *  获取一级城市分类数据
     *
     * @return mixed
     */
    public function add()
    {
        if (request()->isPost()) {
            //第一点 检验数据 tp5 validate机制(待完成 )
            $data = input('post.');
            $bisId = $this->getLoginUser()->bis_id;
            $data['cat'] = '';
            if (!empty($data['se_category_id'])) {
                $data['cat'] = implode('|', $data['se_category_id']);
            }
            //获取经纬度
            $lnglat = \Map::getLngLat($data['address']);
            if (empty($lnglat) || $lnglat['status'] != 0 || $lnglat['result']['precise'] != 1) {
                $this->error('无法获取数据，或者匹配的地址不精确');
            }
            //门店入库操作
            //总店相关信息入库
            $locationData = [
                'bis_id' => $bisId,
                'name' => $data['name'],
                'logo' => $data['logo'],
                'tel' => $data['tel'],
                'contact' => $data['contact'],
                'category_id' => $data['category_id'],
                'category_path' => $data['category_id'] . ',' . $data['cat'],
                'city_id' => $data['city_id'],
                'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'] . ',' . $data['se_city_id'],
                'api_address' => $data['address'],
                'open_time' => $data['open_time'],
                'content' => empty($data['content']) ? '' : $data['content'],
                'is_main' => 0,
                'xpoint' => empty($lnglat['result']['location']['lng']) ? '' : $lnglat['result']['location']['lng'],
                'ypoint' => empty($lnglat['result']['location']['lat']) ? '' : $lnglat['result']['location']['lat'],
            ];
            $locationId = model('BisLocation')->add($locationData);
            if ($locationId) {
                return $this->success('门店申请成功');
            } else {
                return $this->error('门店申请失败');
            }
        } else {
            $citys = model('City')->getNormalCityByParentId();
            //获取一级栏目的数据
            $categorys = model('Category')->getNormalCategoryByParentId();
            return $this->fetch('', [
                'citys' => $citys,
                'categorys' => $categorys,
            ]);
        }
    }

    /**
     * o2o平台-商户中心单条记录详情
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
        $locationData = model('BisLocation')->get(['id' => $id]);
        return $this->fetch('', [
            'citys' => $citys,
            'categorys' => $categorys,
            'locationData' => $locationData,
        ]);
    }

    public function status()
    {
        $data = input('get.');
        $res = $this->obj->save(['status' => $data['status']], ['id' => $data['id']]);
        if ($res) {
            $this->success('删除门店成功');
        } else {
            $this->error('删除门店失败');
        }
    }
}
