<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/28
 * Time: 下午3:59
 */

namespace WeChat;


use WeChat\Exception\WeResultException;
use WeChat\Tools\Http;
use WeChat\Tools\Token;
use WeChat\Tools\Tools;

class Template extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    private $links = [
        // 设置所属行业
        'SET_INDUSTRY'                 => 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?' ,
        // 获取设置的所属行业
        'GET_INDUSTRY'                 => 'https://api.weixin.qq.com/cgi-bin/template/get_industry?' ,
        // 添加模板
        'ADD_TEMPLATE'                 => 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?' ,
        // 获取已添加的模板列表
        'GET_PRIVATE_TEMPLATE_LIST'    => 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?' ,
        // 删除已添加的模板
        'DELETE_PRIVATE_TEMPLATE_LIST' => 'https://api.weixin.qq.com/cgi-bin/template/del_private_template?' ,
        // 发送模板消息
        'SEND_TEMPLATE_MESSAGE'        => 'https://api.weixin.qq.com/cgi-bin/message/template/send?' ,
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
     * 设置所属行业
     *
     * @param $industryId1
     * @param $industryId2
     *
     * @return bool
     * @throws WeResultException
     */
    public function setIndustry ( $industryId1 , $industryId2 )
    {
        $_url = $this->links['SET_INDUSTRY'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'industry_id1' => $industryId1 ,
            'industry_id2' => $industryId2 ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 获取行业数据
     *
     * @return mixed
     */
    public function getIndustry ()
    {
        $_url = $this->links['GET_INDUSTRY'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpGet( $_url ) );

        return $_result;
    }

    /**
     * 添加模板
     *
     * @param string $templateId 模板ID
     *
     * @return mixed
     * @throws WeResultException
     */
    public function addTemplate ( $templateId )
    {

        $_url = $this->links['ADD_TEMPLATE'] . 'access_token=' . Token::getAccessToken();

        $_data ['template_id_short'] = $templateId;

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['template_id'];
    }

    /**
     * 获取已添加的模板列表
     *
     * @return mixed
     * @throws WeResultException
     */
    public function getPrivateTemplateList ()
    {
        $_url = $this->links['GET_PRIVATE_TEMPLATE_LIST'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpGet( $_url ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['template_list'];
    }

    /**
     * 删除已添加的模板
     *
     * @param string $templateId 添加的模板ID
     *
     * @return bool
     * @throws WeResultException
     */
    public function deletePrivateTemplate ( $templateId )
    {
        $_url = $this->links['DELETE_PRIVATE_TEMPLATE_LIST'] . 'access_token=' . Token::getAccessToken();

        $_data ['template_id'] = $templateId;

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 推送消息模板
     *
     * @param string      $openId           接收者openid
     * @param string      $templateId       模板ID
     * @param array       $data             模板数据
     *                                      示例:
     *                                      [
     *                                          [
     *                                          'value'=>'恭喜你购买成功！',
     *                                          'color'=>'#173177'
     *                                          ]
     *                                      ]
     * @param null|string $url              模板跳转链接
     * @param null|array  $miniProgram      跳小程序所需数据，不需跳小程序可不用传该数据
     *                                      示例:
     *                                      [
     *                                          'appid'     =>  '123123',       所需跳转到的小程序appid（该小程序appid必须与发模板消息的公众号是绑定关联关系，并且小程序要求是已发布的）
     *                                          'pagepath'  =>  'page/index'    所需跳转到小程序的具体页面路径，支持带参数,（示例index?foo=bar）
     *                                      ]
     *
     * @return bool
     * @throws WeResultException
     */
    public function sendTemplateMessage ( $openId , $templateId , $data , $url = NULL , $miniProgram = NULL )
    {
        $_url = $this->links['SEND_TEMPLATE_MESSAGE'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'touser'      => $openId ,
            'template_id' => $templateId ,
        ];
        // 设置跳转链接
        if ( isset( $url ) )
        {
            $_data['url'] = $url;
        }
        // 设置跳转小程序
        if ( isset( $miniProgram ) )
        {
            $_data['miniprogram'] = $miniProgram;
        }

        $_countData     = count( $data );
        $_lastIndexData = $_countData - 1;


        for ( $i = 0; $i < $_countData; $i++ )
        {
            switch ( $i )
            {
                case 0:
                    $_data['data']['first'] = $data[ $i ];
                break;
                case $_lastIndexData:
                    $_data['data']['remark'] = $data[ $i ];
                break;
                default:
                    $_data['data'][ 'keynote' . $i ] = $data[ $i ];
            }
        }

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }
}