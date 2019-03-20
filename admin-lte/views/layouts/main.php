<?php
use yii\helpers\Html;
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
        <link rel="shortcut icon" href="static/images/favicon.ico" type="image/x-icon">
        <title>Admin-LTE</title>
        <?php $this->head() ?>
        <?php if(isset($this->blocks['head'])) echo $this->blocks['head']?>
    </head>
    <body class="skin-blue sidebar-mini">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <?php echo $this->render('_header') ?>
            <?php echo $this->render('_left') ?>
            <div class="content-wrapper">
                <section class="content-header">
                        <?php if (isset($this->params['breadcrumbs'])):?>
                        <div class="breadcrumbs" id="breadcrumbs">
                            <?= \app\components\BreadcrumbsNew::widget([
                                'links' => $this->params['breadcrumbs']
                            ]) ?>
                        <?php endif;?>
                    </div>
                </section>
                <section class="content">
                     <?php echo $content; ?>
                </section>
            </div>
        </div>
        <?php $this->endBody() ?>
        <script>
            $.widget.bridge('uibutton', $.ui.button);
            function modifyPwdMe(){
                fbwindow('修改密码',"<?php echo \yii\helpers\Url::toRoute('operators/modify-pwd') ?>",'');
            }
        </script>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <?php if(isset($this->blocks['script'])) echo $this->blocks['script']?>
    </body>
</html>
<?php $this->endPage() ?>