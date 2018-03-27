<?php
namespace app\index\controller;

use think\Controller;
use think\Validate;

class User extends Controller
{
    public function login()
    {
        //获取session
        $user = session('o2o_user', '', 'o2o');
        if ($user) {
            $this->redirect(url('index/index'));
        }
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
                $this->success('注册成功', 'user/login');
            } else {
                $this->error('注册失败');
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 登陆检测
     */
    public function logincheck()
    {
        //判定
        if (!request()->isPost()) {
            $this->error('提交不合法');
        }
        $data = input('post.');
        //严格检验 valiate
        $validate = Validate('Index');
        if (!$validate->scene('login')->check($data)) {
            $this->error($validate->getError());
        }
        try {
            $user = model('User')->getUserByUsername($data['username']);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        if (!$user || $user->status != 1) {
            $this->error('该用户不存在');
        }
        //判定密码是否合理
        if (md5($data['password'] . $user['code']) != $user->password) {
            $this->error('密码不正确');
        }
        //登录成功
        model('User')->updateById(['last_login_time' => time()], $user->id);
        //把用户的信息记录到session (标识 内容 作用域)
        session('o2o_user', $user, 'o2o');
        $this->success('登录成功', url('index/index'));
    }

    /**
     * 退出登陆
     */
    public function logout()
    {
        //清空session(session值  作用域)
        session(null, 'o2o');
        $this->redirect(url('user/login'));//重定向
    }
}
