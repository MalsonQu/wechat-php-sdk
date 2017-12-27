<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/23
 * Time: 上午8:14
 */

namespace WeChat;


use WeChat\Tools\Sign;
use WeChat\Tools\Tools;

class JsApi extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    /**
     * 配置文件
     *
     * @var array
     */
    private $jsConfig = [
        'debug'     => FALSE ,
        'appId'     => '' ,
        'timestamp' => '' ,
        'nonceStr'  => '' ,
        'jsApiList' => [] ,
        'signature' => '' ,
    ];

    /**
     * api列表
     *
     * @var array
     */
    private $apiList = [
        'onMenuShareTimeline' ,
        'onMenuShareAppMessage' ,
        'onMenuShareQQ' ,
        'onMenuShareWeibo' ,
        'onMenuShareQZone' ,
        'hideOptionMenu' ,
        'showOptionMenu' ,
        'hideMenuItems' ,
        'showMenuItems' ,
        'hideAllNonBaseMenuItem' ,
        'showAllNonBaseMenuItem' ,
        'chooseImage' ,
        'previewImage' ,
        'uploadImage' ,
        'downloadImage' ,
        'closeWindow' ,
        'scanQRCode' ,
        'chooseWXPay' ,
        'translateVoice' ,
        'getNetworkType' ,
        'openLocation' ,
        'getLocation' ,
        'openProductSpecificView' ,
        'addCard' ,
        'chooseCard' ,
        'openCard' ,
        'startRecord' ,
        'stopRecord' ,
        'onVoiceRecordEnd' ,
        'playVoice' ,
        'pauseVoice' ,
        'stopVoice' ,
        'onVoicePlayEnd' ,
        'uploadVoice' ,
        'downloadVoice' ,
        'openWXDeviceLib' ,
        'closeWXDeviceLib' ,
        'getWXDeviceInfos' ,
        'sendDataToWXDevice' ,
        'disconnectWXDevice' ,
        'getWXDeviceTicket' ,
        'connectWXDevice' ,
        'startScanWXDevice' ,
        'stopScanWXDevice' ,
        'onWXDeviceBindStateChange' ,
        'onScanWXDeviceResult' ,
        'onReceiveDataFromWXDevice' ,
        'onWXDeviceBluetoothStateChange' ,
        'onWXDeviceStateChange' ,
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

    public function config ( $apiList = [] , $debug = FALSE )
    {
        $_data = [
            'debug'     => $debug ,
            'appId'     => self::$config['AppID'] ,
            'timestamp' => time() ,
            'nonceStr'  => Tools::getRandomStr() ,
            'jsApiList' => !empty( $apiList ) ? $apiList : $this->apiList ,
        ];

        $_data['signature'] = Sign::jsSdkConfig( $_data );

        return $_data;
    }

    public function pay ( $packageID )
    {
        $_data = [
            'appId'     => self::$config['AppID'] ,
            'timeStamp' => time() ,
            'nonceStr'  => Tools::getRandomStr() ,
            'package'   => 'prepay_id='.$packageID ,
            'signType'  => 'MD5' ,
        ];

        $_data['paySign'] = Sign::Pay( $_data);

        return $_data;
    }
}