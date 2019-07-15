# encoding: utf-8
# @author: John
# @contact: BoHongtao@yeah.net
# @software: PyCharm
# @time: 2019/4/30 10:28

import time,pymysql
##产生头部
def header(env,author,auto_date,auto_time,model_name):
    info = """
<?php
/**
 * Created by """ + env + """.
 * User: """ + author + """
 * Date: """ + auto_date + """
 * Time: """ + auto_time + """
 */
use Yii;
use app\models\\""" + model_name + """;
$this->params['breadcrumbs'][] = ["label"=>Yii::t('common','xx管理'),"url"=>Url::toRoute(" """+ model_name +"""/index")];
$this->params['breadcrumbs'][] = Yii::t('common','列表');
"""
    return info

def search(model_name):
    info = """
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <form id="searchform" class="form-inline" role="form">
                <div class="form-group">
                 <label class="sr-only" for="exampleInputOperid">标题</label>
                    <input name="name"  type="text" class="form-control" id="exampleInputOperid" placeholder="标题">
                 </div>
                <button type="button" class="btn btn-warning btn-sm tooltip-warning" onclick="search()"><i class="icon-search"></i> 搜索</button>
                <a href="<?= yii\helpers\\Url::toRoute(['"""+ model_name +"""/add']) ?>" class="btn btn-primary btn-sm tooltip-info"><i class="icon-plus"></i> 增加</a>
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
"""
    return info

def js(model_name):
    info = """
    <?php $this->beginBlock('script') ?>
<script type="text/javascript">
    function search() {
        $.ajax({
            url: "<?= Url::to(['"""+model_name+"""/data']) ?>",
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
    function del(id='') {
        if(id){
            $.ajax({
                url: "<?= Url::to(['"""+ model_name +"""/del']) ?>",
                data: {id:id},
                dataType: 'json',
                type: 'post',
                success:function(data){
                    if(data.code==200){
                        showToast('success', data.msg, '2S后返回', 5500);
                        setTimeout('window.location.href="<?= Url::toRoute(['"""+ model_name +"""/index']) ?>"', 1500);
                    }else{
                        showToast('error', "操作失败", data.msg, 5500);
                    }
                }
            });
        }
    }
</script>
<?php $this->endBlock(); ?>
"""
    return info

# 获取mysql字段备注
def get_column_brief(sql):
    comment = []
    conn = pymysql.Connect(user='root', password='', host='127.0.0.1', database='base',charset='utf8')
    cursor = conn.cursor()
    cursor.execute(sql)
    table_info = cursor.fetchall()
    for column in table_info:
        comment.append([column[0],column[-1]])
    return comment

def list_body(comment):
    info = """
<?php
  use app\components\AjaxPager;
?>
<style>
    #sample-table-1 td{
        display: table-cell;
        vertical-align: middle;
    }
</style>
<table id="sample-table-1" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
    """
    for column in comment:
        info = info + """ <th class="center">""" + column[1] + """</th>"""
    info = info + """ 
     </tr>
    </thead>
    <tbody>
    """
    info = info + """
    <?php if (empty($Info)): ?>
            <tr>
                <td colspan=""""+ str(len(comment)) +"""" class="text-center">暂无数据</td>
            </tr>
        <?php else: ?>
    """
    info = info + """
    <?php foreach ($Info as $key => $value): ?>
                <tr>
    """
    print(comment)
    for column in comment:
        print(column)
        info = info + """ <td class="center" ><?= $value['"""+str(column[0])+"""'] ?></td> """
    info = info + """
    <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<?php
echo AjaxPager::widget([
    'pagination' => $pager
]);
?>
    """
    return info

if __name__ == '__main__':
    table_name = input("请输入表名字\n")
    sql = "show full columns from "+table_name
    comment = get_column_brief(sql)
    print(comment)
    model_name = input("请输入模型名字\n")
    #代码生成时间
    auto_date = time.strftime('%Y-%m-%d',time.localtime(time.time()))
    auto_time = time.strftime('%H:%M:%S',time.localtime(time.time()))
    #作者
    author = 'auto_code_robots'
    # 环境
    env = 'Python 3.6'
    auto_header = header(env, author, auto_date, auto_time, model_name)
    search_body = search(model_name)
    js_body = js(model_name)
    out_put = auto_header + search_body + js_body

    # 生成list
    list = list_body(comment)

    f = open('index.php', 'w', encoding='utf-8')
    f.write(out_put)
    f.close()

    f = open('_list.php', 'w', encoding='utf-8')
    f.write(list)
    f.close()