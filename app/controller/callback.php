<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20
 * Time: 14:50
 */
/*
$signature = $_GET["signature"];
$timestamp = $_GET["timestamp"];
$nonce = $_GET["nonce"];

//var_dump($_GET);exit;
$token = 'tanlex';
$tmpArr = array($token, $timestamp, $nonce);
sort($tmpArr, SORT_STRING);
$tmpStr = implode( $tmpArr );
$tmpStr = sha1( $tmpStr );

if( $tmpStr == $signature ){
//    return true;
    echo $_GET["echostr"];
}else{
    return false;
}
*/
require_once "../config/api.php";
require_once "../library/MyGuzzlehttp.php";

$AppID = APPID;
$AppSecret = APPSECRET;


//接收推送
$postStr = file_get_contents("php://input");
//var_dump($postStr);exit;

$res = file_put_contents('./callback.log',$postStr."\n",FILE_APPEND);
//var_dump($res);exit;

if (!empty($postStr) && is_string($postStr)) {

    $postArr = json_decode($postStr, true);
    if (!empty($postArr['MsgType']) && $postArr['MsgType'] == 'text') {   //文本消息
        $fromUsername = $postArr['FromUserName'];   //发送者openid
        $toUserName = $postArr['ToUserName'];       //小程序id
        $Content = $postStr['Content'];

        if($Content == '你好'){
            //客服回复
            $access_token = get_access_token($AppID,$AppSecret);
            $access_token = json_decode($access_token);
            $access_token = $access_token->access_token;
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
            $data = array(
                'access_token' => $access_token,
                'touser' => $openid,
                'msgtype' => 'text',
                'text'  => array(
                    'content' => '您好，这是小程序平台'
                )
            );
            $MyGuzzlehttp = new MyGuzzlehttp();
            $res = $MyGuzzlehttp->post($url,[
                'json' => $data
            ]);
        }
    }
}


function get_access_token($AppID,$AppSecret)
{
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$AppID}&secret={$AppSecret}";
    $res = file_get_contents($url);
    return $res;
}









