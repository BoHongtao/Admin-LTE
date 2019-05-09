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
    'controllerNamespace' => 'app\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'app\models\Operators',
            'enableAutoLogin' => true,
        ],
        //本地
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
    'params' => $params,
];

