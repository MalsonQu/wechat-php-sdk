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
     * @param string       $url     POST提交的内容
     * @param array|string $data    请求的地址
     * @param string       $ssl_cer 证书Cer路径 | 证书内容
     * @param string       $ssl_key 证书Key路径 | 证书内容
     * @param int          $second  设置请求超时时间
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
     * 以get方式提交请求
     *
     * @param string $url
     *
     * @param array  $data
     *
     * @return bool|mixed
     */
    static public function httpGet ( $url , $data = [] )
    {
        $curl = curl_init();

        if ( stripos( $url , "https://" ) !== 0 )
        {
            curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , FALSE );
            curl_setopt( $curl , CURLOPT_SSL_VERIFYHOST , FALSE );
            curl_setopt( $curl , CURLOPT_SSLVERSION , 1 );
        }

        if ( !empty( $data ) )
        {
            $url .= http_build_query( $data );
        }

        curl_setopt( $curl , CURLOPT_URL , $url );
        curl_setopt( $curl , CURLOPT_RETURNTRANSFER , 1 );
        list( $content , $status ) = [
            curl_exec( $curl ) ,
            curl_getinfo( $curl ) ,
            curl_close( $curl ) ,
        ];

        return ( intval( $status["http_code"] ) === 200 ) ? $content : FALSE;
    }

    /**
     * 下载一个文件
     *
     * @param string|array $url             需要下载的链接
     * @param string       $savePath        储存图片的路径路径最后需要带 /
     * @param int          $connectTimeOut  连接超时时间
     * @param int          $timeOut         执行超时时间
     *
     * @return array|bool|string    返回结果为 FALSE 时表明有一个或多个下载出错
     *                              返回结果为 string 时 结果为 保存的文件名   (只有 url 为 string 时才会返回此结果)
     *                              返回结果为 array 时 数组内容为 保存的文件名数组    (只有 url 为 array 时才会返回此结果)
     */
    static public function httpDownload ( $url , $savePath , $connectTimeOut = 5 , $timeOut = 100 )
    {
        if ( is_string( $url ) )
        {
            $_curl = curl_init();

            if ( stripos( $url , "https://" ) !== 0 )
            {
                curl_setopt( $_curl , CURLOPT_SSL_VERIFYPEER , FALSE );
                curl_setopt( $_curl , CURLOPT_SSL_VERIFYHOST , FALSE );
                curl_setopt( $_curl , CURLOPT_SSLVERSION , 1 );
            }

            $_u = new Url( $url );

            $_fileName = Tools::getRandomStr() . '.' . $_u->getQuery( 'wx_fmt' );

            $_file = fopen( $savePath . $_fileName , 'w+' );

            curl_setopt( $_curl , CURLOPT_URL , $url );
            curl_setopt( $_curl , CURLOPT_RETURNTRANSFER , 1 );
            curl_setopt( $_curl , CURLOPT_CONNECTTIMEOUT , $connectTimeOut );
            curl_setopt( $_curl , CURLOPT_TIMEOUT , $timeOut );
            curl_setopt( $_curl , CURLOPT_HEADER , FALSE );
            curl_setopt( $_curl , CURLOPT_FILE , $_file );
            curl_exec( $_curl );
            $status = curl_getinfo( $_curl );
            curl_close( $_curl );
            fclose( $_file );

            return ( intval( $status["http_code"] ) === 200 ) ? $_fileName : FALSE;

        }

        if ( is_array( $url ) )
        {
            // 文件名
            $_fileNames = [];
            // 句柄
            $_handle = curl_multi_init();
            // 文件打开句柄
            $_file = [];
            // curl_init 句柄
            $_chs = [];
            foreach ( $url as $key => $value )
            {

                $_chs[ $key ] = curl_init( $value );

                if ( stripos( $value , "https://" ) !== 0 )
                {
                    curl_setopt( $_chs[ $key ] , CURLOPT_SSL_VERIFYPEER , FALSE );
                    curl_setopt( $_chs[ $key ] , CURLOPT_SSL_VERIFYHOST , FALSE );
                    curl_setopt( $_chs[ $key ] , CURLOPT_SSLVERSION , 1 );
                }

                $_u = new Url( $value );

                $_fileNames[] = $_fileName = Tools::getRandomStr() . '.' . $_u->getQuery( 'wx_fmt' );

                $_file[ $key ] = fopen( $savePath . $_fileName , 'w+' );

                curl_setopt( $_chs[ $key ] , CURLOPT_RETURNTRANSFER , 1 );
                curl_setopt( $_chs[ $key ] , CURLOPT_CONNECTTIMEOUT , $connectTimeOut );
                curl_setopt( $_chs[ $key ] , CURLOPT_TIMEOUT , $timeOut );
                curl_setopt( $_chs[ $key ] , CURLOPT_HEADER , FALSE );
                curl_setopt( $_chs[ $key ] , CURLOPT_FILE , $_file[ $key ] );

                curl_multi_add_handle( $_handle , $_chs[ $key ] );
            }

            do
            {
                while ( ( $_execRun = curl_multi_exec( $_handle , $_running ) ) == CURLM_CALL_MULTI_PERFORM )
                {
                    if ( $_execRun != CURLM_OK )
                    {
                        break;
                    }
                }

                while ( $_done = curl_multi_info_read( $_handle ) )
                {
                    if ( $_done['result'] != CURLM_OK )
                    {
                        return FALSE;
                    }

                    curl_multi_remove_handle( $_handle , $_done['handle'] );
                }

                if ( $_running )
                {
                    $rel = curl_multi_select( $_handle , 1 );
                    if ( $rel == -1 )
                    {
                        usleep( 1000 );
                    }
                }

                if ( $_running == FALSE )
                {
                    break;
                }

            }
            while ( TRUE );

            return $_fileNames;
        }

        return FALSE;

    }
}