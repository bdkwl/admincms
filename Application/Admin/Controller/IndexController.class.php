<?php
// 控制台
namespace Admin\Controller;

class IndexController extends BaseController {
    public function index() {
        //Build::buildController('Admin', 'System');
        //Build::buildModel('Admin', 'System');
        $bread = [ 'title' => '首页' ];

        $this->assign( 'bread', $bread );
        $this->display();
    }

    public function config() {
        $bread = ['title'=>'网站设置', 'ptitle'=>'网站管理'];
        if (IS_POST) {
            $post = I('post.');
            $confDb = D('Config');
            $save = $confDb->saveConf($post);
            if ($save) {
                userDoLog($bread['title'] .'修改成功');

                $this->success( '修改成功', U('config') );
            } else {
                $this->error( $confDb->getError() );
            }
        } else {
            $group = 1;
            $config = D('Config')->getConfList( $group );

            $this->assign( 'bread', $bread );
            $this->assign( 'group', $group );
            $this->assign( 'conf', $config );
            $this->display();
        }
    }
}
