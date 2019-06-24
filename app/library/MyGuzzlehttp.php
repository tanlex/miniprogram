<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/24
 * Time: 14:57
 */

require __DIR__.'/../vendor/autoload.php';

use GuzzleHttp\Client;

class MyGuzzlehttp{

    private $client;
    private $config;

    public function __construct(array $config = [])
    {
        $config = array_merge([
            'allow_redirects' => false,
            'http_errors' => true,
            'base_uri' => '',
        ], $config);
        $this->client = new Client($config);
        $this->config = $config;
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     */
    public function request($method, $url, $options)
    {
        foreach (['form_params', 'json', 'query'] as $k => $v) {
            if (!empty($this->config[$v]) && !empty($options[$v])) {
                $options[$v] = array_merge($this->config[$v], $options[$v]);
            }
        }

        $response = $this->client->request($method, $url, $options);
        //响应的状态码
        $code = $response->getStatusCode();
        //响应原因短语
        $reason = $response->getReasonPhrase();
        //响应消息头
        $headers = new \stdClass;
        foreach ($response->getHeaders() as $name => $values) {
            $headers->$name = implode('| ', $values);
        }
        //响应主体
        $body = $response ->getBody()->getContents();

        $res = new \stdClass;
        $res->code    = $code;
        $res->reason  = $reason;
        $res->headers = $headers;
        $res->body    = $body;
        return $res;
    }

    public function get($url, $options = [])
    {
        return $this->request('GET', $url, $options);
    }

    public function post($url, $options = [])
    {
        return $this->request('POST', $url, $options);
    }

    public function put($url, $options = [])
    {
        return $this->request('PUT', $url, $options);
    }

    public function delete($url, $options = [])
    {
        return $this->request('DELETE', $url, $options);
    }

}