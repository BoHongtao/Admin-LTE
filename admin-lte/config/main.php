<?php
$params = array_merge(
    require(__DIR__ . '/../../yii2-advanced/common/config/params.php'),
    require(__DIR__ . '/params.php')
);
return [
    'id' => 'app-back-admin',
//    'language' => 'zh-TW',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager'=>[
            'basePath' => 'static/assets',
            'baseUrl' => 'static/assets',
            'linkAssets' => true,
            'bundles'=>[
                'yii\bootstrap\BootstrapAsset' => false,
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
            'defaultRoles' => ['系统管理员'],
        ],
        'db' => require(__DIR__ . '/db.php'),
        //配置语言包
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'common' => 'common.php',
                    ],
                ],
            ],
        ],
        //配置缓存组件--文件缓存
        'cache'=>[
            'class'=>'yii\caching\FileCache'
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
            ],
        ],
    ],
    'params' => $params,
];
