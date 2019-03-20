<?php
/**
 * User: chenruixuan
 * Date: 2017/5/31 上午10:38
 * Email: www@chenruixuan.com
 * 处理微信相关事件
 */

namespace app\components;

use app\models\Journey;
use app\models\Qrcode;
use app\models\User;
use Yii;
use EasyWeChat\Foundation\Application;

class WechatEvent
{
    public static function handel($message)
    {
        switch ($message->MsgType) {
            case 'event':
                if ($message->Event == 'subscribe') {
                    return self::subscribe($message);
                }
                if ($message->Event == 'unsubscribe') {
                    return self::unsubscribe($message);
                }
                if ($message->Event == 'SCAN') {
                    return self::subscribe($message);
                }
                if ($message->Event == 'CLICK' && $message->EventKey == 'CUSTOMER_SERVICE') {
                    return self::mini_program_kefu($message);
                    //return self::customer_service($message);
                }
                /*if ($message->Event == 'kf_create_session' || $message->Event == 'kf_close_session') {
                    return self::customer_service_session($message);
                }*/
                if ($message->Event == 'LOCATION') {
                    return self::location($message);
                }
                break;
            /*case 'text':
                return '收到文字消息';
                break;
            case 'images':
                return '收到图片消息';
                break;
            case 'voice':
                return '收到语音消息';
                break;
            case 'video':
                return '收到视频消息';
                break;
            case 'location':
                return '收到坐标消息';
                break;
            case 'link':
                return '收到链接消息';
                break;*/
            default:
                return '您好，点击下方“联系客服”按钮，即可获取在线客服服务！';
                break;
        }
    }

    private static function subscribe($message)
    {
        self::checkuser($message);
        $string = "欢迎关注海外有朋！\n点击下方的“联系客服”按钮，即可获取在线客服服务！";
        if($message->EventKey){ // 新建用户获取场景值
            $array = Qrcode::attrScene($message->EventKey);
            if(!empty($array)){
                switch($array["scencetype"]){
                    case 0: // 行程
                        if(Journey::wxUserBind($message->FromUserName,$array['value'][0],$array['value'][1]))
                            $string = "新行程添加成功！\n点击下方的“查看行程”按钮，即可查看新行程的详细信息！";
                        break;
                    case 1: // 旅游公司
                        User::tourismWxUser($message->FromUserName,$array['value'][0],$array['value'][1]);
                        break;
                    case 2: // 用户小程序
                        break;
                }
            }
        }
        return $string;
    }

    private static function unsubscribe($message)
    {
        User::cancelWxUser($message->FromUserName);
        return "";
    }

    private static function mini_program_kefu($message){
        $userid = self::checkuser($message);
        $wechatnotice = new WechatNotice();
        $result = json_decode($wechatnotice->sendNotice($userid,0,"kefu"),true);
        if($result["errcode"] == 0){
            return "";
        }else{
            return $result["errmsg"];
        }
    }

    private static function customer_service($message){
        self::checkuser($message);
        $app = new Application(Yii::$app->params['WECHAT']);
        User::createWxUser($app->user->get($message->FromUserName));
        $staff = $app->staff; // 客服管理
        $list = $staff->onlines();
        if(count($list->kf_online_list) == 0)
            return "您好，当前没有客服在线，请稍后再试!";

        $session = $app->staff_session; // 客服会话管理
        $cursession = $session->get($message->FromUserName); // 当前会话
        if(empty($cursession->kf_account)){
            $kflist = [];
            foreach ($list->kf_online_list as $kf){
                $data = $session->lists($kf['kf_account']);
                $kflist[$kf['kf_account']] = count($data->sessionlist);
            }
            $kf_account = array_search(min($kflist),$kflist);
            $session->create($kf_account, $message->FromUserName);
        }else{
            $staff = $app->staff; // 客服管理
            $staff->message("有什么可以帮到您吗？")->by($cursession->kf_account)->to($message->FromUserName)->send();
        }
        return "";
    }

    private static function customer_service_session($message){
        self::checkuser($message);
        $app = new Application(Yii::$app->params['WECHAT']);
        $staff = $app->staff; // 客服管理
        if($message->Event == 'kf_create_session'){
            $staff->message("您好，很高兴为您服务，有什么可以帮到您吗？")->by($message->KfAccount)->to($message->FromUserName)->send();
        }
        if($message->Event == 'kf_close_session'){
            $staff->message("当前会话已结束，您可以点击下方的“联系客服”按钮重新获取在线客服服务！")->by($message->KfAccount)->to($message->FromUserName)->send();
        }
        return "";
    }

    private static function location($message){
        self::checkuser($message);
        User::locationWxUser($message);
        return '';
    }

    private static function checkuser($message){
        $app = new Application(Yii::$app->params['WECHAT']);
        return User::createWxUser($app->user->get($message->FromUserName));
    }

    /**根据不同场景获取后台配置的欢迎图文信息
     * @param $message
     * @param $type
     * @param string $senceId
     * @param string $title
     * @param string $attention_type
     * @param int $isfirst  是否是第一次关注
     * @return array|string
     * @throws \yii\db\Exception
     *
     */
    /*public static function sceneWelcome($message, $type, $senceId = '', $title = '', $attention_type = '', $isfirst = 0)
    {
        $data = [];
        $params = [
            ':scene_type' => $type
        ];
        $sqlarr = ['`type`=:scene_type', 'title like :title', "attention_type  = {$attention_type}", "sence_id = :sences_Id", 'is_default = 0'];
        $sql = "select * from {{%scene}} where ";
        //如果是关键字
        if ($title) {
            unset($sqlarr[2]);
            unset($sqlarr[3]);
            $params[':title'] = "%{$title}%";
        }
        //欢迎语
        if ($attention_type) {
            unset($sqlarr[1]);
            //如果是扫描并且有场景值
            if ($attention_type == 2 && $senceId) {
                $sence_Id = str_replace('qrscene_', '', $senceId);
                $params[':sences_Id'] = substr($sence_Id, 0, 32);
                //进行扫码场景值的记录入库
                self::scanRecommand($sence_Id, $message->FromUserName, $isfirst);
            } else {
                unset($sqlarr[3]);
            }


        }

        $nodefault = $sql . join(" and ", $sqlarr) . " order by id desc";

        $result = Yii::$app->db->createCommand($nodefault)->bindValues($params)->queryOne();

        if (!$result) {
            $sqlarr[4] = 'is_default = 1';
            if ($title) {
                unset($sqlarr[1]);
                unset($sqlarr[2]);
                unset($sqlarr[3]);
                unset($params[':title']);
            }

            $default = $sql . join(" and ", $sqlarr) . " order by id desc";
            //获取默认的
            $result = Yii::$app->db->createCommand($default)->bindValues($params)->queryOne();
        }
        //能匹配到数据
        if ($result) {
            if ($result['reply_type'] == 1) {
                return $result['reply_text'];
            } else {

                //图文回复
                $articleInfo = Yii::$app->db->createCommand("select * from {{%scene_article}} where scene_id=:scene_id and status=0 order by id ASC")->bindValue(":scene_id", $result['id'])->queryAll();
                Yii::info(var_export($articleInfo, true), 'articleInfo');
                if ($articleInfo) {
                    foreach ($articleInfo as $k => $v) {
                        //推荐办卡的URL
                        if ($result['type'] == 2 && $attention_type == 2 && $v['is_recommand_url'] == 0) {

                            $url = Url::toRoute(['auth/recommand', 'sence_id' => str_replace('qrscene_', '', $senceId)], true);
                        } else {
                            //普通URL
//                            if ($v['is_url'] == 0) {
                            //添加外链
                            $url = $v['url'];
//                            } else {
//                                //文章
//                                $url = Url::toRoute(['news/detail', 'uuid' => $v['uuid']], true);
////                                $url = Yii::$app->params['SCENE_WELCOME_NEWS_URL'] . $v['uuid'];
//                            }
                        }
                        $tmp = new News([
                            'title' => $v['title'],
                            'url' => $url,
                            'images' => Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . '/' . Yii::$app->params['file_access_url'] . substr($v['cover'], 0, 2) . '/' . $v['cover']
                        ]);
                        if ($v['is_first'] == 0) {
                            array_unshift($data, $tmp);
                        } else {
                            $data[] = $tmp;
                        }
                    }
                    Yii::info(var_export($data, true), 'gucheng');
                    return $data;
                } else {
                    //没有设置图文
                    return '欢迎关注';
                }
            }
        } else {


            return '欢迎关注';

        }

    }*/
}