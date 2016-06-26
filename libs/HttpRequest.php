<?php
namespace libs;
/**
 * HTTP请求模块
 * @author sucd
 */
class HttpRequest{
	public static function request($url, $params=array(), $method='GET'){
		$ret = null;
		if(preg_match("/^(http|https)\:\/\/(\w+\.\w+\.\w+)/", $url)){
			$method = strtoupper($method);
			if($method == "POST"){
				exit('nothing to do.');
			}elseif($method == "PUT"){
				exit('nothing to do.');
			}elseif($method == "DELETE"){
				exit('nothing to do.');
			}else{
				if($params){
					if(stripos('?', $url)){
						$url = $url . "&" . http_build_query($params);
					}else{
						$url = $url . "?" . http_build_query($params);
					}
				}
				$ret = file_get_contents($url);
			}
		}
		return $ret;
	}
}
?>