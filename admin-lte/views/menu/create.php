<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;

$this->title = '创建菜单';
$this->params['breadcrumbs'][] = ["label"=>"菜单管理","url"=>\yii\helpers\Url::toRoute("menu/index")];
$this->params['breadcrumbs'][] = '增加';
?>
<style>
    .form_input input, .form_input textarea{width: 100% !important;}
    /*.form-group .radio{width: 30px !important;}*/
</style>
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <h1>
                菜单管理
                <small>
                    <i class="icon-double-angle-right"></i>
                    增加
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
                            'id' => 'add-region',
                            'options' => [
                                'class' => 'form-horizontal'
                            ],
                            'fieldConfig' => [
                                'template' => "{label}\n<div class='col-xs-6'>{input}{error}</div><div class='col-xs-4' style='padding-left:0'>{hint}</div>",
                                'labelOptions' => [
                                    'class' => 'col-xs-2 control-label'
                                ]
                            ],
                        ])
                        ?>
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'pid' )->dropDownList($menuArr)?>

                        <?= $form->field($model, 'act')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'attr')->textInput()->label('当前控制器名称') ?>

                        <?= $form->field($model, 'icon')->textInput()->label('显示图标的路径') ?>

                        <?= $form->field($model, 'sort')->textInput()->label('排序') ?>

                        <?= $form->field($model, 'display')->radioList(['2'=>'显示','1'=>'隐藏'])->label('显示')?>

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
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
