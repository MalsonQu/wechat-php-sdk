<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/23
 * Time: 上午9:57
 */

namespace WeChat\Tools;


use WeChat\Base;
use WeChat\Exception\ParamException;
use WeChat\Exception\WeResultException;

class Media extends Base
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
     * 上传图片
     *
     * @param array $img 图片数据
     *
     * @return string url地址
     * @throws ParamException
     * @throws WeResultException
     */
    static public function uploadImg ( $img )
    {

        if ( empty( $img['path'] ) )
        {
            throw new ParamException( "参数<path>必须填写" );
        }
        if ( empty( $img['type'] ) )
        {
            throw new ParamException( '参数<type>必须填写' );
        }
        if ( empty( $img['name'] ) )
        {
            throw new ParamException( '参数<name>必须填写' );
        }
        $_url = self::$LINKS['MEDIA_IMG_UPLOAD'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'buffer' => new \CURLFile( realpath( $img['path'] ) , $img['type'] , $img['name'] ) ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['url'];
    }
}