<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/4/15
 * Time: 10:32
 */

$this->context->layout = false;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>404 Page not found</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="static/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="static/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="static/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="static/dist/css/skins/_all-skins.min.css">
</head>
<body style="background-color: #ECF0F5">
    <div id="wrap" style="margin-top: 100px">
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-yellow"> 404</h2>
                <div class="error-content">
                    <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                    <p>
                        We could not find the page you were looking for.
                        Meanwhile, you may <a href="<?= \yii\helpers\Url::toRoute(['operators/index']); ?>">return to dashboard</a> or try using the search form.
                    </p>
                </div>
            </div>
        </section
    </div>
</body>
</html>