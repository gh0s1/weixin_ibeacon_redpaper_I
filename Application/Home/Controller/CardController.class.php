<?php

/*
 *  卡券h5投放页面
 */
 
 
namespace Home\Controller;

use Think\Controller;

class CardController extends Controller{
	
	private $param = array(
		'code' => '',
		'openid' => '', 
		'timestamp' => '',
		'nonce_str' => '',
		'api_ticket' => '',
		'card_id' => ''
	);
	
	public function getshakeinfo($access_token,$ticket){
		$getshakeinfourl='https://api.weixin.qq.com/shakearound/user/getshakeinfo?access_token='.$access_token;
		$jo=0;
		if($access_token){
			$data=array('ticket' =>$ticket);
			$rd=https_request($getshakeinfourl,json_encode($data));
			$jo=json_decode($rd);
		}else{
			echo 'access_token null';
		}
		return $jo;
	}
	
	
	public function index(){
		
		header("Content-type: text/html; charset=utf-8");   
		
		$card_id = $_GET['card_id'];
		
		$ticket=$_GET['ticket'];
		
		$access_token = get_access_token();
		
		$shake = $this->getshakeinfo($access_token,$ticket);
		
		$openid=$shake->data->openid;
		
		
		$qr_card = M("qr_card");
		$where = array();
		$where['card_id'] = $card_id;
		$rs = $qr_card->where($where)->select();
		$rs = $rs[0];
		
		$this->param['timestamp'] = time();
		$this->param['card_id'] = $card_id;
		$this->param['nonce_str'] = random_str(32);
		$this->param['api_ticket'] = $this->get_api_ticket();
		
		$cardExt = array(
			'timestamp' => $this->param['timestamp'],
			'nonce_str' => $this->param['nonce_str'],
			'signature' => $this->get_signature($this->param),
			'outer_id' => 0
		);
		
		$cardExt = json_encode($cardExt);
		
		$sdk_signature_data = array(
			'timestamp' => time(),
			'noncestr' => random_str(32)
		);
		$this->assign("appId",C('APPID'));
		$this->assign("timestamp",$sdk_signature_data['timestamp']);
		$this->assign("nonceStr",$sdk_signature_data['noncestr']);
		
		$this->assign("signature",$this->get_js_signature($sdk_signature_data));
		
		$this->assign("card_id",$card_id);
		$this->assign("cardExt",$cardExt);
		
		$this->display();
		
	} 

	
	//JS SDK 签名
	public function get_js_signature($arr){
		
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		
		$js_ticket = $this->get_js_ticket();
		$data = array(
			'noncestr' => $arr['nonce_str'],
			'jsapi_ticket' => $js_ticket,
			'timestamp' => $arr['timestamp'],
			'url' => $url
		);
		
		ksort($data);
		
		$str = "";
		
		foreach($data as $key => $val){
			
			$str .= $key."=".$val."&amp;";
		}
		
		$str = rtrim($str,'&amp;');
		
		return sha1($str);
	}
	
	
	//卡券签名
	public function get_signature($data){
			
		ksort($data);
		$str = '';
		
		foreach($data as $val){
			
			$str .= $val;
		}
		
		$infos = sha1($str);
		
		return $infos;
	}
	

	
	
	
	public function get_js_ticket(){
		if(!isset($_COOKIE['access_token'])){
			
			$access_token = get_access_token();
			setcookie("access_token",$access_token,7200,'/');
		}else {
			
			$access_token = $_COOKIE['access_token'];
		}
		if(!isset($_COOKIE['js_ticket'])){
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
			$infos = json_decode(https_request($url),true);
			$js_ticket = $infos['ticket'];
			setcookie("js_ticket",$js_ticket,7200,'/');
			
			
		}else {
			
			$js_ticket = $_COOKIE['js_ticket'];
		}
		
		
		echo $js_ticket;
		return $js_ticket;
		
	}
	
	
	public function get_api_ticket(){
		
		$access_token = get_access_token();
		
		if(!isset($_COOKIE['api_ticket'])){
			
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=wx_card";
			$infos = json_decode(https_request($url),true);
			$api_ticket = $infos['api_ticket'];
			setcookie("api_ticket",$api_ticket,7200,'/');
			
			
		}else {
			
			$api_ticket = $_COOKIE['api_ticket'];
		}
		
		return $api_ticket;
		
	}	
}