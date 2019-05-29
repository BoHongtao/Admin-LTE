<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/5/29
 * Time: 10:22
 */

namespace app\modules\v1\controllers\operator;
use app\modules\v1\controllers\BaseAction;
use app\modules\v1\models\Operators;
use Yii;

class ListAction extends BaseAction {
    public function run(){
        //检查token是否过期
        if($this->expire_time()!==false){
            return $this->expire_time();
        }
        $query = Operators::find();
        $operator_name = isset($this->body['$operator_name']) ? $this->body['$operator_name'] : '';
        $operator_name and $query->andWhere(['like', 'operator_name', $operator_name]);
        $pager = new \yii\data\Pagination ([
            'totalCount' => $query->count(),
            'pageSize' => Yii::$app->params ['pageSize'],
            'route' => 'operator/list'
        ]);
        $operatorInfo = $query->offset($pager->offset)->limit($pager->limit)->all();
        $result['code'] = 0;
        $result['data'] = $operatorInfo;
        $result['desc'] = $this->desc[0];
        return $result;
    }
}