<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/5/15
 * Time: 8:57
 */

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

class SiteController extends Controller
{
    public $modelClass = 'api\models\Operators';
    public $url;
    public $body = [] ;
    public function init() {
        parent::init();
        $this->body = Yii::$app->request->post();
    }
    public function actions() {
        return [
            'login' => [
                'class' => 'app\modules\v1\controllers\site\LoginAction'
            ]
        ];
    }
}