<?php
/*
 * @author: 布尔
 * @name: 企业微信部门接口类
 * @desc: 介绍
 * @LastEditTime: 2024-02-22 17:39:22
 */
namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Qyweixin\Service;
use function Hyperf\Support\env;

class Department
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
     * @name: 创建部门
     * @param array $param
     * @return array
     */
    public function create(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/department/create?access_token='. $this->Service->get_access_token($param), eyc_array_key($param, 'name,name_en,parentid,order,order,id'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 更新部门
     * @param array $param
     * @return array
     */
    public function update(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/department/update?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'name,name_en,parentid,order,order,id'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 删除部门
     * @param array $param
     * @return array
     */
    public function delete(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/department/delete?access_token=' . $this->Service->get_access_token($param).'&id='.$param['id']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 部门列表
     * @param array $param
     * @return array
     */
    public function list(array $param) : array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/department/list?access_token=' . $this->Service->get_access_token($param). (isset($param['id']) ? '&id=' . $param['id'] : ''));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r['department'];
    }

    /**
     * @author: 布尔
     * @name: 获取子部门ID列表
     * @param array $param
     * @return array
     */
    public function simplelist(array $param) : array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/department/simplelist?access_token=' . $this->Service->get_access_token($param). (isset($param['id']) ? '&id=' . $param['id'] : ''));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r['department_id'];
    }

    /**
     * @author: 布尔
     * @name: 获取单个部门详情
     * @param array $param
     * @return array
     */
    public function get(array $param) : array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/department/get?access_token=' . $this->Service->get_access_token($param) .'&id=' . $param['id']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r['department'];
    }
}