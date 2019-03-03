<?php
/**
 * Created by PhpStorm.
 * User: kindness
 * Date: 2019/3/3
 * Time: 11:29
 * 主动微信公众号请求类
 */
class WeChat{
    const APPID = 'wx6e3834b70a844e94';
    const SECRET = '4abe033c8a145f1c4ccee39e2656af56';

    //接口数组
    private $config = [];

    public function __construct()
    {
        $this->config = include 'apiConfig.php';
    }

    //获取access_token值
    public function getAccessToken(){
        $url = sprintf($this->config['access_token_url']);
    }
}