<?php
// 用户组
namespace Admin\Controller;
use Think\Controller;
class RoleController extends BaseController {
    // 用户组列表
    public function index(){
        $bread = ['title'=>'管理用户组', 'ptitle'=>'网站管理'];
        $roleList = M('Role')->select();
        $this->assign( 'bread', $bread );
        $this->assign( 'list', $roleList );
        $this->display();
    }

    // 添加权限节点
    public function access() {
        if ( IS_POST ) {
            $data = [
                'accessname' => I('name'),
                'title' => I('title'),
                'pid' => I('pid', 0),
                'stats' => 1
            ];
            if ( M('access')->add($data) ) {
                $this->success( '添加成功', U('Role/access') );
            } else {
                $this->error( '添加失败', U('Role/access') );
            }
        } else {
            $access = M('access')->select();
            $this->assign( 'access', $access );
            $this->display();
        }
    }

    // 新增用户组
    public function create() {
        $bread = ['title'=>'新增用户组', 'ptitle'=>'管理用户组'];
        $access = $this->accessTree( M('access')->select() );
        //dump($access);


        $this->assign( 'bread', $bread );
        $this->assign( 'access', $access );
        $this->display( 'form' );
    }

    // 权限节点树状列表
    function accessTree( $arr, $pid=0 ) {
        $list = array();
        foreach ( $arr as $key => $val ) {
            if ( $val['pid'] == $pid ) {
                $val['_child'] = $this->accessTree( $arr, $val['accessid'] );
                $list[] = $val;
            }
        }
        return $list;
    }

    // 编辑用户组
    public function modify() {
        $bread = ['title'=>'编辑用户组', 'ptitle'=>'管理用户组'];
        $accessList = $this->accessTree( M('access')->select() );

        $rid = I('rid', 0);
        $role = M('Role')->where([ 'roleid' => $rid ])->find();
        $roleAccess = M('RoleAccess')->where([ 'role_id' => $rid ])->getField('access_id', true);

        $this->assign( 'bread', $bread );
        $this->assign( 'access', $accessList );
        $this->assign( 'roleinfo', $role );
        $this->assign( 'roleaccess', $roleAccess );
        $this->display( 'form' );
    }

    // 存储数据
    public function store() {
        $post = I('post.');
        //dump($post);
        if ( !empty( $post['roleid'] ) ) {
            // save
            $store['roleid'] = $post['roleid'];
        } else {
            // add
            $store['created_at'] = time();
        }
        $store['rolename'] = $post['username'];
        $store['updated_at'] = time();
        $stats = ( isset( $post['lock'] ) && $post['lock'] ) ? 0 : 1;  // 勾选了禁用user_stas=0, 否则=1
        $store['stats'] = $stats;

        $result = D('Role')->editRole( $store, $post['access'] );
        if ( $result['status'] ) {
            $this->success( '操作成功', U('Role/index') );
        } else {
            $this->error( '操作失败，请重试', U('Role/index') );
        }
    }


}