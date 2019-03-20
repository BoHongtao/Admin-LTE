<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="container-fluid">
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
        'validationUrl' => yii\helpers\Url::toRoute('operators/validate-pwd')
    ])?>
    <?= $form->field($model,'pwd')->label('旧密码')->passwordInput(['placeholder'=>'旧密码']) ?>
    <?= $form->field($model,'newpwd')->label('新密码')->passwordInput(['placeholder'=>'新密码']) ?>
    <?= $form->field($model, 'repwd')->label('确认密码')->passwordInput(['placeholder'=>'确认密码']) ?>
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
        url:$('#sys-user').attr('action'),
        type:'post',
        data:$('#sys-user').serialize(),
        success:function(data){
            if(data.code == 0){
                parent.showToast('success','修改密码成功','',1500);
                parent.closeModal();
                window.location.reload();
            }else{
                var desc = data.desc;
                for(var i in desc){
                    $('.field-pwd-'+i).addClass('has-error');
                    $('.field-pwd-'+i+' .help-block-error').html(desc[i][0]);
                }
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
<?php $this->endBlock() ?>
