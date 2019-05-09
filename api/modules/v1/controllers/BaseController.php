<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

class BaseController extends Controller {
    public $url;
    public $body = [] ;

    public function init() {
        parent::init();
        $this->url = Yii::$app->request->getUrl();
        $this->body = Yii::$app->request->post();
    }

    public function beforeAction($action) {
        Yii::info($this->body,"request");
        return true;
    }

    public function afterAction($action, $result) {
        Yii::info($result,"response");
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ($result);
    }
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
            ],
        ];

        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = \yii\web\Response::FORMAT_JSON;

        return $behaviors;
    }
}
