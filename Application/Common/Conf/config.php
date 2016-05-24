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

    'DEFAULT_CONTROLLER'    =>  'Admin', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'login', // 默认操作名称
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

    /* SESSION设置 */
    'SESSION_AUTO_START' => true, // 是否自动开启Session
    'SESSION_TYPE'       => 'Redis', //session类型
    'SESSION_PERSISTENT' => 1, //是否长连接(对于php来说0和1都一样)
    'SESSION_CACHE_TIME' => 1, //连接超时时间(秒)
    'SESSION_EXPIRE'     => 0, //session有效期(单位:秒) 0表示永久缓存
    'SESSION_PREFIX'     => '', //session前缀
    'SESSION_REDIS_HOST' => '127.0.0.1', //分布式Redis,默认第一个为主服务器
    'SESSION_REDIS_PORT' => '6379', //端口,如果相同只填一个,用英文逗号分隔
    'SESSION_REDIS_AUTH' => '', //Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔
);