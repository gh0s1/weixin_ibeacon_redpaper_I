<?php
namespace Admin\Controller;
use Admin\Controller;
use Extend;
/**
 * �豸����
 */
class DeviceController extends BaseController {
	
	
	public function index(){
		
		//��ѯ�豸�б�
		$access_token = S("access_token") ? S("access_token") : get_access_token();
		$url = "https://api.weixin.qq.com/shakearound/device/search?access_token=$access_token";
		
		$info = https_request($url);
		$data = array(
			'type' => 2,
			'last_seen' => 0,
			'count' => 50
		);
		
		$infos = https_request($url,json_encode($data));
		$infos = json_decode($infos,true);
		$devices = $infos['data']['devices'];
		
		//��ѯ�豸�б��е��ŵ� ��װ��������
		foreach($devices as $key => $val){
			
			foreach($val as $k => $v){
				if($k == 'poi_id'){
					
					$poi_id = $v;
					$access_token = S("access_token") ? S("access_token") : get_access_token();
					$url = "http://api.weixin.qq.com/cgi-bin/poi/getpoi?access_token=$access_token";
					$data = array(
						'poi_id' => intval($poi_id)
					);
					
					$infos = https_request($url,json_encode($data));
					
					$infos = json_decode($infos,true);
					$devices[$key]['business_name'] = $infos['business']['base_info']['business_name'];
				}
			}
		}
		
		
		
		//print_r($devices);
		$this->assign("devices",$devices);
		$this->display();
	}
	
	
	
	
	public function AddDevice(){
		
		/*
		$data = array();
		
		$poi_name = array_pop($_POST); //�ŵ�����
		
		foreach($_POST as $key => $val){
			
			$data[$key] = $val;
		}
		*/
		
		
		$data = array(
			'quantity' => intval($_POST['quantity']),
			'apply_reason' => $_POST['apply_reason'],
			'comment' => $_POST['comment'],
			'poi_id'  => intval($_POST['poi_id'])
		);
		
		$access_token = S("access_token") ? S("access_token") : get_access_token();
		$url = "https://api.weixin.qq.com/shakearound/device/applyid?access_token=$access_token";
		$infos = https_request($url,json_encode($data));
		$infos = json_decode($infos,true);
		
		$msg = array();
		if($infos['errcode'] == 0 && $infos['errmsg'] == 'success'){
			
			$msg['status'] = 'ok';
			//������ӵ��豸�������ݱ���ȥ
			//TODO
			
		}else {
			
			
			$msg['status'] = 'err';
		}
		
		
		echo json_encode($msg);
		
	}
	
	
	public function edit(){
		
		$this->display();
	
	}
	
	
	public function addform(){
		
		$access_token = S("access_token") ? S("access_token") : get_access_token();
		//��ȡ�ŵ��б�
		$lists = $this->list_pois();
		$pois_lists = $lists[business_list];
		//print_r($pois_lists);
		$this->assign("pois_lists",$pois_lists);
		$this->display();
	}
	
	
	
	//��ȡ�ŵ��б�
	public function list_pois(){
		
		$access_token = S("access_token") ? S("access_token") : get_access_token();
		$url = "https://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token=$access_token";
		$data = array(
			'begin' => 0,
			'limit' => 20
		);
		
		$infos = https_request($url,json_encode($data));
		
		return json_decode($infos,true);
	}
	
}