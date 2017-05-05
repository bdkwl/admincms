<?php
// Admin模块基础控制器
namespace Admin\Controller;
use Think\Controller;
use \Think\Build;
use Think\Page;

class BaseController extends Controller {
    protected function _initialize(){
        // 登录检测
        if (!is_login()) {
            $this->redirect('Admin/Login/index');
        }

        // 系统数据
        $sysConf = D('Config')->getConfList(1);
        $global_setting = [ 'title'=> $sysConf['web_title'][0] ];
        // 当前登录用户信息
        $user = D('Admin/User')->userInfo( session('user_auth') );

        // 权限检测
        $current_url = MODULE_NAME .'/'. CONTROLLER_NAME .'/'. ACTION_NAME;
        if ( $current_url !== 'Admin/Index/index' ) {
            if ( !D('Role')->checkAccess( $current_url ) ) {
                $this->error( '您所在的用户组没有访问权限', U('Index/index') );
            }
        }


        $this->assign('global_setting', $global_setting);
        $this->assign('user', $user);
    }

    /** 快速新建controller文件
     * @param $m 模块名-控制器名
     */
    public function bc($m){
        $arr = explode('-', $m);
        Build::buildController($arr[0], $arr[1]);
        echo "success";
    }

    /** 快速新建model文件
     * @param $m 模块名-模型名
     */
    public function bm($m){
        $arr = explode('-', $m);
        Build::buildModel($arr[0], $arr[1]);
        echo "success";
    }

    // 分页页码样式转化
    public function PageBootstrap ($count, $pagerows=0) {
        $pageRows = ($pagerows>0) ? $pagerows : C('PAGE_ROWS');
        $Page = new Page( $count, $pageRows );
        $Page->lastSuffix = false;//最后一页不显示为总页数
        $Page->setConfig('header','<li class="disabled page-info"><a>共<em>%TOTAL_ROW%</em>条  <em>%NOW_PAGE%</em>/%TOTAL_PAGE%页</a></li>');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('last','末页');
        $Page->setConfig('first','首页');
        $Page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

        $bootPage = bootstrap_page_style( $Page->show() );
        return $bootPage;
    }

}