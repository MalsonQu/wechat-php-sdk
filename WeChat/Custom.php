<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/22
 * Time: 上午9:27
 */

namespace WeChat;


use WeChat\Exception\ParamException;
use WeChat\Exception\WeResultException;
use WeChat\Tools\AccessToken;
use WeChat\Tools\Http;
use WeChat\Tools\Tools;

class Custom extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    /**
     * 可发送的消息类型
     *
     * @var array
     */
    private $canMessageType = [
        // 文本消息
        'text' ,
        // 图片消息
        'image' ,
        // 语音消息
        'voice' ,
        // 视频消息
        'video' ,
        // 音乐消息
        'music' ,
        // 图文消息(外链)
        'news' ,
        // 图文消息(内链)
        'mpnews' ,
        // 卡券
        'wxcard' ,
        // 小程序
        'miniprogrampage' ,
    ];

    /**
     * 将要发送的消息
     *
     * @var array
     */
    private $toBeSendMessage = [];
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
     * 新建客服账号
     *
     * @param string $account  账号
     * @param string $nickname 昵称
     *
     * @return bool
     * @throws WeResultException
     */
    public function create ( $account , $nickname )
    {
        $_data = [
            'kf_account' => $account . '@' . self::$config['WeChatID'] ,
            'nickname'   => $nickname ,
        ];

        $_url    = self::$LINKS['KF_ACCOUNT_CREATE'] . 'access_token=' . AccessToken::get();
        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }


    /**
     * 更新客服信息
     *
     * @param string $account  账号
     * @param string $nickname 昵称
     *
     * @return bool
     * @throws WeResultException
     */
    public function update ( $account , $nickname )
    {
        $_data = [
            'kf_account' => $account . '@' . self::$config['WeChatID'] ,
            'nickname'   => $nickname ,
        ];

        $_url = self::$LINKS['KF_ACCOUNT_UPDATE'] . 'access_token=' . AccessToken::get();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 上传客服头像
     *
     * @param string $account 账号
     * @param array  $media   头像文件
     *                        path  =>  路径
     *                        type  =>  文件类型(mime)
     *                        name  =>  文件名称
     *
     * @return bool
     * @throws WeResultException
     */
    public function uploadHeadImg ( $account , $media )
    {
        $_data = [
            'media' => new \CURLFile( realpath( $media['path'] ) , $media['type'] , $media['name'] ) ,
        ];

        $_url = self::$LINKS['KF_ACCOUNT_HEAD_IMG'] . 'access_token=' . AccessToken::get() . '&kf_account=' . $account . '@' . self::$config['WeChatID'];

        $_result = Tools::json2arr( Http::httpPost( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 删除客服
     *
     * @param string $account 账号
     *
     * @return bool
     * @throws WeResultException
     */
    public function delete ( $account )
    {
        $_data = [
            'access_token' => AccessToken::get() ,
            'kf_account'   => $account . '@' . self::$config['WeChatID'] ,
        ];

        $_url = self::$LINKS['KF_ACCOUNT_DELETE'];

        $_result = Tools::json2arr( Http::httpGet( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 获取列表
     *
     * @return array
     * @throws WeResultException
     */
    public function getList ()
    {
        $_data = [
            'access_token' => AccessToken::get() ,
        ];

        $_url = self::$LINKS['KF_ACCOUNT_LIST'];

        $_result = Tools::json2arr( Http::httpGet( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 获取在线客服列表
     *
     * @return mixed
     * @throws WeResultException
     */
    public function getOnlineList ()
    {
        $_data = [
            'access_token' => AccessToken::get() ,
        ];
        $_url  = self::$LINKS['KF_ACCOUNT_ONLINE_LIST'];

        $_result = Tools::json2arr( Http::httpGet( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 邀请客服绑定
     *
     * @param string $account  客服账号
     * @param string $inviteWx 被邀请用户微信号
     *
     * @return bool
     * @throws WeResultException
     */
    public function inviteWorker ( $account , $inviteWx )
    {
        $_data = [
            'kf_account' => $account . '@' . self::$config['WeChatID'] ,
            'invite_wx'  => $inviteWx ,
        ];
        $_url  = self::$LINKS['KF_ACCOUNT_INVITE_WORKER'] . 'access_token=' . AccessToken::get();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 客服输入状态
     *
     * @param string $to     接收者openID
     * @param bool   $typing 是否正在输入
     *
     * @return bool
     * @throws WeResultException
     */
    public function typing ( $to , $typing = TRUE )
    {
        $_data = [
            'touser'  => $to ,
            'command' => $typing ? 'Typing' : 'CancelTyping' ,
        ];

        $_url = self::$LINKS['KF_ACCOUNT_TYPING'] . 'access_token=' . AccessToken::get();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }


    /**
     * 创建一个会话
     *
     * @param string $account 账号
     * @param string $openid  用户openid
     *
     * @return bool
     * @throws ParamException
     * @throws WeResultException
     */
    public function sessionCreate ( $account , $openid )
    {
        if ( empty( $account ) )
        {
            throw new ParamException( '参数<account>必填' );
        }

        if ( empty( $openid ) )
        {
            throw new ParamException( '参数<openid>必填' );
        }

        $_data = [
            'kf_account' => $account . '@' . self::$config['WeChatID'] ,
            'openid'     => $openid ,
        ];

        $_url = self::$LINKS['KF_SESSION_CREATE'] . 'access_token=' . AccessToken::get();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 结束一个会话
     *
     * @param string $account 账号
     * @param string $openid  用户openid
     *
     * @return bool
     * @throws ParamException
     * @throws WeResultException
     */
    public function sessionClose ( $account , $openid )
    {
        if ( empty( $account ) )
        {
            throw new ParamException( '参数<account>必填' );
        }

        if ( empty( $openid ) )
        {
            throw new ParamException( '参数<openid>必填' );
        }

        $_data = [
            'kf_account' => $account . '@' . self::$config['WeChatID'] ,
            'openid'     => $openid ,
        ];

        $_url = self::$LINKS['KF_SESSION_CLOSE'] . 'access_token=' . AccessToken::get();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 查询会话
     *
     * @param string $openid 用户openid
     *
     * @return bool
     * @throws ParamException
     * @throws WeResultException
     */
    public function sessionGet ( $openid )
    {
        if ( empty( $openid ) )
        {
            throw new ParamException( '参数<openid>必填' );
        }

        $_data = [
            'access_token' => AccessToken::get() ,
            'openid'       => $openid ,
        ];

        $_url = self::$LINKS['KF_SESSION_GET'];

        $_result = Tools::json2arr( Http::httpGet( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 获取客服会话列表
     *
     * @param string $account 客服账号
     *
     * @return bool
     * @throws ParamException
     * @throws WeResultException
     *
     */
    public function sessionList ( $account )
    {
        if ( empty( $account ) )
        {
            throw new ParamException( '参数<account>必填' );
        }

        $_data = [
            'kf_account'   => $account . '@' . self::$config['WeChatID'] ,
            'access_token' => AccessToken::get() ,
        ];

        $_url = self::$LINKS['KF_SESSION_LIST'];

        $_result = Tools::json2arr( Http::httpGet( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 获取未接入会话列表
     *
     * @return bool
     * @throws ParamException
     * @throws WeResultException
     *
     */
    public function sessionWaitList ()
    {
        $_data = [
            'access_token' => AccessToken::get() ,
        ];

        $_url = self::$LINKS['KF_SESSION_WAIT_LIST'];

        $_result = Tools::json2arr( Http::httpGet( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 获取聊天记录
     *
     * @param string $startTime 开始时间
     * @param string $endTime   结束时间
     * @param int    $msgID     消息id顺序从小到大，从1开始,默认为1
     * @param int    $number    每次获取条数，最多10000条,默认为10000
     *
     * @return array
     * @throws ParamException
     * @throws WeResultException
     */
    public function msgList ( $startTime , $endTime , $msgID = 1 , $number = 10000 )
    {
        if ( empty( $startTime ) )
        {
            throw new ParamException( '参数<startTime>格式错误' );
        }

        if ( empty( $endTime ) )
        {
            throw new ParamException( '参数<endTime>格式错误' );
        }

        if ( ( $endTime - 24 * 60 * 60 ) > $startTime )
        {
            throw new ParamException('查询时段不能超过24小时');
        }

        $_data = [
            'starttime' => $startTime ,
            'endtime'   => $endTime ,
            'msgid'     => $msgID ,
            'number'    => $number ,
        ];

        $_url = self::$LINKS['KF_MSG_LIST'] . 'access_token=' . AccessToken::get();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 生成消息
     *
     * @param string       $type 消息类型
     * @param string       $to   接收者
     * @param array|string $data 数据
     * @param string       $from 发送者
     *
     * @return $this
     * @throws ParamException
     */
    public function buildMessage ( $type , $to , $data , $from = NULL )
    {
        if ( !in_array( $type , $this->canMessageType ) )
        {
            throw new ParamException( '参数<type>格式错误' );
        }

        if ( empty( $data ) )
        {
            throw new ParamException( '参数<dada>不能为空' );
        }

        $this->toBeSendMessage['touser']  = $to;
        $this->toBeSendMessage['msgtype'] = $type;

        if ( isset( $from ) )
        {
            $this->toBeSendMessage['customservice']['kf_account'] = $from;
        }

        call_user_func_array( [
            $this ,
            '_' . $type ,
        ] , [ $data ] );

        return $this;
    }

    /**
     * 发送消息
     *
     * @return bool
     * @throws WeResultException
     */
    public function sendMessage ()
    {

        $_url = self::$LINKS['KF_ACCOUNT_SEND'] . 'access_token=' . AccessToken::get();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $this->toBeSendMessage ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;


    }

    public function _text ( $data )
    {
        $this->toBeSendMessage['text']['content'] = $data;
    }

    public function _image ( $data )
    {
        $this->toBeSendMessage['image']['media_id'] = $data;
    }

    public function _voice ( $data )
    {
        $this->toBeSendMessage['voice']['media_id'] = $data;
    }

    public function _video ( $data )
    {

        if ( !isset( $data['media_id'] ) )
        {
            throw new ParamException( '参数<media_id>为必填项' );
        }

        if ( !isset( $data['thumb_media_id'] ) )
        {
            throw new ParamException( '参数<thumb_media_id>为必填项' );
        }

        $this->toBeSendMessage['video']['media_id']       = $data['media_id'];
        $this->toBeSendMessage['video']['thumb_media_id'] = $data['thumb_media_id'];

        if ( isset( $data['title'] ) )
        {
            $this->toBeSendMessage['video']['title'] = $data['title'];

        }

        if ( isset( $data['description'] ) )
        {
            $this->toBeSendMessage['video']['description'] = $data['description'];
        }

    }

    public function _music ( $data )
    {
        if ( !isset( $data['thumb_media_id'] ) )
        {
            throw new ParamException( '参数<thumb_media_id>为必填项' );
        }

        if ( !isset( $data['MusicUrl'] ) )
        {
            throw new ParamException( '参数<MusicUrl>为必填项' );
        }

        if ( !isset( $data['HQMusicUrl'] ) )
        {
            throw new ParamException( '参数<HQMusicUrl>为必填项' );
        }

        $this->toBeSendMessage['music']['thumb_media_id'] = $data['thumb_media_id'];
        $this->toBeSendMessage['music']['musicurl']       = $data['MusicUrl'];
        $this->toBeSendMessage['music']['hqmusicurl']     = $data['HQMusicUrl'];

        if ( isset( $data['title'] ) )
        {
            $this->toBeSendMessage['music']['title'] = $data['title'];

        }

        if ( isset( $data['description'] ) )
        {
            $this->toBeSendMessage['music']['description'] = $data['description'];
        }
    }

    public function _news ( $data )
    {
        $this->toBeSendMessage['news']['articles'];

        foreach ( $data as $key => $val )
        {
            if ( isset( $val['title'] ) )
            {
                $this->toBeSendMessage['news']['articles'][ $key ]['title'] = $val['title'];
            }
            if ( isset( $val['description'] ) )
            {
                $this->toBeSendMessage['news']['articles'][ $key ]['description'] = $val['description'];
            }
            if ( isset( $val['url'] ) )
            {
                $this->toBeSendMessage['news']['articles'][ $key ]['url'] = $val['url'];
            }
            if ( isset( $val['picurl'] ) )
            {
                $this->toBeSendMessage['news']['articles'][ $key ]['picurl'] = $val['picurl'];
            }
        }
    }

    public function _mpnews ( $data )
    {
        $this->toBeSendMessage['mpnews']['media_id'] = $data;
    }

    public function _wxcard ( $data )
    {
        $this->toBeSendMessage['wxcard']['card_id'] = $data;
    }

    public function _miniprogrampage ( $data )
    {
        if ( !isset( $data['appid'] ) )
        {
            throw new ParamException( '参数<appid>为必填项' );
        }

        if ( !isset( $data['pagepath'] ) )
        {
            throw new ParamException( '参数<pagepath>为必填项' );
        }

        if ( !isset( $data['thumb_media_id'] ) )
        {
            throw new ParamException( '参数<thumb_media_id>为必填项' );
        }

        $this->toBeSendMessage['miniprogrampage']['appid']          = $data['appid'];
        $this->toBeSendMessage['miniprogrampage']['pagepath']       = $data['pagepath'];
        $this->toBeSendMessage['miniprogrampage']['thumb_media_id'] = $data['thumb_media_id'];

        if ( isset( $data['title'] ) )
        {
            $this->toBeSendMessage['miniprogrampage']['title'] = $data['title'];
        }
    }
}