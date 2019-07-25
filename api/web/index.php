<?php

defined ( 'YII_DEBUG' ) or define ( 'YII_DEBUG', true );
defined ( 'YII_ENV' ) or define ( 'YII_ENV', 'dev' );
# 如果有预请求，直接屏蔽
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Sessionid, Signature, Userid, Userphone");
    header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
    exit;
}

require (__DIR__ . '/../../yii2-advanced/vendor/autoload.php');
require (__DIR__ . '/../../yii2-advanced/vendor/yiisoft/yii2/Yii.php');
require (__DIR__ . '/../../yii2-advanced/common/config/bootstrap.php');
require (__DIR__ . '/../config/bootstrap.php');

require (__DIR__ . '/../components/function.php');

$config = yii\helpers\ArrayHelper::merge (
    require (__DIR__ . '/../../yii2-advanced/common/config/main.php'),
    require (__DIR__ . '/../config/main.php'),
    require (__DIR__ . '/../config/main-local.php')
);
$application = new yii\web\Application ( $config );
$application->run ();
