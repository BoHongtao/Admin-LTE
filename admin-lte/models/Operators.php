<?php

namespace app\models;

use Yii;

class Operators extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {

    public $new_pwd;
    public $re_pwd;
    public $roles;

    public static function tableName() {
        return 'operators';
    }

    public function rules() {
        return [
            [['operator_name'], 'unique'],
            [['password', 're_pwd'],'required','on' => ['add','tourism']],
            [['password', 're_pwd'], 'string', 'max' => 16,'min' => 6,'on' => ['add','tourism']],
            [['password', 're_pwd'],'match','pattern' => "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/",'message' => '密码必须包含字母和数字','on' => ['add','tourism']],
            [['password','operator_name'],'required','on' => 'tourism'],
//            [['password'],'match','pattern' => "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/",'message' => '密码必须包含字母和数字','on' => 'tourism'],
            [['operator_name'], 'required','on' =>['add','update']],
            [['operator_type'], 'integer'],
            [['record_time', 'last_login_time'], 'safe'],
            [['operator_name', 'contact_name', 'contact_phone', 'wechat', 'password'], 'string', 'max' => 255],
            ['re_pwd', 'compare', 'compareAttribute' => 'password','on' => ['add','tourism'],'message'=>'与密码输入不一致'],
//            [['contact_phone'], 'match', 'pattern' => '/^1[34578]\d{9}$/'],
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
            'bank_name' => '银行名称',
            'password' => '密码',
            'new_pwd' => '新密码',
            're_pwd' => '确认密码',
            'record_time' => '记录时间',
            'last_login_ip' => '最后登录ip',
            'last_login_time' => '最后登录时间',
            'role_id' => '角色'
        ];
    }

    public static function login($user) {
        $model = Operators::find()->where(['operator_name' => $user])->one();
        return $model;
    }

    public function comparePwd($attr, $params) {
        $sys = Operators::findOne(Yii::$app->session['id']);
        if (!Yii::$app->getSecurity()->validatePassword($this->password, $sys->password)) {
            $this->addError($attr, '旧密码错误');
        }
    }

    public function validatePassword($inputPwd, $pwd) {
        //echo $pwd;exit;
//        if (Yii::$app->getSecurity()->validatePassword($password, $hash)) {
        if (Yii::$app->getSecurity()->validatePassword($inputPwd, $pwd)) {
//            增加操作日志
//            addLog($this->login_name, 1, 3, $this->id, '登录成功');
            return true;
        } else {
//            addLog($this->login_name, 1, 3, $this->id, '登录失败:用户名或密码错误');
            return false;
        }
    }

    public function getAuthKey() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function validateAuthKey($authKey) {
        
    }

    public static function findIdentity($id) {
        $user = Operators::find()->where(['id' => $id])->one();
        $distor = null;
        return $user;
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        
    }

    public function getRoles() {
        $query = (new \yii\db\Query())
        ->select(['name', 'type'])
        ->from("auth_item")->andWhere(['type' => 1]);
        return $query->all();
    }

    public function getUserRoles($uid) {
        $query = (new \yii\db\Query())
        ->select(['item_name'])
        ->from("auth_assignment")->andWhere(['user_id' => $uid]);
        return $query->all();
    }

    public function addRoles(array $datas, $user_id, $isdelete = 0) {
        if ($isdelete) {
            Yii::$app->db->createCommand("DELETE  FROM  auth_assignment WHERE user_id= {$user_id}")
            ->execute();
        }
        //添加对应关系
        if (!empty($datas)) {
            $auth = Yii::$app->authManager;
            foreach ($datas as $val) {
                $role = $auth->createRole($val);                //创建角色对象
                //获取用户id，此处假设用户id=1
                $auth->assign($role, $user_id);
            }
        }
        return;
    }

    public function getAuth() {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }
}
