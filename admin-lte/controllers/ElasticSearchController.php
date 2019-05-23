<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/5/23
 * Time: 20:05
 */

namespace app\controllers;

use app\models\ElasticSearch;
use yii\web\Controller;

class ElasticSearchController extends Controller{
    public function actionIndex()
    {
        //search demo
        $query_arr = [
            "bool"=>[
                "must"=>[],
                "filter" => [
                    "range" => [
                        "xxx" => [
                            "from" => '',
                            "to" => ''
                        ]
                    ]
                ],
            ],
        ];
        $es = new ElasticSearch();
        $query = $es::find()->query()->asArray()->all();
    }
}