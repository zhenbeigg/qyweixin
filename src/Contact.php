<?php
/*
 * @author: 布尔
 * @name: 企业微信用通讯录
 * @desc: 介绍
 * @LastEditTime: 2024-04-17 17:56:21
 */

namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Qyweixin\Service;
use function Hyperf\Support\env;

class Contact
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
     * @name: id转译
     * @param array $param
     * @return array
     */
    public function id_translate(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/service/contact/id_translate?provider_access_token=' . $this->Service->get_provider_token($param), eyc_array_key($param, 'auth_corpid|corpid,media_id_list,output_file_name,output_file_format'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取异步任务结果
     * @param array $param
     * @return array
     */
    public function getresult(array $param): array
    {
        $r =  $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/service/batch/getresult?provider_access_token=' . $this->Service->get_provider_token($param) . '&jobid=' . $param['jobid']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }
}
