<?php
/*
 * @author: 布尔
 * @name: 企业微信标签接口类
 * @desc: 介绍
 * @LastEditTime: 2023-08-21 16:59:11
 */
namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Qyweixin\Service;
use function Hyperf\Support\env;

class Tag
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
     * @name: 创建标签
     * @param array $param
     * @return array
     */
    public function create(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/tag/create?access_token='. $this->Service->get_access_token($param), eyc_array_key($param, 'tagname,tagid'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 更新标签
     * @param array $param
     * @return array
     */
    public function update(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/tag/update?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'tagname,tagid'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 删除标签
     * @param array $param
     * @return array
     */
    public function delete(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/tag/delete?access_token=' . $this->Service->get_access_token($param).'&tagid='.$param['tagid']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取标签成员
     * @param array $param
     * @return array
     */
    public function get(array $param) : array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/tag/get?access_token=' . $this->Service->get_access_token($param). '&tagid=' . $param['tagid']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 增加标签成员
     * @param array $param
     * @return array
     */
    public function addtagusers(array $param) : array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/tag/addtagusers?access_token=' . $this->Service->get_access_token($param),eyc_array_key($param, 'tagid,userlist,userlist'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 删除标签成员
     * @param array $param
     * @return array
     */
    public function deltagusers(array $param) : array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/tag/deltagusers?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'tagid,userlist,userlist'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取标签列表
     * @param array $param
     * @return array
     */
    public function list(array $param) : array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/tag/list?access_token=' . $this->Service->get_access_token($param));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r['taglist'];
    }
}