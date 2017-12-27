<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/27
 * Time: 上午8:57
 */

namespace WeChat;


use WeChat\Exception\ParamException;
use WeChat\Tools\Http;
use WeChat\Tools\Token;
use WeChat\Tools\Tools;

class News extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    private $links = [
        'UPLOAD' => 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?' ,
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

    public function upload ( $messages )
    {
        $_messageCount = count( $messages );

        if ( $_messageCount > 8 || $_messageCount < 1 )
        {
            throw new ParamException( '参数<messages>长度错误' );
        }

        $_data['articles'] = $messages;

        $_url = $this->links['UPLOAD'] . 'access_token=' . Token::getAccessToken();


        $_result = Http::httpPost( $_url , Tools::arr2json( $_data ) );
        var_dump( $_result );
    }


}