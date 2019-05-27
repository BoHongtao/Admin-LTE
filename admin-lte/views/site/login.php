<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="login-box">
    <div class="login-logo">
        <b>Admin</b>LTE
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?php
        $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                'options' => [
                    "class" => ""
                ],
                'template' => "{input}\n<div>{error}</div>",
                'inputOptions' => ['class' => 'form-control'],
                'errorOptions' => ['class' => 'help-block help-block-error my-help-error']
            ],
        ]);
        ?>
        <fieldset>
            <div class="form-group has-feedback">
                <?= $form->field($model, 'userName')->textInput(['placeholder' => 'Username']) ?>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']) ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                    <span class="block input-icon input-icon-right">
                        <?= $form->field($model, 'code')->widget(yii\captcha\Captcha::className(), ['captchaAction' => 'site/captcha',
                                'template' => '<div class="row"><div class="col-xs-6">{input}</div><div class="col-xs-6 codeimg">{image}</div></div>',
                                'imageOptions' => ['alt' => '点击换图', 'title' => '点击换图'],
                                'options' => [
                                    'placeholder' => 'Captcha',
                                    'class' => 'form-control',
                                ]
                            ]);
                        ?>
                    </span>
            </div>
            <div class="clearfix">
                <?= Html::submitButton('<i class="icon-key"></i> 登 录', ['class' => 'btn btn-sm btn-primary col-xs-12', 'name' => 'login-button']) ?>
            </div>
            <div class="space"></div>
        </fieldset>
        <?php ActiveForm::end() ?>
    </div>
</div>