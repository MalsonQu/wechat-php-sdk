<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/22
 * Time: 下午4:04
 */

namespace WeChat;


use WeChat\Exception\ParamException;
use WeChat\Tools\Token;
use WeChat\Tools\Http;
use WeChat\Tools\Tools;

class Card extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    /**
     * 卡券颜色列表
     *
     * @var array
     */
    private $cardColors = [
        'Color010' => '#63b359' ,
        'Color020' => '#2c9f67' ,
        'Color030' => '#509fc9' ,
        'Color040' => '#5885cf' ,
        'Color050' => '#9062c0' ,
        'Color060' => '#d09a45' ,
        'Color070' => '#e4b138' ,
        'Color080' => '#ee903c' ,
        'Color081' => '#f08500' ,
        'Color082' => '#a9d92d' ,
        'Color090' => '#dd6549' ,
        'Color100' => '#cc463d' ,
        'Color101' => '#cf3e36' ,
        'Color102' => '#5E6671' ,
    ];
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
     * @param array $img 图片数据
     *
     * @return string url地址
     * @throws ParamException
     */
    public function uploadImg ( $img )
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
        $_url = self::$LINKS['CARD_IMG_UPLOAD'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'buffer' => new \CURLFile( realpath( $img['path'] ) , $img['type'] , $img['name'] ) ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , $_data ) );

        return $_result['url'];

    }
}