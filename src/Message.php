<?php
/*
 * @author: 布尔
 * @name: 消息推送
 * @desc: 介绍
 * @LastEditTime: 2024-03-01 16:38:42
 */

namespace Eykj\Qyweixin;

use Eykj\Base\GuzzleHttp;
use Eykj\Qyweixin\Service;
use function Hyperf\Support\env;
use Eykj\Base\JsonRpcInterface\AuthInterface;

class Message
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    protected ?AuthInterface $AuthInterface;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, ?Service $Service, ?AuthInterface $AuthInterface)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
        $this->AuthInterface = $AuthInterface;
    }

    /**
     * @author: 布尔
     * @name: 发送应用消息
     * @param array $param
     * @return array
     */
    public function send(array $param): array
    {
        /* 查询授权信息 */
        $filter = eyc_array_key($param, 'corpid,types,corp_product');
        $auth_info = $this->AuthInterface->get_info('Qyweixin', $filter);
        $param = eyc_array_insert($param, $auth_info, 'agentid');
        /* 设置消息类型 */
        $param['msgtype'] = $param['msgtype'] ?? 'text';
        /* 标记id转译参数 */
        $param['enable_id_trans'] = $param['enable_id_trans'] ?? 1;
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/message/send?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'touser,toparty,totag,agentid,msgtype,selected_ticket_list,text,image,voice,video,file,textcard,news,mpnews,markdown,miniprogram_notice,template_card,template_msg,enable_id_trans,only_unauth,safe,enable_duplicate_check,duplicate_check_interval'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 更新模版卡片消息
     * @param array $param
     * @return array
     */
    public function update_template_card(array $param): array
    {
        /* 查询授权信息 */
        $filter = eyc_array_key($param, 'corpid,types,corp_product');
        $auth_info = $this->AuthInterface->get_info('Qyweixin', $filter);
        $param = eyc_array_insert($param, $auth_info, 'agentid');
        /* 设置消息类型 */
        $param['msgtype'] = $param['msgtype'] ?? 'text';
        /* 标记id转译参数 */
        $param['enable_id_trans'] = $param['enable_id_trans'] ?? 1;
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/message/update_template_card?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'userids,partyids,tagids,atall,agentid,response_code,button'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 撤回应用消息
     * @param array $param
     * @return array
     */
    public function recall(array $param): array
    {
        $r = $this->GuzzleHttp->post(env('QYWEIXIN_URL', '') . '/cgi-bin/message/recall?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'msgid'));
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }
}
