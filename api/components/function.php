<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2019/6/5
 * Time: 14:34
 */

/**
 * curl 请求post数据
 * @param string $header 请求头部
 * @param string $server_url 请求地址
 * @param array $param 请求参数
 */
function httpPost($header, $server_url, $param) {
    $ch = curl_init();
    if($header){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_URL, $server_url); //地址
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1); //post数据提交
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param); //数据
    $body = curl_exec($ch);
    curl_close($ch);
    return $body;
}