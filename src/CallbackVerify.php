<?php
/*
 * @author: 布尔
 * @name: 企业微信回调验签
 * @desc: 介绍
 * @LastEditTime: 2023-08-24 19:03:11
 */
namespace Eykj\Qyweixin;

use Eykj\Qyweixin\callback\WXBizMsgCrypt;

class CallbackVerify
{
    /**
     * @author: 布尔
     * @name: 验签
     * @return {*}
     */    
    public function verify(array $param)
    {
        $wxcpt = new WXBizMsgCrypt($param['token'], $param['encodingAesKey'], $param['receiveid']);
        $echo_str = '';
        if(isset($param['data'])){
            /* 检验消息的真实性，并且获取解密后的明文 */
            $err_code = $wxcpt->DecryptMsg($param['msg_signature'], $param['timestamp'],$param['nonce'],$param['data'], $echo_str);
        }else{
            /* url验签 */
            $err_code = $wxcpt->VerifyURL($param['msg_signature'],$param['timestamp'],$param['nonce'],$param['echostr'],$echo_str);
        }
        if ($err_code == 0) {
            return $echo_str;
        } else {
            return $err_code;
        }
    }

    
}
