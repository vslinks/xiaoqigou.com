<?php
return array(
    //'配置项'=>'配置值'\


    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'tp1229',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '960226',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  '',    // 数据库表前缀
    'DB_FIELDS_CACHE'       =>  false,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8

    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式


    'SHOW_PAGE_TRACE'       =>  true,  // 开启trace.
    'PAGE_SIZE'             =>  3,    //  设置分页尺寸
    'PAGE_THEME'                  =>  '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
    //>>定义模板替换字符串
    'TMPL_PARSE_STRING'     =>  array(
        '__EXT__'     =>    MY_URL .    '/Public/Admin/ext',
        '__CSS__'     =>    MY_URL .    '/Public/Admin/css',
        '__JS__'      =>    MY_URL .    '/Public/Admin/js',
        '__IMG__'     =>    MY_URL .    '/Public/Admin/ext/blog/img',
    ),
);