<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="container" style="width:100%;overflow: hidden">
    <div class="alert alert_header_style" style="background: #d7f1ff;color: #4583ce">
        您将对 <?= $model->operator_name?> 用户进行修改密码：
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'sys-user',
        'fieldConfig' => [
            'template' => "{label}\n<div class='col-xs-5'>{input}</div><div class='col-xs-8 col-xs-offset-4' style='text-align: center;padding-left: 150px'>{error}</div>",
            'labelOptions' => [
                'class' => 'col-xs-4 control-label'
            ],
        ],
        'options' => [
            'class' => 'form-horizontal'
        ],
        'enableAjaxValidation' => true,
        'validationUrl' => yii\helpers\Url::toRoute('operators/validate-reset-pwd')
    ])?>
    <?= $form->field($pwd,'new_pwd')->label('新密码')->passwordInput(['placeholder'=>'新密码']) ?>
    <?= $form->field($pwd, 're_pwd')->label('确认密码')->passwordInput(['placeholder'=>'确认密码']) ?>
    <div class="form-group text-center">
        <?= Html::submitButton('修改', ['class' => 'btn btn-info', 'style' => 'width:100px','id'=>'modify-pwd-btn']) ?>
    </div>
    <?php ActiveForm::end()?>
</div>
<?php $this->beginBlock('script') ?>
<script type="text/javascript">
$(document).on("beforeSubmit", "#sys-user", function () {
    $('#modify-pwd-btn').attr('disabled',true);
    $.ajax({
        url:"<?= Url::toRoute(['operators/reset-pwd'])?>&id="+<?= $model['id']?>,
        type:'post',
        data:$('#sys-user').serialize(),
        success:function(data){
            if(data.code == 0){
                parent.showToast('success','重置密码成功','',1500);
                parent.closeModal();
                window.location.reload();
            }else{
                parent.showToast('error','重置密码失败','',1500);
                $('#modify-pwd-btn').attr('disabled',false);
            }
        },
        error:function(data){
            parent.showToast('error','系统忙，请稍后重试','',1500);
            $('#modify-pwd-btn').attr('disabled',false);
        }
    });
    return false; // Cancel form submitting.
});
</script>
<style type="text/css">
	.btn:focus{display: none;}
	.btn_green{background: #16b8bd;color: #fff;outline: none;}
	.btn_green:hover{background: #09adad;color: #fff;}
	.btn_green:active{background: #0a9e9a;color: #fff;}
</style>
<?php $this->endBlock() ?>
