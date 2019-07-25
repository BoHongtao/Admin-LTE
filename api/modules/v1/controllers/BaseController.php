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
        //添加CORS过滤器
        $behaviors['corsFilter']=[
            'class'	=>\yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Origin' => ['http://www.myserver.com', 'https://www.myserver.com'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Method' => ['POST', 'PUT'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Request-Headers' => ['X-Wsse'],
                // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                'Access-Control-Allow-Credentials' => true,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],

        ];
        return $behaviors;
    }

    # 鉴权代码（如果需要从app中进入且做了前后端分离，需要前端缓存鉴权数据，header中传递就可以），所有的类都要集成此基类
    public function verifySign()
    {
        $verify_code = 0;
        #######这里放鉴权代码##############
        #   注意鉴权数据一定要签名         #
        ##################################
        return $verify_code;
    }
}
