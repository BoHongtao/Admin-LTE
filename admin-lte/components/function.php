<?php

use app\models\Operlog;

/**
 * @param string $desc  日志描述
 * @param int $type 操作类型：0新增类，1，修改类，2删除类，3查询类，9 其他
 */
function recordLog($desc = '', $type = 9) {
    $operlog = new Operlog();
    $operlog->record_time = $operlog->update_time = date('Y-m-d H:i:s', time());
    $operlog->user_name = Yii::$app->user->identity->operator_name;
    $operlog->user_type = Yii::$app->user->identity->operator_type;
    $operlog->desc = $desc;
    $operlog->op_ip = ip2long(Yii::$app->request->userIP);
    $operlog->op_type= $type;
    $operlog->save();
}

//操作类型
function getOptype($op_type) {
    if ($op_type == 0) {
        $msg = '新增';
    } elseif ($op_type == 1) {
        $msg = '修改';
    } elseif ($op_type == 2) {
        $msg = '删除';
    } elseif ($op_type == 3) {
        $msg = '查询';
    } else {
        $msg = '其他';
    }
    return $msg;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = & $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = & $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = & $refer[$parentId];
                    $parent[$child][] = & $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 生成uuid
 * @return string
 */
function getUuid() {
    $str = md5(uniqid(mt_rand(), true));
    $uuid = substr($str, 0, 8);
    $uuid .= substr($str, 8, 4);
    $uuid .= substr($str, 12, 4);
    $uuid .= substr($str, 16, 4);
    $uuid .= substr($str, 20, 12);
    return $uuid;
}

//显示左侧菜单的方法
function login(){
    //菜单表中的权限
    $omenus = \app\models\Menu::allMenuList(["display"=>2]);
    $auth = Yii::$app->authManager;
    if (Yii::$app->user->identity->id == 1) {
        $res = $omenus;
    }else{
        //获取用户拥有得权限
        $menus = array_keys($auth->getPermissionsByUser(Yii::$app->user->identity->id));
        foreach ($omenus as $k => $val) {
            if(!empty($val['act']) && !in_array($val['act'], $menus))
                unset($omenus[$k]);
        }
        $res = $omenus;
    }
    $menus = list_to_tree($res);
    foreach ($menus as $key => $value){
        if((!isset($value["_child"]) || count($value["_child"]) == 0) && empty($value["route"]))
            unset($menus[$key]);
    }
    return $menus;
}

//递归创建目录
function createFolder($path)
{
    if (!file_exists($path)) {
        createFolder(dirname($path));
        mkdir($path);
    }
}

// 获取图片显示路径
function picPath($file){
    return Yii::$app->params["file_upload"].substr($file,0,2)."/".$file;
}