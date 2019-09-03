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
    function modifyInfo(id) {
        fbwindow('信息修改', "<?php echo \yii\helpers\Url::to(['orgsales/update']) ?>&id=" + id, 'l');
    }

    function del(id){
        layer.confirm('您想删除此管理员？', {
            btn: ['是','否'] //按钮
        }, function(){
            $.ajax({
                url: "<?php echo Url::toRoute(['operators/del'])?>",
                dataType: 'json',
                data:{'id':id,},
                type: 'post',
                success:function(data){
                    if(data.code==200){
                        layer.msg('删除管理员成功', {icon: 1});
                        setTimeout('window.location.href="<?= Url::toRoute(['operators/index']) ?>"', 1500);
                    }else{
                        layer.msg(data.desc, {time: 2000});
                    }
                },
                error: function (data) {
                    layer.msg('系统错误，请稍后重试', {time: 2000});
                }
            });
        }, function(){
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

