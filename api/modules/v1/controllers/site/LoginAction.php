<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/5/14
 * Time: 15:58
 */

namespace app\modules\v1\controllers\site;

use app\modules\v1\controllers\BaseAction;
use app\modules\v1\models\LoginForm;

class LoginAction extends BaseAction {
    public function run(){
        $model = new LoginForm();
        $model->setAttributes($this->body);
        if ($model->login()) {
            $code = 0;
            $result['code'] = $code;
            $result['desc'] = $this->desc[$code];
            $result['data']['token'] = $model->user->access_token;
            return $result;
        }
        $code = 40010;
        $result['code'] = $code;
        $result['desc'] = $this->desc[$code];
        $result['data'] = [];
        return $result;
    }
}