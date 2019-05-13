<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/5/9
 * Time: 22:55
 */
namespace app\modules\v1\controllers\operator;
use app\modules\v1\controllers\BaseAction;
use app\modules\v1\models\Operators;

class ViewAction extends BaseAction {
    public function run(){
        $operator_id = isset($this->body['id']) ? $this->body['id'] : '';
        if($operator_id==''){
            $code = 40012;
            $result['code'] = $code;
            $result['desc'] = $this->desc[$code];
            return $result;
        }
        $operatorInfo = Operators::findOne($operator_id);
        if($operatorInfo){
            $code = 0;
        }else{
            $code = 40018;
        }
        $result['code'] = $code;
        $result['datas'] = $operatorInfo;
        $result['desc'] = $this->desc[$code];
        return $result;
        return $this->body;
    }
}