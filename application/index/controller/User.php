<?php
namespace app\index\controller;

use think\Controller;
use think\Validate;

class User extends Controller
{
    public function login()
    {
        return $this->fetch();
    }

    /**
     * 会员注册
     *
     * @return mixed
     */
    public function register()
    {
        if (request()->isPost()) {
            $data = input('post.');
            if (!captcha_check($data['verifycode'])) {
                //校验失败
                $this->error('验证码不正确');
            }
            // 严格校验
            $validate = Validate('Index');
            if (!$validate->scene('register')->check($data)) {
                $this->error($validate->getError());
            }
            if ($data['password'] != $data['repassword']) {
                $this->error('两次输入的密码不一样');
            }
            //自动生成，密码的加盐字符串
            $data['code'] = mt_rand(100, 10000);
            $data['password'] = md5($data['password'] . $data['code']);
            //如保存有异常将其捕获
            try {
                $res = model('User')->add($data);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            if ($res) {
                $this->success('注册成功','user/login');
            } else {
                $this->error('注册失败');
            }
        } else {
            return $this->fetch();
        }
    }
}
