<?php

use yii\helpers\Html;
use yii\web\View;
use app\components\AjaxPager;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$this->params['breadcrumbs'][] = ["label"=>"管理员","url"=>Url::toRoute("operators/index")];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <h1>
                管理员
                <small>
                    <i class="icon-double-angle-right"></i>
                    修改
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <section class="panel">
                    <div class="panel-body formBox">
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'operators-update-form',
                                    'options' => [
                                        'class' => 'form-horizontal'
                                    ],
                                    'fieldConfig' => [
                                        'template' => "{label}\n<div class='col-xs-6'>{input}{error}</div><div class='col-xs-3' style='padding-left:0'>{hint}</div>",
                                        'labelOptions' => [
                                            'class' => 'col-xs-3 control-label'
                                        ]
                                    ],
                                    'enableAjaxValidation' => true,
                                    'validationUrl' => yii\helpers\Url::toRoute(['operators/validate-add', 'id' => $model->id])
                                ])
                        ?>
                        <?= $form->field($model, 'operator_name')->textInput(['placeholder' => '用户名','readonly'=>'readonly'])->hint('*', ['class' => 'help-block text-danger']); ?>
                        <?= $form->field($model, 'contact_name')->textInput(['placeholder' => '联系人']) ?>
                        <?= $form->field($model, 'contact_phone')->textInput(['placeholder' => '联系电话']) ?>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9" style="padding-left: 158px;">
                                <?= Html::submitButton('确定', ['class' => 'btn btn-info', 'name' => 'login-button', 'id' => 'orgsales-update-btn']) ?>
                                &nbsp; &nbsp; &nbsp;
                                <?= Html::button('取消', ['class' => 'btn btn-default', 'style' => 'margin-left:5px', 'id' => 'orgsales-cancle-btn', 'onclick' => 'history.go(-1)']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </section>
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<!-- page end-->
<?php $this->beginBlock('script') ?>
<script type="text/javascript">
    //选择父级单位
    function chooseRole() {
        fbwindow("父级单位", "<?= \yii\helpers\Url::toRoute('operators/select-role') ?>", 'l');
    }
    function selectCorp(name) {
        $('.modal-open').removeClass('modal-open');
        //自动生成的，根据表单提交
        $('#authassignment-item_name').val(name);
    }
    $(document).on("beforeSubmit", "#operators-update-form", function () {
        $('#operators-update-btn').attr('disabled', true);
        $('#operators-cancle-btn').attr('disabled', true);
        $.ajax({
            url: $('#operators-update-form').attr('action'),
            type: 'post',
            data: $('#operators-update-form').serialize(),
            success: function (data) {
                if (data.code == 200) {
                    showToast('success', '修改用户成功', '', 2500);
                    setTimeout('window.location.href="<?= Url::toRoute(['operators/index']) ?>"', 1500);
                } else {
                    var desc = data.desc;
                    showToast('error', '修改用户失败', desc, 2500);
                    $('#orgsales-update-btn').attr('disabled', false);
                    $('#orgsales-cancle-btn').attr('disabled', false);
                }
            },
            error: function (data) {
                $('#orgsales-update-btn').attr('disabled', false);
                $('#orgsales-cancle-btn').attr('disabled', false);
                showToast('error', '系统忙，请稍后重试', '', 2000);
            }
        });
        return false;
    });
</script>
<?php
$this->endBlock()?>