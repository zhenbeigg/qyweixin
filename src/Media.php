<?php
/*
 * @author: 布尔
 * @name: 素材管理
 * @desc: 介绍
 * @LastEditTime: 2024-03-15 14:21:27
 */

namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Qyweixin\Service;
use function Hyperf\Support\env;

class Media
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, ?Service $Service)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }

    /**
     * @author: 布尔
     * @name: 上传临时素材
     * @param array $param
     * @return array
     */
    public function upload(array $param): array
    {
        $media['name'] = 'media';
        $media['contents'] = fopen($param['file'], 'r+');
        $data[] = $media;
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/media/upload?access_token=' . $this->Service->get_access_token($param) . '&type=' . $param['type'], $data, en_type: 'file');
        /* 关闭资源 */
        if (is_resource($media['contents'])) {
            fclose($media['contents']);
        }
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 上传图片
     * @param array $param
     * @return array
     */
    public function uploadimg(array $param): array
    {
        $media['name'] = 'media';
        $media['contents'] = fopen($param['file'], 'r+');
        $data[] = $media;
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/media/uploadimg?access_token=' . $this->Service->get_access_token($param), $data, en_type: 'file');
        /* 关闭资源 */
        if (is_resource($media['contents'])) {
            fclose($media['contents']);
        }
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取临时素材
     * @param array $param
     * @return array
     */
    public function get(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/media/get?access_token=' . $this->Service->get_access_token($param) . '&media_id=' . $param['media_id']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取高清语音素材
     * @param array $param
     * @return array
     */
    public function get_jssdk(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/media/get/jssdk?access_token=' . $this->Service->get_access_token($param) . '&media_id=' . $param['media_id']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 生成异步上传任务
     * @param array $param
     * @return array
     */
    public function upload_by_url(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/media/upload_by_url?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'scene,type,filename,url,md5'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 查询异步任务结果
     * @param array $param
     * @return array
     */
    public function get_upload_by_url_result(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/media/get_upload_by_url_result?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'jobid'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 服务商上传临时素材
     * @param array $param
     * @return array
     */
    public function service_upload(array $param): array
    {
        $media['name'] = 'media';
        $media['contents'] = fopen($param['file'], 'r+');
        $data[] = $media;
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/service/media/upload?provider_access_token=' . $this->Service->get_access_token($param) . '&type=' . $param['type'] . '&attachment_type=' . $param['attachment_type'], $data, en_type: 'file');
        /* 关闭资源 */
        if (is_resource($media['contents'])) {
            fclose($media['contents']);
        }
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }
}
