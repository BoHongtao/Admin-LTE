<?php

use yii\helpers\Url;
?>

<div style="margin-top: 15px;">
    <section id="unseen">
        <!--跳转到控制器,不会找data.php页面显示数据-->
        <table class="table table-hover table-striped dataTable">
            <thead>
                <tr>
                    <th width="5%">序号</th>
                    <th width="7%">登录名</th>
                    <th width="8%">描述</th>
                    <th width="8%">创建时间</th>
                    <th width="20%" class="text-right"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($command)): ?>
                    <tr>
                        <td colspan="13" class="text-center">暂无数据</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($command as $key => $result): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $result['name'] ?></td>
                            <td><?= $result['description'] ?></td>
                            <td><?= date("Y-m-d H:i:s", $result['created_at']) ?></td>
                            <td class="text-right">
                                <a class="btn btn-info btn-xs" href="javascript:void(0)" onclick="selectCorpr('<?= $result['name'] ?>')"> 选择</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
<?php $this->beginBlock('script') ?>
<script type="text/javascript">

//    按搜索框里的条件进行检索
    function search() {
        $.ajax({

//            跳转到控制器里查找数据
            url: "<?php echo Url::to(['suppliers/data1']) ?>",

//            对数据进行序列化，传递搜索框里面的数据,作为url的参数
            data: $('#searchform').serialize(),

//            在进行搜索时显示的等待圆圈
            beforeSend: function (xhr) {
                $('body').append('<div id="loading"><img src="static/images/loading.gif" /></div>');
            },

//             成功之后显示数据
            success: function (data) {
                $('#unseen').html(data);
            },

//             完成之后去掉加载的圆圈
            complete: function (xhr, sc) {
                $('#loading').remove();
            }
        });
    }

    function selectCorpr(name) {
        parent.selectCorp(name);
        parent.closeModal();
    }
</script>
<?php $this->endBlock(); ?>



