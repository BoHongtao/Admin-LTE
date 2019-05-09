<?php
$params = array_merge(
    require(__DIR__ . '/params.php')
);
$config = [
    'id' => 'app-ccser-admin',
    'language' => 'zh-CN',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
//            'basePath' => '@api/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'ax5Ajm6GbpGhhmNX2lAnAfgwMj2RO2Cq',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'class'=>'yii\web\Request',
            'enableCookieValidation'=>false
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' =>true,

            'rules' => [
                [
                    'class'=>'yii\rest\UrlRule',
                    'controller' => ['v1/site'],
                ]
            ]
        ],
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
    'params' => $params,
];
if (YII_ENV_DEV) {
    //$config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1']
    ];
}
return $config;

