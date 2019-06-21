<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20
 * Time: 15:45
 */

require_once "../config/api.php";


$AppID = APPID;
$AppSecret = APPSECRET;
$code = $_GET['code'];

if(!empty($code)){
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$AppID}&secret={$AppSecret}&js_code={$code}&grant_type=authorization_code";
    $res = file_get_contents($url);

    echo $res;

}else{
    echo 'The code require';
}