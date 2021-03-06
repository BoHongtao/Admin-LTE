<?php
/**
 * User: kongda
 * Date: 2017/10/10
 * Time: 16:45
 */

namespace app\controllers;
use app\models\Operlog;
use yii;
use yii\data\Pagination;

class OperLogController extends BaseController
{
    public function actionIndex(){
        return $this->render('index');
    }

    public function actionData($keyword = '')
    {
        $query = Operlog::find()->orderBy('id DESC');
        if(Yii::$app->request->isAjax){
            $keyword and $query->andWhere(['LIKE','desc',$keyword]);
        }

        $operator = Yii::$app->user->identity;

        if($operator['id'] != 1){
            $query->andWhere(['user_name' => $operator['operator_name']]);
        }

        $pager = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => $this->pageSize,
            'route' => 'oper-log/data'
        ]);
        $operateLog = $query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->renderPartial('_list',[
            'operatelog' => $operateLog,
            'pager' => $pager
        ]);
    }
}