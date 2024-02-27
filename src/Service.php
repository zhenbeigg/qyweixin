<?php
/*
 * @author: 布尔
 * @name: 钉钉Service类
 * @desc: 介绍
 * @LastEditTime: 2024-02-27 16:21:38
 */

namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Base\JsonRpcInterface\AuthInterface;
use function Hyperf\Support\env;

class Service
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?AuthInterface $AuthInterface;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, ?AuthInterface $AuthInterface)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->AuthInterface = $AuthInterface;
    }
    /**
     * @author: 布尔
     * @name: 获取access_token
     * @param array $param
     * @return string
     */
    public function get_access_token(array $param): string
    {
        return $this->AuthInterface->get_access_token('Qyweixin', $param);
    }

    /**
     * @author: 布尔
     * @name: 获取第三方应用凭证
     * @param array $param
     * @return string
     */
    public function get_suite_token(array $param): string
    {
        return $this->AuthInterface->get_suite_token('Qyweixin', $param);
    }

    /**
     * @author: 布尔
     * @name: 获取服务商的token
     * @param array $param
     * @return string
     */
    public function get_provider_token(array $param): string
    {
        return $this->AuthInterface->get_provider_token('Qyweixin', $param);
    }

    /**
     * @author: 布尔
     * @name: 获取预授权码
     * @param array $param
     * @return string
     */
    public function get_pre_auth_code(array $param): string
    {
        $key = $param['corp_product'] . '_' . $param['types'] . '_qyweixxin_pre_auth_code';
        if (!redis()->get($key)) {
            $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/cgi-bin/service/get_pre_auth_code?suite_access_token=' . $this->get_suite_token($param));
            if ($r["errcode"] == 0) {
                redis()->set($key, $r["pre_auth_code"], $r['expires_in']);
                return $r["pre_auth_code"];
            } else {
                error(500, $r['errmsg']);
            }
        } else {
            return redis()->get($key);
        }
    }

    /**
     * @author: 布尔
     * @name: 获取企业永久授权码
     * @param array $param
     * @return array
     */
    public function get_permanent_code(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/cgi-bin/service/get_permanent_code?suite_access_token=' . $this->get_suite_token($param), eyc_array_key($param, 'auth_code|AuthCode,auth_code'));
        if ($r["errcode"] == 0) {
            return $r;
        } else {
            error(500, $r['errmsg']);
        }
    }

    /**
     * @author: 布尔
     * @name: 获取企业授权信息
     * @param array $param
     * @return array
     */
    public function get_auth_info(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/cgi-bin/service/get_auth_info?suite_access_token=' . $this->get_suite_token($param), eyc_array_key($param, 'auth_corpid,permanent_code'));
        if ($r["errcode"] == 0) {
            return $r;
        } else {
            error(500, $r['errmsg']);
        }
    }

    /**
     * @author: 布尔
     * @name: code2Session 临时登录凭证校验接口
     * @param array $param
     * @return array
     */
    public function jscode2session(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/cgi-bin/service/miniprogram/jscode2session?suite_access_token=' . $this->get_suite_token($param) . '&js_code=' . $param['code'] . '&grant_type=authorization_code');
        if ($r["errcode"] == 0) {
            return $r;
        } else {
            error(500, $r['errmsg']);
        }
    }

    /**
     * @author: 布尔
     * @name: 获取访问用户身份
     * @param array $param
     * @return array
     */
    public function getuserinfo3rd(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/service/auth/getuserinfo3rd?suite_access_token=' . $this->get_suite_token($param) . '&code=' . $param['code']);
        if ($r["errcode"] == 0) {
            return $r;
        } else {
            error(500, $r['errmsg']);
        }
    }

    /**
     * @author: 布尔
     * @name: 获取访问用户敏感信息
     * @param array $param
     * @return array
     */
    public function getuserdetail3rd(array $param): array
    {
        $$r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/service/auth/getuserdetail3rd?suite_access_token=' . $this->get_suite_token($param), eyc_array_key($param, 'user_ticket'));
        if ($r["errcode"] == 0) {
            return $r;
        } else {
            error(500, $r['errmsg']);
        }
    }

    /**
     * @author: 布尔
     * @name: jsapi授权
     * @param array $param
     * @return string
     */
    public function get_jsapi_ticket(array $param): string
    {
        if (!redis()->get($param['corpid'] . '_' . $param['corp_product'] . '_jsapi_ticket_token')) {
            $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/cgi-bin/get_jsapi_ticket?access_token=' . $this->get_access_token($param));
            if (isset($r['ticket']) && $r["errcode"] == 0) {
                redis()->set($param['corpid'] . '_' . $param['corp_product'] . '_jsapi_ticket_token', $r["ticket"], $r['expires_in']);
                return $r["ticket"];
            } else {
                error(500, $r['errmsg']);
            }
        }
        return redis()->get($param['corpid'] . '_' . $param['corp_product'] . '_jsapi_ticket_token');
    }
}
