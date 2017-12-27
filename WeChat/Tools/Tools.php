<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 下午3:44
 */

namespace WeChat\Tools;


use function simplexml_load_string;

class Tools
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
     * json转array
     *
     * @param $json
     *
     * @return mixed
     */
    static function json2arr ( $json )
    {
        return json_decode( $json , TRUE );
    }

    /**
     * array转json (中文安全)
     *
     * @param $data
     *
     * @return string
     */
    static function arr2json ( $data )
    {
        return json_encode( $data , JSON_UNESCAPED_UNICODE );
    }

    /**
     * 将xml转为array
     *
     * @param string $xml
     *
     * @return array
     */
    static public function xml2arr ( $xml )
    {
        return $xml?json_decode( self::json_encode( simplexml_load_string( $xml , 'SimpleXMLElement' , LIBXML_NOCDATA ) ) , TRUE ):FALSE;
    }

    /**
     * 生成安全JSON数据
     *
     * @param array $array
     *
     * @return string
     */
    static public function json_encode ( $array )
    {
        return preg_replace_callback( '/\\\\u([0-9a-f]{4})/i' , create_function( '$matches' , 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");' ) , json_encode( $array ) );
    }

    /**
     * 数组转xml
     *
     * @param $array
     *
     * @return string
     */
    static public function arr2xml ( $array )
    {
        $_xml = '<xml>' . self::arr2str( $array ) . '</xml>';

        return $_xml;
    }

    static private function arr2str ( $arr )
    {
        $_xml = '';
        foreach ( $arr as $key => $value )
        {
            if ( $key === 'CreateTime' || $key === 'TimeStamp' )
            {
                $_xml .= "<{$key}>{$value}</{$key}>";
            }
            else
            {
                $_key = is_int( $key ) ? 'item' : $key;

                $_xml .= "<{$_key}>";
                if ( is_array( $value ) )
                {
                    $_xml .= self::arr2str( $value );
                }
                else
                {
                    $_xml .= "<![CDATA[{$value}]]>";
                }
                $_xml .= "</{$_key}>";
            }
        }

        return $_xml;
    }

    /**
     * 获取随机字符串
     *
     * @return string
     */
    static public function getRandomStr ()
    {
        $str     = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max     = strlen( $str_pol ) - 1;
        for ( $i = 0; $i < 16; $i++ )
        {
            $str .= $str_pol[ mt_rand( 0 , $max ) ];
        }

        return $str;
    }

    static public function getIp ()
    {
        $ip = FALSE;
        if ( !empty( $_SERVER["HTTP_CLIENT_IP"] ) )
        {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
        {
            $ips = explode( ", " , $_SERVER['HTTP_X_FORWARDED_FOR'] );
            if ( $ip )
            {
                array_unshift( $ips , $ip );
                $ip = FALSE;
            }
            for ( $i = 0; $i < count( $ips ); $i++ )
            {
                if ( !eregi( "^(10│172.16│192.168)." , $ips[ $i ] ) )
                {
                    $ip = $ips[ $i ];
                    break;
                }
            }
        }

        return ( $ip ? $ip : $_SERVER['REMOTE_ADDR'] );
    }

}