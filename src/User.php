<?php
/*
 * @author: 布尔
 * @name: 企业微信用户接口类
 * @desc: 介绍
 * @LastEditTime: 2023-08-21 16:45:44
 */
namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Qyweixin\Service;
use function Hyperf\Support\env;

class User
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
     * @name: 创建用户
     * @param array $param
     * @return int
     */
    public function create(array $param): int
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/create?access_token='. $this->Service->get_access_token($param), eyc_array_key($param, 'userid,name,alias,mobile,department,order,position,gender,email,biz_mail,biz_mail,is_leader_in_dept,direct_leader,direct_leader,enable,extattr,to_invite,external_profile,external_position,nickname,address,main_department'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }


    /**
     * @author: 布尔
     * @name: 读取成员
     * @param array $param
     * @return array
     */
    public function get(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/user/get?access_token=' . $this->Service->get_access_token($param) . '&userid=' . $param['userid']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }


    /**
     * @author: 布尔
     * @name: 更新用户
     * @param array $param
     * @return array
     */
    public function update(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/update?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'userid,name,alias,mobile,department,order,position,gender,email,biz_mail,biz_mail,is_leader_in_dept,direct_leader,direct_leader,enable,extattr,to_invite,external_profile,external_position,nickname,address,main_department'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 删除用户
     * @param array $param
     * @return array
     */
    public function delete(array $param): array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/user/delete?access_token=' . $this->Service->get_access_token($param) . '&userid=' . $param['userid']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 批量删除用户
     * @param array $param
     * @return array
     */
    public function batchdelete(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/delete?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'useridlist'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取部门成员
     * @param array $param
     * @return array
     */
    public function simplelist(array $param) : array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/user/list?access_token=' . $this->Service->get_access_token($param).'&department_id=' . $param['department_id']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r['userlist'];
    }

    /**
     * @author: 布尔
     * @name: 获取部门成员详情
     * @param array $param
     * @return array
     */
    public function list(array $param) : array
    {
        $r = $this->GuzzleHttp->get(env('QYWEIXIN_URL', '') . '/cgi-bin/user/list?access_token=' . $this->Service->get_access_token($param) . '&department_id=' . $param['department_id']);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r['userlist'];
    }

    /**
     * @author: 布尔
     * @name: userid与openid互换
     * @param array $param
     * @return array
     */
    public function convert_to_openid(array $param) : array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/convert_to_openid?access_token=' . $this->Service->get_access_token($param),eyc_array_key($param, 'userid'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 邀请成员
     * @param array $param
     * @return array
     */
    public function invite(array $param) : array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/batch/batch?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'user,party,tag'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 手机号获取userid
     * @param array $param
     * @return array
     */
    public function getuserid(array $param) : array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/getuserid?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'mobile'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 通过邮箱获取其所对应的userid
     * @param array $param
     * @return array
     */
    public function get_userid_by_email(array $param) : array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/get_userid_by_email?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'email,email_type'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取成员授权列表
     * @param array $param
     * @return array
     */
    public function list_member_auth(array $param) : array
    {
        $data = ['limit'=>1000];
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/list_member_auth?access_token=' . $this->Service->get_access_token($param),$data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        if ($r["next_cursor"]) {
            do {
                $data['cursor'] = $r['next_cursor'];
                $rs = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/list_member_auth?access_token=' . $this->Service->get_access_token($param), $data);
                $r = array_merge_recursive($r, $rs);
            } while ($rs["next_cursor"]);
        }
        return $r['member_auth_list'];
    }

    /**
     * @author: 布尔
     * @name: 获取成员授权列表
     * @param array $param
     * @return array
     */
    public function check_member_auth(array $param) : array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/check_member_auth?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'open_userid'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取选人ticket对应的用户
     * @param array $param
     * @return array
     */
    public function list_selected_ticket_user(array $param) : array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/list_selected_ticket_user?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'selected_ticket'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 获取成员ID列表
     * @param array $param
     * @return array
     */
    public function list_id(array $param) : array
    {
        $data = ['limit' => 1000];
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/list_id?access_token=' . $this->Service->get_access_token($param), $data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        if ($r["next_cursor"]) {
            do {
                $data['cursor'] = $r['next_cursor'];
                $rs = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/user/list_id?access_token=' . $this->Service->get_access_token($param), $data);
                $r = array_merge_recursive($r, $rs);
            } while ($rs["next_cursor"]);
        }
        return $r['dept_user'];
    }
}