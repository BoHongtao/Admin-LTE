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
        <?= Html::encode($this->title) ?>
        <?php $this->head() ?>
        <link href="static/css/toastr.css" rel="stylesheet" type="text/css"/>
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
