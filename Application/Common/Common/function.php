<?php
// common function

// 登录密码加密
function passwd( $pwd ) {
    return sha1( encrypt( $pwd ) );
}

// 检测登录状态
function is_login() {
    return D('Admin/user')->is_login();
}

// 加密字符串
function encrypt( $str, $key ) {
    if (!$key) {
        $key = C('AUTH_KEY') ?: 'thinkphp';
    }
    $cr = new \Think\Crypt();
    return $cr::encrypt($str, $key);
}

// 解密字符串
function decrypt( $str, $key ) {
    if (!$key) {
        $key = C('AUTH_KEY') ?: 'thinkphp';
    }
    $cr = new \Think\Crypt();
    return $cr::decrypt($str, $key);
}

// 信息提示
function setAlert( $msg, $url='' ) {
    echo "<script>alert('". $msg ."')</script>";
    exit();
}

// 获取文件路径
function get_cover( $id=null, $type=null ) {
    return D('Admin/Upload')->getCover($id, $type);
}

// 插入用户操作日志 $log:操作事件内容
function userDoLog( $log ) {
    $auth = session('user_auth');
    if ($auth) {
        $name = $auth['username'];
    } else {
        $name = '';
    }
    $data = [
        'ip' => get_client_ip(),
        'time' => date( 'Y-m-d H:i:s', time() ),
        'username' => $name,
        'doing' => $log,
        'action' => __ACTION__
    ];
    M('logUserDo')->add($data);
}