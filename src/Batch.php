<?php
/*
 * @author: 布尔
 * @name: 异步导入接口
 * @desc: 介绍
 * @LastEditTime: 2023-08-21 16:45:44
 */
namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Qyweixin\Service;
use function Hyperf\Support\env;

class Batch
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
     * @name: 增量更新成员
     * @param array $param
     * @return int
     */
    public function syncuser(array $param): int
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/batch/syncuser?access_token='. $this->Service->get_access_token($param), eyc_array_key($param, 'media_id,to_invite,callback'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }


    /**
     * @author: 布尔
     * @name: 全量覆盖成员
     * @param array $param
     * @return array
     */
    public function replaceuser(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/batch/replaceuser?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'media_id,to_invite,callback'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }


    /**
     * @author: 布尔
     * @name: 全量覆盖部门
     * @param array $param
     * @return array
     */
    public function replaceparty(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/batch/replaceparty?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'media_id,to_invite,callback'));
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
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/batch/getresult?access_token=' . $this->Service->get_access_token($param) . '&jobid=' . $param['jobid']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }
}