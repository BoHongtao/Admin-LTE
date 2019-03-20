<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class ResetPwd extends Model
{
    public $new_pwd;
    public $re_pwd;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['new_pwd','re_pwd'], 'required'],
            ['re_pwd','compare','compareAttribute'=>'new_pwd'],
            [['new_pwd', 're_pwd'], 'string', 'max' => 16,'min' => 6],
            [['new_pwd', 're_pwd'],'match','pattern' => "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/",'message' => '密码必须包含字母和数字'],
        ];
    }
    public function attributeLabels() {
        return [
            'new_pwd' => '新密码',
            're_pwd' => '确认密码',
        ];
    }
}
