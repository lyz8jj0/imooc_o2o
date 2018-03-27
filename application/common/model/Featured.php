<?php
namespace app\common\model;

use think\Exception;

class Featured extends BaseModel
{
    /**
     * 根据类型来获取列表数据
     *
     * @param $type
     *
     * @return \think\Paginator
     * @throws Exception
     */
    public function getFeaturedByType($type)
    {
        $data = [
            'type' => $type,
            'status' => ['neq', -1],
        ];
        $order = ['id' => 'desc'];
        $result = $this->where($data)->order($order)->paginate();
        return $result;
    }
}
