<?php

		/**
		 * 工具函数库
		 *
		 */
		
		
		
		define("APPID","wxc452fa15657e54bf");
		define("APPKEY","176e12b4581ef4aebc6143c61eac3f2c");
		
		
		
		//随机串 16位
		function random_str( $length = 16 ) {  
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
			$str ="";  
			for ( $i = 0; $i < $length; $i++ )  {  
				$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
				//$str .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
			}  
			return $str;  
		}
			
		
		
		//array生成xml数据
		function array2xml($arr){
			
			$xml = "<xml>";
			foreach ($arr as $key=>$val)
			{
				
				 if (is_numeric($val))
				 {
					$xml.="<".$key.">".$val."</".$key.">"; 

				 }
				 else{
					$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
				 } 
				 
				 //$xml.="<".$key."><![CDATA[".$val."]]></".$key.">"; 
				 
			}
			$xml.="</xml>";
			return $xml; 
			
		}
		
		
		
		//sign
		function formatQueryParaMap($paraMap, $urlencode){
			$buff = "";
			ksort($paraMap);
			foreach ($paraMap as $k => $v){
				if (null != $v && "null" != $v && "sign" != $k) {
					if($urlencode){
					   $v = urlencode($v);
					}
					$buff .= $k . "=" . $v . "&";
				}
			}
			$reqPar;
			if (strlen($buff) > 0) {
				$reqPar = substr($buff, 0, strlen($buff)-1);
			}
			return $reqPar;
		}
		
		
		function xml_send($url,$xmldata){
			//第一种发送方式，也是推荐的方式：
			$header[] = "Content-type: text/xml";        //定义content-type为xml,注意是数组
			$ch = curl_init ($url);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata);
			$response = curl_exec($ch);
			if(curl_errno($ch)){
				print curl_error($ch);
			}
			return $response;
			curl_close($ch);
		}
		
		
		
		//获取access_token
		function get_access_token(){
			/*
			$appid = 'wxc452fa15657e54bf';
			$key = '176e12b4581ef4aebc6143c61eac3f2c';
			*/
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPKEY."";
			$access_token = https_request($url);
			$access_token = json_decode($access_token);
			$access_token = $access_token->access_token;
			return $access_token;
			
		}
		
		
		
		
		
		
		
		//curl请求  post
		function https_request($url,$data = null){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
			@curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
			if (!empty($data)){
				//var_dump($data);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}  
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($curl);
			curl_close($curl);
			return $output;
		}	

?>