<?php

/*
 *  摇一摇红包入口页面
 */
namespace Home\Controller;

use Think\Controller;

define("DS",DIRECTORY_SEPARATOR);

class IndexController extends Controller{

	
	private $preparam = array(
			'nonce_str' =>  "",
			'mch_billno' => "",
			'mch_id' => "",
			'wxappid' => "",
			'send_name' => "",
			'hb_type' => "",
			'total_amount' => "",
			'total_num' => "",
			'amt_type' => "",
			'wishing' => "",
			'act_name' => "",
			'remark' => "",
			'auth_mchid' => "",
			'auth_appid' => "",
			'risk_cntl' => "",
			'sign' => ""
		);

	
	

    public function index(){
		
		header("Content-type: text/html; charset=utf-8");   
		//$lottery_id = "IIe5KOcR33C-qVP4jhQWYg"; 
		$lottery_id = $_GET['lottery_id'];
		//$status = $this->checkhb($lottery_id);

		//print_r($status);
		//return;
		$ticket=$_GET['ticket'];//获叏设备信息，包括 U UID 、 major 、 minor ，以及距离、 openID 等信息
		
		$access_token = get_access_token();
		
		$shake = $this->getshakeinfo($access_token,$ticket);
		
		$openid=$shake->data->openid;
		
		$pre = M("preorder");
		$where['lottery_id'] = $lottery_id;
		$rs = $pre->where($where)->select();
		$rs = $rs[0];
		$this->initPreparam($rs);
		
	
		$infos = $this->preorder();


		//$infos=json_decode(json_encode(simplexml_load_string($infos)),true);
		//$infos=json_decode(json_encode($infos),true);
		//$infos = simplexml_load_string($infos);
			
		$infos=simplexml_load_string($infos,'SimpleXMLElement',LIBXML_NOCDATA);
		


		$sp_ticket  = (array)$infos->sp_ticket;
		
		$data=array(
			'lottery_id' =>$lottery_id, //之前创建的活动id
			'mchid'=>C('mch_id'),
			'sponsor_appid'=>C('APPID'),
			'prize_info_list'=>array(array('ticket' =>$sp_ticket[0])),
		);
		//录入红包到红包活动中去

		

		$infos = $this->add2act($data);
		

		
		// 调用接口参数集合
		$params = array(
		  'lottery_id' => $lottery_id,//活动id
		  'noncestr' =>'',
		  'openid' => '',
		  'sign' => '',
		);  
		$params['noncestr']=random_str(32);
		$params['openid']=$openid;
		$params['sign']=$this->get_sign($params);
		
		$this->assign("lottery_id",$params['lottery_id']);
		$this->assign("noncestr",$params['noncestr']);
		$this->assign("openid",$params['openid']);
		$this->assign("sign",$params['sign']);
		
		
		$this->display();
		
    }
	
	
	
	//红包预下单
	public function preorder(){
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/hbpreorder";
		$param = $this->preparam;

		$xml  = array2xml($param);
		//return  simplexml_load_string($this->curl_post_ssl($url,$xml,'SimpleXMLElement', LIBXML_NOCDATA));
		return  $this->curl_post_ssl($url,$xml);
	}
	
	
	
	//录入红包到红包活动中去
	public function add2act($data = array()){
		
		$access_token = get_access_token();
		
		$url = "https://api.weixin.qq.com/shakearound/lottery/setprizebucket?access_token=$access_token";
		
		$infos = https_request($url,json_encode($data));
		
		return $infos;
	}
	
	
	
	
	//获取摇一摇周边信息
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
	
	
	
	public function initPreparam($data = array()){
		
		$this->preparam['nonce_str'] =  random_str();
		$this->preparam['mch_billno'] = $this->get_mch_billno();
		$this->preparam['mch_id'] = C('mch_id');
		$this->preparam['wxappid'] = C('APPID');
		$this->preparam['send_name'] = $data['send_name'];
		$this->preparam['hb_type'] = $data['hb_type'];
		if($data['jetype'] == 'sj'){
			$this->preparam['total_amount'] = mt_rand($data['min'],$data['max']);
		}
		
		if($data['jetype'] == 'gd'){
			$this->preparam['total_amount'] = $data['signle_total'];
		}
		
		if($data['hb_type'] == 'GROUP'){
			$this->preparam['total_num'] = $data['total_num'];
		}
		
		if($data['hb_type'] == 'NORMAL'){
			$this->preparam['total_num'] = 1;
		}
		$this->preparam['amt_type'] = "ALL_RAND";
		$this->preparam['wishing'] = $data['wishing'];
		$this->preparam['act_name'] = $data['act_name'];
		$this->preparam['remark'] = $data['remark'];
		$this->preparam['auth_mchid'] = C('auth_mchid');
		$this->preparam['auth_appid'] = C('auth_appid');
		$this->preparam['risk_cntl'] = "NORMAL";
		$this->preparam['sign'] = $this->get_sign($this->preparam);
		
		//ksort($this->preparam);

		
	}
	
	public function get_sign($param){
		$unSignParaStr = formatQueryParaMap($param,false);
		$signStr=$unSignParaStr."&key=".C('APISECRET');
		return strtoupper(md5($signStr));
	}
	
	
	public function get_mch_billno(){
		
		$mch_id = C('mch_id');
		$rand = random_str(10);
		$mch_billno = $mch_id.date("Ymd").$rand;
		
		return $mch_billno; 
	}
	
	
	public function curl_post_ssl($url, $vars, $second=30,$aHeader=array()){
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		
		//以下两种方式需选择一种
		//第一种方法，cert 与 key 分别属于两个.pem文件
		curl_setopt($ch,CURLOPT_SSLCERT,getcwd().DS.'cert'.DS.'apiclient_cert.pem');
		curl_setopt($ch,CURLOPT_SSLKEY,getcwd().DS.'cert'.DS.'apiclient_key.pem');
		curl_setopt($ch,CURLOPT_CAINFO,getcwd().DS.'cert'.DS.'rootca.pem');
		//第二种方式，两个文件合成一个.pem文件
		//curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
	 
		if( count($aHeader) >= 1 ){
			$header[] = "Content-type: text/xml"; 
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
	 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		}
		else { 
			$error = curl_errno($ch);
			echo "call faild, errorCode:$error\n"; 
			curl_close($ch);
			return false;
		}
	}
	
	
	//红包查询
	
	public function checkhb($lottery_id){
		$access_token = S("access_token") ? S("access_token") : get_access_token();
		$url = "https://api.weixin.qq.com/shakearound/lottery/querylottery?access_token=$access_token&lottery_id=$lottery_id";
		$infos  = https_request($url);
		return $infos;
	}
	
	
}
