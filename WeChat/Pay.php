<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/23
 * Time: 上午11:25
 */

namespace WeChat;


use WeChat\Exception\ParamException;
use WeChat\Exception\WeResultException;
use WeChat\Tools\Http;
use WeChat\Tools\Sign;
use WeChat\Tools\Tools;

class Pay extends Base
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
     * 统一下单
     *
     * @param string $body           商品简单描述
     * @param string $outTradeNo     商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一
     * @param string $totalFee       订单总金额，单位为分
     * @param string $spBillCreateIp APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP
     * @param string $notifyUrl      异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数
     * @param array  $other          其他信息
     * @param string $tradeType      取值如下：JSAPI，NATIVE，APP等
     *
     * @return array
     * @throws ParamException
     * @throws WeResultException
     * @internal param string $openId 用户openID
     *
     */
    public function unifiedOrder ( $body , $outTradeNo , $totalFee , $spBillCreateIp , $notifyUrl , $other = [] , $tradeType = 'JSAPI' )
    {
        if ( empty( $body ) )
        {
            throw new ParamException( '参数<body>不能为空' );
        }
        if ( empty( $outTradeNo ) )
        {
            throw new ParamException( '参数<outTradeNo>不能为空' );
        }
        if ( empty( $totalFee ) )
        {
            throw new ParamException( '参数<totalFee>不能为空' );
        }
        if ( empty( $spBillCreateIp ) )
        {
            throw new ParamException( '参数<spBillCreateIp>不能为空' );
        }
        if ( empty( $notifyUrl ) )
        {
            throw new ParamException( '参数<notifyUrl>不能为空' );
        }
        if ( $tradeType === 'JSAPI' && !isset( $other['openid'] ) )
        {
            throw new ParamException( '参数<tradeType>为JSAPI时<openid>不能为空' );
        }

        $_param = [
            'appid'            => self::$config['AppID'] ,
            'mch_id'           => self::$config['MchID'] ,
            'nonce_str'        => Tools::getRandomStr() ,
            'body'             => $body ,
            'out_trade_no'     => $outTradeNo ,
            'total_fee'        => $totalFee ,
            'spbill_create_ip' => $spBillCreateIp ,
            'notify_url'       => $notifyUrl ,
            'trade_type'       => $tradeType ,
        ];

        $_param = array_merge( $other , $_param );

        $_param['sign'] = Sign::Pay( $_param );

        $_result = Tools::xml2arr( Http::httpPost( self::$LINKS['PAY_UNIFIED_ORDER'] , Tools::arr2xml( $_param ) ) );

        if ( isset( $_result['return_code'] ) && $_result['return_code'] !== 'SUCCESS' )
        {
            throw new WeResultException( $_result['return_msg'] );
        }

        unset( $_result['return_code'] , $_result['return_msg'] );

        return $_result;
    }

    /**
     * 微信二维码支付
     *
     * @param $productId
     *
     * @return string
     */
    public function qrCodePay ( $productId )
    {
        $_timeStamp = time();
        $_nonceStr  = Tools::getRandomStr();

        $_param = [
            'appid'      => self::$config['AppID'] ,
            'mch_id'     => self::$config['MchID'] ,
            'time_stamp' => $_timeStamp ,
            'nonce_str'  => $_nonceStr ,
            'product_id' => $productId ,
        ];

        $_param['sign'] = Sign::Pay( $_param );

        return 'weixin://wxpay/bizpayurl?' . http_build_query( $_param );
    }
}