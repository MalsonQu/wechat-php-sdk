<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 下午2:11
 */

namespace WeChat\Tools;


use WeChat\Exception\FileException;

class Cache
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

    static function set ( $name , $val , $time )
    {

        $_data = [
            'exp'  => time() + $time ,
            'data' => $val ,
        ];

        if ( file_put_contents( self::getTmpFile() . $name , serialize( $_data ) ) === FALSE )
        {
            throw new FileException( '无法写入缓存' );
        }

        return TRUE;
    }

    static function get ( $name )
    {
        if ( !self::has( $name ) )
        {
            return FALSE;
        }

        $_content = unserialize( file_get_contents( self::getTmpFile() . $name ) );

        if ( time() < $_content['exp'] )
        {
            return $_content['data'];
        }

        return FALSE;
    }

    static function has ( $name )
    {
        return file_exists( self::getTmpFile() . $name );
    }

    static private function getTmpFile ()
    {
        $_cacheDirPath = dirname( __FILE__ ) . '/../../cache/';

        if ( !is_dir( $_cacheDirPath ) )
        {
            if ( !mkdir( $_cacheDirPath ) )
            {
                throw new FileException( '缓存目录不存在,并且权限不足以创建它' );
            }
        }

        return $_cacheDirPath;
    }


}