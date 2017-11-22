<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 下午2:04
 */

namespace WeChat\Tools;


use WeChat\Base;
use WeChat\Exception\WeResultException;

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

    /**
     * 获取accessToken
     * @return bool|mixed
     * @throws WeResultException
     */
    static public function get ()
    {
        $_accessToken = Cache::get( 'accessToken' );

        if ( $_accessToken === FALSE || empty( $_accessToken ) )
        {
            $_data = [
                'grant_type' => 'client_credential' ,
                'appid'      => self::$config['AppID'] ,
                'secret'     => self::$config['AppSecret'] ,
            ];

            $_accessToken = Tools::json2arr( Http::httpGet( self::$LINKS['ACCESS_TOKEN_GET'] , $_data ) );

            if ( isset( $_accessToken['errcode'] ) && $_accessToken['errcode'] !== 0 )
            {
                throw new WeResultException( $_accessToken['errcode'] );
            }

            Cache::set( 'accessToken' , $_accessToken['access_token'] , 7100 );

            $_accessToken = $_accessToken['access_token'];

        };

        return $_accessToken;
    }

}