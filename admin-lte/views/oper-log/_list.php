<?php
use app\components\AjaxPager;
use yii\helpers\Url;
?>
<table id="sample-table-1" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th class="center">编号</th>
        <th>操作</th>
        <th>描述</th>
        <th>操作人</th>
        <th>操作人IP</th>
        <th>操作时间</th>
    </tr>
    </thead>

    <tbody>
    <?php if (empty($operatelog)): ?>
        <tr>
            <td colspan="13" class="text-center">暂无数据</td>
        </tr>
    <?php else: ?>
    <?php foreach ($operatelog as $key => $result): ?>
    <tr>
        <td class="center"><?= $result['id'] ?></td>
        <td>
            <?php echo getOptype($result['op_type']);?>
        </td>
        <td><?= $result['desc'] ?></td>
        <td><?= $result['user_name'] ?></td>
        <td><?= long2ip((int)$result['op_ip']) ?></td>
        <td class="hidden-480">
            <span class="label label-sm label-warning"><?= $result['record_time'] ?></span></td>
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
