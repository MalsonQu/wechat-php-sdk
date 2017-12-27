<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/22
 * Time: 下午4:04
 */

namespace WeChat;


use WeChat\Exception\ParamException;
use WeChat\Exception\WeResultException;
use WeChat\Tools\Http;
use WeChat\Tools\Token;
use WeChat\Tools\Tools;

class Card extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    /**
     * 卡券颜色列表
     *
     * @var array
     */
    private $cardColors = [
        'Color010' => '#63b359' ,
        'Color020' => '#2c9f67' ,
        'Color030' => '#509fc9' ,
        'Color040' => '#5885cf' ,
        'Color050' => '#9062c0' ,
        'Color060' => '#d09a45' ,
        'Color070' => '#e4b138' ,
        'Color080' => '#ee903c' ,
        'Color081' => '#f08500' ,
        'Color082' => '#a9d92d' ,
        'Color090' => '#dd6549' ,
        'Color100' => '#cc463d' ,
        'Color101' => '#cf3e36' ,
        'Color102' => '#5E6671' ,
    ];

    private $links = [
        // 图片上传
        'IMG_UPLOAD'               => 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?' ,
        // 核销卡券
        'CONSUME'                  => 'https://api.weixin.qq.com/card/code/consume?' ,
        // 检查Code
        'GET_INFO'                 => 'https://api.weixin.qq.com/card/code/get?' ,
        // 获取用户已领取卡券接口
        'GET_USER_CARD_LIST'       => 'https://api.weixin.qq.com/card/user/getcardlist?' ,
        // 获取卡券信息
        'GET_CARD_INFO'            => 'https://api.weixin.qq.com/card/get?' ,
        // 批量查询卡券列表
        'BATCH_GET'                => 'https://api.weixin.qq.com/card/batchget?' ,
        // 更新卡券
        'UPDATE'                   => 'https://api.weixin.qq.com/card/update?' ,
        // 修改卡券库存
        'MODIFY_STOCK'             => 'https://api.weixin.qq.com/card/modifystock?' ,
        // 修改卡券code
        'CHANGE_CODE'              => 'https://api.weixin.qq.com/card/code/update?' ,
        // 删除卡券
        'DELETE'                   => 'https://api.weixin.qq.com/card/delete?' ,
        // 设置卡券失效
        'UNAVAILABLE'              => 'https://api.weixin.qq.com/card/code/unavailable?' ,
        // 获取卡券概况
        'GET_OVERVIEW'             => 'https://api.weixin.qq.com/datacube/getcardbizuininfo?' ,
        // 获取免费券数据接口
        'GET_FREE_CARD_OVERVIEW'   => 'https://api.weixin.qq.com/datacube/getcardcardinfo?' ,
        // 创建会员卡
        'CREATE'                   => 'https://api.weixin.qq.com/card/create?' ,
        // 设置开卡字段
        'SET_ACTIVE_FORM'          => 'https://api.weixin.qq.com/card/membercard/activateuserform/set?' ,
        // 激活会员卡
        'ACTIVE_CARD'              => 'https://api.weixin.qq.com/card/membercard/activate?' ,
        // 拉取会员信息
        'GET_MEM_CARD_USERINFO'    => 'https://api.weixin.qq.com/card/membercard/userinfo/get?' ,
        // 获取用户提交的信息
        'GET_USER_SUBMIT_INFO'     => 'https://api.weixin.qq.com/card/membercard/activatetempinfo/get?' ,
        // 更新会员信息
        'UPDATE_MEM_CARD_USERINFO' => 'https://api.weixin.qq.com/card/membercard/updateuser?' ,
        // 支付后投放卡券接口
        'PAY_GIFT_CARD'            => 'https://api.weixin.qq.com/card/paygiftcard/add?' ,
    ];
    // +----------------------------------------------------------------------
    // | 绑定
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | 方法
    // +----------------------------------------------------------------------

    /**
     * 上传卡券图片
     *
     * @param $img
     *
     * @return mixed
     * @throws ParamException
     * @throws WeResultException
     */
    public function uploadImg ( $img )
    {
        if ( empty( $img['path'] ) )
        {
            throw new ParamException( "参数<path>必须填写" );
        }
        if ( empty( $img['type'] ) )
        {
            throw new ParamException( '参数<type>必须填写' );
        }
        if ( empty( $img['name'] ) )
        {
            throw new ParamException( '参数<name>必须填写' );
        }

        $_url = $this->links['IMG_UPLOAD'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'media' => new \CURLFile( realpath( $img['path'] ) , $img['type'] , $img['name'] ) ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 创建卡券
     *
     * @param $data
     *
     * @return string
     * @throws WeResultException
     *
     */
    public function create ( $data )
    {
        $_url = self::$LINKS['CARD_CREATE'] . 'access_token=' . Token::getAccessToken();

        $_data['card'] = $data;

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['card_id'];
    }


    /**
     * 卡券测试白名单
     *
     * @param array $openid
     * @param array $username
     *
     * @return mixed
     * @throws ParamException
     * @throws WeResultException
     */
    public function testWhiteList ( $openid = [] , $username = [] )
    {
        if ( empty( $openid ) && empty( $username ) )
        {
            throw new ParamException( '参数<openid>与<username>至少填写一项' );
        }

        $_data = [];

        if ( !empty( $openid ) )
        {
            $_data['openid'] = $openid;
        }

        if ( !empty( $username ) )
        {
            $_data['username'] = $username;
        }

        $_url = self::$LINKS['CARD_TEST_WHITE_LIST'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;


    }

    /**
     * 核销卡券
     *
     * @param string $code   需核销的Code码
     * @param string $cardId 卡券ID。创建卡券时use_custom_code填写true时必填。非自定义Code不必填写
     *
     * @return mixed
     * @throws WeResultException
     */
    public function consume ( $code , $cardId = NULL )
    {
        $_data['code'] = $code;

        if ( isset( $cardId ) )
        {
            $_data['card_id'] = $cardId;
        }

        $_url = $this->links['CONSUME'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;

    }

    /**
     * 创建领券二维码
     *
     * @param $data
     *
     * @return mixed
     * @throws WeResultException
     */
    public function createQrCode ( $data )
    {
        $_url = self::$LINKS['CARD_CREATE_QR_CODE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;
    }

    /**
     * 导入code接口
     *
     * @param string $cardId 卡券ID
     * @param array  $code   卡券code
     *
     * @return mixed
     * @throws ParamException
     * @throws WeResultException
     */
    public function codeDeposit ( $cardId , $code )
    {
        if ( count( $code ) > 100 )
        {
            throw new ParamException( '参数<code>不能大于100条' );
        }

        $_url = self::$LINKS['CARD_CODE_DEPOSIT'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'card_id' => $cardId ,
            'code'    => $code ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;

    }


    /**
     * 查询导入code数目
     *
     * @param string $cardId 卡券ID
     *
     * @return int|string 已导入的数目
     * @throws WeResultException
     */
    public function getDepositCount ( $cardId )
    {
        $_url = self::$LINKS['CARD_CODE_GET_DEPOSIT_COUNT'] . 'access_token=' . Token::getAccessToken();

        $_data['card_id'] = $cardId;

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['count'];
    }


    /**
     * 核查code接口
     *
     * @param string $cardId 卡券ID
     * @param array  $code   卡券code
     *
     * @return mixed
     * @throws ParamException
     * @throws WeResultException
     */
    public function checkCode ( $cardId , $code )
    {
        if ( count( $code ) > 100 )
        {
            throw new ParamException( '参数<code>不能大于100条' );
        }

        $_url = self::$LINKS['CARD_CODE_CHECK'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'card_id' => $cardId ,
            'code'    => $code ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;
    }

    /**
     * 图文消息群发卡券
     *
     * @param string $cardId 卡券ID
     *
     * @return mixed
     * @throws WeResultException
     */
    public function getMpNewsHtml ( $cardId )
    {
        $_url = self::$LINKS['CARD_GET_MP_NEWS_HTML'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'card_id' => $cardId ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['content'];
    }

    /**
     * 查询Code
     *
     * @param string      $code         卡券的code
     * @param null|string $cardId       卡券ID
     * @param bool        $checkConsume 是否校验code核销状态，填入true和false时的code异常状态返回数据不同
     *
     * @return mixed
     * @throws WeResultException
     */
    public function get ( $code , $cardId = NULL , $checkConsume = FALSE )
    {
        $_url = $this->links['GET_INFO'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'code'          => $code ,
            'check_consume' => $checkConsume ,
        ];

        if ( isset( $cardId ) )
        {
            $_data['card_id'] = $cardId;
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
     * 获取用户已领取卡券接口
     *
     * @param string      $openId 用户的openid
     * @param null|string $cardId 卡券ID
     *
     * @return mixed
     * @throws WeResultException
     */
    public function getUserCardList ( $openId , $cardId = NULL )
    {

        $_url = $this->links['GET_USER_CARD_LIST'] . 'access_token=' . Token::getAccessToken();

        $_data['openid'] = $openId;

        if ( isset( $cardId ) )
        {
            $_data['card_id'] = $cardId;
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
     * 获取卡券的详细信息
     *
     * @param string $cardId 卡券ID
     *
     * @return array
     * @throws WeResultException
     */
    public function getCardInfo ( $cardId )
    {
        $_url = $this->links['GET_CARD_INFO'] . 'access_token=' . Token::getAccessToken();

        $_data['card_id'] = $cardId;

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result['card'];
    }


    /**
     * 批量查询卡券列表
     *
     * @param int         $page
     * @param null|string $statusList
     * @param int         $count
     *
     * @return mixed
     * @throws WeResultException
     */
    public function batchGet ( $page = 1 , $statusList = NULL , $count = 50 )
    {
        $page = $page < 1 ? 1 : $page;

        $_data = [
            'offset' => $count * ( $page - 1 ) ,
            'count'  => $count ,
        ];

        if ( isset( $statusList ) )
        {
            $_data['status_list'] = $statusList;
        }

        $_url = $this->links['BATCH_GET'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;

    }

    /**
     * 更新卡券
     *
     * @param string     $cardId   卡券ID
     * @param array      $baseInfo 卡片基本信息
     * @param null|array $others   卡券其他信息
     *
     * @return mixed
     * @throws WeResultException
     */
    public function update ( $cardId , $baseInfo , $others = NULL )
    {

        $_data = [
            'card_id'     => $cardId ,
            'member_card' => [
                'base_info' => $baseInfo ,
            ] ,
        ];

        if ( isset( $others ) )
        {
            $_data['member_card'] = array_merge( $others , $_data['member_card'] );
        }

        $_url = $this->links['UPDATE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;
    }

    /**
     * 修改库存
     *
     * @param string $cardId 卡券ID
     * @param string $num    修改的数目 正数为添加 负数为减少
     *
     * @return bool
     * @throws WeResultException
     */
    public function modifyStock ( $cardId , $num )
    {
        $_data['card_id'] = $cardId;

        if ( $num > 0 )
        {
            $_data['increase_stock_value'] = $num;
        }
        else
        {
            if ( $num < 0 )
            {
                $_data['reduce_stock_value'] = -$num;
            }
        }

        $_url = $this->links['MODIFY_STOCK'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;

    }

    /**
     * 修改卡券code
     *
     * @param string      $code    卡券code
     * @param string      $newCode 卡券新code
     * @param null|string $cardId  卡券ID
     *
     * @return bool
     * @throws WeResultException
     */
    public function changeCode ( $code , $newCode , $cardId = NULL )
    {
        $_data = [
            'code'     => $code ,
            'new_code' => $newCode ,
        ];

        if ( isset( $cardId ) )
        {
            $_data['card_id'] = $cardId;
        }

        $_url = $this->links['CHANGE_CODE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 删除卡券
     *
     * @param string $cardId 删除卡券
     *
     * @return bool
     * @throws WeResultException
     */
    public function delete ( $cardId )
    {
        $_data['card_id'] = $cardId;

        $_url = $this->links['DELETE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;

    }

    /**
     * 设置卡券失效
     *
     * @param string      $code   设置失效的Code码
     * @param null|string $cardId 卡券ID
     * @param null|string $reason 失效理由
     *
     * @return bool
     * @throws WeResultException
     */
    public function unavailable ( $code , $cardId = NULL , $reason = NULL )
    {
        $_data['code'] = $code;

        if ( isset( $cardId ) )
        {
            $_data['card_id'] = $cardId;
        }

        if ( isset( $reason ) )
        {
            $_data['reason'] = $reason;
        }

        $_url = $this->links['UNAVAILABLE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 拉取卡券概况数据
     *
     * @param string $beginDate 开始日期
     * @param string $endDate   结束日期
     * @param bool   $fromApi   是否是来自api创建的卡券
     *
     * @return mixed
     * @throws WeResultException
     */
    public function getCardOverview ( $beginDate , $endDate , $fromApi = TRUE )
    {
        $_data = [
            'begin_date'  => $beginDate ,
            'end_date'    => $endDate ,
            'cond_source' => $fromApi ? 1 : 0 ,
        ];

        $_url = $this->links['GET_OVERVIEW'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['list'];
    }

    /**
     * 获取免费券数据
     *
     * @param string      $beginDate 开始日期
     * @param string      $endDate   结束日期
     * @param null|string $cardId    卡券ID
     * @param bool        $fromApi   是否是来自api创建的卡券
     *
     * @return mixed
     * @throws WeResultException
     */
    public function getFreeCardOverview ( $beginDate , $endDate , $cardId = NULL , $fromApi = TRUE )
    {
        $_data = [
            'begin_date'  => $beginDate ,
            'end_date'    => $endDate ,
            'cond_source' => $fromApi ? 1 : 0 ,
        ];

        if ( isset( $cardId ) )
        {
            $_data['card_id'] = $cardId;
        }

        $_url = $this->links['GET_FREE_CARD_OVERVIEW'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['list'];
    }

    /**
     * 创建会员卡
     *
     * @param array $data 卡券信息
     *
     * @return mixed
     * @throws WeResultException
     */
    public function MemCreate ( $data )
    {
        $_data['card'] = [
            'card_type'   => 'MEMBER_CARD' ,
            'member_card' => $data ,
        ];

        $_url = $this->links['CREATE'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['card_id'];
    }

    /**
     * 设置激活字段
     *
     * @param $cardId
     * @param $data
     *
     * @return mixed
     * @throws WeResultException
     */
    public function setActiveForm ( $cardId , $data )
    {
        $_data['card_id'] = $cardId;
        $_data            = array_merge( $data , $_data );

        $_url = $this->links['SET_ACTIVE_FORM'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;
    }


    /**
     * 激活卡片
     *
     * @param string $membershipNumber 会员卡编号，由开发者填入，作为序列号显示在用户的卡包里。可与Code码保持等值。
     * @param string $code             领取会员卡用户获得的code
     * @param string $cardId           卡券ID,自定义code卡券必填
     * @param array  $data             其他数据
     *                                 background_pic_url       =>  商家自定义会员卡背景图，须先调用上传图片接口将背景图上传至CDN，否则报错，卡面设计请遵循微信会员卡自定义背景设计规范
     *                                 activate_begin_time      =>
     *                                 activate_end_time        =>  激活后的有效截至时间。若不填写默认以创建时的 data_info 为准。Unix时间戳格式
     *                                 init_bonus               =>  初始积分，不填为0
     *                                 init_bonus_record        =>  积分同步说明
     *                                 init_balance             =>  初始余额，不填为0
     *                                 init_custom_field_value1 =>  创建时字段custom_field1定义类型的初始值，限制为4个汉字，12字节
     *                                 init_custom_field_value2 =>  创建时字段custom_field2定义类型的初始值，限制为4个汉字，12字节
     *                                 init_custom_field_value3 =>  创建时字段custom_field3定义类型的初始值，限制为4个汉字，12字节
     *
     * @return bool
     * @throws WeResultException
     */
    public function activeCard ( $membershipNumber , $code , $cardId , $data )
    {
        $_fields = [
            'background_pic_url' ,
            'activate_begin_time' ,
            'activate_end_time' ,
            'init_bonus' ,
            'init_bonus_record' ,
            'init_balance' ,
            'init_custom_field_value1' ,
            'init_custom_field_value2' ,
            'init_custom_field_value3' ,
        ];
        $_url    = $this->links['ACTIVE_CARD'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'membership_number' => $membershipNumber ,
            'code'              => $code ,
            'card_id'           => $cardId ,
        ];

        foreach ( $_fields as $value )
        {
            if ( isset( $data[ $value ] ) )
            {
                $_data[ $value ] = $data[ $value ];
            }
        }

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;

    }


    /**
     * 拉取会员信息接口
     *
     * @param string $cardId 卡片ID
     * @param string $code   卡片code
     *
     * @return mixed
     * @throws WeResultException
     */
    public function getMemUserInfo ( $cardId , $code )
    {
        $_data = [
            'card_id' => $cardId ,
            'code'    => $code ,
        ];

        $_url = $this->links['GET_MEM_CARD_USERINFO'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;

    }

    /**
     * 获取用户提交的信息
     *
     * @param string $activateTicket
     *
     * @return mixed
     * @throws WeResultException
     */
    public function getUserSubmitInfo ( $activateTicket )
    {
        $_data['activate_ticket'] = $activateTicket;

        $_url = $this->links['GET_USER_SUBMIT_INFO'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['info'];
    }

    /**
     * 更新会员信息
     *
     * @param string $cardId 卡片ID
     * @param string $code   卡片code
     * @param array  $other  其他信息
     *
     * @return mixed
     * @throws WeResultException
     */
    public function updateMemUserInfo ( $cardId , $code , $other )
    {
        $_url = $this->links['UPDATE_MEM_CARD_USERINFO'] . 'access_token=' . Token::getAccessToken();

        $_data = $other;

        $_data['card_id'] = $cardId;
        $_data['code']    = $code;

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );


        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        unset( $_result['errcode'] , $_result['errmsg'] );

        return $_result;

    }


    // 支付后投放卡券接口
    public function payGiftMemCard ( $baseInfo , $memberRule = NULL )
    {
        $_data['rule_info'] = [
            'type'      => 'RULE_TYPE_PAY_MEMBER_CARD' ,
            'base_info' => $baseInfo ,
        ];

        if ( isset( $memberRule ) )
        {
            $_data['rule_info']['member_rule'] = $memberRule;
        }

        $_url = $this->links['PAY_GIFT_CARD'] . 'access_token=' . Token::getAccessToken();
        

    }


    // +----------------------------------------------------------------------
    // | 第三方代制
    // +----------------------------------------------------------------------

    //TODO:: 未完成 无法测试
    /**
     * @param string $brandName             子商户名称（12个汉字内），该名称将在制券时填入并显示在卡券页面上
     * @param string $logoUrl               子商户logo，可通过上传图片接口获取。该logo将在制券时填入并显示在卡券页面上
     * @param string $protocol              授权函ID，即通过上传临时素材接口上传授权函后获得的meida_id
     * @param string $endTime               授权函有效期截止时间（东八区时间，单位为秒），需要与提交的扫描件一致
     * @param string $primaryCategoryId     一级类目id,可以通过本文档中接口查询
     * @param string $secondaryCateGoryId   二级类目id，可以通过本文档中接口查询
     * @param array  $other                 其他非必填选项
     *                                      app_id              =>  子商户的公众号app_id，配置后子商户卡券券面上的app_id为该app_id。注意：该app_id须经过认证
     *                                      agreement_media_id  =>  营业执照或个体工商户营业执照彩照或扫描件
     *                                      operator_media_id   =>  营业执照内登记的经营者身份证彩照或扫描件
     */
    public function createChildBrand ( $brandName , $logoUrl , $protocol , $endTime , $primaryCategoryId , $secondaryCateGoryId , $other = [] )
    {
        $_url = self::$LINKS['CREATE_CHILD_BRAND'] . 'access_token=' . Token::getAccessToken();

        $_data['info'] = [
            'brand_name'            => $brandName ,
            'logo_url'              => $logoUrl ,
            'protocol'              => $protocol ,
            'end_time'              => $endTime ,
            'primary_category_id'   => $primaryCategoryId ,
            'secondary_category_id' => $secondaryCateGoryId ,
        ];

        if ( !empty( $other ) )
        {
            $_data['info'] = array_merge( $other , $_data['info'] );
        }

        $_result = Http::httpPost( $_url , Tools::arr2json( $_data ) );

        var_dump( $_result );

    }
}