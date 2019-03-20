<?php
return [
    'host' => [
        'ip' => '10.25.156.232',
        'project' => 'sand-dev',
        'application' => 'admin',
    ],
    'adminEmail' => 'admin@example.com',
    'title' => '沙盘',
    'pageSize' => 13,
    'sources' => require(__DIR__ . '/sources.php'),

    'geo_map_key' => 'AIzaSyCNfNwVmOUpl_nC4UJgp71cPgBTmMvNUgo',
    'tencent_map_key'=>'DBEBZ-TPAWO-LPNWO-S4RBQ-DIV27-R4FCH',

    'source_query_url'=>'/mobile-index.php?r=sources/index&sid=',

    //服务器计划任务配置
    'IPC_task'=>[
        'ID'=>'A',
        'CMD'=>10,
        'MSGTYPE'=> 1001,
        'FILE'=>'/data/home/whost/cfg/ajob_mq.key'
    ],

    'file_upload' => 'uploads/', //文件上传目录 相对于web
    'WECHAT' => [
        /**
         * Debug 模式，bool 值：true/false
         *
         * 当值为 false 时，所有的日志都不会记录
         */
        'debug'  => false,
        /**
         * 账号基本信息，请从微信公众平台/开放平台获取
         */
        'app_id'  => 'wxd7ae15ca1ae2d05c',         // AppID
        'secret'  => 'b065b1efe145218a5409d2a19dac45de',     // AppSecret
        'token'   => 'Abcd1234',          // Token
        'aes_key' => 'gz3ZPcut2hZHKZOyMi9QtaUpH5BwXBwpMJd4GoRmTK9',                    // EncodingAESKey，安全模式下请一定要填写！！！
        /**
         * 日志配置
         *
         * level: 日志级别, 可选为：
         *         debug/info/notice/warning/error/critical/alert/emergency
         * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log' => [
            'level'      => 'debug',
            'permission' => 0777,
            'file'       => '/data/home/whost/www/gotrip/trip/web/easywechat.log',
        ],
        /**
         * OAuth 配置
         *
         * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
         * callback：OAuth授权完成后的回调页地址
         */
        'oauth' => [
            'scopes'   => ['snsapi_userinfo'],
            'callback' => '/trip/admin/mobile-index.php?r=wechat-api/callback',
        ],
        /**
         * Guzzle 全局设置
         *
         * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
         */
        'guzzle' => [
            'timeout' => 3.0, // 超时时间（秒）
            //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
        ],
        // 小程序配置
        'mini_program' => [
            'app_id'   => 'wx213d46a878b1ab2a',
            'secret'   => 'acda24dd56e517bf4feacf8d4df8c947',
            // token 和 aes_key 开启消息推送后可见
            'token'    => 'your-token',
            'aes_key'  => 'your-aes-key'
        ],
    ]
];