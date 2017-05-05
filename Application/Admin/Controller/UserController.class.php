<?php
// 用户
namespace Admin\Controller;
use Think\Controller;
use Think\Page;

class UserController extends BaseController {
    // 用户列表
    public function index(){
        $bread = ['title'=>'管理用户', 'ptitle'=>'网站管理'];

        $keyword = I( 'keyword', '', 'string' );
        if ( $keyword ) {
            $condition = array( 'like', '%'. $keyword .'%' );
            $map['userid|username|truename|email|phone'] = array(
                $condition,
                $condition,
                $condition,
                $condition,
                $condition,
                '_multi' => true
            );
        }

        $userDb = M('user');
        $map['user_stats'] = array( 'egt', 0 ); // 用户状态
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1; // 当前页数
        $count = $userDb->where($map)->count(); // 数据总数
        $page_rows = C('PAGE_ROWS'); //

        $Page = new Page( $count, C('PAGE_ROWS') );
        foreach ( $map as $key => $val ) {
            $Page->parameter[$key] = urlencode( $val );
        }
        $userList = $userDb->field([ 'avatar', 'wx_opeid' ], true)->where($map)->order('userid')->page($p, $page_rows)->select();
        foreach ( $userList as $k => $val ) {
            $userList[$k]['role'] = D('Admin/User')->getRole( $val['role_id'] );
        }

        $this->assign( 'bread', $bread );
        $this->assign( 'list', $userList );
        $this->assign( 'page', $this->PageBootstrap( $count, $page_rows ) ); //页码
        $this->display();
    }

    // 新增用户
    public function create() {
        $bread = [ 'title' => '新增用户', 'ptitle' => '管理用户' ];

        $this->assign( 'bread', $bread );
        $this->assign( 'rolelist', D('Role')->roleAll() );
        $this->display('form');
    }


    // 编辑用户
    public function modify() {
        $bread = [ 'title' => '编辑用户', 'ptitle' => '管理用户' ];
        $id = I('id', 0);
        $userDb = D('User');
        $userInfo = $userDb->where([ 'userid' => $id ])->find();
        $userInfo['avatar_url'] = $userDb->avatarUrl($userInfo['avatar']);

        $this->assign( 'bread', $bread );
        $this->assign( 'userinfo', $userInfo );
        $this->assign( 'rolelist', D('Role')->roleAll() );
        $this->display('form');
    }

    // 存储数据
    public function store() {
        $post = I('post.');
        //dump($data);
        $store = [];
        if ( !empty( $post['uid'] ) ) { // save
            $store['userid'] = $post['uid'];
            // if input password
            if ( !empty( $post['password'] ) ) {
                if ( $post['repwd'] != $post['password'] ) {
                    $this->error( '两次密码输入不一致' );
                }
                $store['password'] = passwd( $post['password'] );
            }
            // if input avatar
            if ( !empty( $post['avatar'] ) ) {
                $store['avatar'] = $post['avatar'];
            }
        } else { // add
            if ( $post['repwd'] != $post['password'] ) {
                $this->error( '两次密码输入不一致' );
            }
            $store['password'] = passwd( $post['password'] );
        }
        $user_stats = ( isset( $post['lock'] ) && $post['lock'] ) ? 0 : 1;  // 勾选了禁用user_stas=0, 否则=1

        $store['username'] = $post['username'];
        $store['truename'] = $post['truename'];
        $store['email'] = $post['email'];
        $store['phone'] = $post['phone'];
        $store['role_id'] = $post['role'] ? $post['role'] : 0;
        $store['user_stats'] = $user_stats;
        $store['updated_at'] = time();

        $result = D('User')->editUserInfo($store);
        if ( $result['status'] ) {
            $this->success( '操作成功', U('User/index') );
        } else {
            $this->error ( '操作失败，请重试', U('User/index') );
        }
    }

    // 用户锁定状态设置
    public function locklogin() {
        $uid = I('uid', 0);
        $stats = I('stat', 0); // 用户状态 1:开启用户 0:禁用用户
        $log = ( $stats > 0 ) ? '开启' : '锁定';

        if ( M('user')->where([ 'userid'=>$uid ])->setField('user_stats', $stats) ) {
            userDoLog( '修改uid为'. $uid. '用户状态为: '. $log .'成功' );
            $this->success( '操作成功', U('User/index') );
        } else {
            userDoLog( '修改uid为'. $uid. '用户状态为: '. $log .'失败' );
            $this->error( '操作失败，请重试', U('User/index') );
        }
    }

    // 删除用户
    public function del() {
        $id = I('uid', 0);
        if ( M('user')->where([ 'userid' => $id ])->delete() ){
            userDoLog( '删除用户uid:'. $id .'成功' );
            $this->success( '操作成功', U('User/index') );
        } else {
            userDoLog( '删除用户uid:'. $id .'失败' );
            $this->error( '操作失败，请重试', U('User/index') );
        }
    }

}