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
        'AppID'          => '' ,
        'AppSecret'      => '' ,
        'token'          => '' ,
        'EncodingAesKey' => '' ,
        'Encrypt'        => FALSE ,
        'WeChatID'       => '' ,
        'MchID'          => '' ,
        'Key'            => '' ,
    ];

    static protected $LINKS = [
        // 获取access_token
        'ACCESS_TOKEN_GET'            => 'https://api.weixin.qq.com/cgi-bin/token?' ,
        // 获取jsapi_ticket
        'API_TICKET_GET'              => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?' ,
        // 授权登录的 access_token
        'OAUTH_ACCESS_TOKEN_GET'      => 'https://api.weixin.qq.com/sns/oauth2/access_token?' ,
        // 重新获取用户的access_token
        'OAUTH_REFRESH_TOKEN'         => 'https://api.weixin.qq.com/sns/oauth2/refresh_token?' ,
        //拉取用户信息
        'USER_INFO'                   => 'https://api.weixin.qq.com/sns/userinfo?' ,
        // 检验授权凭证（access_token）是否有效
        'CHECK_OAUTH_ACCESS_TOKEN'    => 'https://api.weixin.qq.com/sns/auth?' ,
        // 菜单创建
        'MENU_CREATE'                 => 'https://api.weixin.qq.com/cgi-bin/menu/create?' ,
        // 获取菜单
        'MENU_GET'                    => 'https://api.weixin.qq.com/cgi-bin/menu/get?' ,
        // 删除菜单
        'MENU_DELETE'                 => 'https://api.weixin.qq.com/cgi-bin/menu/delete?' ,
        // 创建个性化菜单
        'DIY_MENU_CREATE'             => 'https://api.weixin.qq.com/cgi-bin/menu/addconditional?' ,
        // 删除个性化菜单
        'DIY_MENU_DELETE'             => 'https://api.weixin.qq.com/cgi-bin/menu/delconditional?' ,
        // 测试个性化菜单匹配结果
        'DIY_MENU_TEST'               => 'https://api.weixin.qq.com/cgi-bin/menu/trymatch?' ,
        // 授权跳转
        'OAUTH'                       => 'https://open.weixin.qq.com/connect/oauth2/authorize?' ,
        // 添加客服
        'KF_ACCOUNT_CREATE'           => 'https://api.weixin.qq.com/customservice/kfaccount/add?' ,
        // 编辑客服
        'KF_ACCOUNT_UPDATE'           => 'https://api.weixin.qq.com/customservice/kfaccount/update?' ,
        // 删除客服
        'KF_ACCOUNT_DELETE'           => 'https://api.weixin.qq.com/customservice/kfaccount/del?' ,
        // 上传头像
        'KF_ACCOUNT_HEAD_IMG'         => 'https://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?' ,
        // 获取列表
        'KF_ACCOUNT_LIST'             => 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?' ,
        // 获取在线客服列表
        'KF_ACCOUNT_ONLINE_LIST'      => 'https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist?' ,
        // 邀请绑定
        'KF_ACCOUNT_INVITE_WORKER'    => 'https://api.weixin.qq.com/customservice/kfaccount/inviteworker?' ,
        // 发送消息
        'KF_ACCOUNT_SEND'             => 'https://api.weixin.qq.com/cgi-bin/message/custom/send?' ,
        // 类型
        'KF_ACCOUNT_TYPING'           => 'https://api.weixin.qq.com/cgi-bin/message/custom/typing?' ,
        // 创建客服会话
        'KF_SESSION_CREATE'           => 'https://api.weixin.qq.com/customservice/kfsession/create?' ,
        // 关闭会话
        'KF_SESSION_CLOSE'            => 'https://api.weixin.qq.com/customservice/kfsession/close?' ,
        // 获取客户会话状态
        'KF_SESSION_GET'              => 'https://api.weixin.qq.com/customservice/kfsession/getsession?' ,
        // 获取客服会话列表
        'KF_SESSION_LIST'             => 'https://api.weixin.qq.com/customservice/kfsession/getsessionlist?' ,
        // 获取未接入会话列表
        'KF_SESSION_WAIT_LIST'        => 'https://api.weixin.qq.com/customservice/kfsession/getwaitcase?' ,
        // 获取聊天记录
        'KF_MSG_LIST'                 => 'https://api.weixin.qq.com/customservice/msgrecord/getmsglist?' ,
        // 新建门店
        'POI_ADD'                     => 'http://api.weixin.qq.com/cgi-bin/poi/addpoi?' ,
        // 媒体图片上传接口
        'MEDIA_IMG_UPLOAD'            => 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?' ,
        // 统一下单
        'PAY_UNIFIED_ORDER'           => 'https://api.mch.weixin.qq.com/pay/unifiedorder' ,
        // 创建子商户
        'CREATE_CHILD_BRAND'          => 'https://api.weixin.qq.com/card/submerchant/submit?' ,
        // 创建卡券
        'CARD_CREATE'                 => 'https://api.weixin.qq.com/card/create?' ,
        // 创建二维码接口
        'CARD_CREATE_QR_CODE'         => 'https://api.weixin.qq.com/card/qrcode/create?' ,
        // 卡券白名单
        'CARD_TEST_WHITE_LIST'        => 'https://api.weixin.qq.com/card/testwhitelist/set?' ,
        // 导入卡券code
        'CARD_CODE_DEPOSIT'           => 'https://api.weixin.qq.com/card/code/deposit?' ,
        // 查询导入code数目接口
        'CARD_CODE_GET_DEPOSIT_COUNT' => 'https://api.weixin.qq.com/card/code/getdepositcount?' ,
        // 核查code接口
        'CARD_CODE_CHECK'             => 'https://api.weixin.qq.com/card/code/checkcode?' ,
        // 图文消息群发卡券
        'CARD_GET_MP_NEWS_HTML'       => 'https://api.weixin.qq.com/card/mpnews/gethtml?' ,
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

    protected function _initialize (){}

}