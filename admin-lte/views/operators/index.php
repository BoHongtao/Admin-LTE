<?php
use yii\helpers\Url;
$this->params['breadcrumbs'][] = ["label"=>"管理员","url"=>Url::toRoute("operators/index")];
$this->params['breadcrumbs'][] = '列表';
?>
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <form id="searchform" class="form-inline" role="form">
                <div class="form-group">
                    <label class="sr-only" for="exampleInputOperid">登录名</label>
                    <input name="operator_name"  type="text" class="form-control" id="exampleInputOperid" placeholder="登录名">
                </div>
                <button type="button" class="btn btn-warning btn-sm tooltip-warning" onclick="search()"><i class="icon-search"></i> 搜索</button>
                <?php if (\app\components\Utils::checkAccess("operators/add")): ?>
                    <a href="<?= yii\helpers\Url::toRoute(['operators/add']) ?>" class="btn btn-primary btn-sm tooltip-info"><i class="icon-plus"></i> 增加</a>
                <?php endif; ?>
            </form>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive" id="unseen">
                    <?php echo $this->context->actionData() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('script') ?>
<script type="text/javascript">
    function search() {
        $.ajax({
            url: "<?php echo Url::to(['operators/data']) ?>",
            data: $('#searchform').serialize(),
            beforeSend: function (xhr) {
                $('#unseen').append('<div style="text-align:center;"><span class="ui-icon icon-refresh green"></span></div>');
            },
            success: function (data) {
                $('#unseen').html(data);
            },
            complete: function (xhr, sc) {
                $('#loading').remove();
            }
        });
    }
    function showDetail(id) {
        fbwindow('业务员详情', "<?php echo \yii\helpers\Url::to(['orgsales/detail']) ?>&id=" + id, 'l');
    }
    function delDetail(id) {
        fbwindow('确定删除业务员吗？', "<?php echo \yii\helpers\Url::to(['manager/del-detail']) ?>&id=" + id, 'l');
    }
    function modifyInfo(id) {
        fbwindow('信息修改', "<?php echo \yii\helpers\Url::to(['orgsales/update']) ?>&id=" + id, 'l');
    }
    //function del(id) {
    //    closeModal();
    //    var flag = confirm('您确定删除此管理员吗？');
    //    if (flag) {
    //        $.ajax({
    //            url: "<?//= Url::toRoute('operators/del') ?>//",
    //            type: 'post',
    //            data: {id: id,_csrf: "<?//= Yii::$app->request->csrfToken ?>//"},
    //            success: function (data) {
    //                if (data.code == 200) {
    //                    showToast('success', '删除业务员成功', '', 2000);
    //                    search();
    //                } else{
    //                    showToast('error', '删除业务员失败', data.desc, 2000);
    //                }
    //            },
    //            error: function (data) {
    //                showToast('error', '系统错误，请稍后重试', '', 2000);
    //            }
    //        });
    //    }
    //}
    //
    function changeStatus(id) {
        layer.confirm('您想改变此管理员状态？', {
            btn: ['启用','停用'] //按钮
        }, function(){
            $.ajax({
                url: "<?= Url::toRoute('operators/undel') ?>",
                type: 'post',
                data: {id: id,_csrf: "<?= Yii::$app->request->csrfToken ?>"},
                success: function (data) {
                    if (data.code == 200) {
                        layer.msg('启用管理员成功', {icon: 1});
                        search();
                    } else{
                        layer.msg(data.desc, {time: 2000});
                    }
                },
                error: function (data) {
                    layer.msg('系统错误，请稍后重试', {time: 2000});
                }
            });
        }, function(){
            $.ajax({
                url: "<?= Url::toRoute('operators/del') ?>",
                type: 'post',
                data: {id: id,_csrf: "<?= Yii::$app->request->csrfToken ?>"},
                success: function (data) {
                    if (data.code == 200) {
                        layer.msg('禁用管理员成功', {icon: 1});
                        search();
                    } else{
                        layer.msg(data.desc, {time: 2000});
                    }
                },
                error: function (data) {
                    layer.msg('系统错误，请稍后重试', {time: 2000});
                }
            });
        });
    }


    //选择父级单位
    function chooseParorg() {
        fbwindow("选择单位", "<?= \yii\helpers\Url::toRoute('organizations/select-org') ?>", 'l');
    }
    function selectCorp(id, name) {
        $('.modal-open').removeClass('modal-open');
        //自动生成的，根据表单提交
        $('#corpName').val(name);
        $('#orgId').val(id);
    }
    function resetPwd(id) {
        fbwindow('重置密码', '<?= Url::toRoute(['operators/reset-pwd']) ?>&id=' + id, '');
    }
</script>
<?php $this->endBlock(); ?>

