<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/5/23
 * Time: 20:04
 */

namespace app\models;
use yii\base\Model;

class ElasticSearch extends Model{
    //需要返回的字段
    public function attributes()
    {
        return [];
    }

    //索引
    public static function index()
    {
        return '';
    }

    //文档类型
    public static function type()
    {
        return '';
    }

    public static function getDb()
    {
        return \Yii::$app->get('elasticsearch');
    }
}