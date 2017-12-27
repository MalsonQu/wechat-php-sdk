<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/23
 * Time: 上午8:13
 */

namespace WeChat;


use WeChat\Exception\ParamException;
use WeChat\Exception\WeResultException;
use WeChat\Tools\Http;
use WeChat\Tools\Token;
use WeChat\Tools\Tools;

class Poi extends Base
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
     * 门店基础信息字段
     *
     * @param string $businessName  门店名称（仅为商户名，如：国美、麦当劳，不应包含地区、地址、分店名等信息，错误示例：北京国美）
     * @param string $branchName    分店名称（不应包含地区信息，不应与门店名有重复，错误示例：北京王府井店）
     * @param string $province      门店所在的省份（直辖市填城市名,如：北京市）
     * @param string $city          门店所在的城市
     * @param string $district      门店所在地区
     * @param string $address       门店所在的详细街道地址（不要填写省市信息）
     * @param string $telephone     门店的电话（纯数字，区号、分机号均由“-”隔开）
     * @param string $categories    门店的类型（不同级分类用“,”隔开，如：美食，川菜，火锅。详细分类参见附件：微信门店类目表）
     * @param string $offsetType    坐标类型：
     *                              1 为火星坐标
     *                              2 为sogou经纬度
     *                              3 为百度经纬度
     *                              4 为mapbar经纬度
     *                              5 为GPS坐标
     *                              6 为sogou墨卡托坐标
     *                              注：高德经纬度无需转换可直接使用
     * @param string $longitude     门店所在地理位置的经度
     * @param string $latitude      门店所在地理位置的纬度（经纬度均为火星坐标，最好选用腾讯地图标记的坐标）
     * @param array  $other         其他文档中的非必填信息
     *                              sid             =>  商户自己的id，用于后续审核通过收到poi_id 的通知时，做对应关系。请商户自己保证唯一识别性
     *                              photo_list      =>  图片列表，url 形式，可以有多张图片，尺寸为640*340px。必须为上一接口生成的url。图片内容不允许与门店不相关，不允许为二维码、员工合照（或模特肖像）、营业执照、无门店正门的街景、地图截图、公交地铁站牌、菜单截图等
     *                              recommend       =>  推荐品，餐厅可为推荐菜；酒店为推荐套房；景点为推荐游玩景点等，针对自己行业的推荐内容
     *                              special         =>  特色服务，如免费wifi，免费停车，送货上门等商户能提供的特色功能或服务
     *                              introduction    =>  商户简介，主要介绍商户信息等
     *                              open_time       =>  营业时间，24 小时制表示，用“-”连接，如8:00-20:00
     *                              avg_price       =>  人均价格，大于0 的整数
     *
     * @return
     * @throws ParamException
     * @throws WeResultException
     */
    public function add ( $businessName , $branchName , $province , $city , $district , $address , $telephone , $categories , $offsetType , $longitude , $latitude , $other = [] )
    {

        if ( empty( $businessName ) )
        {
            throw new ParamException( '参数<businessName>不能为空' );
        }

        if ( empty( $branchName ) )
        {
            throw new ParamException( '参数<branchName>不能为空' );
        }

        if ( empty( $province ) )
        {
            throw new ParamException( '参数<province>不能为空' );
        }

        if ( empty( $city ) )
        {
            throw new ParamException( '参数<city>不能为空' );
        }

        if ( empty( $district ) )
        {
            throw new ParamException( '参数<district>不能为空' );
        }

        if ( empty( $address ) )
        {
            throw new ParamException( '参数<address>不能为空' );
        }

        if ( empty( $telephone ) )
        {
            throw new ParamException( '参数<telephone>不能为空' );
        }

        if ( empty( $categories ) )
        {
            throw new ParamException( '参数<categories>不能为空' );
        }

        if ( empty( $offsetType ) )
        {
            throw new ParamException( '参数<offsetType>不能为空' );
        }

        if ( empty( $longitude ) )
        {
            throw new ParamException( '参数<longitude>不能为空' );
        }

        if ( empty( $latitude ) )
        {
            throw new ParamException( '参数<latitude>不能为空' );
        }

        if ( !empty( $other ) )
        {
            $_data = $other;
        }

        $_data['business_name'] = $businessName;
        $_data['branch_name']   = $branchName;
        $_data['province']      = $province;
        $_data['city']          = $city;
        $_data['district']      = $district;
        $_data['address']       = $address;
        $_data['telephone']     = $telephone;
        $_data['categories']    = $categories;
        $_data['offset_type']   = $offsetType;
        $_data['longitude']     = $longitude;
        $_data['latitude']      = $latitude;


        $_url = self::$LINKS['POI_ADD'] . 'access_token=' . Token::getAccessToken();

        $_param['buffer']['business']['base_info'] = $_data;

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_param ) ) );

        var_dump( $_result );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['poi_id'];

    }
}