<?php
use app\components\AjaxPager;
?>
<table id="sample-table-1" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="center">序号</th>
            <th>登录名</th>
            <th>角色</th>
            <th class="hidden-480">联系人</th>
            <th class="hidden-480">电话</th>
            <th>
                <i class="icon-time bigger-110 hidden-480"></i>
                登陆时间
            </th>
            <th>操作</th>
        </tr>
    </thead>

    <tbody>
        <?php if (empty($model)): ?>
            <tr>
                <td colspan="13" class="text-center">暂无数据</td>
            </tr>
        <?php else: ?>
            <?php foreach ($model as $key => $result): ?>
                <tr>
                    <td class="center"><?= $key + 1 + $pager->offset ?></td>
                    <td><?= $result->operator_name ?></td>
                    <td class="hidden-480"><?= isset($roles[$result['auth']['item_name']])?$roles[$result['auth']['item_name']]:""; ?></td>
                    <td><?= $result->contact_name ?></td>
                    <td><?= $result->contact_phone ?></td>
                    <td class="hidden-480">
                        <span class="label label-sm label-warning"><?= $result->record_time ?></span>
                    </td>
                    <td>
                        <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                            <?php if (\app\components\Utils::checkAccess("operators/update")): ?>
                                <a href="<?= yii\helpers\Url::toRoute(['operators/update','id' => $result->id]) ?>" class="btn btn-primary btn-xs" style="display: block"> 编辑</a>
                            <?php endif; ?>
                            <?php if (\app\components\Utils::checkAccess("operators/reset-pwd")): ?>
                                <a href="javascript:void(0)"  class="btn btn-info btn-xs" style="margin-left: 5px;display: block" onclick="resetPwd('<?php echo $result['id'] ?>')"> 重置密码</a>
                            <?php endif; ?>
                            <?php if (\app\components\Utils::checkAccess("operators/update")): ?>
                                <a href="#" onclick="del(<?= $result->id ?>)" class="btn btn-default btn-xs" style="display: block"> 删除</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<?php
echo AjaxPager::widget([
    'pagination' => $pager
]);
?>
