<?php
/**
 * Created by PhpStorm.
 * User: lixinyu
 * Date: 2018/3/20
 * Time: 下午3:36
 */
namespace app\bis\controller;

use think\Exception;
use think\Validate;

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
     *  门店列表页
     *
     * @return mixed
     */
    public function index()
    {
        //通过session获取本店bisId
        $bisId = $this->getLoginUser()->bis_id;
        $bisLocation = $this->obj->getBisLocationByBisId($bisId);
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
            $data = input('post.');
            //第一点 检验数据 tp5 validate机制
            $validate = Validate('Bis');
            $result = $validate->scene('addLocation')->check($data);
            if (!$result) {
                $this->error($validate->getError());
            }
            $bisId = $this->getLoginUser()->bis_id;
            $data['cat'] = '';
            if (!empty($data['se_category_id'])) {
                $data['cat'] = implode(',', $data['se_category_id']);
            }
            $demo['se_category_id'] = $data['se_category_id'];
            $demo['cat'] = $data['cat'];
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
            $locationId = model('BisLocation')->add($locationData); //添加入库
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
        $category_info = $this->getCategoryInfo($id);
        return $this->fetch('', [
            'citys' => $citys,
            'categorys' => $categorys,
            'locationData' => $locationData,
            'category_info' => $category_info,
        ]);
    }

    /**
     * 获取点击详情对应id的所属分类的二级分类信息
     *
     * @param $id
     *
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function getCategoryInfo($id)
    {
        $locationData = model('BisLocation')->get(['id' => $id]);
        $category_path_all = $locationData['category_path'];
        $category_path = explode(',', $category_path_all);
        //循环遍历 将所属分类中的主分类去掉
        foreach ($category_path as $k => $v) {
            if ($k == 0) {
                unset($category_path[$k]);
            }
        }
        $category_path = array_values($category_path); //设置数组从零开始
        //通过二级分类的id来查询二级分类的名字
        foreach ($category_path as $k => $v) {
            $category_path_name[] = model('Category')->get($category_path[$k])['name'];
        }
        foreach ($category_path_name as $k => $v) {
            foreach ($category_path as $kk => $vv) {
                if ($k == $kk) {
                    $category_info[$vv] = $v;
                }
            }
        }
        return $category_info;
    }

    /**
     * o2o平台-商户中心 删除门店
     */
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
