<?php

namespace app\models;

use yii\db\ActiveRecord;

class Base extends ActiveRecord
{
    public $file;

    //上传单个文件
    public function upload()
    {
        if (!$this->file) return;
        $uploadinfo = $this->getUploadInfo($this->file->extension);
        if($this->file->saveAs($uploadinfo['path'].$uploadinfo['name']))
            return $uploadinfo['name'];
            return false;
    }

    // 返回上传文件信息
    function getUploadInfo($extension){
        $name = getUuid().'.'.$extension;
        $folder = substr($name,0,2);
        $path = __DIR__.'/../web/uploads/'.$folder.'/';
        createFolder($path);
        return [
            "name" => $name,
            "floder" => $folder,
            "path" => $path
        ];
    }

    // 获取图片信息
    public function attrPics($data, $pathhandle = true){
        if(!is_array($data) || empty($data)){
            return $data;
        }
        if(count($data) == count($data,1)){
            if(isset($data["id"])){
                $result = Pictures::find()
                    ->select("filename")
                    ->where(["resource_id"=>$data["id"], "model"=>get_class($this)])
                    ->asArray()->all();
                $result = array_column($result,"filename");
                if($pathhandle && !empty($result)){
                    foreach ($result as &$pic){
                        $pic = picPath($pic);
                    }
                }
                $data["pics"] = $result;
            }
            return $data;
        }
        foreach ($data as $k=>&$v){
            $v = $this->attrPics($v,$pathhandle);
        }
        unset($v);
        return $data;
    }
}