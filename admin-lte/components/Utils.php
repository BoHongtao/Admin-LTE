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

    /*
     * 通过url上传图片到七牛
     */
    public static function SaveQiniu($extension,$file_path){
        //七牛的密钥、域名和空间
        $access_key = Yii::$app->params['access_key'];
        $secret_key = Yii::$app->params['secret_key'];
        $bucket = Yii::$app->params['bucket'];
        //上传7牛的文件的名字及路径
        $name = getUuid().'.'.$extension;
        $folder = substr($name,0,2);
        $file_name = $folder.'/'.$name;
        //授权
        $auth = new Auth($access_key,$secret_key);
        //生成token
        $token = $auth->uploadToken($bucket);
        $uploadManager = new UploadManager();
        try{
            $uploadManager->putFile($token,$file_name,$file_path);
        }catch (\Exception $e){
            return false;
        }
        return Yii::$app->params['domain'].'/'.$file_name;
    }
    /*
     * 显示七牛上传的图片
     */
    public static function ShowQiniu($url)
    {
        if(empty($url)){
            return '';
        }
        if(substr($url,0,4)=='http'){
            return $url;
        }
        return Yii::$app->params['domain'].'/'.$url;
    }


}