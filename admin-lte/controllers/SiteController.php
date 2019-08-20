<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actions() {
        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 40,
                'width' => 130,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'maxLength' => 4,
                'minLength' => 4,
                'padding' => 0,
                'fontFile'=>'static/fonts/captcha.ttf'
            ],
        ];
    }

    /*
     * 错误处理页面
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if(Yii::$app->user->isGuest){
            return $this->redirect(\yii\helpers\Url::to(['site/login']));
        }
        if ($exception !== null) {
            return $this->render('error');
        }
    }



    public function actionLogin() {
        $this->layout = 'main_login';
        if (!\Yii::$app->user->isGuest){
            $this->actionMenu();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $users = Yii::$app->user->identity;
            $users->record_time = date('Y-m-d H:i:s');
            $users->last_login_ip = ip2long(Yii::$app->request->userIP);
            $users->save();
            $this->actionMenu();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionMenu()
    {
        $menus = login();
        foreach ($menus as $value){
            if(!isset($value['_child']))   return $this->redirect([$value['route']]);
            foreach ($value['_child'] as $val){
                if($val['display'] == 2) return $this->redirect([$val['route']]);
            }
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->redirect(\yii\helpers\Url::to(['site/login']));
    }

}
