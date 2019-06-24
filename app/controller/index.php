<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20
 * Time: 15:45
 */

require_once "../config/api.php";
require_once "../library/MyGuzzlehttp.php";


$AppID = APPID;
$AppSecret = APPSECRET;

/**
 * 获取openid
 */
if($_REQUEST['act'] == 'get_openid')
{
    $code = $_GET['code'];
    if(!empty($code)){
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$AppID}&secret={$AppSecret}&js_code={$code}&grant_type=authorization_code";
        $res = file_get_contents($url);

        echo $res;

    }else{
        echo 'The code require';
    }
}
/**
 * 获取unionid
 */
elseif($_REQUEST['act'] == 'get_unionid')
{
    $openid = 'o9YuQ4jZhf_6lVSzbr26_PTxrpzs';
    $access_token = get_access_token($AppID,$AppSecret);
    $access_token = json_decode($access_token);
    $access_token = $access_token->access_token;
//    echo $access_token;exit;

    $url = "https://api.weixin.qq.com/wxa/getpaidunionid?access_token={$access_token}&openid={$openid}";
    $url = $url."&transaction_id=&mch_id=&=out_trade_no";
//    $res = file_get_contents($url);

    $MyGuzzlehttp = new MyGuzzlehttp();
    $res = $MyGuzzlehttp->get($url);

    echo $res->body;
}
/**
 * 数据分析
 */
elseif($_REQUEST['act'] == 'getweanalysisappidvisitpage')
{
    $access_token = get_access_token($AppID,$AppSecret);
    $access_token = json_decode($access_token);
    $access_token = $access_token->access_token;
    $url = "https://api.weixin.qq.com/datacube/getweanalysisappidvisitpage?access_token={$access_token}";
    $data = array(
        'access_token' => $access_token,
        'begin_date' => '20190623',
        'end_date' => '20190623'
    );
    $MyGuzzlehttp = new MyGuzzlehttp();
    $res = $MyGuzzlehttp->post($url,[
        'json' => $data
    ]);
    echo $res->body;
}
/**
 * 客服消息
 */
elseif($_REQUEST['act'] == 'service_send')
{
    $openid = 'o9YuQ4jZhf_6lVSzbr26_PTxrpzs';
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
    echo $res->body;
}
/**
 * 模板消息
 */
elseif($_REQUEST['act'] == 'template_send')
{
    $openid = 'o9YuQ4jZhf_6lVSzbr26_PTxrpzs';
    $access_token = get_access_token($AppID,$AppSecret);
    $access_token = json_decode($access_token);
    $access_token = $access_token->access_token;
    $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
    $data = array(
        'access_token' => $access_token,
        'touser' => $openid,
        'template_id' => 'opOj5Sc4fFfh27Xm6d6hPq3D_q68OPEMQ8O3LmsAXw0',
        'form_id' => 'changeClick',
    );
    $MyGuzzlehttp = new MyGuzzlehttp();
    $res = $MyGuzzlehttp->post($url,[
        'json' => $data
    ]);
    echo $res->body;
}
/**
 * 插件管理
 */
elseif($_REQUEST['act'] == 'plugin_list')
{
    $access_token = get_access_token($AppID,$AppSecret);
    $access_token = json_decode($access_token);
    $access_token = $access_token->access_token;
    $url = "https://api.weixin.qq.com/wxa/plugin?access_token={$access_token}";
    $data = array(
        'action' => 'list'
    );
    $MyGuzzlehttp = new MyGuzzlehttp();
    $res = $MyGuzzlehttp->post($url,[
        'json' => $data
    ]);
    echo $res->body;
}
else
{
    echo 'The request error';
}


function get_access_token($AppID,$AppSecret)
{
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$AppID}&secret={$AppSecret}";
    $res = file_get_contents($url);
    return $res;
}