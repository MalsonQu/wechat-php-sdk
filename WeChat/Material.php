<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/21
 * Time: 上午11:21
 */

namespace WeChat;


use WeChat\Exception\ParamException;
use WeChat\Exception\WeResultException;
use WeChat\Tools\AccessToken;
use WeChat\Tools\Http;
use WeChat\Tools\Tools;

class Material extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    private $materialType = [
        'image' ,
        'video' ,
        'voice' ,
        'news' ,
    ];

    // 每页显示数量
    private $listRow = 20;

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
     * 获取素材列表
     *
     * @param  string $type 素材类型
     * @param int     $page 页码
     *
     * @return mixed
     * @throws ParamException
     * @throws WeResultException
     */
    public function batchGetMaterial ( $type , $page = 1 )
    {
        if ( !is_string( $type ) || !in_array( $type , $this->materialType ) )
        {
            throw new ParamException( '参数<type>不合法' );
        }

        // 强转int
        $page = (int) $page;

        if ( $page <= 0 )
        {
            throw new ParamException( '参数<type>只能为大于1的数字' );
        }

        $_url   = self::$LINKS['BATCH_GET_MATERIAL'] . 'access_token=' . AccessToken::get();
        $_param = [
            'type'   => $type ,
            'offset' => ( $page - 1 ) * $this->listRow ,
            'count'  => $this->listRow ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_param ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }
}