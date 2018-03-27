<?php
namespace app\common\model;
class User extends BaseModel
{
    /**
     * 会员注册添加入数据库
     *
     * @param array $data
     *
     * @return false|int|mixed
     * @throws \Exception
     */
    public function add($data = [])
    {
        //如果提交的数据不是数组
        if (!is_array($data)) {
            exception('传递的数据不是数组');//抛出异常
        }
        $data['status'] = 1;
        return $this->allowField(true)->save($data);
    }
}
