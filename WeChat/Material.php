<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/21
 * Time: 上午11:21
 */

namespace WeChat;


use WeChat\Exception\ParamException;
use WeChat\Exception\WeResultException;
use WeChat\Tools\Http;
use WeChat\Tools\Token;
use WeChat\Tools\Tools;

class Material extends Base
{

    // +----------------------------------------------------------------------
    // | 定义
    // +----------------------------------------------------------------------

    private $materialType = [
        'image' ,
        'voice' ,
        'video' ,
        'thumb' ,
        'news' ,
    ];

    // 每页显示数量
    private $listRow = 20;

    private $links = [
        // 新增临时素材
        'TEMP_MEDIA_UPLOAD'   => 'https://api.weixin.qq.com/cgi-bin/media/upload?' ,
        // 新增其他类型永久素材
        'OTHER_MEDIA_UPLOAD'  => 'https://api.weixin.qq.com/cgi-bin/material/add_material?' ,
        // 新增永久图文素材
        'NEWS_UPLOAD'         => 'https://api.weixin.qq.com/cgi-bin/material/add_news?' ,
        // 上传图文消息内的图片获取URL
        'IMG_UPLOAD_FOR_NEWS' => 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?' ,
        // 获取素材列表
        'BATCH_GET_MATERIAL'  => 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?' ,
        // 获取素材总数
        'GET_MATERIAL_COUNT'  => 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?' ,
        // 删除永久素材
        'DELETE_MATERIAL'     => 'https://api.weixin.qq.com/cgi-bin/material/del_material?' ,
        // 修改永久图文素材
        'NEWS_EDIT'           => 'https://api.weixin.qq.com/cgi-bin/material/update_news?' ,
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
     * 新增临时素材
     *
     * @param string $type 文件类型
     * @param array  $file 文件
     *                     path => 文件地址
     *                     type => 文件类型
     *                     name => 文件名
     *
     * @return string url地址
     * @throws ParamException
     * @throws WeResultException
     */
    public function uploadTemp ( $type , $file )
    {
        if ( empty( $file['path'] ) )
        {
            throw new ParamException( "参数<path>必须填写" );
        }
        if ( empty( $file['type'] ) )
        {
            throw new ParamException( '参数<type>必须填写' );
        }
        if ( empty( $file['name'] ) )
        {
            throw new ParamException( '参数<name>必须填写' );
        }
        if ( empty( $type ) )
        {
            throw new ParamException( '参数<type>必须填写' );
        }

        $_url = $this->links['TEMP_MEDIA_UPLOAD'] . 'access_token=' . Token::getAccessToken() . '&type=' . $type;

        $_data = [
            'media' => new \CURLFile( realpath( $file['path'] ) , $file['type'] , $file['name'] ) ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 新增其他类型永久素材
     *
     * @param string $type 文件类型
     * @param array  $file 文件
     *                     path => 文件地址
     *                     type => 文件类型
     *                     name => 文件名
     *
     * @return mixed
     * @throws ParamException
     * @throws WeResultException
     */
    public function upload ( $type , $file )
    {
        if ( empty( $file['path'] ) )
        {
            throw new ParamException( "参数<path>必须填写" );
        }
        if ( empty( $file['type'] ) )
        {
            throw new ParamException( '参数<type>必须填写' );
        }
        if ( empty( $file['name'] ) )
        {
            throw new ParamException( '参数<name>必须填写' );
        }
        if ( empty( $type ) )
        {
            throw new ParamException( '参数<type>必须填写' );
        }

        $_url = $this->links['OTHER_MEDIA_UPLOAD'] . 'access_token=' . Token::getAccessToken() . '&type=' . $type;

        $_data = [
            'media' => new \CURLFile( realpath( $file['path'] ) , $file['type'] , $file['name'] ) ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 新增永久图文素材
     *
     * @param array $messages 消息数组
     *
     * @return mixed
     * @throws ParamException
     * @throws WeResultException
     */
    public function uploadNews ( $messages )
    {
        $_messageCount = count( $messages );

        if ( $_messageCount > 8 || $_messageCount < 1 )
        {
            throw new ParamException( '参数<messages>长度错误' );
        }

        $_data['articles'] = $messages;

        $_url = $this->links['NEWS_UPLOAD'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );


        print_r( Tools::arr2json( $_data ) );
        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 上传图文消息内的图片获取URL
     *
     * @param array $img 图片数据
     *
     * @return string url地址
     * @throws ParamException
     * @throws WeResultException
     */
    public function uploadImgForNews ( $img )
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
        $_url = $this->links['IMG_UPLOAD_FOR_NEWS'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'media' => new \CURLFile( realpath( $img['path'] ) , $img['type'] , $img['name'] ) ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , $_data ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result['url'];
    }

    /**
     * 获取素材列表
     *
     * @param string $type 素材类型
     * @param int    $page 页码
     *
     * @return mixed
     * @throws ParamException
     * @throws WeResultException
     */
    public function batchGetMaterial ( $type , $page = 1 )
    {
        if ( !is_string( $type ) || !in_array( $type , $this->materialType ) )
        {
            throw new ParamException( '参数<type>不合法' );
        }

        // 强转int
        $page = (int) $page;

        if ( $page <= 0 )
        {
            throw new ParamException( '参数<type>只能为大于1的数字' );
        }

        $_url   = $this->links['BATCH_GET_MATERIAL'] . 'access_token=' . Token::getAccessToken();
        $_param = [
            'type'   => $type ,
            'offset' => ( $page - 1 ) * $this->listRow ,
            'count'  => $this->listRow ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_param ) ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }

    /**
     * 获取素材总数
     *
     * @return array 素材数量
     *               voice_count    声音媒体数量
     *               video_count    视频数量
     *               image_count    图片数量
     *               news_count     图文数量
     *
     * @throws WeResultException
     */
    public function getCount ()
    {
        $_url = $this->links['GET_MATERIAL_COUNT'] . 'access_token=' . Token::getAccessToken();

        $_result = Tools::json2arr( Http::httpGet( $_url ) );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return $_result;
    }


    /**
     * 删除永久素材
     *
     * @param string $mediaId 素材ID
     *
     * @return bool 删除成功返回true
     * @throws WeResultException
     */
    public function delete ( $mediaId )
    {
        $_url = $this->links['DELETE_MATERIAL'] . 'access_token=' . Token::getAccessToken();

        $_data['media_id'] = $mediaId;

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        var_dump( $_result );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;
    }

    /**
     * 修改永久图文素材
     *
     * @param string $mediaId 要修改的图文消息的id
     * @param int    $index   要更新的文章在图文消息中的位置（多图文消息时，此字段才有意义），第一篇为0
     * @param array  $data    图文消息内容
     *
     * @return bool
     * @throws WeResultException
     */
    public function editNews ( $mediaId , $index , $data )
    {
        $_url = $this->links['NEWS_EDIT'] . 'access_token=' . Token::getAccessToken();

        $_data = [
            'media_id' => $mediaId ,
            'index'    => $index ,
            'articles' => $data ,
        ];

        $_result = Tools::json2arr( Http::httpPost( $_url , Tools::arr2json( $_data ) ) );

        var_dump( $_result );

        if ( isset( $_result['errcode'] ) && $_result['errcode'] !== 0 )
        {
            throw new WeResultException( $_result['errcode'] );
        }

        return TRUE;

    }
}