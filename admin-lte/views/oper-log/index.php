<?php
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ["label"=>"日志管理","url"=>Url::toRoute("oper-log/index")];
$this->params['breadcrumbs'][] = '列表';
?>
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <form id="searchform" class="form-inline" role="form">
                <div class="form-group">
                    <label class="sr-only">关键词</label>
                    <input name="keyword"  type="text" class="form-control" id="exampleInputOperid" placeholder="查询的关键词">
                </div>
                <?php if (\app\components\Utils::checkAccess("oper-log/data")): ?>
                    <button type="button" class="btn btn-warning btn-sm tooltip-warning" onclick="search()">
                        <i class="icon-search"></i> 搜索
                    </button>
                <?php endif; ?>
            </form>
        </div><!-- /.page-header -->
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive" id="unseen" style="min-width: 100%;">
                    <?php echo $this->context->actionData() ?>
                </div><!-- /.table-responsive -->
            </div><!-- /span -->
        </div>
    </div>
</div>
<!-- page end-->
<!--main content end-->
<?php $this->beginBlock('script') ?>
<script type="text/javascript">
    function search() {
        $.ajax({
            url: "<?php echo Url::toRoute('oper-log/data') ?>",
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
</script>
<?php $this->endBlock(); ?>