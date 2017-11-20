<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 上午10:15
 */

namespace WeChat;


use WeChat\Exception\WeResultException;
use WeChat\Tools\Http;
use WeChat\Tools\Tools;

class Oauth extends Base
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
     * 获取 OpenID
     *
     * @param string $redirectUri 回调地址
     * @param string $state       携带参数
     * @param string $scope
     */
    public function getOpenID ( $redirectUri , $state = NULL , $scope = 'snsapi_base' )
    {
        $_param = [
            'appid'         => self::$config['AppID'] ,
            'redirect_uri'  => $redirectUri ,
            'response_type' => 'code' ,
        ];

        $_param['scope'] = $scope;

        if ( isset( $state ) )
        {
            $_param['state'] = $state;
        }

        $_url = self::$LINKS['OAUTH'] . http_build_query( $_param ) . '#wechat_redirect';

        header( "Location: {$_url}" );

    }

    /**
     * code 换取AccessToken
     *
     * @return bool|mixed
     * @throws WeResultException
     */
    public function code2accessToken ()
    {
        $_param = [
            'appid'      => self::$config['AppID'] ,
            'secret'     => self::$config['AppSecret'] ,
            'code'       => $_GET['code'] ,
            'grant_type' => 'authorization_code' ,
        ];

        $_url = self::$LINKS['OAUTH_ACCESS_TOKEN_GET'];

        $_result = Tools::json2arr( Http::httpGet( $_url , $_param ) );


        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;

    }

    /**
     * 检验授权凭证（access_token）是否有效
     *
     * @param string $access_token access_token
     * @param string $openid       用户的open_id
     *
     * @return bool
     * @throws WeResultException
     */
    public function checkAccessToken ( $access_token , $openid )
    {
        $_param = [
            'access_token' => $access_token ,
            'openid'       => $openid ,
        ];

        $_result = Tools::json2arr( Http::httpGet( self::$LINKS['CHECK_OAUTH_ACCESS_TOKEN'] , $_param ) );

        return !( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 );
    }

    /**
     * 刷新access_token
     *
     * @param $refresh_token
     *
     * @return mixed
     * @throws WeResultException
     */
    public function refreshToken2accessToken ( $refresh_token )
    {
        $_param = [
            'appid'         => self::$config['AppID'] ,
            'grant_type'    => 'refresh_token' ,
            'refresh_token' => $refresh_token ,
        ];

        $_result = Tools::json2arr( Http::httpGet( self::$LINKS['OAUTH_REFRESH_TOKEN'] , $_param ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 获取用户信息 无论哪种授权方式,都使用这个方法来获取用户信息
     *
     * @param string $access_token access_token
     * @param string $openID       用户的open_id
     * @param string $lang         语言
     *
     * @return bool|mixed
     * @throws WeResultException
     */
    public function getUserInfo ( $access_token = NULL , $openID = NULL , $lang = 'zh_CN' )
    {

        $_user = NULL;

        if ( isset( $access_token ) && isset( $openID ) )
        {
            $_access_token = $access_token;
            $_openID       = $openID;
            $_scope        = 'snsapi_userinfo';
        }
        else
        {
            $_user         = $this->code2accessToken();
            $_access_token = $_user['access_token'];
            $_openID       = $_user['openid'];
            $_scope        = $_user['scope'];
        }


        if ( $_scope === 'snsapi_base' )
        {
            return $_user;
        }

        $_param = [
            'access_token' => $_access_token ,
            'openid'       => $_openID ,
            'lang'         => $lang ,
        ];

        $_result = Tools::json2arr( Http::httpGet( self::$LINKS['USER_INFO'] , $_param ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;

    }


}