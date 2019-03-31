<?php

namespace app\controllers;

use app\components\WechatEvent;
use yii;
use yii\web\Controller;
use EasyWeChat\Foundation\Application;

class WechatApiController extends Controller
{
    public $app;
    public function init()
    {
        parent::init();
        $this->app = new Application(Yii::$app->params['WECHAT']);
        $this->enableCsrfValidation = false;
    }

    public function actionError()
    {
        $this->layout = false;
        return $this->render('error');
    }

    public function actionBind()
    {
        $server = $this->app->server;
        $server->setMessageHandler(function ($message) {
            return WechatEvent::handel($message);
        });
        $response = $server->serve();
        $response->send();
    }

    public function actionCallback()
    {
        $session = Yii::$app->session;
        $user = $this->app->oauth->user();
        $session->set('wechat_user', $user->toArray());
        $targetUrl = $session['target_url'] ? $session['target_url'] : '/';
        header('location:' . $targetUrl); // 跳转到目标URL
    }

    public function actionCreateMenu(){
        $menu = $this->app->menu;
        $url = Yii::$app->request->hostInfo.Yii::$app->homeUrl;
        $buttons = [
            [
                "type" => "click",
                "name" => "",
                "key"  => ""
            ],
            [
                "type" => "view",
                "name" => "",
                "url"  => $url . '?r='
            ],
            [
                "type" => "view",
                "name" => "",
                "url"  => $url . '?r='
            ]
        ];
        $menu->add($buttons);
    }


}