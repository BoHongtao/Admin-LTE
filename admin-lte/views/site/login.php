<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<style>
    .codeimg{text-align: right;}
    .codeimg img{ max-height: 34px; max-width: 100%; cursor: pointer;}
</style>
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center" style="height: 80px">
                        <!--<h1>
                            <i class="icon-leaf green"></i>
                            <span class="red">Ace</span>
                            <span class="white" id="id-text2">Application</span>
                        </h1>
                        <h4 class="blue" id="id-company-text">&copy; Company Name</h4>-->
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="icon-coffee green"></i>
                                        请输入您的登录信息
                                    </h4>

                                    <div class="space-6"></div>
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
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <?= $form->field($model, 'userName')->textInput(['placeholder' => 'Username']) ?>
                                                <i class="icon-user"></i>
                                            </span>
                                        </label>
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']) ?>
                                                <i class="icon-lock"></i>
                                            </span>
                                        </label>
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <?=
                                                $form->field($model, 'code')->widget(yii\captcha\Captcha::className(), ['captchaAction' => 'site/captcha',
                                                        'template' => '<div class="row"><div class="col-xs-6">{input}</div><div class="col-xs-6 codeimg">{image}</div></div>',
                                                        'imageOptions' => ['alt' => '点击换图', 'title' => '点击换图'],
                                                        'options' => [
                                                            'placeholder' => 'Captcha',
                                                            'class' => 'form-control',
                                                        ]
                                                    ]
                                                );
                                                ?>
                                            </span>
                                        </label>
                                        <div class="clearfix"><?= Html::submitButton('<i class="icon-key"></i> 登 录', ['class' => 'btn btn-sm btn-primary col-xs-12', 'name' => 'login-button']) ?></div>
                                        <div class="space"></div>
                                    </fieldset>
                                    <?php ActiveForm::end() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>