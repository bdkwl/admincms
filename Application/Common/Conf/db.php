<?php
if (ENV == 'dev') {
    $db_config = [
        'DB_TYPE'               =>  'mysql',     // 数据库类型
        'DB_HOST'               =>  '127.0.0.1', // 服务器地址
        'DB_NAME'               =>  'loancms',          // 数据库名
        'DB_USER'               =>  'root',      // 用户名
        'DB_PWD'                =>  'root',          // 密码
        'DB_PORT'               =>  '3306',        // 端口
        'DB_PREFIX'             =>  '',    // 数据库表前缀
        'DB_FIELDS_CACHE'       =>  false,        // 启用字段缓存
    ];
} else {
    $db_config = [
        'DB_TYPE'               =>  'mysql',     // 数据库类型
        'DB_HOST'               =>  '127.0.0.1', // 服务器地址
        'DB_NAME'               =>  'loancms',          // 数据库名
        'DB_USER'               =>  'loancms',      // 用户名
        'DB_PWD'                =>  'loancms',          // 密码
        'DB_PORT'               =>  '3306',        // 端口
        'DB_PREFIX'             =>  '',    // 数据库表前缀
        'DB_FIELDS_CACHE'       =>  false,        // 启用字段缓存
    ];
}

return $db_config;