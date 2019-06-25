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
    $fromUsername = $postArr['FromUserName'];   //发送者openid
    $toUserName = $postArr['ToUserName'];       //小程序id
    if (!empty($postArr['MsgType']) && $postArr['MsgType'] == 'text')
    {
        //文本消息
        $Content = $postArr['Content'];
        file_put_contents('./callback.log',$Content."\n",FILE_APPEND);

        if($Content == '你好'){
            //客服回复接口
            $access_token = get_access_token($AppID,$AppSecret);
            $access_token = json_decode($access_token);
            $access_token = $access_token->access_token;
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
            $data = [
                'touser'  => $fromUsername,
                'msgtype' => 'text',
                'text'    => ['content'=>'您好，这是小程序平台']
            ];
            $MyGuzzlehttp = new MyGuzzlehttp();
            $res = $MyGuzzlehttp->post($url,[
                'json' => $data
            ]);
            file_put_contents('./callback.log',$res->body."\n",FILE_APPEND);
        }
    }
    elseif($postArr['MsgType'] == 'event' && $postArr['Event']=='user_enter_tempsession')
    {
        //进入客服动作
        $access_token = get_access_token($AppID,$AppSecret);
        $access_token = json_decode($access_token);
        $access_token = $access_token->access_token;

        $content = '您好，有什么能帮助你?';
        $data=array(
            "touser"=>$fromUsername,
            "msgtype"=>"text",
            "text"=>array("content"=>$content)
        );
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);

        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        //以'json'格式发送post的https请求
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($json)){
            curl_setopt($curl, CURLOPT_POSTFIELDS,$json);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            //捕抓异常
            file_put_contents('./callback.log','Errno'.curl_error($curl)."\n",FILE_APPEND);
        }
        curl_close($curl);

        file_put_contents('./callback.log',$output."\n",FILE_APPEND);

    }


}


function get_access_token($AppID,$AppSecret)
{
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$AppID}&secret={$AppSecret}";
    $res = file_get_contents($url);
    file_put_contents('./callback.log',$res."\n",FILE_APPEND);
    return $res;
}









