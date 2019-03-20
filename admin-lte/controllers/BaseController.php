<?php

namespace app\controllers;

use app\components\Utils;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class BaseController extends Controller
{
    public $pageSize;

    public function init()
    {
        parent::init();
        $this->pageSize = Yii::$app->params ['pageSize'];
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login'])->send();
            die ();
        } else {
            $authname = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
            if (!Utils::checkAccess($authname)) {
                throw new ForbiddenHttpException ("对不起，您现在还没获此操作的权限");
                exit ();
            }
        }
        return true;
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

    //response a json format
    public function returnJson()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    // return curl error message
    public function getMsg($model)
    {
        $errors = $model->errors;
        return $errors [array_keys($errors) [0]] [0];
    }

    // pagination
    public function Pager($model, $route)
    {
        return new \yii\data\Pagination ([
            'totalCount' => $model->count(),
            'pageSize' => $this->pageSize,
            'route' => $route
        ]);
    }
}
