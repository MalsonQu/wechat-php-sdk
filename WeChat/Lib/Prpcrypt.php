<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/20
 * Time: 下午4:22
 */

namespace WeChat\Lib;
/**
 * 接收和推送给公众平台消息的加解密
 * @category   WechatSDK
 * @subpackage library
 * @date       2016/06/28 11:59
 */
class Prpcrypt
{

    public $key;

    function __construct ( $k )
    {
        $this->key = base64_decode( $k . "=" );
    }

    /**
     * 对明文进行加密
     *
     * @param string $text 需要加密的明文
     *
     * @return string 加密后的密文
     */
    public function encrypt ( $text , $appid )
    {
        $encrypted = FALSE;
        //获得16位随机字符串，填充到明文之前
        $random      = $this->getRandomStr();//"aaaabbbbccccdddd";
        $text        = $random . pack( "N" , strlen( $text ) ) . $text . $appid;
        $iv          = substr( $this->key , 0 , 16 );
        $pkc_encoder = new PKCS7Encoder;
        $text        = $pkc_encoder->encode( $text );
        $encrypted   = openssl_encrypt( $text , 'AES-256-CBC' , substr( $this->key , 0 , 32 ) , OPENSSL_ZERO_PADDING , $iv );

        return $encrypted;
    }

    /**
     * 对密文进行解密
     *
     * @param string $encrypted 需要解密的密文
     *
     * @return string 解密得到的明文
     */
    public function decrypt ( $encrypted , $appid )
    {
        $iv        = substr( $this->key , 0 , 16 );
        $decrypted = openssl_decrypt( $encrypted , 'AES-256-CBC' , substr( $this->key , 0 , 32 ) , OPENSSL_ZERO_PADDING , $iv );
        //去除补位字符
        $pkc_encoder = new PKCS7Encoder;
        $result      = $pkc_encoder->decode( $decrypted );
        //去除16位随机字符串,网络字节序和AppId
        if ( strlen( $result ) < 16 )
        {
            return "";
        }
        $content     = substr( $result , 16 , strlen( $result ) );
        $len_list    = unpack( "N" , substr( $content , 0 , 4 ) );
        $xml_len     = $len_list[1];
        $xml_content = substr( $content , 4 , $xml_len );

        return $xml_content;
    }

    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    public function getRandomStr ()
    {
        $str     = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max     = strlen( $str_pol ) - 1;
        for ( $i = 0; $i < 16; $i++ )
        {
            $str .= $str_pol[ mt_rand( 0 , $max ) ];
        }

        return $str;
    }

}