<?php
/*
 * @author: 布尔
 * @name: 钉钉Service类
 * @desc: 介绍
 * @LastEditTime: 2023-08-18 18:07:52
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
        $key = $param['corpid'] . '_' . $param['corp_product'] . '_' . $param['types'] . '_qyweixxin_suite_token';
        if (!redis()->get($key)) {
            /* 查询产品信息 */
            $product_info = $this->Product->get_info(eyc_array_key($param, 'corp_product,types'));
            if (!$product_info) {
                error(13004);
            }
            /*查询suiteTicket*/
            $filter['biz_type'] = 2;
            $filter['biz_id'] = $product_info['qyweixin_suite_id'];
            $filter['subscribe_id'] = $product_info['qyweixin_suite_id'] . '_0';
            $biz_info = $this->BizDatum->get_info($filter);
            if (!$biz_info) {
                error(10002);
            }
            $data = eyc_array_key($product_info, 'suite_id|qyweixin_suite_id,suite_secret|qyweixin_suite_secret');
            $data = eyc_array_insert($data, json_decode($biz_info['biz_data'], true), 'suite_ticket');
            $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/cgi-bin/service/get_suite_token', $data);
            if ($r["errcode"] == 0) {
                redis()->set($key, $r["suite_access_token"], $r['expires_in']);
                return $r["suite_access_token"];
            } else {
                error(500, $r['errmsg']);
            }
        } else {
            return redis()->get($key);
        }
    }

    /**
     * @author: 布尔
     * @name: 获取预授权码
     * @param array $param
     * @return string
     */
    public function get_pre_auth_code(array $param): string
    {
        $key = $param['corpid'] . '_' . $param['corp_product'] . '_' . $param['types'] . '_qyweixxin_pre_auth_code';
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
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/cgi-bin/service/get_permanent_code?suite_access_token=' . $this->get_suite_token($param), eyc_array_key($param, 'auth_code|AuthCode,auth_code'));
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
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', 'https://qyapi.weixin.qq.com') . '/cgi-bin/service/get_auth_info?suite_access_token=' . $this->get_suite_token($param), eyc_array_key($param, 'get_auth_info,get_auth_info'));
        if ($r["errcode"] == 0) {
            return $r;
        } else {
            error(500, $r['errmsg']);
        }
    }
}
