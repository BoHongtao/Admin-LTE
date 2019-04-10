<?php

use yii\helpers\Html;
use yii\web\View;
use app\components\AjaxPager;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->params['breadcrumbs'][] = ["label"=>"角色管理","url"=>Url::toRoute("role/index")];
$this->params['breadcrumbs'][] = '增加';
?>
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <h1>
                角色管理
                <small>
                    <i class="icon-double-angle-right"></i>
                    增加
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <section class="panel">
                    <div class="panel-body formBox">
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'add-role',
                                    'options' => [
                                        'class' => 'form-horizontal'
                                    ],
                                    'fieldConfig' => [
                                        'template' => "{label}\n<div class='col-xs-6'>{input}{error}</div><div class='col-xs-4' style='padding-left:0'>{hint}</div>",
                                        'labelOptions' => [
                                            'class' => 'col-xs-2 control-label'
                                        ]
                                    ],
                                    'enableAjaxValidation' => true,
                                    'validationUrl' => yii\helpers\Url::toRoute('role/validate-add')
                                ])
                        ?>
                        <?= $form->field($model, 'name')->textInput(['placeholder' => '角色名'])->hint('*', ['class' => 'help-block text-danger']); ?>
                        <?= $form->field($model, 'description')->textArea(['rows' => '3']) ?> 
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <?= Html::submitButton('确定', ['class' => 'btn btn-info', 'name' => 'login-button', 'id' => 'manager-add-btn']) ?>
                                &nbsp; &nbsp; &nbsp;
                                <?= Html::button('取消', ['class' => 'btn btn-default', 'style' => 'margin-left:5px', 'id' => 'manager-cancle-btn', 'onclick' => 'history.go(-1)']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('script') ?>
<script type="text/javascript">
    $(document).on("beforeSubmit", "#add-role", function () {
        $.ajax({
            url: $('#add-role').attr('action'),
            type: 'post',
            data: $('#add-role').serialize(),
            success: function (data) {
                if (data.code == 200) {
                    showToast('success', '添加角色成功', '2S后返回', 5500);
                    setTimeout('window.location.href="<?= Url::toRoute(['role/index']) ?>"', 1500);
                } else {
                    showToast('error', '添加角色失败', data.desc, 2000);
                    $('#manager-add-btn').html('确定');
                    $('#manager-add-btn').attr('disabled', false);
                    $('#manager-cancle-btn').attr('disabled', false);
                }
            }
        });
        return false; // Cancel form submitting.
    });
</script>
<?php
$this->endBlock()?>