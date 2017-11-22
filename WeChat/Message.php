<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/20
 * Time: 下午3:02
 */

namespace WeChat;


use WeChat\Exception\InputException;
use WeChat\Exception\ParamException;
use WeChat\Lib\Prpcrypt;
use WeChat\Lib\SHA1;
use WeChat\Tools\Tools;

class Message extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    /**
     * 微信服务器发送过来的消息
     *
     * @var array
     */
    private $message = [];

    /**
     * 可以发送的消息类型
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
        // 图文消息
        'news' ,
    ];

    /**
     * 消息类型
     *
     * @var string
     */
    private $messageType = '';

    /**
     * 事件类型
     *
     * @var string
     */
    private $eventType = '';

    /**
     * 记录时间
     *
     * @var int
     */
    private $time = 0;

    /**
     * 待发送的消息
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

    public function _initialize ()
    {
        $this->message = $this->postXml();

        if ( isset( $this->message['Encrypt'] ) )
        {
            $this->message = $this->decryptMsg();
        }

        $this->messageType = $this->message['MsgType'];

        $this->time = time();
    }

    /**
     * 获取 服务器发过来的xml
     *
     * @return array
     * @throws InputException
     */
    private function postXml ()
    {
        $_input = file_get_contents( 'php://input' );

        if ( !$_message = Tools::xml2arr( $_input ) )
        {
            throw new InputException( '输入的数据格式错误' );
        }

        return $_message;
    }

    /**
     * 解密信息
     *
     * @return array 解密后的数组
     * @throws ParamException
     */
    private function decryptMsg ()
    {
        if ( strlen( self::$config['EncodingAesKey'] ) !== 43 )
        {
            throw new ParamException( '参数<EncodingAesKey>长度错误' );
        }

        $pc = new Prpcrypt( self::$config['EncodingAesKey'] );

        $_result = $pc->decrypt( $this->message['Encrypt'] , self::$config['AppID'] );

        return Tools::xml2arr( $_result );
    }

    /**
     * 加密信息
     *
     * @param $text
     *
     * @return string
     */

    private function encryptMsg ( $text )
    {
        $pc = new Prpcrypt( self::$config['EncodingAesKey'] );

        $encrypt_msg = $pc->encrypt( $text , self::$config['AppID'] );

        $nonce = $pc->getRandomStr();

        $sha1 = new SHA1();

        $signature = $sha1->getSHA1( self::$config['token'] , $this->time , $nonce , $encrypt_msg );

        $array = [
            'Encrypt'      => $encrypt_msg ,
            'MsgSignature' => $signature ,
            'TimeStamp'    => $this->time ,
            'Nonce'        => $nonce ,
        ];

        return Tools::arr2xml( $array );
    }

    /**
     * 获取message内容
     *
     * @return array
     */
    public function getMessage ()
    {
        return $this->message;
    }

    /**
     * 获取文本消息内容
     *
     * @return array
     */
    public function getTextContent ()
    {
        return [
            'Content' => $this->message['Content'] ,
        ];
    }

    /**
     * 获取图片消息内容
     *
     * @return array
     */
    public function getImageContent ()
    {
        return [
            'PicUrl'  => $this->message['PicUrl'] ,
            'MediaId' => $this->message['MediaId'] ,
        ];
    }

    /**
     * 获取语音消息内容
     *
     * @return array
     */
    public function getVoiceContent ()
    {
        return [
            'Format'  => $this->message['Format'] ,
            'MediaId' => $this->message['MediaId'] ,
        ];
    }

    /**
     * 获取视频消息内容
     *
     * @return array
     */
    public function getVideoContent ()
    {
        return [
            'ThumbMediaId' => $this->message['ThumbMediaId'] ,
            'MediaId'      => $this->message['MediaId'] ,
        ];
    }

    /**
     * 获取小视频消息内容
     *
     * @return array
     */
    public function getShortVideoContent ()
    {
        return [
            'ThumbMediaId' => $this->message['ThumbMediaId'] ,
            'MediaId'      => $this->message['MediaId'] ,
        ];
    }

    /**
     * 获取地理位置消息内容
     *
     * @return array
     */
    public function getLocationContent ()
    {
        return [
            'Location_X' => $this->message['Location_X'] ,
            'Location_Y' => $this->message['Location_Y'] ,
            'Scale'      => $this->message['Scale'] ,
            'Label'      => $this->message['Label'] ,
        ];
    }

    /**
     * 获取链接消息内容
     *
     * @return array
     */
    public function getLinkContent ()
    {
        return [
            'Title'       => $this->message['Title'] ,
            'Description' => $this->message['Description'] ,
            'Url'         => $this->message['Url'] ,
        ];
    }

    /**
     * 获取文件消息内容
     *
     * @return array
     */
    public function getFileContent ()
    {
        return [
            'Title'        => $this->message['Title'] ,
            'Description'  => $this->message['Description'] ,
            'FileKey'      => $this->message['FileKey'] ,
            'FileMd5'      => $this->message['FileMd5'] ,
            'FileTotalLen' => $this->message['FileTotalLen'] ,
        ];
    }

    /**
     * 获取消息请求的链接
     *
     * @return string
     */
    public function getFormURL ()
    {
        return $this->message['URL'];
    }

    /**
     * 获取消息发送者ID
     *
     * @return string
     */
    public function getToUserName ()
    {
        return $this->message['ToUserName'];
    }

    /**
     * 获取消息接收者ID
     *
     * @return string
     */
    public function getFromUserName ()
    {
        return $this->message['FromUserName'];
    }

    /**
     * 获取消息的创建时间
     *
     * @return string
     */
    public function getCreateTime ()
    {
        return $this->message['CreateTime'];
    }

    /**
     * 获取事件类型
     *
     * @return string
     */
    public function getEventType ()
    {
        return $this->message['Event'];
    }

    /**
     * 获取消息的类型
     *
     * @return string
     */
    public function getMsgType ()
    {
        return $this->message['MsgType'];
    }

    /**
     * 获取消息的ID
     *
     * @return string
     */
    public function getMsgId ()
    {
        return $this->message['MsgId'];
    }

    /**
     * 获取文本消息内容
     *
     * @return string
     */
    public function getContent ()
    {
        return $this->message['Content'];
    }

    /**
     * 获取图片链接
     *
     * @return string
     */
    public function getPicUrl ()
    {
        return $this->message['PicUrl'];
    }

    /**
     * 获取消息媒体ID
     *
     * @return string
     */
    public function getMediaId ()
    {
        return $this->message['MediaId'];
    }

    /**
     * 获取语音格式
     *
     * @return string
     */
    public function getFormat ()
    {
        return $this->message['Format'];
    }

    /**
     * 获取语音识别结果
     *
     * @return string
     */
    public function getRecognition ()
    {
        return $this->message['Recognition'];
    }

    /**
     * 获取消息缩略图的媒体ID
     *
     * @return string
     */
    public function getThumbMediaId ()
    {
        return $this->message['ThumbMediaId'];
    }

    /**
     * 获取地理位置纬度
     *
     * @return string
     */
    public function getLocationX ()
    {
        return $this->message['Location_X'];
    }

    /**
     * 获取地理位置经度
     *
     * @return string
     */
    public function getLocationY ()
    {
        return $this->message['Location_Y'];
    }

    /**
     * 获取地图缩放大小
     *
     * @return string
     */
    public function getScale ()
    {
        return $this->message['Scale'];
    }

    /**
     * 获取地理位置信息
     *
     * @return string
     */
    public function getLabel ()
    {
        return $this->message['Label'];
    }

    /**
     * 获取消息标题
     *
     * @return string
     */
    public function getTitle ()
    {
        return $this->message['Location_Y'];
    }

    /**
     * 获取消息描述
     *
     * @return string
     */
    public function getDescription ()
    {
        return $this->message['Location_Y'];
    }

    /**
     * 获取消息链接
     *
     * @return string
     */
    public function getUrl ()
    {
        return $this->message['Url'];
    }

    /**
     * 发送消息
     *
     * @param $to
     */
    public function sendMessage ( $to )
    {
    }

    /**
     * 回复消息
     */
    public function ReplyMessage ()
    {
        $this->toBeSendMessage['ToUserName']   = $this->message['FromUserName'];
        $this->toBeSendMessage['FromUserName'] = $this->message['ToUserName'];

        echo $this->encryptMsg( Tools::arr2xml( $this->toBeSendMessage ) );
    }

    /**
     * 构建消息
     *
     * @param string $type 消息类型
     * @param array  $data 参数
     *
     * @return $this
     * @throws ParamException
     */
    public function buildMessage ( $type , $data = [] )
    {
        if ( !in_array( $type , $this->canMessageType ) )
        {
            throw new ParamException( '<type>参数格式错误' );
        }

        if ( empty( $data ) )
        {
            throw new ParamException( '<data>参数不能为空' );
        }

        $type = '_' . $type;

        call_user_func_array( [
            $this ,
            $type ,
        ] , [ $data ] );

        return $this;
    }

    /**
     * 回复文本消息
     *
     * @param string $data
     *
     * @throws ParamException
     */
    public function _text ( $data )
    {
        if ( !is_string( $data ) )
        {
            throw new ParamException( '<data>必须是字符串' );
        }
        $this->toBeSendMessage = [
            'CreateTime' => $this->time ,
            'MsgType'    => 'text' ,
            'Content'    => $data ,
        ];
    }

    /**
     * 回复图片消息
     *
     * @param string $data
     *
     * @throws ParamException
     */
    public function _image ( $data )
    {
        if ( !is_string( $data ) )
        {
            throw new ParamException( '<data>必须是字符串' );
        }

        $this->toBeSendMessage = [
            'CreateTime' => $this->time ,
            'MsgType'    => 'image' ,
            'Image'      => [
                'MediaId' => $data ,
            ] ,
        ];
    }

    /**
     * 回复语音消息
     *
     * @param string $data
     *
     * @throws ParamException
     */
    public function _voice ( $data )
    {
        if ( !is_string( $data ) )
        {
            throw new ParamException( '<data>必须是字符串' );
        }

        $this->toBeSendMessage = [
            'CreateTime' => $this->time ,
            'MsgType'    => 'voice' ,
            'Voice'      => [
                'MediaId' => $data ,
            ] ,
        ];
    }

    /**
     * 回复视频消息
     *
     * @param array|string $data
     */
    public function _video ( $data )
    {
        $this->toBeSendMessage = [
            'CreateTime' => $this->time ,
            'MsgType'    => 'video' ,
            'Video'      => [] ,
        ];

        // 判断类型 如果是数组则进行判断如果是字符串这直接赋值
        if ( is_string( $data ) )
        {
            $this->toBeSendMessage['Video']['MediaId'] = $data;

        }
        else
        {
            if ( is_array( $data ) )
            {
                $this->toBeSendMessage['Video']['MediaId'] = $data['MediaId'];
                if ( isset( $data['Title'] ) )
                {
                    $this->toBeSendMessage['Video']['Title'] = $data['Title'];
                }
                if ( isset( $data['Description'] ) )
                {
                    $this->toBeSendMessage['Video']['Description'] = $data['Description'];
                }
            }
        }
    }

    /**
     * 回复音乐消息
     *
     * @param array|string $data
     */
    public function _music ( $data )
    {
        $this->toBeSendMessage = [
            'CreateTime' => $this->time ,
            'MsgType'    => 'music' ,
            'Music'      => [] ,
        ];

        // 判断类型 如果是数组则进行判断如果是字符串这直接赋值
        if ( is_string( $data ) )
        {
            $this->toBeSendMessage['Music']['ThumbMediaId'] = $data;
        }
        else
        {
            if ( is_array( $data ) )
            {

                if ( isset( $data['Title'] ) )
                {
                    $this->toBeSendMessage['Music']['Title'] = $data['Title'];
                }
                if ( isset( $data['Description'] ) )
                {
                    $this->toBeSendMessage['Music']['Description'] = $data['Description'];
                }
                if ( isset( $data['MusicUrl'] ) )
                {
                    $this->toBeSendMessage['Music']['MusicUrl'] = $data['MusicUrl'];
                }
                if ( isset( $data['HQMusicUrl'] ) )
                {
                    $this->toBeSendMessage['Music']['HQMusicUrl'] = $data['HQMusicUrl'];
                }
                $this->toBeSendMessage['Music']['ThumbMediaId'] = $data['ThumbMediaId'];
            }
        }
    }

    /**
     * 回复图文消息
     *
     * @param array $data
     *
     * @throws ParamException
     */
    public function _news ( $data )
    {
        $this->toBeSendMessage = [
            'CreateTime'   => $this->time ,
            'MsgType'      => 'news' ,
            'ArticleCount' => count( $data ) ,
            'Articles'     => [] ,
        ];

        // 判断类型 如果是数组则进行判断如果是字符串这直接赋值
        if ( !is_array( $data ) )
        {
            throw new ParamException( '参数<data>必须是数组' );
        }
        if ( $this->toBeSendMessage['ArticleCount'] > 8 )
        {
            throw new ParamException( '参数<data>不能超过8条' );
        }

        $i = 0;

        foreach ( $data as $key => $value )
        {
            if ( !isset( $value['Title'] ) || !isset( $value['Description'] ) || !isset( $value['PicUrl'] ) || !isset( $value['Url'] ) )
            {
                throw new ParamException( '参数<data>第' . $i . '条数据参数不完整' );
            }

            $i++;
        }
        $this->toBeSendMessage['Articles'] = $data;
    }

}