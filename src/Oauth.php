<?php
/*
 * @author: 布尔
 * @name: 企业微信授权接口类
 * @desc: 介绍
 * @LastEditTime: 2024-02-27 11:01:03
 */

namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Qyweixin\Service;
use function Hyperf\Support\env;

class Oauth
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
     * @name: 构造企业oauth2链接
     * @param array $param
     * @return array
     */
    public function authorize(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/connect/oauth2/authorize?appid=' . $param['appid'] . '&redirect_uri=' . urlencode($param['redirect_uri']) . '&response_type=code&scope=' . $param['scope'] . (isset($param['agentid']) ? '&agentid=' . $param['agentid'] : '') . (isset($param['state']) ? '&state=' . $param['state'] : '') . '#wechat_redirect');
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }
}
