<?php

namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\web\Controller;
class SiteController extends ActiveController {
    public $modelClass = "common\models\user";
    public function actionIndex(){
       echo 22;die;
    }
}
