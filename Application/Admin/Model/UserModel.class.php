<?php
namespace Admin\Model;
use Think\Crypt;
use Think\Model;
class UserModel extends Model {
    protected $tableName = 'user';

    /** 检测用户是否登录
     * @return int 0：未登录|大于0:当前登录用户ID
     */
    public function is_login() {
        $user = session('user_auth');
        if ( empty( $user ) ) {
            return 0;
        } else {
            if ( session('user_auth_sign') == $this->auth_sign($user) ) {
                return $user['uid'];
            } else {
                return 0;
            }
        }
    }

    /** 用户登录
     * @return success 返回user_info信息|fail 返回错误信息
     */
    public function login( $login_name, $password ) {
        $map['username'] = $login_name;
        $map['user_stats'] = 1;

        //$userDb = M('User');
        $user_info = $this->where($map)->find();
        if (!$user_info) {
            $this->error = '用户不存在或被禁用！';
        } else {
            if ( passwd( $password ) !== $user_info['password'] ) {
                $this->error = '密码错误';
            } else {
                // 更新user信息
                $update = [
                    'userid' => $user_info['userid'],
                    'loginnum' => $user_info['loginnum']+1,
                    'lastip' => get_client_ip(),
                    'lasttime' => time()
                ];
                $this->save($update);

                return $user_info;
            }
        }
        return false;
    }

    /**
     * 设置登录状态
     */
    public function auto_login( $userlogin ) {
        $auth = [
            'uid' => $userlogin['userid'],
            'username' => $userlogin['username'],
        ];
        session('user_auth', $auth);
        session('user_auth_sign', $this->auth_sign($auth));
        return $this->is_login();
    }

    /**
     * 数据签名认证
     */
    public function auth_sign( $data ) {
        $code = $data['uid'].'|'.$data['username'].'|'.C('AUTH_KEY').'|'.$_SERVER['HTTP_USER_AGENT'];
        //$auth['sign'] = encrypt($sign);
        $sign = sha1( $code ); // 生成签名
        return $sign;
    }

    /**
     * 登录用户信息数据
     */
    public function userInfo( $auth ) {
        $uid = $auth['uid'];
        $user = M('User')->where([ 'userid' => $uid ])->find();
        $avatar = $this->avatarUrl( $user['avatar'] );
        $user['avatar'] = $avatar;
        $user['group'] = $this->getRole( $user['role_id'] );
        return $user;
    }

    // 获取用户组名称
    public function getRole( $roleid=0 ) {
        if ( !$roleid ) {
            return "系统管理员";
        }
        return M('Role')->where([ 'roleid' => $roleid ])->getField('rolename');
    }

    /**
     * 用户头像路径
     */
    public function avatarUrl( $media_id=1 ) {
        /*$data = M('media')->where( ['mediaid'=>$media_id, 'type'=>'avatar'] )->find();
        if ($data['mediaid'] == 1) {
            $avatar = __ROOT__.'/'.$data['realpath'];
        }*/
        $avatar = D('Admin/Upload')->getCover($media_id);
        return $avatar;
    }

    /** 插入用户登录日志
     * @param $code 登录状态码 1：登录成功 0：登录失败
     */
    public function addlog( $code ){
        $model = M('logUserLogin');
        $auth = session('user_auth');
        if ($auth) {
            $name = $auth['username'];
        } else {
            $name = '';
        }
        $data = [
            'username' => $name,
            'logintime' => date( 'Y-m-d H:i:s', time() ),
            'loginip' => get_client_ip(),
            'stats' => $code,
            'ua' => $_SERVER['HTTP_USER_AGENT']
        ];
        $model->data($data)->add();
    }

    /** 编辑用户信息
     * $data[uid]为空 新增用户| $data[uid]有值 修改用户
     * @param $data post数据
     * @return array
     */
    public function editUserInfo($data) {

        if ( !empty( $data['userid'] ) ) {
            // update
            if ( $this->where(['userid'=>$data['userid']])->data($data)->save() ) {
                userDoLog( '修改用户userid:'. $data['userid'] .'成功' );
                $result = ['msg' => 'update user success', 'status' => 1];
            } else {
                userDoLog( '修改用户userid:'. $data['userid'] .'失败' );
                $result = ['msg' => 'update user fail', 'status' => 0 ];
            }
        } else {
            // insert
            if ( $uid = $this->data($data)->add() ) {
                userDoLog( '新增用户userid:'. $uid .'成功' );
                $result = ['msg' => 'add user success', 'status' => 1];
            } else {
                userDoLog( '新增用户失败' );
                $result = ['msg' => 'add user fail', 'status' => 0];
            }
        }
        return $result;
    }
}