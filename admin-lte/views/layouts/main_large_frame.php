<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <!--<title><?= Html::encode($this->title) ?></title>-->
        <title></title>
        <?php $this->head() ?>
        <link href="static/css/toastr.css" rel="stylesheet" type="text/css"/>
        <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
<![endif]-->
        <?php if(isset($this->blocks['head'])) echo $this->blocks['head']?>
    </head>
    <body style="background-color: #fff">
        <?php $this->beginBody() ?>
            <?php echo $content; ?>
        <?php $this->endBody() ?>
    </body>
</html>
<script src="static/js/toastr.js" type="text/javascript"></script>
<script src="static/js/wang.js" type="text/javascript"></script>
<?php if(isset($this->blocks['script'])) echo $this->blocks['script']?>
<?php $this->endPage() ?>
