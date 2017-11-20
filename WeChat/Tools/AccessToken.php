<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 下午2:04
 */

namespace WeChat\Tools;


use WeChat\Base;

class AccessToken extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | 绑定
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | 获取器
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | 方法
    // +----------------------------------------------------------------------

    static public function get ()
    {
        $_accessToken = Cache::get( 'accessToken' );

        if ( $_accessToken === FALSE )
        {
            $_data = [
                'grant_type' => 'client_credential' ,
                'appid'      => self::$config['AppID'] ,
                'secret'     => self::$config['AppSecret'] ,
            ];

            $_accessToken = Tools::json2arr( Http::httpGet( self::$LINKS['ACCESS_TOKEN_GET'] , $_data ) )['access_token'];

            Cache::set( 'accessToken' , $_accessToken , 7100 );

        };

        return $_accessToken;


    }

}