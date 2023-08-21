<?php
/*
 * @author: 布尔
 * @name: 异步导出接口
 * @desc: 介绍
 * @LastEditTime: 2023-08-21 17:48:58
 */
namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Qyweixin\Service;
use function Hyperf\Support\env;

class Export
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp,?Service $Service)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }

    /**
     * @author: 布尔
     * @name: 导出成员
     * @param array $param
     * @return int
     */
    public function simple_user(array $param): int
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/export/simple_user?access_token='. $this->Service->get_access_token($param), eyc_array_key($param, 'encoding_aeskey,block_size'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 导出成员详情
     * @param array $param
     * @return array
     */
    public function user(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/export/user?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'encoding_aeskey,block_size'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 导出部门
     * @param array $param
     * @return array
     */
    public function department(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/export/department?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'encoding_aeskey,encoding_aeskey'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 导出标签成员
     * @param array $param
     * @return array
     */
    public function taguser(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/export/taguser?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'tagid,encoding_aeskey,encoding_aeskey'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取导出结果
     * @param array $param
     * @return array
     */
    public function get_result(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/export/get_result?access_token=' . $this->Service->get_access_token($param) . '&jobid=' . $param['jobid']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }
}