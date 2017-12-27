<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/28
 * Time: 上午10:06
 */

namespace WeChat\Tools;


class Url
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    private $url = '';

    private $urlInfo = [];

    private $query = [];
    // +----------------------------------------------------------------------
    // | 绑定
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | 获取器
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | 方法
    // +----------------------------------------------------------------------

    function __construct ( $url )
    {
        $this->url     = $url;
        $this->urlInfo = parse_url( $url );

        if ( isset( $this->urlInfo['query'] ) )
        {
            $this->buildQuery();
        }
    }

    private function buildQuery ()
    {
        $_queryString = $this->urlInfo['query'];

        $_queryKeyValue = explode( '&' , $_queryString );

        foreach ( $_queryKeyValue as $value )
        {
            $_query = explode( '=' , $value );

            $this->query[ $_query[0] ] = $_query[1];
        }
    }

    /**
     * 获取链接参数
     *
     * @param null|string $key 参数的下标
     *
     * @return array|mixed|null
     */
    public function getQuery ( $key = NULL )
    {
        if ( isset( $key ) )
        {
            return isset( $this->query[ $key ] ) ? $this->query[ $key ] : NULL;
        }
        else
        {
            return $this->query;
        }
    }

    public function getFragment ()
    {
        return isset( $this->urlInfo['fragment'] ) ? $this->urlInfo['fragment'] : NULL;
    }

}