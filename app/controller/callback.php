<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20
 * Time: 14:50
 */
require_once "../config/api.php";
require_once "../library/MyCurlhttp.php";

class Callback{

    private $AppID;
    private $AppSecret;

    public function __construct($AppID,$AppSecret){
        $this->AppID = $AppID;
        $this->AppSecret = $AppSecret;
    }

    /**
     * 分发
     * @param $postStr
     */
    public function handle(){
        //判断是否为认证　
        if (isset($_GET['echostr'])) {
            $this->valid();
        }else{
            //否则接收客户发送消息
            $this->responseMsg();
        }
    }

    /**
     * 验证
     */
    public function valid()
    {
        $echoStr   = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];
        //var_dump($_GET);exit;
        $token = 'tanlex';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
//            return true;
            echo $echoStr;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        //接收推送
        $postStr = file_get_contents("php://input");
        //var_dump($postStr);exit;

        file_put_contents('../log/callback.log',$postStr."\n",FILE_APPEND);

        if (!empty($postStr) && is_string($postStr)) {

            $postArr = json_decode($postStr, true);
            $fromUsername = $postArr['FromUserName'];   //发送者openid
            $toUserName   = $postArr['ToUserName'];     //小程序id
            if (!empty($postArr['MsgType']) && $postArr['MsgType'] == 'text')
            {
                //文本消息
                $Content = $postArr['Content'];

                if($Content == '你好'){
                    //客服回复接口
                    $access_token = $this->get_access_token();
                    $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                    $data = [
                        'touser'  => $fromUsername,
                        'msgtype' => 'text',
                        'text'    => ['content'=>'您好，这是小程序平台']
                    ];
                    $json = json_encode($data,JSON_UNESCAPED_UNICODE);
                    $MyCurlhttp = new MyCurlhttp();
                    $res = $MyCurlhttp->_post_json_new($url,$json);
                    file_put_contents('../log/callback.log',$res."\n",FILE_APPEND);
                }
            }
            elseif($postArr['MsgType'] == 'event' && $postArr['Event']=='user_enter_tempsession')
            {
                //进入客服动作
                $access_token = $this->get_access_token();

                $content = '您好，有什么能帮助你?';
                $data=array(
                    "touser"  =>$fromUsername,
                    "msgtype" =>"text",
                    "text"    =>array("content"=>$content)
                );
                $json = json_encode($data,JSON_UNESCAPED_UNICODE);

                $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                $MyCurlhttp = new MyCurlhttp();
                $res = $MyCurlhttp->_post_json_new($url,$json);

                file_put_contents('../log/callback.log',$res."\n",FILE_APPEND);
            }
            elseif(!empty($postArr['MsgType']) && $postArr['MsgType'] == 'image')
            {
                //图文消息
                $PicUrl  = $postArr['PicUrl'];
                $MsgId   = $postArr['MsgId'];
                $MediaId = $postArr['MediaId'];

                //推送图文
                $access_token = $this->get_access_token();
                $data = [
                    'touser'  => $fromUsername,
                    'msgtype' => 'image',
                    'image'   => ['media_id'=>$MediaId]
                ];
                $json = json_encode($data,JSON_UNESCAPED_UNICODE);

                $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                $MyCurlhttp = new MyCurlhttp();
                $res = $MyCurlhttp->_post_json_new($url,$json);
                file_put_contents('../log/callback.log',$res."\n",FILE_APPEND);

                //推送链接
                $data = [
                    'touser'  => $fromUsername,
                    'msgtype' => 'link',
                    'link'    => [
                        'title'       =>'图片赞赞赞！',
                        'description' =>'推荐更多精彩请看！！',
                        'url'         =>'http://sports.sina.com.cn/nba/',
                        'thumb_url'   => 'http://n.sinaimg.cn/sports/crawl/116/w550h366/20190617/bf65-hymscpr0538150.jpg'
                    ]
                ];
                $json = json_encode($data,JSON_UNESCAPED_UNICODE);
                $res = $MyCurlhttp->_post_json_new($url,$json);
                file_put_contents('../log/callback.log',$res."\n",FILE_APPEND);
            }

        }
    }

    /**
     * 采用文件缓存
     * access_token
     */
    private function get_access_token()
    {
        //读取缓存
        $res = file_get_contents('../log/access_token.log');
        if(empty($res)){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->AppID}&secret={$this->AppSecret}";
            $res = file_get_contents($url);
            //日志
            file_put_contents('../log/callback.log',$res."\n",FILE_APPEND);

            $res = json_decode($res);
            $access_token = $res->access_token;
            $expires_in = $res->expires_in;

            //根据过期时间重组缓存数据
            $expires_time = time() + $expires_in;
            $arr = ['access_token'=>$access_token,'expires_time'=>$expires_time];
            //缓存
            file_put_contents('../log/access_token.log',json_encode($arr));
        }else{
            //判断是否过期
            $res = json_decode($res);
            $access_token = $res->access_token;
            $expires_time = $res->expires_time;
            if(time() > $expires_time){
                //已过期，重新获取
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->AppID}&secret={$this->AppSecret}";
                $res = file_get_contents($url);
                //日志
                file_put_contents('../log/callback.log',$res."\n",FILE_APPEND);

                $res = json_decode($res);
                $access_token = $res->access_token;
                $expires_in = $res->expires_in;

                //根据过期时间重组缓存数据
                $expires_time = time() + $expires_in;
                $arr = ['access_token'=>$access_token,'expires_time'=>$expires_time];
                //缓存
                file_put_contents('../log/access_token.log',json_encode($arr));
            }
        }
        return $access_token;
    }
}


$callback = new Callback(APPID,APPSECRET);

$callback->handle();















