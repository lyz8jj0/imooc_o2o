<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件
function status($status)
{
    if ($status == 1) {
        $str = "<span class='label label-success radius'>正常</span>";
    } elseif ($status == 0) {
        $str = "<span class='label label-danger radius'>待审</span>";
    } else {
        $str = "<span class='label label-danger radius'>删除</span>";
    }
    return $str;
}

/**
 * 百度地图
 *
 * @param       $url
 * @param int   $type 0 get 1 post
 * @param array $data
 */
function doCurl($url, $type = 0, $data = [])
{
    $ch = curl_init(); //初始化
    //设置选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if ($type == 1) {
        //post
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //执行并获取内容
    $output = curl_exec($ch);
    //释放curl句柄
    return $output;
}

function bisRegister($status)
{
    if ($status == 1) {
        $str = "入驻申请成功";
    } elseif ($status == 0) {
        $str = "待审核，审核后平台方会发送邮件通知，请关注邮件";
    } elseif ($status == 2) {
        $str = "非常抱歉，您提交的材料不符合条件，请重新提交";
    } else {
        $str = "该申请已被删除";
    }
    return $str;
}

/**
 * 通用分布样式
 *
 * @param $obj
 *
 * @return string
 */
function pagination($obj)
{
    if (!$obj) {
        return '';
    }
    return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">' . $obj->render() . '</div>';
}

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
/**
 *获取后台点击详情时的二级城市
 *
 * @param $path
 *
 * @return bool|string
 * @throws Exception
 */
function getSeCityName($path)
{
    if (empty($path)) {
        return '';
    }
    //preg_match — 执行匹配正则表达式
    if (preg_match('/,/', $path)) {
        $cityPath = explode(',', $path);
        $cityId = $cityPath[1];
    } else {
        $cityId = $path;
    }
    $city = model('City')->get($cityId);
    return $city->name;
}

///**
// * 获取后台点击详情时的二级分类
// *
// * @param $path
// *
// * @return bool|string
// * @throws Exception
// */
//function getSeCategoryName($path)
//{
//    if (empty($path)) {
//        return '';
//    }
//    if (preg_match('/,/', $path)) {
//        $categoryPath = explode(',', $path);
////        $categoryPath = $categoryPath[0];
//        print_r($path);
//        echo"<hr>";
//        print_r($categoryPath);
//        echo"<hr>";
//    } else {
//        $categoryPath = $path;
//    }
//    $category = model('Category')->get($categoryPath);
////    print_r($categoryPath);die;
//    return $category->name;
//}

/**
 * 获取分店个数
 *
 * @param $ids
 *
 * @return int
 */
function countLocation($ids)
{
    if (!$ids) {
        return 1;
    }
    if (preg_match('/,/', $ids)) { //检测是否有逗号
        $arr = explode(',', $ids); //$ids中的数据有','分开
        return count($arr);
    }
}