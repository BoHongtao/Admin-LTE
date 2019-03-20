<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->params['breadcrumbs'][] = ["label"=>"管理员","url"=>Url::toRoute("operators/index")];
$this->params['breadcrumbs'][] = '增加';
?>
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <h1>
                管理员
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
                                    'id' => 'add-operators',
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
                                    'validationUrl' => yii\helpers\Url::toRoute('operators/validate-add')
                                ])
                        ?>
                        <?= $form->field($model, 'operator_name')->textInput(['placeholder' => '用户名'])->hint('*', ['class' => 'help-block text-danger']); ?>
                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => '密码'])->hint('*', ['class' => 'help-block text-danger']) ?>
                        <?= $form->field($model, 're_pwd')->passwordInput(['placeholder' => '确认密码'])->hint('*', ['class' => 'help-block text-danger']) ?>
                        <?= $form->field($model, 'role_id')->dropDownList($roles,['prompt' => '----请选择角色----'])->hint('*', ['class' => 'help-block text-danger']); ?>
                        <?= $form->field($model, 'contact_name')->textInput(['placeholder' => '联系人']) ?>
                        <?= $form->field($model, 'contact_phone')->textInput(['placeholder' => '联系电话']) ?>
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
    $(document).on("beforeSubmit", "#add-operators", function () {
        $('#add-operators').ajaxSubmit({
            url: $('#add-operators').attr('action'),
            type: 'post',
            data: $('#add-operators').serialize(),
            success: function (data) {
                if (data.code == 0) {
                    showToast('success', '添加用户成功', '2S后返回', 5500);
                    setTimeout('window.location.href="<?= Url::toRoute(['operators/index']) ?>"', 1500);
                } else {
                    showToast('error', '添加用户失败', data.desc, 3000);
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