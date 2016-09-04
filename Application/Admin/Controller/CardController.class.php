<?php
namespace Admin\Controller;
use Admin\Controller;
/**
 * 卡券管理
 */
class CardController extends BaseController{
	
	
	public function index(){
		
		$this->display();
	}
	
	
	
	//创建卡券
	public function create(){
		
		if($_POST['subs']){
			
			$step = $_POST['step'];
			
			if($step == "one"){
				
				$types = $_POST['types'];
				
				
			}
		}
		
	}
	
	
	
	//投放卡券
	public function uses(){
		if(isset($_POST['subs'])){
			$types = $_POST['types'];
			
			/*
			if(!isset($_COOKIE['api_ticket'])){
				$infos = $this->get_api_ticket();
				if($infos['errmsg'] == 'ok'){
					
					$api_ticket = $infos['ticket'];
					setcookie("api_ticket",$api_ticket,time()+7200,"/");
					
				}
			}else {
				
					$api_ticket = $_COOKIE['api_ticket'];
			}
			*/
			
			if($types == "qrcode"){
					
					header("Location:".U('card/qrcode'));
			
			}
			
			if($types == "h5"){
				
					header("Location:".U('card/h5'));
			}
			
			
			
		}else {
			
			$this->display();
		}
		
	}
	
	
	
	
	//二维码投放
	public function  qrcode(){
		
		if(isset($_POST['subs'])){

			$num = (int)$_POST['nums'];
			
			if($_POST['is_unique_code'] == 'true'){
				
				$is_unique_code = true;
			}
			
			if($_POST['is_unique_code'] == 'false'){
				
				$is_unique_code = false;
			}
			
			if($num == 1){
				
					$data = array(
					
						'action_name' => 'QR_CARD',
						'expire_seconds' => $_POST['expire_seconds'],
						'action_info' => array(
							'card' => array(
								'card_id' => $_POST['card_id'],
								'is_unique_code' => $is_unique_code,
								'outer_id' => 0
							)
						)
					);
					
					
			}
			
			if($num > 1){
				
				$card_list  = array();
				for($i=0;$i<$num;$i++){
					
					$card_list[$i] = array(
						'card_id' => $_POST['card_id'],
						'is_unique_code' => $is_unique_code,
						'outer_id' => 0
					);
				}
				$data = array(
					'action_name' => 'QR_MULTIPLE_CARD',
					'action_info' => array(
						'multiple_card' => array(
							'card_list' => $card_list
						)
					)
				);
				
			}
			
			$access_token = get_access_token();
			$url  = "https://api.weixin.qq.com/card/qrcode/create?access_token=$access_token";
			$infos = https_request($url,json_encode($data));
			
			$infos = json_decode($infos,true);

			if($infos['errcode'] == 0 && $infos['errmsg'] == 'ok'){
				
				$card_infos = $this->get_card_infos($_POST['card_id']);
				$index = strtolower($card_infos['card']['card_type']);
				$card_type = $card_infos['card']['card_type'];
				$logo_url = $card_infos['card'][$index]['base_info']['logo_url'];
				$brand_name = $card_infos['card'][$index]['base_info']['brand_name'];
				$title = $card_infos['card'][$index]['base_info']['title'];
				
				
				$qr_card = M("qr_card");
				$data = array();
				$data['brand_name'] = $brand_name;
				$data['title'] = $title;
				$data['card_type'] = $card_type;
				$data['logo_url'] = $logo_url;
				$data['nums'] = $_POST['nums'];
				$data['card_id'] = $_POST['card_id'];
				$data['ticket'] = $infos['ticket'];
				$data['url'] = $infos['url'];
				$data['expire_seconds'] = $infos['expire_seconds'];
				$data['show_qrcode_url'] = $infos['show_qrcode_url'];
				$data['is_unique_code'] = $is_unique_code;
				$data['timestamp'] = time();
				header("Content-type: text/html; charset=utf-8");        
				if(!$qr_card->add($data)){
					echo "<script>alert('投放失败!!!');history.go(-1)</script>";
				}else {
					
					echo "<script>alert('投放成功!!!');history.go(-1)</script>";
				}
				
			}
			
		}else {
			
			$infos = $this->list_card_ids(10);
			
			if($infos['errmsg'] == 'ok' && ($infos['total_num'] > 0)){
				
				$card_id_list = $infos['card_id_list'];
				
				
				$lists = array();
				foreach($card_id_list as $key => $val){
					
					$lists[$key]['card_id'] = $val;
					$card_infos = $this->get_card_infos($val);
					$index = strtolower($card_infos['card']['card_type']);
					$lists[$key]['title'] = $card_infos['card'][$index]['base_info']['title'];
				}
				
				$this->assign("lists",$lists);
			}
			

			
			$this->display();
		}
	}
	
	
	
	//h5投放
	public function h5(){
		
		if(isset($_POST['subs'])){
			
			
		}else {
			
			$infos = $this->list_card_ids(10);
			
			if($infos['errmsg'] == 'ok' && ($infos['total_num'] > 0)){
				
				$card_id_list = $infos['card_id_list'];
				
				
				$lists = array();
				foreach($card_id_list as $key => $val){
					
					$lists[$key]['card_id'] = $val;
					$card_infos = $this->get_card_infos($val);
					$index = strtolower($card_infos['card']['card_type']);
					$lists[$key]['title'] = $card_infos['card'][$index]['base_info']['title'];
				}
				
				$this->assign("lists",$lists);
			}
			$this->display();
		}
		
	}
	
	
	
	
	
	public function get_card_infos($card_id){
		
		$access_token = get_access_token();
		$url = "https://api.weixin.qq.com/card/get?access_token=$access_token";
		$data = array(
			'card_id' => $card_id
		);
		$infos = https_request($url,json_encode($data));
		return json_decode($infos,true);
	}
	
	
	
	
	public function list_card_ids($count){
		
		$access_token = get_access_token();
		$url = "https://api.weixin.qq.com/card/batchget?access_token=$access_token";
		$data = array(
			'offset' => 0,
			'count' => $count,
			'status_list' => array('CARD_STATUS_VERIFY_OK','CARD_STATUS_DISPATCH') 
		);
		
		
		$infos = https_request($url,json_encode($data));
		
		return json_decode($infos,true);
	}
	
	
	//核销卡券
	public function dels(){
		
		
		
	}
	
	
	public function get_api_ticket(){
		
		$access_token = get_access_token();
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=wx_card";
		$infos = https_request($url);
		$infos = json_decode($infos,true);
		return $infos;
		
	}
	
	
}


?>