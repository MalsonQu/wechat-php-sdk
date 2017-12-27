<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/27
 * Time: 下午2:47
 */

namespace WeChat;


use WeChat\Exception\ParamException;
use WeChat\Exception\WeResultException;
use WeChat\Tools\Http;
use WeChat\Tools\Token;
use WeChat\Tools\Tools;

class SendMessage extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    /**
     * 链接汇总
     *
     * @var array
     */
    private $links = [
        // 根据OpenID列表群发
        'MASS_SEND_BY_OPENID' => 'https://api.weixin.qq.com/cgi-bin/message/mass/send?' ,
        // 根据标签进行群发
        'MASS_SEND_BY_TAGID'  => 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?' ,
        // 删除群发
        'MASS_DELETE'         => 'https://api.weixin.qq.com/cgi-bin/message/mass/delete?' ,
        // 群发预览
        'MASS_PREVIEW'        => 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?' ,
        // 获取群发状态
        'MASS_GET_STATUS'     => 'https://api.weixin.qq.com/cgi-bin/message/mass/get?' ,
        // 获取群发速度
        'MASS_GET_SPEED'      => 'https://api.weixin.qq.com/cgi-bin/message/mass/speed/get?' ,
        // 设置群发速度
        'MASS_SET_SPEED'      => 'https://api.weixin.qq.com/cgi-bin/message/mass/speed/set?' ,
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
     * 根据标签进行群发
     *
     * @param string|int   $tagId               标签ID
     * @param string       $msgType             消息类型
     * @param array|string $data                消息数据
     * @param string       $clientMsgId         开发者侧群发msgid，长度限制64字节，如不填，则后台默认以群发范围和群发内容的摘要值做为clientmsgid
     * @param bool         $toAll               是否发送给所有人 默认为是
     * @param int          $sendIgnoreReprint   非原创时是否继续发送
     *                                          1   =>  发送(转载)
     *                                          0   =>  不发送
     *
     * @return array 微信服务器返回的数据
     *                  msg_id      =>  消息发送任务的ID
     *                  msg_data_id =>  消息的数据ID，该字段只有在群发图文消息时，才会出现。可以用于在图文分析数据接口中，获取到对应的图文消息的数据，是图文分析数据接口中的msgid字段中的前半部分，详见图文分析数据接口中的msgid字段的介绍。
     * @throws WeResultException
     */
    public function massSendByTag ( $tagId , $msgType , $data , $clientMsgId , $toAll = TRUE , $sendIgnoreReprint = 0 )
    {
        $_data = [
            'filter' => [
                'is_to_all'   => $toAll ,
                'tag_id'      => $tagId ,
                'clientmsgid' => $clientMsgId ,
            ] ,
        ];

        $_url = $this->links['MASS_SEND_BY_TAGID'] . 'access_token=' . Token::getAccessToken();


        // 判断消息类型
        switch ( $msgType )
        {
            // 图文
            case 'mpnews':
                $_data[ $msgType ]['media_id'] = $data;
                $_data['send_ignore_reprint']  = $sendIgnoreReprint;
            break;
            // 文本
            case 'text':
                $_data[ $msgType ]['content'] = $data;
            break;
            // 声音
            case 'voice':
                $_data[ $msgType ]['media_id'] = $data;
            break;
            // 图片
            case 'image':
                $_data[ $msgType ]['media_id'] = $data;
            break;
            // 视频
            case 'mpvideo':
                $_data[ $msgType ] = $data;
            break;
            // 卡券
            case 'wxcard':
                $_data[ $msgType ]['card_id'] = $data;
            break;
            default:
                throw new ParamException( '参数<msgType>不合法' );
        }
        $_result = Tools::json2arr( Http::HttpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;


    }

    /**
     * 根据openid群发消息
     *
     * @param array        $toUser              接收的用户列表
     * @param string       $msgType             消息类型
     * @param array|string $data                内容
     * @param int          $sendIgnoreReprint   非原创时是否继续发送
     *                                          1   =>  发送(转载)
     *                                          0   =>  不发送
     *
     * @return array 微信服务器返回的数据
     *                  msg_id      =>  消息发送任务的ID
     *                  msg_data_id =>  消息的数据ID，该字段只有在群发图文消息时，才会出现。可以用于在图文分析数据接口中，获取到对应的图文消息的数据，是图文分析数据接口中的msgid字段中的前半部分，详见图文分析数据接口中的msgid字段的介绍。
     * @throws WeResultException
     */
    public function massSendByOpenID ( $toUser , $msgType , $data , $sendIgnoreReprint = 0 )
    {
        $_url = $this->links['MASS_SEND_BY_OPENID'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'touser'  => $toUser ,
            'msgtype' => $msgType ,
        ];

        // 判断消息类型
        switch ( $msgType )
        {
            // 图文
            case 'mpnews':
                $_data[ $msgType ]['media_id'] = $data;
                $_data['send_ignore_reprint']  = $sendIgnoreReprint;
            break;
            // 文本
            case 'text':
                $_data[ $msgType ]['content'] = $data;
            break;
            // 声音
            case 'voice':
                $_data[ $msgType ]['media_id'] = $data;
            break;
            // 图片
            case 'image':
                $_data[ $msgType ]['media_id'] = $data;
            break;
            // 视频
            case 'mpvideo':
                $_data[ $msgType ] = $data;
            break;
            // 卡券
            case 'wxcard':
                $_data[ $msgType ]['card_id'] = $data;
            break;
            default:
                throw new ParamException( '参数<msgType>不合法' );
        }

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;
    }

    /**
     * 删除群发
     *      只有已经发送成功的消息才能删除
     *      删除消息是将消息的图文详情页失效，已经收到的用户，还是能在其本地看到消息卡片。
     *      删除群发消息只能删除图文消息和视频消息，其他类型的消息一经发送，无法删除。
     *      如果多次群发发送的是一个图文消息，那么删除其中一次群发，就会删除掉这个图文消息也，导致所有群发都失效
     *
     * @param string $msgId      发送出去的消息ID
     * @param int    $articleIdx 要删除的文章在图文消息中的位置，第一篇编号为1，该字段不填或填0会删除全部文章
     *
     * @return bool
     * @throws WeResultException
     */
    public function delete ( $msgId , $articleIdx = 0 )
    {
        $_data = [
            'msg_id'      => $msgId ,
            'article_idx' => $articleIdx ,
        ];

        $_url = $this->links['MASS_DELETE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 预览接口
     *
     * @param string       $touser   接收用户标识
     * @param string       $msgType  消息类型
     * @param string|array $data     消息内容
     * @param bool         $toOpenId 用户标识是否是 openID TRUE为是 FALSE为 微信号
     *
     * @return bool
     * @throws ParamException
     * @throws WeResultException
     */
    public function preview ( $touser , $msgType , $data , $toOpenId = TRUE )
    {
        $_url = $this->links['MASS_PREVIEW'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'msgtype' => $msgType ,
        ];

        $_to = $toOpenId ? 'touser' : 'towxname';

        $_data[ $_to ] = $touser;

        // 判断消息类型
        switch ( $msgType )
        {
            // 图文
            case 'mpnews':
                $_data[ $msgType ]['media_id'] = $data;
            break;
            // 文本
            case 'text':
                $_data[ $msgType ]['content'] = $data;
            break;
            // 声音
            case 'voice':
                $_data[ $msgType ]['media_id'] = $data;
            break;
            // 图片
            case 'image':
                $_data[ $msgType ]['media_id'] = $data;
            break;
            // 视频
            case 'mpvideo':
                $_data[ $msgType ] = $data;
            break;
            // 卡券
            case 'wxcard':
                $_data[ $msgType ]['card_id'] = $data;
            break;
            default:
                throw new ParamException( '参数<msgType>不合法' );
        }

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;

    }

    /**
     * 获取群发状态
     *
     * @param string $msgId 群发消息id
     *
     * @return array
     */
    public function getStatus ( $msgId )
    {
        $_url = $this->links['MASS_GET_STATUS'] . 'access_token=' . Token::getAccessToken();

        $_data['msg_id'] = $msgId;

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        return $_result;
    }


    /**
     * 获取群发速度
     */
    public function getSpeed ()
    {
        $_url = $this->links['MASS_GET_SPEED'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::HttpPost( $_url , '' ) );

        return $_result;
    }

    /**
     * 设置速度
     *
     * @param $speed
     *
     * @return mixed
     */
    public function setSpeed ( $speed )
    {
        $_url = $this->links['MASS_SET_SPEED'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'speed' => $speed ,
        ];

        $_result = Tools::json2arr( Http::HttpPost( $_url , Tools::arr2json( $_data ) ) );

        return $_result;
    }

}