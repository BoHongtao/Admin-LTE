<?php
use yii\helpers\Url;
$this->params['breadcrumbs'][] = ["label"=>"角色管理","url"=>Url::toRoute("role/index")];
$this->params['breadcrumbs'][] = '列表';
?>
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <?php if (\app\components\Utils::checkAccess("role/create-role")): ?>
                <a href="<?= yii\helpers\Url::toRoute(['role/create-role']) ?>" class="btn btn-primary btn-sm tooltip-warning">
                    <i class="icon-plus"></i> 增加角色
                </a>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive" id="unseen">
                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>角色名</th>
                                <th>描述</th>
                                <th>
                                    <i class="icon-time bigger-110 hidden-480"></i>
                                    创建时间
                                </th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($roles)): ?>
                                <tr>
                                    <td colspan="13" class="text-center">暂无数据</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($roles as $key => $role): ?>
                                    <tr>
                                        <td><?= $role->name ?></td>
                                        <td class="hidden-480"><?= $role->description ?></td>
                                        <td class="hidden-480">
                                            <span class="label label-sm label-warning"><?= date("Y-m-d H:i:s", $role->created_at) ?></span>
                                        </td>
                                        <td>
                                             <?php if (\app\components\Utils::checkAccess("role/quanxian")): ?>
                                                 <a class="btn btn-primary btn-xs" href="javascript:void(0)" onclick="quanxian('<?= $role->name ?>')">
                                                     <i class="fa fa-edit"></i>分配权限
                                                 </a>
                                            <?php endif; ?>
                                            <?php if (\app\components\Utils::checkAccess("role/del")): ?>
                                                <a class="btn btn-default btn-xs" href="javascript:void(0)" onclick="del('<?= $role->name?>')">
                                                    <i class="fa fa-close"></i>删除
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('script') ?>
<script type="text/javascript">
    function quanxian(name) {
        motai('权限配置', '<?= Url::toRoute(['role/quanxian']) ?>&name=' + name, 'l');
    }
    function del(name){
        layer.confirm('您确定删除该角色吗？', {
            btn: ['是','否'] //按钮
        }, function(){
            $.ajax({
                url: "<?php echo Url::toRoute(['role/del'])?>",
                dataType: 'json',
                data:{name:name},
                type: 'post',
                success:function(data){
                    if(data.code==200){
                        layer.msg('删除角色成功', {icon: 1});
                        setTimeout('window.location.href="<?= Url::toRoute(['role/index']) ?>"', 1500);
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
</script>
<?php
$this->endBlock()?>
