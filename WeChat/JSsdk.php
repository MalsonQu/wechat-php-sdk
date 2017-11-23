<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/23
 * Time: 上午8:14
 */

namespace WeChat;


use WeChat\Tools\Token;
use WeChat\Tools\Tools;

class JSsdk extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    /**
     * 配置文件
     *
     * @var array
     */
    private $jsConfig = [
        'debug'     => FALSE ,
        'appId'     => '' ,
        'timestamp' => '' ,
        'nonceStr'  => '' ,
        'jsApiList' => [] ,
        'signature' => '' ,
    ];


    // +----------------------------------------------------------------------
    // | 绑定
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | 获取器
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | 方法
    // +----------------------------------------------------------------------

    public function config ( $apiList = [] , $debug = FALSE )
    {
        $this->jsConfig['debug']     = $debug;
        $this->jsConfig['appId']     = self::$config['AppID'];
        $this->jsConfig['timestamp'] = time();
        $this->jsConfig['nonceStr']  = Tools::getRandomStr();
        $this->jsConfig['jsApiList'] = $apiList;
        $this->jsConfig['signature'] = $this->signature();

        return $this->jsConfig;
    }

    /**
     * 签名
     *
     * @return string
     */
    private function signature ()
    {
        $_toBeSignature['noncestr']     = $this->jsConfig['noncestr'];
        $_toBeSignature['jsapi_ticket'] = Token::getJsApiTicket();
        $_toBeSignature['timestamp']    = $this->jsConfig['timestamp'];
        $_toBeSignature['url']          = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        sort( $_toBeSignature );

        return sha1( http_build_query( $_toBeSignature ) );
    }
}