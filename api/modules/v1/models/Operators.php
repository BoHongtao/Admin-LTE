<?php

namespace app\modules\v1\models;

use Yii;
use yii\filters\RateLimitInterface;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Operators extends ActiveRecord implements IdentityInterface ,RateLimitInterface{

    public function rules() {
        return [
            [['operator_name'], 'unique'],
            [['password', 're_pwd'],'required','on' => ['add','tourism']],
            [['password', 're_pwd'], 'string', 'max' => 16,'min' => 6,'on' => ['add','tourism']],
            [['password', 're_pwd'],'match','pattern' => "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/",'message' => '密码必须包含字母和数字','on' => ['add','tourism']],
            [['password','operator_name'],'required','on' => 'tourism'],
            [['operator_name'], 'required','on' =>['add','update']],
            [['operator_type'], 'integer'],
            [['record_time', 'last_login_time','allowance','allowance_updated_at','access_token','expire_time'], 'safe'],
            [['operator_name', 'contact_name', 'contact_phone', 'wechat', 'password'], 'string', 'max' => 255],
            ['re_pwd', 'compare', 'compareAttribute' => 'password','on' => ['add','tourism'],'message'=>'与密码输入不一致'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'operator_name' => '登录名',
            'operator_type' => '类型',
            'contact_name' => '联系人',
            'contact_phone' => '联系电话',
            'wechat' => '微信号',
            'password' => '密码',
            'new_pwd' => '新密码',
            're_pwd' => '确认密码',
            'record_time' => '记录时间',
            'last_login_ip' => '最后登录ip',
            'last_login_time' => '最后登录时间',
            'role_id' => '角色',
            'allowance_updated_at'=>'allowance_updated_at',
            'allowance'=>'allowance',
            'access_token'=>'access_token',
            'expire_time'=>'expire_time'
        ];
    }
    /**
     * 根据用户名查找用户
     * Finds an identity by username
     * @param null $username
     * @return null|static
     */
    public static function findByUsername($username = null) {
        return static::findOne(['operator_name' => $username]);
    }

    public function validatePassword($inputPwd, $pwd) {
        return Yii::$app->getSecurity()->validatePassword($inputPwd, $pwd) ? true : false;
    }

    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    /**
     * 生成随机的token并加上时间戳
     * Generated random accessToken with timestamp
     * @throws \yii\base\Exception
     */
    public function generateAccessToken() {
        $this->access_token = Yii::$app->security->generateRandomString();
        $this->expire_time = time();
    }

    /**
     * 验证token是否过期
     * Validates if accessToken expired
     * @param null $token
     * @return bool
     */
    public static function validateAccessToken($token = null,$timestamp=0) {
        if ($token === null) {
            return false;
        } else {
            $expire = Yii::$app->params['accessTokenExpire'];
            return $timestamp + $expire >= time();
        }
    }

    public function getRateLimit($request, $action)
    {
        return [1, 1]; // $rateLimit requests per second
    }

    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }

}
