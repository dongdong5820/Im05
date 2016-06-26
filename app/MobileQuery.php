<?php
namespace app;
/**
 * @手机归属地查询模块
 * @author sucd
 */
use libs\HttpRequest;
use libs\ImRedis;

class MobileQuery{
	const PHONE_API = 'https://tcc.taobao.com/cc/json/mobile_tel_segment.htm';
	const QUERY_PHONE = 'PHONE:INFO';

	protected static $redisInstance = null;

	public static function query($phone){
		$phoneData = null;
		self::$redisInstance = ImRedis::getRedis();
		if(self::verifyPhone($phone)){
			$redisKey = sprintf(self::QUERY_PHONE . '%s', substr($phone, 0, 7));
			$phoneInfo = self::$redisInstance->get($redisKey);
			if(!$phoneInfo){
				$response = HttpRequest::request(self::PHONE_API, array('tel'=>$phone));
				$phoneData = self::formData($response);
				if($phoneData){
					self::$redisInstance->set($redisKey, json_encode($phoneData));
				}
				$phoneData['msg'] = '数据由阿里巴巴提供！';
			}else{
				$phoneData = json_decode($phoneInfo, true);
				$phoneData['msg'] = '数据由redis提供！';
			}
			return $phoneData;
		}
	}

	public static function verifyPhone($phone){
		if(preg_match("/^1[34578]{1}\d{9}/", $phone)){
			return true;
		}else{
			return false;
		}
	}

	public static function formData($data){
		$ret = null;
		if(!empty($data)){
			preg_match_all("/(\w+):'([^']+)/", $data, $res);
			$items = array_combine($res[1], $res[2]);
			foreach ($items as $key => $value) {
				$ret[$key] = iconv('GB2312', 'UTF-8', $value);
			}
		}
		return $ret;
	}
}

?>