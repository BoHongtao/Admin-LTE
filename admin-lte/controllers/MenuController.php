<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 2017/10/31
 * Time: 15:05
 */
namespace app\controllers;
use app\models\Menu;
use Yii;

class MenuController extends BaseController{

    public function actionIndex(){
        $menu = Menu::getMenuList();
        return $this->render('index',[
            'menu'=>$menu
        ]);
    }
    //创建菜单
    public function actionCreate(){
        $model = new Menu();
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                //数据保存在菜单表中
                if(!$model->save()) throw new \Exception('操作失败');
                //以下操作就是把功能权限保存在权限表中
                $auth = Yii::$app->authManager;
                $result = explode(',',$model->act);
                foreach ($result as $val){
                    if(!$val) continue;
                    $item = $auth->getPermission($val);
                    if(empty($item)){
                        $authitem = $auth->createPermission($val);
                        if(!$auth->add($authitem)) throw new \Exception('权限添加失败！');
                    }
                }
                $transaction->commit();
            }catch(\Exception $e){
                $transaction->rollBack();
            }
            return $this->redirect(['index']);
        }

        $menu = $model->getMenuList();
        //创建菜单的时候的父级菜单列表
        $menuArr = array('0'=>'顶级菜单');
        foreach ($menu as $vo){
            $menuArr[$vo['id']] = $vo['name'];
            if(!empty($vo['_child'])){
                foreach ($vo['_child'] as $v){
                    $menuArr[$v['id']] =  "└─".$v['name'];
                    if(!empty($v['_child'])){
                        foreach ($v['_child'] as $v3){
                            $menuArr[$v3['id']] = "│ ├─".$v3['name'];
                        }
                    }
                }
            }
        }
        return $this->render('create',[
            'model' => $model,
            'menuArr' => $menuArr
        ]);
    }

    //修改菜单
    public function actionUpdate($id){
        $model = Menu::findOne($id);
        $oldresult = explode(',',$model->act);
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if(!$model->save()) throw new \Exception('操作失败');
                //添加权限
                $auth = Yii::$app->authManager;
                $result = explode(',',$model->act);
                // 新权限
                $newdata = array_diff($result,$oldresult);
                foreach ($newdata as $val){
                    if(!$val) continue;
                    $item = $auth->getPermission($val);
                    if(empty($item)){
                        $authitem = $auth->createPermission($val);
                        if(!$auth->add($authitem)) throw new \Exception('权限添加失败！');
                    }
                }
                // 去掉的权限
                $deldata = array_diff($oldresult,$result);
                foreach ($deldata as $val){
                    if(!$val) continue;
                    $item = $auth->getPermission($val);
                    if(!empty($item)) $auth->remove($item);
                }
                $transaction->commit();
            }catch(\Exception $e){
                $transaction->rollBack();
            }
            return $this->redirect(['index']);
        }

        $menu = $model->getMenuList();
        $menuArr = array('0'=>'顶级菜单');
        foreach ($menu as $vo){
            $menuArr[$vo['id']] = $vo['name'];
            if(!empty($vo['_child'])){
                foreach ($vo['_child'] as $v){
                    $menuArr[$v['id']] =  "|--".$v['name'];
                    if(!empty($v['_child'])){
                        foreach ($v['_child'] as $v3){
                            $menuArr[$v3['id']] =  "|----".$v3['name'];
                        }
                    }
                }
            }
        }
        return $this->render('update',[
            'model' => $model,
            'menuArr' => $menuArr
        ]);
    }

    //删除菜单
    public function actionDelete(){
        if(Yii::$app->request->isAjax){
            //删除id以及id下所有子菜单
            $id = Yii::$app->request->post('id','');
            $ids = Menu::getChildrenIds($id,true);
            $menus = Menu::find()->select("id,name,act")->where(["id"=>$ids])->asArray()->all();
            $this->returnJson();
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if(!Menu::deleteAll(["id"=>$ids])) throw new \Exception("删除失败");
                $auth = Yii::$app->authManager;
                foreach ($menus as $menu){
                    $deldata = explode(',',$menu["act"]);
                    foreach ($deldata as $val){
                        if(!$val) continue;
                        $item = $auth->getPermission($val);
                        if(!empty($item)) $auth->remove($item);
                    }
                }
                $transaction->commit();
                return ['code'=>200];
            }catch(\Exception $e){
                $transaction->rollBack();
                return ['code'=>0];
            }
        }
    }

    public function actionReset(){
        $auth = Yii::$app->authManager;
        $auth->removeAllPermissions();
        $menus = Menu::find()->select("id,name,act")->asArray()->all();
        foreach ($menus as $menu){
            $newdata = explode(',',$menu["act"]);
            foreach ($newdata as $val){
                if(!$val) continue;
                $item = $auth->getPermission($val);
                if(empty($item)){
                    $authitem = $auth->createPermission($val);
                    if(!$auth->add($authitem)) throw new \Exception('权限添加失败！');
                }
            }
        }
    }

}