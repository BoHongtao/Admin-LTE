<?php

namespace app\controllers;

use app\models\AuthAssignment;
use Yii;
use app\models\Operators;
use app\models\Pwd;
use yii\db\Exception;

class OperatorsController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionData($operator_name = '')
    {
        $query = $this->getQuery($operator_name);
        $pager = new \yii\data\Pagination([
            'totalCount' => $query->count(),
            'pageSize' => $this->pageSize,
            'route' => 'operators/data'
        ]);
        $model = $query->offset($pager->offset)->limit($pager->limit)->all();
        $rules = Yii::$app->authManager->getRoles();
        $rules = array_column($rules, "description", "name");
        return $this->renderPartial('_list', [
            'model' => $model,
            'pager' => $pager,
            "roles" => $rules
        ]);
    }
    public function getQuery($operator_name)
    {
        $query = Operators::find()->where(['operator_type' => 1]);
        $operator_name and $query->andWhere(['like', 'operator_name', $operator_name]);
        return $query;
    }

    public function actionAdd()
    {
        $model = new Operators();
        $model->scenario = 'add';
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $this->returnJson();
            if (!$model->validate()) return ['code' => 9999, 'desc' => $this->getMsg($model)];
            $data = Yii::$app->request->post();
            $model->operator_type = 1;
            $model->password = Yii::$app->getSecurity()->generatePasswordHash($data['Operators']['password']);
            $tr = Yii::$app->db->beginTransaction();
            try {
                if (!$model->save(false)) throw new \Exception("管理员保存失败！");
                $auth = Yii::$app->authManager;
                $role = $auth->getRole($data['Operators']['role_id']);
                if (!$auth->assign($role, $model->id)) throw new \Exception("管理员角色配置失败！");
                recordLog('添加了管理员' . $model->operator_name, 1);
                $tr->commit();
                return ['code' => 0, 'desc' => '添加成功'];
            } catch (\Exception $e) {
                $tr->rollBack();
                return ['code' => 9999, 'desc' => $e->getMessage()];
            }
        }
        $roles = $model->getRoles();
        foreach ($roles as $k=>$v) $data[$v['name']] = $v['name'];
        return $this->render('add', [
            'model' => $model,
            'roles' =>$data
        ]);
    }

    public function actionModifyPwd()
    {
        $this->layout = 'main_large_frame';
        $model = new Pwd();
        $username = Yii::$app->user->identity->operator_name;
        if (Yii::$app->request->post()) {
            $this->returnJson();
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->updatePwd($model->newpwd)) {
                recordLog('修改了管理员' . $username . '的密码', 1);
                return ['code' => 0, 'msg' => '密码修改成功'];
            }
            return ['code' => 9999, 'msg' => $model->errors];
        }
        return $this->render('modify-pwd', [
            'model' => $model
        ]);
    }

    public function actionResetPwd()
    {
        $id = Yii::$app->request->get('id');
        $this->layout = 'main_large_frame';
        $model = Operators::findOne(['id' => $id]);
        $pwd = new \app\models\ResetPwd();
        if (Yii::$app->request->isAjax && $pwd->load(Yii::$app->request->post())) {
            $this->returnJson();
            if ($pwd->validate()) {
                $rs = Yii::$app->db->createCommand("update operators set password=:password where id=:id", [
                    ':password' => Yii::$app->security->generatePasswordHash($pwd->re_pwd),
                    ':id' => $id
                ])->execute();
                if ($rs) {
                    recordLog('重置了管理员' . $model['operator_name'] . '的密码', 1);
                    return [
                        'code' => 0,
                        'desc' => '重置密码成功',
                    ];
                } else {
                    return [
                        'code' => 9999,
                        'desc' => '重置密码失败',
                    ];
                }
            }
        }
        return $this->render('reset-pwd', [
            'model' => $model,
            'pwd' => $pwd
        ]);
    }

    public function actionUpdate($id = '')
    {
        $model = Operators::find()->where(['id' => $id])->one();
        if ($model->load(Yii::$app->request->post())) {
            $this->returnJson();
            if (!$model->validate()) {
                $errors = $model->errors;
                return [
                    'code' => 9999,
                    'desc' => $errors[array_keys($errors)[0]][0]
                ];
            }
            if ($model->save()) {
                recordLog('修改了管理员' . $model->operator_name, 1);
                return [
                    'code' => 0,
                    'desc' => '信息修改成功'
                ];
            } else {
                return [
                    'code' => 9999,
                    'desc' => $model->errors
                ];
            }
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }
    public function actionDel()
    {
        if(Yii::$app->request->isAjax){
            $this->returnJson();
            $id = Yii::$app->request->post('id','');
            if($id==1) return ['code'=>0,'desc'=>'系统管理员无法删除'];
            $tr = Yii::$app->db->beginTransaction();
            try{
                if(Operators::deleteAll(['id'=>$id])!==1) throw new Exception('删除管理员失败');
                if(AuthAssignment::deleteAll(['user_id'=>$id])!==1) throw new Exception('删除管理员失败');
                $tr->commit();
                return ['code'=>200];
            }catch (\Exception $e){
                $tr->rollBack();
                return ['code'=>0,'desc'=>$e->getMessage()];
            }
        }
    }

    /**
     * 选择父级单位
     */
    public function actionSelectRole()
    {
        $this->layout = 'main_large_frame';
        $connection = Yii::$app->db; //连接
        $sql = "select * from auth_item where type =:type";
        $command = $connection->createCommand($sql, [
            ':type' => 1
        ])->queryAll();
        return $this->render('select-role', [
            'commands' => $command
        ]);
    }

    //验证密码
    public function actionValidatePwd()
    {
        $model = new Pwd();
        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }
    }

    public function actionValidateAdd($id = '')
    {
        if ($id) {
            $model = Operators::findOne($id);
        } else {
            $model = new Operators();
        }

        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }
    }

    public function actionValidateResetPwd()
    {
        $model = new \app\models\ResetPwd();
        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }
    }

}
