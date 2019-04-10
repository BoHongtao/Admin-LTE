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
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_array($list, $code = 'code', $pk = 'id', $pid = 'pid', $child = 'c', $child_2 = 'a', $root = 0) {
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            //$region_type = $data['region_type'];
            if ($root == $parentId) {
                $list[$key]['p'] = $data['name'];
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $list[$key]['n'] = $data['name'];
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
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

/**
 * 隐藏中间部分
 */
function middle_replace($str) {
    $middle = substr($str, 2, 28);
    $hide = '';
    for ($i = 0; $i < strlen($str)-2; $i++) {
        $hide .= '*';
    }
    return str_replace($middle, $hide, $str);
}

/**
 * 根据时间戳获取周几
 * @param type $time  时间戳
 * @param type $i
 * @return type
 */
function getTimeWeek($time, $i = 0) {
    $weekarray = array("日", "一", "二", "三", "四", "五", "六");
    $oneD = 24 * 60 * 60;
    return "周" . $weekarray[date("w", $time + $oneD * $i)];
}

//显示左侧菜单的方法
function login(){
    //菜单表中的权限
    $omenus = \app\models\Menu::allMenuList(["display"=>2]);
    $auth = Yii::$app->authManager;
    if (Yii::$app->user->id === 1) {
        $res = $omenus;
    }else{
        $menus = array_keys($auth->getPermissionsByUser(Yii::$app->user->identity->id));
        foreach ($omenus as $k => $val) {
            $rolearr = explode(',', $val['act']);
            if(!empty($rolearr[0]) && !in_array($rolearr[0], $menus))
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

function taUrldecode($string = ''){
    if(empty($string)) return "";
    $codearr = ['0' => '&', '1' => '=', '2' => 'p', '3' => '6', '4' => '?', '5' => 'H', '6' => '%', '7' => 'B', '8' => '.com', '9' => 'k',
        'a' => 'm', 'b'  => 'A', 'c'  => 'l', 'd'  => '4', 'e'  => 'R', 'f'  => 'C', 'g'  => 'y', 'h'  => 'S', 'i'  => 'o', 'j'  => '+', 'k'  => '7', 'l'  => 'I', 'm'  => '3', 'n'  => 'c', 'o'  => '5', 'p'  => 'u', 'q'  => '0', 'r'  => 'T', 's'  => 'v', 't'  => 's', 'u'  => 'w', 'v'  => '8', 'w'  => 'P', 'x'  => '0', 'y'  => 'g', 'z'  => '0',
        'A' => '9', 'B'  => '.html', 'C'  => 'n', 'D'  => 'M', 'E'  => 'r', 'F'  => 'www.', 'G'  => 'h', 'H'  => 'b', 'I'  => 't', 'J'  => 'a', 'K'  => '0', 'L'  => '/', 'M'  => 'd', 'N'  => 'O', 'O'  => 'j', 'P'  => 'http://', 'Q'  => '_', 'R'  => 'L', 'S'  => 'i', 'T'  => 'f', 'U'  => '1', 'V'  => 'e', 'W'  => '-', 'X'  => '2', 'Y'  => '.', 'Z'  => 'N',
        ];
    $result = [];
    for ($i =0; $i<strlen($string);$i++){
        isset($codearr[$string[$i]]) and $result[] = $codearr[$string[$i]];
    }
    return implode("",$result);
}

/**
 * 二维数组根据字段进行排序
 * @params array $array 需要排序的数组
 * @params string $field 排序的字段
 * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 */
function arraySequence($array, $field, $sort = 'SORT_DESC')
{
    if(empty($array))return $array;
    $arrSort = array();
    foreach ($array as $uniqid => $row) {
        foreach ($row as $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($sort), $array);
    return $array;
}
