<?php

namespace app\modules\v1\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $userName;
    public $password;
    private $_user = false;

    const GET_ACCESS_TOKEN = 'generate_access_token';

    public function init()
    {
        parent::init();
        $this->on(self::GET_ACCESS_TOKEN,[$this, 'onGenerateAccessToken']);
    }

    public function rules()
    {
        return [
            [['userName', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels() {
        return [
            'userName' => '用户名',
            'password' => '密码',
        ];
    }
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || (!$user->validatePassword($this->password,$user->password))) {
                $this->addError($attribute, '用户名/密码错误');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            $this->_user = $this->getUser();
            //如果token过期或者为空，直接生成新token
            if(!Operators::validateAccessToken($this->_user->access_token,$this->_user->expire_time)){
                $this->trigger(self::GET_ACCESS_TOKEN);
            }
            return Yii::$app->user->login($this->getUser(),0);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Operators::findByUsername($this->userName);
        }
        return $this->_user;
    }

    /**
     * 当登录成功时更新用户的token
     * Generated new accessToken when validate successful.
     * If accessToken is invalid, generated a new token for it.
     * @throws \yii\base\Exception
     */
    public function onGenerateAccessToken() {
        if (!Operators::validateAccessToken($this->getUser()->access_token,$this->getUser()->expire_time)) {
            $this->getUser()->generateAccessToken();
            $this->getUser()->save(false);
        }
    }
}
