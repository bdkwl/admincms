<?php
// 管理员登录
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    // 后台登录
    public function index(){
        if (IS_POST) {
            $name = I('user');
            $pwd = I('password');

            $userDb = D('Admin/User');
            $user_login = $userDb->login( $name, $pwd );
            if (!$user_login) {
                $this->error( $userDb->getError() );
                //setAlert($userDb->getError());
            }

            // 设置登录状态
            $uid = $userDb->auto_login( $user_login );

            $userDb->addlog(1);
            //$this->success('登录成功', U('Admin/Index/index'));
            $this->redirect( ('Admin/Index/index') );
        } else {
            $this->display();
        }
    }

    public function logout() {
        session('user_auth', null);
        session('user_auth_sign', null);
        $this->success( '退出成功', U('Login/index') );
    }
}