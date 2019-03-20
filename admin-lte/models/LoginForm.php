<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $userName;
    public $password;
    public $code;
    private $_sysUser = false;


    public function rules()
    {
        return [
            [['userName', 'password'], 'required'],
            ['password', 'validatePassword'],
            ['code','captcha']
        ];
    }
    public function attributeLabels() {
        return [
            'userName' => '用户名',
            'password' => '密码',
            'code' => '验证码'
        ];
    }
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getSysUser();
                if (!$user || (!$user->validatePassword($this->password,$user->password))) {
                    $this->addError($attribute, '用户名/密码错误');
                }
        }
    }

    public function login()
    {
        if ($this->validate()) return Yii::$app->user->login($this->getSysUser(), 0);
        return false;
    }

    public function getSysUser()
    {
        if ($this->_sysUser === false) {
            $this->_sysUser = Operators::login($this->userName);
        }
        return $this->_sysUser;
    }
    public function getAuthKey(){
        return \Yii::$app->security->generateRandomString();
    }
}
