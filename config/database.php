<?php

return [
    // 默认使用的数据库连接配置
    'default'         => 'mysql',

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    // true为自动识别类型 false关闭
    // 字符串则明确指定时间字段类型 支持 int timestamp datetime date
    'auto_timestamp'  => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 时间字段配置 配置格式：create_time,update_time
    'datetime_field'  => '',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'            => 'mysql',
            // 服务器地址
            'hostname'        => '127.0.0.1',
            // 数据库名
            'database'        => env('DATABASE_NAME'),
            // 用户名
            'username'        => env('DATABASE_USER'),
            // 密码
            'password'        => env('DATABASE_PASS'),
            // 端口
            'hostport'        => '3306',
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8
            'charset'         => 'utf8mb4',
            // 数据库表前缀
            'prefix'          => '',

            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'          => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'     => false,
            // 读写分离后 主服务器数量
            'master_num'      => 1,
            // 指定从服务器序号
            'slave_no'        => '',
            // 是否严格检查字段是否存在
            'fields_strict'   => true,
            // 是否需要断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql'     => false,
            // 开启字段缓存
            'fields_cache'    => true,
        ],

        // 更多的数据库配置信息
    ],
];
