<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/5/9
 * Time: 22:54
 */

namespace app\modules\v1\controllers;

class OperatorController extends BaseController
{
    public $modelClass = 'api\models\Operators';
    public function actions() {
        return [
            'view' => [
                'class' => 'app\modules\v1\controllers\operator\ViewAction'
            ],
            'list' => [
                'class' => 'app\modules\v1\controllers\operator\ListAction'
            ],
        ];
    }
}
