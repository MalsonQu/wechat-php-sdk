<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 下午2:58
 */

namespace WeChat\Tools;


class Http
{
    
    // +----------------------------------------------------------------------
    // | 方法
    // +----------------------------------------------------------------------
    /**
     * 以post方式提交请求
     *
     * @param string       $url
     * @param array|string $data
     *
     * @return bool|mixed
     */
    static public function httpPost ( $url , $data )
    {
        $curl = curl_init();
        curl_setopt( $curl , CURLOPT_URL , $url );
        curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , FALSE );
        curl_setopt( $curl , CURLOPT_SSL_VERIFYHOST , FALSE );
        curl_setopt( $curl , CURLOPT_RETURNTRANSFER , TRUE );
        curl_setopt( $curl , CURLOPT_HEADER , FALSE );
        curl_setopt( $curl , CURLOPT_POST , TRUE );
        curl_setopt( $curl , CURLOPT_POSTFIELDS , $data );
        list( $content , $status ) = [
            curl_exec( $curl ) ,
            curl_getinfo( $curl ) ,
            curl_close( $curl ) ,
        ];

        return ( intval( $status["http_code"] ) === 200 ) ? $content : FALSE;
    }

    /**
     * 使用证书，以post方式提交xml到对应的接口url
     *
     * @param string $url     POST提交的内容
     * @param array  $data    请求的地址
     * @param string $ssl_cer 证书Cer路径 | 证书内容
     * @param string $ssl_key 证书Key路径 | 证书内容
     * @param int    $second  设置请求超时时间
     *
     * @return bool|mixed
     */
    static public function httpsPost ( $url , $data , $ssl_cer = NULL , $ssl_key = NULL , $second = 30 )
    {
        $curl = curl_init();
        curl_setopt( $curl , CURLOPT_URL , $url );
        curl_setopt( $curl , CURLOPT_TIMEOUT , $second );
        curl_setopt( $curl , CURLOPT_HEADER , FALSE );
        curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , FALSE );
        curl_setopt( $curl , CURLOPT_SSL_VERIFYHOST , FALSE );
        curl_setopt( $curl , CURLOPT_RETURNTRANSFER , TRUE );
        if ( !is_null( $ssl_cer ) && file_exists( $ssl_cer ) && is_file( $ssl_cer ) )
        {
            curl_setopt( $curl , CURLOPT_SSLCERTTYPE , 'PEM' );
            curl_setopt( $curl , CURLOPT_SSLCERT , $ssl_cer );
        }
        if ( !is_null( $ssl_key ) && file_exists( $ssl_key ) && is_file( $ssl_key ) )
        {
            curl_setopt( $curl , CURLOPT_SSLKEYTYPE , 'PEM' );
            curl_setopt( $curl , CURLOPT_SSLKEY , $ssl_key );
        }
        curl_setopt( $curl , CURLOPT_POST , TRUE );
        curl_setopt( $curl , CURLOPT_POSTFIELDS , $data );
        list( $content , $status ) = [
            curl_exec( $curl ) ,
            curl_getinfo( $curl ) ,
            curl_close( $curl ) ,
        ];

        return ( intval( $status["http_code"] ) === 200 ) ? $content : FALSE;
    }

    /**
     * 以GET方式提交请求
     *
     * @param string $url  请求的地址
     * @param array  $data 请求内容
     *
     * @return bool|mixed
     */
    static public function httpGet ( $url , $data = [] )
    {
        $curl = curl_init();
        if ( !empty( $var ) )
        {
            $url .= http_build_query( $data );
        }
        curl_setopt( $curl , CURLOPT_URL , $url );
        curl_setopt( $curl , CURLOPT_HEADER , FALSE );
        curl_setopt( $curl , CURLOPT_RETURNTRANSFER , TRUE );
        list( $content , $status ) = [
            curl_exec( $curl ) ,
            curl_getinfo( $curl ) ,
            curl_close( $curl ) ,
        ];

        return ( intval( $status["http_code"] ) === 200 ) ? $content : FALSE;


    }
}