<?php
/** 
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAssetLogin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/bower_components/bootstrap/dist/css/bootstrap.min.css',
        'static/bower_components/font-awesome/css/font-awesome.min.css',
        'static/bower_components/Ionicons/css/ionicons.min.css',
        'static/dist/css/AdminLTE.min.css',
        'static/plugins/iCheck/square/blue.css',
    ];
    public $js = [
        'static/bower_components/jquery/dist/jquery.min.js',
        'static/bower_components/bootstrap/dist/js/bootstrap.min.js',
        'static/plugins/iCheck/icheck.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
