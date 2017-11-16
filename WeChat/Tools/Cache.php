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

    static function set ( $name , $val )
    {
        if ( is_array( $val ) )
        {
            $val = serialize( $val );
        }

        file_put_contents( self::getTmpFile() . $name , $val );
    }

    static function get ( $name )
    {
        if ( !self::has( $name ) )
        {
            return FALSE;
        }

        $_content = file_get_contents( self::getTmpFile() . $name );

        if ( @$_val = unserialize( $_content ) )
        {
            return $_val;
        }

        return $_content;
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
            if (!mkdir( $_cacheDirPath ))
            {
                throw new FileException('权限不足');
            }
        }

        return $_cacheDirPath;
    }


}