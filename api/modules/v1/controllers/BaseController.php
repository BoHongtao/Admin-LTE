<?php

namespace app\modules\v1\controllers;

use app\modules\v1\models\Operators;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\web\Response;

class BaseController extends Controller {
    public $url;
    public $body = [] ;
    public $_user;
    public function init() {
        parent::init();
        $this->body = Yii::$app->request->post();
    }

    public function beforeAction($action) {
        parent::beforeAction($action);
        return $action;
    }

    public function afterAction($action, $result) {
        return $result;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //速率限制
        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::className(),
            'enableRateLimitHeaders' => true,
        ];
        //认证,验证
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBasicAuth::className(),
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];
        return $behaviors;
    }
}
