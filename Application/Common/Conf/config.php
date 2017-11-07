<?php
return array(
	//'配置项'=>'配置值'
    // 添加数据库配置信息
    'DB_TYPE'=>'mysql',// 数据库类型
    'DB_HOST'=>'127.0.0.1',// 服务器地址
    'DB_NAME'=>'jddoctor',// 数据库名
    'DB_USER'=>'root',// 用户名
    'DB_PWD'=>'123456',// 密码
    'DB_PORT'=>3306,// 端口
    'DB_PREFIX'=>'t_',// 数据库表前缀
    'DB_CHARSET'=>'utf8',// 数据库字符集

    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ .'/Public',
        '__JS__' => __ROOT__.'/Public/js',
        '__CSS__' => __ROOT__.'/Public/css',
        '__IMAGE__' => __ROOT__.'/Public/images',
    ),

    'URL_MODEL'=>'3',
);