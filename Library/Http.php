<?php

class Http
{
    /**
     * 发送HTTP/HTTPS请求
     * @param $url
     * @param $params 参数   array('api'=>'user/list', 'company_ticket'=>'xxxx'); or json string
     * @param $method 请求类型
     * @param $multi 是否上传文件
     * @return string
     */
    public static function request($url, $params = array(), $method = 'GET', $file = false, $opt = array())
    {
        if(!function_exists('curl_init')) exit('Did not find the CURL extension');
        $method = strtoupper($method);
        $curl = curl_init();
		$option = array(
			CURLOPT_USERAGENT => 'Atom Sdk',
			CURLOPT_CONNECTTIMEOUT => 3, //在发起连接前等待的时间，如果设置为0，则无限等待。
			CURLOPT_TIMEOUT => 9999999,		//设置cURL允许执行的最长秒数
			CURLOPT_RETURNTRANSFER => true,//在启用CURLOPT_RETURNTRANSFER的时候，返回原生的（Raw）输出
			CURLOPT_SSL_VERIFYPEER => false, //禁用后cURL将终止从服务端进行验证
			CURLOPT_SSL_VERIFYHOST => false, //检查服务器SSL证书中是否存在一个公用名
			CURLOPT_HEADER => false, //启用时会将头文件的信息作为数据流输出
			//CURLOPT_HTTPHEADER=>array('Content-type:application/json'),
		);
		//这里不能用 array_merge,数字下标会重置
		foreach($opt as $key=>$value){
			$option[$key] = $value;
		}
		switch ($method) {
			case 'POST':
				$option[CURLOPT_POST] = TRUE;
				if (!empty($params)) {
					if ($file) {
						foreach ($file as $key => $value) {
                            $params[$key] = '@' . $value;
						}
						$option[CURLOPT_POSTFIELDS] = $params;
					} else {
                        if(is_array($params))
                            $option[CURLOPT_POSTFIELDS] = http_build_query($params);
                        else
                            $option[CURLOPT_POSTFIELDS] = $params;
					}
				}
				break;
			
			case 'GET':
				if (!empty($params)){
                    $url = $url . (strpos($url, '?') ? '&' : '?') . (is_array($params) ? http_build_query($params) : $params);
                }
				break;
            case 'DELETE':
                $option[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                if (!empty($params)){
                    $url = $url . (strpos($url, '?') ? '&' : '?') . (is_array($params) ? http_build_query($params) : $params);
                }
                break;
		}
        
		$option[CURLOPT_URL] = $url;
		curl_setopt_array($curl, $option);		
        $returndata = curl_exec($curl);
        curl_close ($curl);
        return $returndata;
    }
}