<?php

/*
 *  签到设置页面
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
			
			//如果是第一次设置 则插入  否则更新
			
			$sign = M("sign");
			$counts = $sign->field("id")->count();
			
			if($counts == 0){
				
				if($sign->add($_POST)){
					
					echo "<script>alert('设置成功!');history.go(-1)</script>";
				}
			}
			
			if($counts > 0){
				
				if($sign->where("id=1")->save($_POST)){
					
						echo "<script>alert('更新成功!');history.go(-1)</script>";
				}
				
			}
			
		}
	}
	
	
	
	//签到列表
	
	public function lists(){
		
		$user = M("shakeuser");
		$lists = $user->order("id desc")->select();
		$this->assign("lists",$lists);
		
		$this->display();
	}
	
}



