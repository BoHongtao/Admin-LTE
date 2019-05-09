<?php

namespace api\utils;

class Utils {

    public static function isMobilePhone($phone){
        return (preg_match("/^1[34578]\d{9}$/",$phone)==0)?false:true;
    }

    public static function createUUID() {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        //return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        return vsprintf('%s%s%s%s%s%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function createToken() {
        return hash('md5',self::createUUID());
    }
    public static function createPwd($raw){
        $options = ['cost' => 10];
        return password_hash($raw, PASSWORD_BCRYPT, $options);
    }
    public static function verifyPwd($raw,$hash) {
        return password_verify($raw, $hash);
    }

    public static function verifyUrl($url){
        $result = preg_match("/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i", $url);
        return ($result==0)?false:true;
    }

    public static function genUniqueId(){
        list($sec, $usec) = explode('.', microtime(true));
        $time_str = sprintf('%s%4d',date("YmdHis",$sec),(float)$usec);
        return $time_str;
    }
}
