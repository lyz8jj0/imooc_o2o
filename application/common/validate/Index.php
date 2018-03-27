<?php
namespace app\common\validate;

use think\Validate;

class Index extends Validate
{
    protected $rule = [
        ['username', 'require', '用户名不存在'],
        ['email', 'email', '邮箱格式不对'],
        ['password', 'require', '密码不存在'],
        ['repassword', 'require', '确认密码不存在'],
    ];
    protected $scene = [
        'register' => ['username', 'email', 'password', 'repassword']
    ];
}