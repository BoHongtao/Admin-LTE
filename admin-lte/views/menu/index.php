<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
$this->params['breadcrumbs'][] = ["label"=>"菜单管理","url"=>Url::toRoute("menu/index")];
$this->params['breadcrumbs'][] = '列表';
?>
<div class="main-content">
    <div class="page-content">
        <?php if (\app\components\Utils::checkAccess("menu/create")): ?>
            <div class="page-header">
                <?= Html::a('<i class="icon-plus"></i> 创建菜单', ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive" id="unseen">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th width="200">名称</th>
                            <th>菜单路由</th>
                            <th width="20%">功能路由</th>
                            <th width="20%">控制器列表</th>
                            <th>状态</th>
                            <th width="50">排序</th>
                            <th width="150">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($menu as $k=>$vo): ?>
                            <tr>
                                <td><?= $vo['name'] ?></td>
                                <td><?= $vo['route'] ?></td>
                                <td style="word-wrap:break-word;word-break:break-all;"><?= $vo['act'] ?></td>
                                <td style="word-wrap:break-word;word-break:break-all;"><?= $vo['attr'] ?></td>
                                <td><?= $vo['display'] > 1 ? '<span class="label label-info">显示</span>' : '<span class="label label-default">隐藏</span>' ?></td>
                                <td><?= ($vo['sort'] == '' ? '' : $vo['sort']) ?></td>
                                <td>
                                    <?php if (\app\components\Utils::checkAccess("menu/update")): ?>
                                        <a class="btn btn-primary btn-xs"
                                           href="<?= Url::toRoute(['menu/update', 'id' => $vo['id']]) ?>">
                                            <i class="fa fa-edit"></i>编辑
                                        </a>
                                    <?php endif; ?>

                                    <?php if (\app\components\Utils::checkAccess("menu/delete")): ?>
                                        <a class="btn btn-default btn-xs"
                                           href="javascript:void(0)" onclick="del(<?=$vo['id']?>)">
                                            <i class="fa fa-close"></i>删除
                                        </a>
                                    <?php endif; ?>

                                </td>
                            </tr>
                            <!--二级菜单-->
                            <?php if (!empty($vo['_child'])): ?>
                                <?php foreach ($vo['_child'] as $v): ?>
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;|--<?= $v['name'] ?></td>
                                        <td><?= $v['route'] ?></td>
                                        <td style="word-wrap:break-word;word-break:break-all;"><?= $v['act'] ?></td>
                                        <td style="word-wrap:break-word;word-break:break-all;"><?= $v['attr'] ?></td>
                                        <td><?= $v['display'] > 1 ? '<span class="label label-info">显示</span>' : '<span class="label label-default">隐藏</span>' ?></td>
                                        <td><?= ($v['sort'] == '' ? '' : $v['sort']) ?></td>
                                        <td>
                                            <?php if (\app\components\Utils::checkAccess("menu/update")): ?>
                                                <a class="btn btn-primary btn-xs"
                                                   href="<?= Url::toRoute(['menu/update', 'id' => $v['id']]) ?>"><i
                                                            class="fa fa-edit"></i>编辑
                                                </a>
                                            <?php endif; ?>

                                            <?php if (\app\components\Utils::checkAccess("menu/delete")): ?>
                                                <a class="btn btn-default btn-xs" href="javascript:void(0)" onclick="del(<?=$v['id']?>)">
                                                    <i class="fa fa-close"></i>删除
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <!--三级菜单-->
                                    <?php if (!empty($v['_child'])): ?>
                                        <?php foreach ($v['_child'] as $v3): ?>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|----<?= $v3['name'] ?></td>
                                                <td><?= $v3['route'] ?></td>
                                                <td style="word-wrap:break-word;word-break:break-all;"><?= $v3['act'] ?></td>
                                                <td style="word-wrap:break-word;word-break:break-all;"><?= $v3['attr'] ?></td>
                                                <td><?= $v3['display'] > 1 ? '<span class="label label-info">显示</span>' : '<span class="label label-default">隐藏</span>' ?></td>
                                                <td><?= ($v3['sort'] == '' ? '' : $v3['sort']) ?></td>
                                                <td>
                                                    <?php if (\app\components\Utils::checkAccess("menu/update")): ?>
                                                        <a class="btn btn-primary btn-xs"
                                                           href="<?= Url::toRoute(['menu/update', 'id' => $v3['id']]) ?>">
                                                            <i class="fa fa-edit"></i>编辑
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (\app\components\Utils::checkAccess("menu/delete")): ?>
                                                        <a class="btn btn-default btn-xs" href="javascript:void(0)" onclick="del(<?=$v3['id']?>)">
                                                            <i class="fa fa-close"></i>删除
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <!--三级菜单 end-->
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <!--二级菜单 end-->
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--</div>-->
<?php $this->beginBlock('script') ?>
<script type="text/javascript">
    function del(id){
        layer.confirm('您确定删除此权限及子权限吗？', {
            btn: ['是','否'] //按钮
        }, function(){
            $.ajax({
                url: "<?php echo Url::toRoute(['menu/delete'])?>",
                dataType: 'json',
                data:{'id':id,},
                type: 'post',
                success:function(data){
                    if(data.code==200){
                        layer.msg('权限及子权限已成功删除', {icon: 1});
                        setTimeout('window.location.href="<?= Url::toRoute(['menu/index']) ?>"', 1500);
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
<?php $this->endBlock(); ?>
