<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/5/7
 * Time: 10:08
 */

namespace app\assets;

use yii\web\AssetBundle;

class AppAssetFrame extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        "static/bower_components/jquery-ui/jquery-ui.min.js",
        'static/js/dialog.js',
        'static/js/wang.js',
        'static/js/toastr.js',
        'static/js/jquery-form.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}
