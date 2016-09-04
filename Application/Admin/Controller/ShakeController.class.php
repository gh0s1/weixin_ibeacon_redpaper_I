<?php
namespace Admin\Controller;
use Admin\Controller;

/**
 * 摇一摇红包 */
class ShakeController extends BaseController {
	
	private $param;//创建红包活动 参数

	
	public  function index(){
		header("Content-type: text/html; charset= utf-8"); 
		$step = isset($_GET['step']) ? $_GET['step'] : 1;
		
		if($step  == 1){
			
			$this->display();
		}
		
		if($step == 2){
			if(isset($_GET['lottery_id'])){
				
				$this->assign("lottery_id",$_GET['lottery_id']);
				$this->display('shake/preorder');
			
			
			}else {
				
				echo "<script>alert('还未创建红包活动！！');history.go(-1)</script>";
				
			}
		}
	}
	
	
	public function initParam($data = array()){
		
		$this->param = $data;
		$this->param['onoff'] = 1;
		$this->param['sponsor_appid'] = C('APPID');
		$this->param['key'] = C('APISECRET');
		$this->param['jump_url'] = "";
		
		
		
	}
	
	
	//创建红包活动
	public function add_activity(){
		
		header("Content-type: text/html; charset=utf-8");   
		if(isset($_POST['subs'])){
			
			array_pop($_POST);
			/*
			$encode = mb_detect_encoding($_POST['title'],array('ASCII','UTF-8','GB2312','GBK','BIG5')); 
			echo $encode;
			echo "<br>"; 
			echo mb_strlen($_POST['title'],"UTF-8");
			return;
			*/
			if(strtotime($_POST['expire_time']) < strtotime($_POST['begin_time'])){
				
				echo "<script>alert('活动结束时间不能小于开始时间!!');history.go(-1)</script>";
				return ;
			}
			
			if((strtotime($_POST['expire_time'])-strtotime($_POST['begin_time'])) > 91*24*3600){
				
				echo "<script>alert('活动时间不能长于91小时!!');history.go(-1)</script>";
				return ;
			}
			
			//$_POST['title'] = mb_convert_encoding($_POST['title'],"GBK","UTF-8");
			$_POST['begin_time'] = strtotime($_POST['begin_time']);
			$_POST['expire_time']  = strtotime($_POST['expire_time']);
			$this->initParam($_POST);
			
			///print_r($this->param);
			
			$access_token = get_access_token();
			$url = "https://api.weixin.qq.com/shakearound/lottery/addlotteryinfo?access_token=$access_token&use_template=2";

			$data  = array( 
				//'title' => iconv("UTF-8","GBK",$this->param['title']),
				'title' => $this->param['title'],
				'desc' => $this->param['desc'],
				'begin_time' => $this->param['begin_time'],
				'expire_time' => $this->param['expire_time'],
				'onoff' => 1,
				'total' => $this->param['total'],
				'sponsor_appid' => $this->param['sponsor_appid'],
				'key' => $this->param['key'],
				'jump' => $this->param['jump_url']
			);
			

			$infos = https_request($url,json_encode($data));
			
			$infos = json_decode($infos,true);
			
			
			if(isset($infos['lottery_id'])){
				
				//将活动信息插入到数据表中
				
				$data = array();
				$data['lottery_id'] = $infos['lottery_id'];
				$data['title'] = $this->param['title'];
				$data['desc'] = $this->param['desc'];
				$data['begin_time'] = date("Y-m-d H:i:s",$this->param['begin_time']);
				$data['expire_time'] = date("Y-m-d H:i:s",$this->param['expire_time']); 
				$data['sponsor_appid'] = $this->param['sponsor_appid'];
				$data['jump_url'] = $this->param['jump_url'];
				$data['total'] = $this->param['total'];
				$data['creattime'] = time();
				
				$activity = M("shakeactivity");
				
				if($activity->add($data)){
					
					header("Location:".U('shake/preorder')."&lottery_id=".$data['lottery_id']);
				
				
				}else {
					
					 echo "<script>alert('添加红包活动失败!!');history.go(-1)</script>";
				}
				
			}else {
				$err = $infos['errmsg'];
				echo "<script>alert('".$err."');history.go(-1)</script>";
			}
			
		}
	}
	
	public function preorder(){
		header("Content-type: text/html; charset=utf-8");  
		if(isset($_POST['subs'])){
			array_pop($_POST);
			$pre = M("preorder");
			if($pre->add($_POST)){
				
				echo "<script>alert('设置成功!!!');history.go(-2)</script>";
			}else {
				
				echo "<script>alert('设置失败!!!');</script>";
			}
			
		}else {
			$lottery_id = $_GET['lottery_id'];
			$this->assign("lottery_id",$lottery_id);
			$this->display();
		}
	}
	

	
	//红包活动列表
	
	public function lists(){
		header("Content-type: text/html; charset=utf-8");  
		$act = M("shakeactivity");
		$lists= $act->alias("a")->join("tp_preorder p on a.lottery_id = p.lottery_id","LEFT")->select();
		$this->assign("lists",$lists);
		$this->display();
	}
}
	