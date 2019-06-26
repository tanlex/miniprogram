<?php


class MyCurlhttp {


    
    
    /**
	 * curl模拟post请求方式
	 * @param string $url
	 * @param array  $post_data
	 */
	public function _post_array_request($url,$post_data){
		$ch = curl_init();
		curl_setopt ( $ch, CURLOPT_URL, $url);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ( $ch, CURLOPT_POST, 1);
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false); //跳过SSL证书检查
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt ( $ch, CURLOPT_TIMEOUT,10);//设置cURL允许执行的最长秒数
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	
	/**
	 * curl模拟post请求
	 * @param string $url
	 * @param string $json
	 * @return json
	 */
	public function _post_json_new($url, $json, $cookie='')
	{
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false); //跳过SSL证书检查
		curl_setopt ( $ch, CURLOPT_TIMEOUT,60);//设置cURL允许执行的最长秒数
		if (!empty($json)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($json))
			);
		}
		$response = curl_exec($ch);
// 		var_dump(curl_error($ch));
		curl_close($ch);
		return $response;
	}

	
	/**
	 * get方式
	 */
	public function _get_request_new($url,$querystring,$cookie_file=''){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url.$querystring);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		if(!empty($cookie_file)){
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); //请求带上COOKIE文件
		}
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	
	/**
	 * @param  $url
	 * @param  $json
	 * @param  $cookie_file
	 */
	public function post_json_cookie($url,$json,$cookie_file){
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false); //跳过SSL证书检查
		curl_setopt ( $ch, CURLOPT_TIMEOUT,10); //设置cURL允许执行的最长秒数
		if (!empty($json)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($json))
			);
		}
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); //生成COOKIE信息到文件
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}


	/**
	 * curl模拟get请求方式,通过BASIC认证的方式
	 * @param string  $url
	 * @param string  $querystring
	 * @param string  $user
	 * @param string  $pass
	 */
	public function _get_request_by_basic($url,$querystring,$user,$pass){
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url.$querystring );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false); //跳过SSL证书检查
		curl_setopt ( $ch, CURLOPT_TIMEOUT,60);//设置cURL允许执行的最长秒数
		curl_setopt ( $ch, CURLOPT_USERPWD, "{$user}:{$pass}");//basic认证
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);//使用的HTTP验证方法
		$response = curl_exec($ch);
// 		var_dump(curl_error($ch));
		curl_close($ch);
		return $response;
	}


	/**
	 * curl模拟post请求，通过BASIC认证的方式
	 * @param string $url
	 * @param string $json
	 * @return json
	 */
	public function _post_request_by_basic($url, $json, $user, $pass)
	{
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false); //跳过SSL证书检查
		curl_setopt ( $ch, CURLOPT_TIMEOUT,60);//设置cURL允许执行的最长秒数
		curl_setopt ( $ch, CURLOPT_USERPWD, "{$user}:{$pass}");//basic认证
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);//使用的HTTP验证方法
		if (!empty($json)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($json))
			);
		}
		$response = curl_exec($ch);
// 		var_dump(curl_error($ch));
		curl_close($ch);
		return $response;
	}


    /**
     * curl模拟post请求，通过BASIC认证的方式
     * @param string $url
     * @param string $array
     * @return array
     */
    public function _post_array_request_by_basic($url, $array, $user, $pass){
        $ch = curl_init();
        curl_setopt ( $ch, CURLOPT_URL, $url);
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ( $ch, CURLOPT_POST, 1);
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false); //跳过SSL证书检查
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $array);
        curl_setopt ( $ch, CURLOPT_TIMEOUT,10);//设置cURL允许执行的最长秒数
        curl_setopt ( $ch, CURLOPT_USERPWD, "{$user}:{$pass}");//basic认证
        curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);//使用的HTTP验证方法
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }



}