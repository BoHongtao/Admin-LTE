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
    //每次请求检查token是否过期
    public function expire_time(){
        if(Yii::$app->user->identity->expire_time + Yii::$app->params['accessTokenExpire'] < time()){
            $code = 40043;
            $result['code'] = $code;
            $result['desc'] = $this->desc[$code];
            return $result;
        }
        return false;
    }
}
