<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/23
 * Time: 下午2:37
 */

namespace WeChat\Tools;


use WeChat\Base;

class Sign extends Base
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
     * jsSdk 签名
     *
     * @param $data
     *
     * @return string
     */
    static public function jsSdkConfig ( $data )
    {
        $_toBeSignature['noncestr']     = $data['nonceStr'];
        $_toBeSignature['timestamp']    = $data['timestamp'];
        $_toBeSignature['jsapi_ticket'] = Token::getJsApiTicket();
        $_toBeSignature['url']          = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        ksort( $_toBeSignature );

        $_param = [];

        foreach ( $_toBeSignature as $k => $v )
        {
            $_param[] = "{$k}={$v}";
        }

        return sha1( join( '&' , $_param ) );
    }

    static public function Pay ( $data )
    {
        ksort( $data );

        $_param = [];
        foreach ( $data as $k => $v )
        {
            if ( !empty( $v ) )
            {
                $_param[] = "{$k}={$v}";
            }
        }

        return strtoupper( md5( join( '&' , $_param ) . '&key=' . self::$config['Key'] ) );
    }

}