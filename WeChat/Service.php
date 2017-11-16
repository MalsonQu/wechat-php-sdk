<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 上午10:20
 */

namespace WeChat;


use WeChat\Exception\ConfigException;
use WeChat\Tools\Verify;

class Service extends Base
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
     * 验证微信服务器配置
     * @return bool
     * @throws ConfigException
     */
    static public function verify ()
    {
        foreach ( [
                      'timestamp' ,
                      'nonce',
                  ] as $item )
        {
            if ( !isset( $_GET[ $item ] ) )
            {
                throw new ConfigException( '未定义' . $item );
            }
        }

        $_verifySignatureArr = [
            self::$config['token'] ,
            $_GET['timestamp'] ,
            $_GET['nonce'] ,
        ];

        return Verify::signature( $_verifySignatureArr , $_GET['signature'] ) ? $_GET['echostr'] : FALSE;
    }

}