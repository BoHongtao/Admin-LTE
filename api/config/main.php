<?php
$params = array_merge(
    require(__DIR__ . '/../../yii2-advanced/common/config/params.php'),
    require(__DIR__ . '/params.php')
);
return [
    'id' => 'app-back-admin',
    'language' => 'zh-CN',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'site/login',
    'controllerNamespace' => 'app\modules\v1\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'app\modules\v1\models\Operators',
            'enableAutoLogin' => true,
            //禁用session组件
            'enableSession'=>false,
        ],
        //api接收json格式数据
        'request' => [
            'cookieValidationKey' => 'ax5Ajm6GbpGhhmNX2lAnAfgwMj2RO2Cq',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        //本地日志
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => "@app/runtime/log-" . date('Y-m-d'). '.log'
                ],
            ],
        ],
        //配置路由
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        //数据库
        'db' => require(__DIR__ . '/db.php')
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
    'params' => $params,
];

