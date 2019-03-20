<?php
/* @var $this yii\web\View */

$this->title = '权限配置';
use yii\helpers\Url;

?>
<style>
    .checkmod {
        margin-bottom: 20px;
        border: 1px solid #ebebeb;
    }

    .checkmod dt {
        border-bottom-color: #ebebeb;
        background-color: #ECECEC;
    }

    .checkmod dt {
        padding-left: 10px;
        height: 30px;
        line-height: 30px;
        font-weight: bold;
        border-bottom: 1px solid #ebebeb;
        background-color: #ECECEC;
    }

    .checkbox {
        display: inline-block;
        height: 20px;
        line-height: 20px;
    }

    .checkbox input {
        margin-right: 5px;
        vertical-align: -1px;
    }

    .auth-form {
        padding: 15px;
        min-width: 100px;
    }

    .checkmod dd {
        padding-left: 10px;
        line-height: 30px;
    }

    .checkmod dd .checkbox {
        margin: 0 25px 0 0;
    }

    .checkmod dd .divsion {
        margin-right: 20px;
    }
</style>
<div class="tab-content">
    <div class="tab-pane in" style="display: block;">
        <form action="" method="POST" class="form-horizontal auth-form" id="quanxian">
            <?php foreach ($data as $va) { ?>
                <dl class="checkmod">
                    <dt class="hd">
                        <label class="checkbox" style="padding-top: 0px;">
                            <input class="auth_rules rules_all" type="checkbox" name="rules[]" value="<?php echo $va['act'] ?>">
                            <?php echo $va['name'] ?>
                        </label>
                    </dt>
                    <dd class="bd">
                        <?php if (isset($va['_child'])): ?>
                            <?php foreach ($va['_child'] as $val) { ?>
                                <div class="rule_check">
                                    <div class="rule_pp">
                                        <label class="checkbox">
                                            <input class="auth_rules rules_row" type="checkbox" name="rules[]" value="<?php echo $val['act'] ?>"><?php echo $val['name'] ?>
                                        </label>
                                    </div>
                                    <p class="child_row" style="margin-left: 32px;">
                                        <?php if (isset($val['_child'])) { ?>
                                            <?php foreach ($val['_child'] as $key => $v) {
                                                $re[] = $v['act'] ?>
                                                <label class="checkbox">
                                                    <input class="auth_rules threes" type="checkbox" name="rules[]" value="<?php echo $v['act'] ?>"><?php echo $v['name'] ?>
                                                </label>
                                            <?php }
                                        } ?>
                                    </p>
                                </div>
                            <?php } ?>
                        <?php endif; ?>
                    </dd>
                </dl>
            <?php } ?>

        </form>
        <div class="form-group text-center">
            <button class="btn btn-success" name="dosubmit" type="submit" onclick="update('<?= $name ?>')" id="update-quanxian">确认修改</button>
            <button type="button" class="btn btn-default" onclick="closeModal()">关闭</button>
        </div>
    </div>
</div>

<script>
    <?PHP $this->beginBlock('js_end') ?>
    $(function () {
        //选中状态
        var rules = <?php echo $rule?>;
        $('.auth_rules').each(function () {
            var orules = this.value.split(',');
            for (i = 0; i < orules.length; i++) {
                if ($.inArray(orules[i], rules) > -1) {
                    $(this).prop('checked', true);
                }
                if (this.value == '') {
                    $(this).prop('checked', false);
                }
            }
        });
        $(".hd").each(function () {
            var checkcount = $(this).next().find("input[type='checkbox']:checked").length;
            var count = $(this).next().find("input[type='checkbox']").length;
            if (checkcount > 0) {
                if (checkcount == count) {
                    $(this).children(".checkbox").find("input[type='checkbox']").prop('checked', true);
                }
            }
        });
        //全选节点
        $('.rules_all').on('change', function () {
            $(this).closest('dl').find('dd').find('input').prop('checked', this.checked);
        });
        $('.rules_row').on('change', function () {
            $(this).closest('.rule_check').find('.child_row').find('input').prop('checked', this.checked);
            var allcheck = $(this).parents('.bd').find("input[type='checkbox']:checked").length;
            var allcount = $(this).parents('.bd').find("input[type='checkbox']").length;
            if (allcheck == allcount) {
                $(this).parents('.bd').prev().find('input').prop('checked', true);
            } else {
                $(this).parents('.bd').prev().find('input').prop('checked', false);
            }
        });
        $('.threes').on('change', function () {
            var parent = $(this).parents('.child_row');
            var checkcount = parent.find("input[type='checkbox']:checked").length;
            var sublenth = parent.find('.checkbox').length; //获取同级下的兄弟元素的个数
            if (checkcount == sublenth) {
                parent.prev().find('input').prop('checked', this.checked);
            } else {
                parent.prev().find('input').prop('checked', false);
            }
            if (checkcount == 0) {
                parent.prev().find('input').prop('checked', false);
            }
            var pparent = parent.parents('.bd');
            var ppcountcheck = pparent.find("input[type='checkbox']:checked").length;
            if (ppcountcheck == pparent.find("input[type='checkbox']").length) {
                parent.parents('.bd').prev().find('input').prop('checked', true);
            } else {
                parent.parents('.bd').prev().find('input').prop('checked', false);
            }
        })
    });

    function update(name) {
        $.ajax({
            url: "<?php echo Url::to(['role/quanxian']) ?>&name=" + name,
            data: $('#quanxian').serialize(),
            type: "POST",
            success: function (data) {
                if (data.code == 0) {
                    parent.closeModal();
                    parent.showToast('success', '修改权限成功', '', 2500);
                } else {
                    var desc = data.desc;
                    parent.showToast('error', '错误提示', data.desc, 2500);
                    $('#update-quanxian').attr('disabled', false);
                }
            },
            error: function (data) {
                $('#update-quanxian').attr('disabled', false);
                showToast('error', '错误提示', '系统忙，请稍后重试', 2000);
            }
        });
        return false;
    }
    <?php $this->endBlock() ?>
</script>

<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>