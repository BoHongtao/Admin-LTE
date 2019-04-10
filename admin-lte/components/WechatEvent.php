<?php
/*
 * Created by PhpStorm.
 * User: john
 * Date: 2017/10/31
 * Time: 15:05
*/
namespace app\components;

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
                if ($message->Event == 'LOCATION') {
                    return self::location($message);
                }
                break;
            case 'text':
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
                break;
            default:
                return 'xxx';
                break;
        }
    }

    private static function subscribe()
    {
        return "";
    }

    private static function unsubscribe()
    {
        return "";
    }

    private static function location(){
        return '';
    }
}