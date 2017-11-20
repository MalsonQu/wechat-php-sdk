<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 上午10:08
 */

namespace WeChat;


class Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    // Tokken 验证
    static protected $config = [
        'AppID'     => '' ,
        'AppSecret' => '' ,
        'token'     => '' ,
    ];

    static protected $LINKS = [
        // 获取access_token
        'ACCESS_TOKEN_GET'         => 'https://api.weixin.qq.com/cgi-bin/token?' ,
        // 授权登录的 access_token
        'OAUTH_ACCESS_TOKEN_GET'   => 'https://api.weixin.qq.com/sns/oauth2/access_token?' ,
        // 重新获取用户的access_token
        'OAUTH_REFRESH_TOKEN'  => 'https://api.weixin.qq.com/sns/oauth2/refresh_token?' ,
        //拉取用户信息
        'USER_INFO'                => 'https://api.weixin.qq.com/sns/userinfo?' ,
        // 检验授权凭证（access_token）是否有效
        'CHECK_OAUTH_ACCESS_TOKEN' => 'https://api.weixin.qq.com/sns/auth?' ,
        // 菜单创建
        'MENU_CREATE'              => 'https://api.weixin.qq.com/cgi-bin/menu/create?' ,
        // 获取菜单
        'MENU_GET'                 => 'https://api.weixin.qq.com/cgi-bin/menu/get?' ,
        // 删除菜单
        'MENU_DELETE'              => 'https://api.weixin.qq.com/cgi-bin/menu/delete?' ,
        // 创建个性化菜单
        'DIY_MENU_CREATE'          => 'https://api.weixin.qq.com/cgi-bin/menu/addconditional?' ,
        // 删除个性化菜单
        'DIY_MENU_DELETE'          => 'https://api.weixin.qq.com/cgi-bin/menu/delconditional?' ,
        // 测试个性化菜单匹配结果
        'DIY_MENU_TEST'            => 'https://api.weixin.qq.com/cgi-bin/menu/trymatch?' ,
        // 授权跳转
        'OAUTH'                    => 'https://open.weixin.qq.com/connect/oauth2/authorize?' ,
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

    function __construct ()
    {
        $this->_initialize();
    }

    protected function _initialize ()
    {

    }

}