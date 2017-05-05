<?php
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model {
    protected $tableName = 'role';

    /** 编辑用户组信息数据
     * @param $data 用户组表单数据
     * @param $access 分配给用户组的权限id数组
     * @return array
     */
    public function editRole($data, $access) {
        // step1 save role data
        // step2  save role_access relation data
        if ( !empty( $data['roleid'] ) ) { // update
            if ( $this->where([ 'roleid' => $data['roleid'] ])->data($data)->save() ) {
                $setAccess = $this->setAccess( $data['roleid'], $access );
                if ( $setAccess ) {
                    userDoLog( '修改用户组rid:'. $data['roleid'] .'权限成功' );
                    $result = ['msg' => 'edit role and set access success', 'status' => 1];
                } else {
                    userDoLog( '修改用户组rid:'. $data['roleid'] .'权限失败' );
                    $result = ['msg' => 'edit role and set access fail', 'status' => 0];
                }
            } else {
                userDoLog( '更新用户组信息出错'.$this->getDbError() );
                $result = ['msg' => 'save role fail', 'status' => 0];
            }
        } else { //insert
            if ( $newRole = $this->data($data)->add() ) {
                $setAccess = $this->setAccess( $newRole, $access );
                if ( $setAccess ) {
                    userDoLog( '新增用户组rid:'. $newRole .'成功' );
                    $result = ['msg' => 'add role and set access success', 'status' => 1];
                } else {
                    userDoLog( '新增用户组rid:'. $newRole .'失败'  );
                    $result = ['msg' => 'add role and set access fail', 'status' => 0];
                }
            } else {
                userDoLog( '保存用户组信息出错' );
                $result = ['msg' => 'add role fail', 'status' => 0];
            }
        }
        return $result;


    }

    /** 分配用户组权限方法
     * @param $role_id int 用户组id
     * @param $access_arr array 分配的权限array
     * @return int role_access表数据保存返回结果 1:保存成功 0:保存失败
     */
    private function setAccess( $role_id, $access_arr) {
        foreach ( $access_arr as $key => $val ) {
            $temp = explode( '_', $val );
            //if ( $temp[0] > 0 ) {
                $data[] = [
                    'role_id' => $role_id,
                    'access_id' => $temp[1],
                    'created_at' => time()
                ];
            //}
        }
        $roleAccessDb = M('RoleAccess');
        $roleAccessDb->where(array('role_id' => $role_id))->delete();
        if ( $roleAccessDb->addAll($data) ) {
            return 1;
        } else {
            return 0;
        }
    }

    // 返回可用用户组数据
    public function roleAll() {
        return $this->field([ 'updated_time', 'created_at' ], true)->where([ 'stats' => 1 ])->select();
    }

    /** 检测用户组访问权限
     * @param $current_url 当前访问url
     * @return bool
     */
    public function checkAccess( $current_url ) {
        // 获取当前登录用户所在用户组id
        $user_role = D('user')->getFieldByUserid(session('user_auth.uid'), 'role_id');

        if ( $user_role !== '0' ) {
            $model = new Model();
            $table = [
                'role' => C('DB_PREFIX').'role',
                'access' => C('DB_PREFIX').'access',
                'role_access' => C('DB_PREFIX').'role_access'
            ];
            $sql = "select accessname from access as a join role_access as m on a.accessid=m.access_id where m.role_id=". $user_role;
            //dump($sql);
            $select = $model->query($sql);
            $modules = array();
            foreach ( $select as $k =>$val ) {
                $modules[] = $val['accessname'];
            }
            if ( in_array( $current_url, $modules ) ) {
                return true;
            }
        } else {
            return true; //系统管理员无需验证
        }
        return false;
    }

}