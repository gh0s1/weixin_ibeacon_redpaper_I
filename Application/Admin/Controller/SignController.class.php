<?php

/*
 *  ǩ������ҳ��
 */
namespace Admin\Controller;
use Admin\Controller;

class SignController extends BaseController{
	
	public function index(){
		
		header("Content-type: text/html; charset= utf-8"); 
		$act = M("activity");
		$act_lists = $act->order("id desc")->select();		
		$this->assign("act_lists",$act_lists);
		$this->display();
	}
	
	
	
	public function set(){
		
		if(isset($_POST['subs'])){
			
			array_pop($_POST);
			
			$_POST["timestamp"] = time();
			
			//����ǵ�һ������ �����  �������
			
			$sign = M("sign");
			$counts = $sign->field("id")->count();
			
			if($counts == 0){
				
				if($sign->add($_POST)){
					
					echo "<script>alert('���óɹ�!');history.go(-1)</script>";
				}
			}
			
			if($counts > 0){
				
				if($sign->where("id=1")->save($_POST)){
					
						echo "<script>alert('���³ɹ�!');history.go(-1)</script>";
				}
				
			}
			
		}
	}
	
	
	
	//ǩ���б�
	
	public function lists(){
		
		$user = M("shakeuser");
		$lists = $user->order("id desc")->select();
		$this->assign("lists",$lists);
		
		$this->display();
	}
	
}



