# encoding: utf-8
# @author: John
# @contact: BoHongtao@yeah.net
# @software: PyCharm
# @time: 2019/4/30 10:28

import time

##产生头部
def header(env,author,auto_date,auto_time,model_name):
    info = """
<?php
/**
 * Created by """ + env + """.
 * User: """ + author + """
 * Date: """ + auto_date + """
 * Time: """ + auto_time + """
 */
namespace app\controllers;
use Yii;
use app\models\\""" + model_name + """;
use yii\db\Exception;
"""
    return info

#产生控制器主体
def controller(controller_name,model_name,route):
    info = """
class """ + controller_name + """ extends BaseController
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
        $query = """ + model_name + """::find();
        $name and $query->filterWhere(['like','name',$name]);
        $pager = $this->Pager($query,'""" + route + """');
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
        $""" + model_name.lower() + """ = new """ + model_name + """();
        if(Yii::$app->request->isPost && $""" + model_name.lower() + """->load(Yii::$app->request->post())){
            $this->returnJson();
            if(!$""" + model_name.lower() + """->validate())
                return ['code'=>0,'msg'=>$this->getMsg($""" + model_name.lower() + """)];
            $""" + model_name.lower() + """->create_time = date('Y-m-d H:i:s',time());
            if($""" + model_name.lower() + """->save()){
                return ['code'=>200,'msg'=>'增加成功'];
            }
            return ['code'=>0,'msg'=>$this->getMsg($""" + model_name.lower() + """)];
        }
        return $this->render('add',[
            'model'=>$""" + model_name.lower() + """,
        ]);
    }
    
    /*
     * update
     */
    public function actionUpdate($id='')
    {
        $id and $""" + model_name.lower() + """ =  """ + model_name + """::findOne($id);
        if(Yii::$app->request->isPost && $""" + model_name.lower() + """->load(Yii::$app->request->post())){
            $this->returnJson();
            if(!$""" + model_name.lower() + """->validate())
                return ['code'=>0,'msg'=>$this->getMsg($""" + model_name.lower() + """)];
            $""" + model_name.lower() + """->create_time = date('Y-m-d H:i:s',time());
            if($""" + model_name.lower() + """->save()){
                return ['code'=>200,'msg'=>'修改成功'];
            }
            return ['code'=>0,'msg'=>$this->getMsg($""" + model_name.lower() + """)];
        }
        return $this->render('update',[
            'model'=>$""" + model_name.lower() + """,
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
                if(""" + model_name + """::deleteAll(['id'=>$id])!==false){
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
        $id and $model = """ + model_name + """::findOne($id) or $model = new """ + model_name + """();
        if ($model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\\bootstrap\ActiveForm::validate($model);
        }
    }
}
    
"""
    return info

if __name__ == '__main__':
    controller_name = input("请输入控制器名称:\n")
    model_name = input("请输入模型名字\n")
    #代码生成时间
    auto_date = time.strftime('%Y-%m-%d',time.localtime(time.time()))
    auto_time = time.strftime('%H:%M:%S',time.localtime(time.time()))
    #作者
    author = 'auto_code_robots'
    # 环境
    env = 'Python 3.6'
    # 分页路由
    route = controller_name.replace('Controller','').lower()+'data'

    auto_header = header(env,author,auto_date,auto_time,model_name)
    controller = controller(controller_name,model_name,route)
    out_put = auto_header + controller
    f = open(controller_name+'.php','w',encoding='utf-8')
    f.write(out_put)
    f.close()




