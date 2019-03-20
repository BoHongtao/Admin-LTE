<?php
namespace app\controllers;
use app\models\Menu;
use Yii;
use app\models\AuthItem;

class RoleController extends BaseController
{
    public function actionIndex() {
        $command = AuthItem::find()->where(['type'=>1])->all();
        return $this->render('index', [
            'roles' => $command
        ]);
    }

    public function actionCreateRole(){
        $model = new AuthItem();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $this->returnJson();
            $auth = Yii::$app->authManager;
            $role = $auth->createRole($model->name);
            $role->description = $model->description;
            if ($auth->add($role)) {
                recordLog('添加了角色'.$role->name, 0); //记录日志
                return ['code' => 0, 'desc' => '添加成功'];
            } else {
                return ['code' => 999, 'desc' => '添加失败'];
            }
        }
        return $this->render('add', [
            'model' => $model
        ]);
    }

    //删除
    public function actionDel(){
        if(Yii::$app->request->isAjax){
            $this->returnJson();
            $auth = Yii::$app->authManager;
            $name = Yii::$app->request->post('name','');
            if($name){
                $user = $auth->getUserIdsByRole($name);
                if(!empty($user))  return ['code'=>0,'desc'=>'当前角色绑定管理员，请先解除绑定'];
                $role = $auth->getRole($name);
                $tr = Yii::$app->db->beginTransaction();
                try{
                    $data = $auth->getPermission($name);
                    $remove_child = true;
                    if(!empty($data)) $remove_child = $auth->removeChildren($role);
                    $remove_role = $auth->remove( $role );
                    if(!$remove_child || !$remove_role) throw new \Exception('删除失败');
                    $tr->commit();
                    return ['code'=>200,'desc'=>'成功'];
                }catch (\Exception $e){
                    $tr->rollBack();
                    return ['code'=>0,'desc'=>$e->getMessage()];
                }
            }
            return ['code'=>0,'desc'=>'失败'];
        }
    }


    //权限配置
    public function actionQuanxian($name)
    {
        if (Yii::$app->request->isPost) {
            $this->returnJson();
            $data = Yii::$app->request->post();
            $permission = [];
            if(!isset($data['rules'])) return ['code'=>9999,'desc'=>'未选中权限'];
            foreach ($data['rules'] as $value) {
                $permission = array_merge($permission, explode(',', $value));
            }
            $permission = array_filter(array_unique($permission));
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($name);
            $tr = Yii::$app->db->beginTransaction();
            try{
                $auth->removeChildren($role);
                foreach ($permission as $val) {
                    $authitem = $auth->getPermission($val);
                    if(!empty($authitem)){
                        if(!$auth->addChild($role, $authitem))
                            throw new \Exception("配置角色权限失败！");
                    }else{
                        throw new \Exception('未知错误，可能权限不存在');
                    }
                }
                recordLog('为角色'.$name.'重新配置了权限', 9); //记录日志
                $tr->commit();
                return ['code'=>0,'desc'=>'添加成功'];
            }catch (\Exception $e){
                $tr->rollBack();
                return ['code'=>9999,'desc'=>$e->getMessage()];
            }
        }
        $this->layout = 'main_large_frame';
        $auth = Yii::$app->authManager;
        $omenus = Menu::allMenuList();
        $menus = list_to_tree($omenus);
        $authitems = $auth->getPermissionsByRole($name);
        $child = array_keys($authitems);
        return $this->render('quanxian', [
            'name' => $name,
            'data' => $menus,
            'rule' => json_encode($child)
        ]);
    }

    /**
     * ajax表单验证
     * @return type
     */
    public function actionValidateAdd()
    {
        $user = new \app\models\AuthItem();
        if ($user->load(Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($user);
        }
    }
}
