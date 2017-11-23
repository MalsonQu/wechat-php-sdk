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

class Token extends Base
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
     * 获取access_token
     *
     * @return bool|mixed
     * @throws WeResultException
     */
    static public function getAccessToken ()
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

    /**
     * 获取jsapi_ticket
     *
     * @return bool|mixed
     * @throws WeResultException
     */
    static public function getJsApiTicket ()
    {
        $_accessToken = Cache::get( 'jsApiTicket' );

        if ( $_accessToken === FALSE || empty( $_accessToken ) )
        {
            $_data = [
                'access_token' => Token::getAccessToken() ,
                'type'         => 'jsapi' ,
            ];

            $_accessToken = Tools::json2arr( Http::httpGet( self::$LINKS['JS_API_TICKET_GET'] , $_data ) );

            if ( isset( $_accessToken['errcode'] ) && $_accessToken['errcode'] !== 0 )
            {
                throw new WeResultException( $_accessToken['errcode'] );
            }

            Cache::set( 'jsApiTicket' , $_accessToken['ticket'] , 7100 );

            $_accessToken = $_accessToken['ticket'];

        };

        return $_accessToken;
    }

}