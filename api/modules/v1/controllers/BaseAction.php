<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\base\Action;

class BaseAction extends Action {
    public $url ;
    public $body = [];
    public $desc = [];

    public function init() {
        $this->url = $this->controller->url;
        $this->body = $this->controller->body;
        $this->desc = Yii::$app->params['statusCode'];
    }

    public function verifySign($token){
        $result = false;
        $header = Yii::$app->request->getHeaders();
        Yii::info($header,"header");
        //var_dump($header);exit;
        $remote_sign = isset($header['X-PT-SIGN'])?$header['X-PT-SIGN']:'';

        if(!empty($remote_sign)){
            if(empty($this->body))
                $tmpstr = '/'.$this->url.$token;
            else
                $tmpstr = '/'.$this->url.json_encode($this->body,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES).$token;

            $local_sign = hash('sha256',$tmpstr);
            $result = ($remote_sign==$local_sign);
        }
        //$result = true;
        return $result;
    }

    public static function xysjHttpsPost($url,$pdata)
    {
        ksort($pdata,SORT_STRING);
        $data = urldecode(http_build_query($pdata));
        Yii::info($data,"xysjHttpsPost");

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_TIMEOUT,20);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_URL,Yii::$app->params['xysj_pay']['host'].$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

        $http_hd = [];
        $timestr = date('YmdHis');
        $username = Yii::$app->params['xysj_pay']['app_id'].'_'.$timestr;
        $password = self::createXYSJHttpBasicPassword($timestr,$data,$url);
        Yii::info($username,"xysjHttpsPost username");
        Yii::info($password,"xysjHttpsPost password");

        $http_hd[] = 'Content-Length: ' . strlen($data);
        $http_hd[] = 'Content-type: application/x-www-form-urlencoded';
        $http_hd[] = 'Authorization: Basic '. base64_encode($username.":".$password);
        Yii::info($http_hd[2],"xysjHttpsPost Authorization");


        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$http_hd);

        $resp = curl_exec($ch);
        if($resp === FALSE){
            $desc = curl_errno($ch).':'.curl_error($ch);
            $result = ["code"=>49999,"desc"=>$desc];
        } else {
            $result = json_decode($resp,true);
        }
        curl_close($ch);
        return $result;
    }

    private static function createXYSJHttpBasicPassword($timestr,$data,$url) {
        $tmpstr = Yii::$app->params['xysj_pay']['app_id'].'&'.$timestr.'&POST'.'&'.$url.'&'.$data;
        Yii::info($tmpstr,"createXYSJHttpBasicPassword tmpstr");
        $key = openssl_pkey_get_private(file_get_contents(Yii::$app->params['xysj_pay']['rsa_file']));
        openssl_sign($tmpstr, $sign, $key, OPENSSL_ALGO_SHA1);
        $rst_sign = base64_encode($sign);
        return $rst_sign;

    }
}
