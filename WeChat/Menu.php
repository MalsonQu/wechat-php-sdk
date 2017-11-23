<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 下午1:59
 */

namespace WeChat;

use WeChat\Exception\ParamException;
use WeChat\Exception\WeResultException;
use WeChat\Tools\Token;
use WeChat\Tools\Http;
use WeChat\Tools\Tools;

/**
 * 自定义菜单
 *
 * Class Menu
 * @package WeChat
 */
class Menu extends Base
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
     * 创建(设置)自定义菜单
     *
     * @param $data
     *
     * @return bool
     * @throws ParamException
     * @throws WeResultException
     */
    static function create ( $data )
    {
        if ( empty( $data ) )
        {
            throw new ParamException( '请传入<创建(设置)自定义菜单>所需的参数' );
        }
        $_url = self::$LINKS['MENU_CREATE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 获取当前设置的 自定义菜单
     * @return mixed
     * @throws WeResultException
     */
    static function get ()
    {
        $_url = self::$LINKS['MENU_GET'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpGet( $_url ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 清空所有的自定义菜单
     * @return bool
     * @throws WeResultException
     */
    static function delete ()
    {
        $_url = self::$LINKS['MENU_DELETE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpGet( $_url ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 创建(设置)个性化菜单
     *
     * @param $data
     *
     * @return bool
     * @throws ParamException
     * @throws WeResultException
     */
    static function diyCreate ( $data )
    {
        if ( empty( $data ) )
        {
            throw new ParamException( '请传入<创建(设置)创建个性化菜单>所需的参数' );
        }
        $_url = self::$LINKS['DIY_MENU_CREATE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['menuid'];
    }

    /**
     * 删除个性化菜单
     *
     * @param string $menuId 菜单ID
     *
     * @return bool
     * @throws ParamException
     * @throws WeResultException
     */
    static function diyDelete ( $menuId )
    {
        if ( empty( $menuId ) )
        {
            throw new ParamException( '缺少参数<$menuId>' );
        }
        $_url = self::$LINKS['DIY_MENU_DELETE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( [ 'menuid' => $menuId ] ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 测试个性化菜单匹配结果
     *
     * @param string $user_id 粉丝的OpenID或微信号
     *
     * @return mixed
     * @throws ParamException
     * @throws WeResultException
     */
    static function diyTest ( $user_id )
    {
        if ( empty( $user_id ) )
        {
            throw new ParamException( '缺少参数<$user_id>' );
        }

        $_url = self::$LINKS['DIY_MENU_TEST'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( [ 'user_id' => $user_id ] ) ) );

        //        var_dump( $_result );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;

    }

}