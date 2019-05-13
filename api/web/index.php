<?php
defined ( 'YII_DEBUG' ) or define ( 'YII_DEBUG', true );
defined ( 'YII_ENV' ) or define ( 'YII_ENV', 'dev' );
require (__DIR__ . '/../../yii2-advanced/vendor/autoload.php');
require (__DIR__ . '/../../yii2-advanced/vendor/yiisoft/yii2/Yii.php');
require (__DIR__ . '/../../yii2-advanced/common/config/bootstrap.php');
require (__DIR__ . '/../config/bootstrap.php');
$config = yii\helpers\ArrayHelper::merge ( require (__DIR__ . '/../../yii2-advanced/common/config/main.php'), require (__DIR__ . '/../config/main.php'), require (__DIR__ . '/../config/main-local.php') );
$application = new yii\web\Application ( $config );
$application->run ();