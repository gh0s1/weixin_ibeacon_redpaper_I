<?php
namespace Extend;
class Wx {
		
		const APPID = "wxc452fa15657e54bf";
		const APPKEY = "176e12b4581ef4aebc6143c61eac3f2c";
		
		private $access_token;
		
		public function __construct(){
		
			$this->access_token = $this->get_access_token();
		}
		
		
		
		
		/**
		 * 申请开通摇一摇周边功能
		 *
		 */
		public function open(){
			
			
			
		}
		
		
		/**
		 * 查询审核状态
		 *
		 *
		 */
		
		public function check(){
			
			$access_token = $this->access_token;
			$url = "https://api.weixin.qq.com/shakearound/account/auditstatus?access_token=$access_token";
			$status = json_decode($this->https_request($url));
			
			return rtrim($status->errmsg,'.');
			
		}
		  
		
		/**
		 *  新增设备页面
		 *  @ data 页面信息
		 */
		 
		 public function add_page($data = array()){
			 $access_token  = $this->get_access_token();
			 $url = "https://api.weixin.qq.com/shakearound/page/add?access_token=$access_token";
			 $infos = $this->https_request($url,$data);
			 return $infos;
		 }
		 
		 
		 /**
		  *  查看页面列表
		  *  @param $type 指定 是查询指定页面 还是查询一定范围的页面 
		  *  @param $data  要查询的页面编号
		  */
		  
		  public function get_list_page($type="",$data = array()){
			  
			  $access_token = $this->get_access_token();
			  $url = "https://api.weixin.qq.com/shakearound/page/search?access_token=$access_token";
			  if($type == 'set' || $type == ''){
				  
				  $data = array(
					'type'=> 1,
					'page_ids' => "[".implode(',',$data)."]"
				  );
			  }
			  
			  if($type == 'range'){
				  
				  $data = array(
					'type' => 2,
					'begin' => $data[0],
					'count' => $data[1]
				  );
			  }
			  
			  $infos = $this->https_request($url,json_encode($data));
			  return json_decode($infos,true);
			  
		  }
		 
		
		
		
		
		//获取access_token
		
		public function get_access_token(){
			$appid = self::APPID;
			$key = self::APPKEY;
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$key";
			$access_token = $this->https_request($url);
			$access_token = json_decode($access_token);
			$access_token = $access_token->access_token;
			return $access_token;
			
		}
		
		
		
		//新增永久素材
		
		public  function upload($filename){
			
			$access_token = $this->access_token;
			$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$access_token&type=image";

			$file_info=array(
				'filename'=> '/images/'.$filename,  //国片相对于网站根目录的路径
				'content-type'=>'image/png',  //文件类型
				'filelength'=>filesize($_SERVER['DOCUMENT_ROOT'].'/images/'.$filename)         //图文大小
			);
			
			$data= array(
				"media" => "@".$_SERVER['DOCUMENT_ROOT'].$file_info['filename'],
				"form-data"=>http_build_query($file_info)
			);

			$infos = $this->https_request($url,$data);
			$infos = json_decode($infos,true);
			if(isset($infos['type']) && isset($infos['media_id'])){
				
				return $infos;
			}
			
		}
		
		
		//获取临时素材列表
		public function get_media_list(){
			
			$access_token = $this->access_token;
			$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$access_token";
			$data = array(
				'type' => 'image',
				'offset' => 0,
				'count' => 20
			);
			$pic_lists = $this->https_request($url,json_encode($data));
			return json_decode($pic_lists,true);
		}
		
		
		
		
		//curl请求  post
		protected function https_request($url,$data = null){
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
}