<?php

namespace app\components;

use Yii;

class Utils
{
    public static function checkAccess($authname = "")
    {
        $auth = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        if (array_keys($auth)[0]=='系统管理员'){
            return true;
        }else{
            $auth = Yii::$app->authManager;
            $authitem = $auth->getPermission($authname);
            if (empty($authitem))
                return true;
            return $auth->checkAccess(Yii::$app->user->id, $authname);
        }
    }

    public static function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return $list;
    }
}