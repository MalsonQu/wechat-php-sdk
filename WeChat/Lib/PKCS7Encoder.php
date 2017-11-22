<?php

namespace WeChat\Lib;
/**
 * PKCS7算法 加解密
 * @category   WechatSDK
 * @subpackage library
 * @date       2016/06/28 11:59
 */
class PKCS7Encoder
{

    public static $block_size = 32;

    /**
     * 对需要加密的明文进行填充补位
     *
     * @param string $text 需要进行填充补位操作的明文
     *
     * @return string 补齐明文字符串
     */
    function encode ( $text )
    {
        $text_length   = strlen( $text );
        $amount_to_pad = PKCS7Encoder::$block_size - ( $text_length % PKCS7Encoder::$block_size );
        if ( $amount_to_pad == 0 )
        {
            $amount_to_pad = PKCS7Encoder::$block_size;
        }
        $pad_chr = chr( $amount_to_pad );
        $tmp     = "";
        for ( $index = 0; $index < $amount_to_pad; $index++ )
        {
            $tmp .= $pad_chr;
        }

        return $text . $tmp;
    }

    /**
     * 对解密后的明文进行补位删除
     *
     * @param string $text 解密后的明文
     *
     * @return string 删除填充补位后的明文
     */
    function decode ( $text )
    {
        $pad = ord( substr( $text , -1 ) );
        if ( $pad < 1 || $pad > PKCS7Encoder::$block_size )
        {
            $pad = 0;
        }

        return substr( $text , 0 , ( strlen( $text ) - $pad ) );
    }

}

