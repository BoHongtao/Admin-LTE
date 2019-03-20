<?php
namespace app\components;

use app\models\Journey;
use app\models\Qrcode;
use app\models\User;
use Yii;
use EasyWeChat\Foundation\Application;

class WechatNotice
{
    public $page = "pages/trip/trip";
    public $template = [
        "query" => [ // 确认通知
            //"tpid" => "1nZxtYgsEFJqmuqGq3294Eyj4wsnKTrnDE_9Mg0JYdE",
            "tpid" => "d2TxkHH01x_brPQrMGbQVjwdRWxdtMxSV3fEB-Q0KbM",
            "first" => "您的行程已确认。",
            "remark" => "请点击查看您的行程规划"
        ],
        "change" => [ // 变更通知
            "tpid" => "d2TxkHH01x_brPQrMGbQVjwdRWxdtMxSV3fEB-Q0KbM",
            "first" => "您的行程已变更，请及时产看，以免影响您的旅行。",
            "remark" => "请点击继续您的行程规划"
        ],
        "kefu" => [ // 在线客服
            "tpid" => "d2TxkHH01x_brPQrMGbQVjwdRWxdtMxSV3fEB-Q0KbM",
            "first" => "已为您连接到在线客服。",
            "remark" => "请点击立即咨询",
            "page" => "pages/message/message"
        ]
    ];
    /**
     * 发送模板消息
     * @param int $userid
     * @param int $journeyid
     * @param string $type
     * @return string
     */
    public function sendNotice($userid =0,$journeyid = 0,$type = ""){
        if(!isset($this->template[$type])){
            return json_encode([
                "errcode" => 9999,
                "errmsg" => "模板类型未定义！"
            ]);
        }
        $user = User::find()->where(["id"=>$userid])->asArray()->one();
        $journey = Journey::find()->where(["id"=>$journeyid])->asArray()->one();
        $scene = Qrcode::getScene(2, [$userid,$journeyid]);
        $page = isset($this->template[$type]["page"])?$this->template[$type]["page"]:$this->page;
        $app = new Application(Yii::$app->params['WECHAT']);
        $data = [
            'touser' => $user["wx_openid"],
            'template_id' => $this->template[$type]["tpid"],
            //'url' => Url::toRoute(["wechat-journey/qrcode","userid"=>$userid,"journeyid"=>$journeyid]),
            'miniprogram' => [
                "appid" => Yii::$app->params["WECHAT"]["mini_program"]["app_id"],
                "pagepath" => $page . "?scene=".$scene
            ],
            'data' => [
                'first' => $this->template[$type]["first"],
                'keyword1' => isset($journey["name"])?$journey["name"]:"暂无",
                'keyword2' => isset($journey["trip_time"])?$journey["trip_time"]:"暂无",
                'remark' => $this->template[$type]["remark"]
            ]
        ];
        return $app->notice->send($data)->toJson();
    }


}