<?php
return array(
    'SHOW_ERROR_MSG' =>  true,
    'SHOW_PAGE_TRACE' =>true,

    /* 项目设置 */
    'AUTH_KEY' => 'loan', // 登录通信key
    // 文件上传配置
    'UPLOAD_CONFIG' => array(
        'mimes'    => '',
        'maxSize'  => 2 * 1024 * 1024, // 上传的文件大小限制 (0-不做限制，默认为2M，后台配置会覆盖此值)
        'autoSub'  => true,
        'subName'  => array('date', 'Ymd'),
        'rootPath' => './Public/uploads/',
        //'savePath' => '', // 保存路径
        'saveName' => array('uniqid', ''),
        'saveExt'  => '',
        'replace'  => false,
        'hash'     => true,
        'callback' => false,
    ),
    'PAGE_ROWS' => 15, //分页列表一页显示记录数

    /* URL设置 */
    'URL_MODEL'             =>  2, // URL访问模式,
    'URL_HTML_SUFFIX'       => '', // 伪静态后缀

    // 加载扩展配置文件
    'LOAD_EXT_CONFIG' => 'db',
);