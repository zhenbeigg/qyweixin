<?php
namespace Eykj\Qyweixin\callback;

use Eykj\Qyweixin\callback\ErrorCode;
use Eykj\Qyweixin\callback\Sha1;
use Eykj\Qyweixin\callback\Prpcrypt;

class WXBizMsgCrypt
{
	private $m_sToken;
	private $m_sEncodingAesKey;
	private $m_sReceiveId;

	/**
	 * 构造函数
	 * @param $token string 开发者设置的token
	 * @param $encodingAesKey string 开发者设置的EncodingAESKey
	 * @param $receiveId string, 不同应用场景传不同的id
	 */
	public function __construct($token, $encodingAesKey, $receiveId)
	{
		$this->m_sToken = $token;
		$this->m_sEncodingAesKey = $encodingAesKey;
		$this->m_sReceiveId = $receiveId;
	}
	
    /*
	*验证URL
    *@param sMsgSignature: 签名串，对应URL参数的msg_signature
    *@param sTimeStamp: 时间戳，对应URL参数的timestamp
    *@param sNonce: 随机串，对应URL参数的nonce
    *@param sEchoStr: 随机串，对应URL参数的echostr
    *@param sReplyEchoStr: 解密之后的echostr，当return返回0时有效
    *@return：成功0，失败返回对应的错误码
	*/
	public function VerifyURL($sMsgSignature, $sTimeStamp, $sNonce, $sEchoStr, &$sReplyEchoStr)
	{
		if (strlen($this->m_sEncodingAesKey) != 43) {
			return ErrorCode::$IllegalAesKey;
		}

		$pc = new Prpcrypt($this->m_sEncodingAesKey);
		//verify msg_signature
		$sha1 = new Sha1;
		$array = $sha1->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $sEchoStr);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		$signature = $array[1];
		if ($signature != $sMsgSignature) {
			return ErrorCode::$ValidateSignatureError;
		}

		$result = $pc->decrypt($sEchoStr, $this->m_sReceiveId);
		if ($result[0] != 0) {
			return $result[0];
		}
		$sReplyEchoStr = $result[1];

		return ErrorCode::$OK;
	}
}

