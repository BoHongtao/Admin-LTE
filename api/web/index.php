<?php

define('YII_DEBUG', TRUE);
defined('YII_ENV') or define('YII_ENV', 'dev');  //开发环境
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require(__DIR__ . '/../../yii2-advanced/vendor/autoload.php');
require(__DIR__ . '/../../yii2-advanced/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../yii2-advanced/common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../yii2-advanced/common/config/main.php'), require(__DIR__ . '/../config/main.php')
);
$application = new yii\web\Application($config);
$application->run();
