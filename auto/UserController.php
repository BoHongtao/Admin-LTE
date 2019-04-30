
<?php
/**
 * Created by Python 3.6.
 * User: auto_code_robots
 * Date: 2019-04-30
 * Time: 17:31:55
 */
namespace app\controllers;
use Yii;
use app\models\User;
use yii\db\Exception;

class UserController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /*
     * list
     */
    public function actionData($name='')
    {
        $query = User::find();
        $name and $query->filterWhere(['like','name',$name]);
        $pager = $this->Pager($query,'userdata');
        $Info = $query->offset($pager->offset)->limit($pager->limit)->orderBy('create_time desc')->asArray()->all();
        return $this->renderPartial('_list',[
            'Info'=>$Info,
            'pager'=>$pager
        ]);
    }
    
    /*
     * add
     */
    public function actionAdd()
    {
        $user = new User();
        if(Yii::$app->request->isPost && $user->load(Yii::$app->request->post())){
            $this->returnJson();
            if(!$user->validate())
                return ['code'=>0,'msg'=>$this->getMsg($user)];
            $user->create_time = date('Y-m-d H:i:s',time());
            if($user->save()){
                return ['code'=>200,'msg'=>'增加成功'];
            }
            return ['code'=>0,'msg'=>$this->getMsg($user)];
        }
        return $this->render('add',[
            'model'=>$user,
        ]);
    }
    
    /*
     * update
     */
    public function actionUpdate($id='')
    {
        $id and $user =  User::findOne($id);
        if(Yii::$app->request->isPost && $user->load(Yii::$app->request->post())){
            $this->returnJson();
            if(!$user->validate())
                return ['code'=>0,'msg'=>$this->getMsg($user)];
            $user->create_time = date('Y-m-d H:i:s',time());
            if($user->save()){
                return ['code'=>200,'msg'=>'修改成功'];
            }
            return ['code'=>0,'msg'=>$this->getMsg($user)];
        }
        return $this->render('update',[
            'model'=>$user,
            'id'=>$id
        ]);
    }
    
    /*
     *  del
     */
    public function actionDel()
    {
        if(Yii::$app->request->isAjax){
            $this->returnJson();
            $id = Yii::$app->request->post('id','');
            if($id){
                if(User::deleteAll(['id'=>$id])!==false){
                    return ['code'=>200,'msg'=>'删除成功'];
                }
                return ['code'=>0,'msg'=>'删除失败'];
            }
            return ['code'=>0,'msg'=>'参数错误'];
        }
    }
    
    /*
     * validate add and update form
     */
    public function actionValidate($id='')
    {
        $id and $model = User::findOne($id) or $model = new User();
        if ($model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }
    }
}
    
