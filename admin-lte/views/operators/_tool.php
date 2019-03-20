<form id="searchform" class="form-inline" role="form">
    <div class="form-group">
        <label class="sr-only" for="exampleInputOperid">登录名</label>
        <input name="operator_name"  type="text" class="form-control" id="exampleInputOperid" placeholder="登录名">
    </div>
<!--    <div class="form-group">
        <label class="sr-only">分类</label>
        <select name="operator_type" class="form-control">
            <option value=''>请选择角色</option>
            <option value="1">系统管理员</option>
            <option value="2">银行管理员</option>
        </select>
    </div>-->
    <button type="button" class="btn btn-warning" onclick="search()"><i class="fa fa-search"></i> 搜索</button>
    <a href="<?= yii\helpers\Url::toRoute(['operators/add']) ?>" class="btn btn-warning"><i class="fa fa-plus"></i> 增加</a>
</form>