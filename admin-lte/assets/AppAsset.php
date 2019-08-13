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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/bower_components/bootstrap/dist/css/bootstrap.min.css',
        'static/bower_components/font-awesome/css/font-awesome.min.css',
//        'static/bower_components/Ionicons/css/ionicons.min.css',
        'static/dist/css/AdminLTE.min.css',
        'static/dist/css/skins/_all-skins.min.css',
//        'static/bower_components/morris.js/morris.css',
//        'static/bower_components/jvectormap/jquery-jvectormap.css',
//        'static/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
//        'static/bower_components/bootstrap-daterangepicker/daterangepicker.css',
//        'static/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        'static/css/toastr.css',
        'static/css/table_row_hidden.css',
    ];
    public $js = [
        "static/bower_components/jquery-ui/jquery-ui.min.js",
        "static/bower_components/bootstrap/dist/js/bootstrap.min.js",
//        "static/bower_components/raphael/raphael.min.js",
//        "static/bower_components/morris.js/morris.min.js",
//        "static/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js",
//        "static/bower_components/jquery-knob/dist/jquery.knob.min.js",
//        "static/bower_components/moment/min/moment.min.js",
//        "static/bower_components/bootstrap-daterangepicker/daterangepicker.js",
//        "static/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
//        "static/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js",
//        "static/bower_components/jquery-slimscroll/jquery.slimscroll.min.js",
//        "static/bower_components/fastclick/lib/fastclick.js",
        "static/dist/js/adminlte.min.js",
//        "static/dist/js/demo.js",
        "static/js/layer.js",
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
