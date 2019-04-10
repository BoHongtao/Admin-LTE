<?php
namespace app\models;

use Yii;
class Pwd extends \yii\base\Model{
    public $pwd;
    public $newpwd;
    public $repwd;
    public function rules() {
        return [
            [['pwd','newpwd','repwd'],'required'],
            [['pwd','repwd','newpwd'], 'string', 'max' => 16,'min' => 6],
            [['newpwd','repwd'],'match','pattern' => "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/",'message' => '密码必须包含字母和数字'],
            ['repwd','compare','compareAttribute'=>'newpwd'],
            [['pwd'],'comparepwd']
        ];
    }
    public function attributeLabels()
    {
        return [
            'pwd' => '旧密码',
            'newpwd' => '新密码',
            'repwd' => '确认密码'
        ];
    }
    public function comparepwd($attr,$param){
        $pwd = Yii::$app->user->identity->password;
        if(!Yii::$app->security->validatePassword($this->pwd, $pwd)){
            $this->addError($attr,'旧密码不正确');
        }
    }

    /**
     * 修改密码
     * @param type $param0
     */
    public function updatePwd($pwd) {
        $newPwd = Yii::$app->security->generatePasswordHash($pwd);
        $model = Yii::$app->user->identity;
        $model->password = $newPwd;
        $model->record_time = date('Y-m-d H:i:s');
        return $model->save(false);
    }

}
