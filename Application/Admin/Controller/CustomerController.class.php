<?php
	/*
		客户资料搜集设置页面
	*/

namespace Admin\Controller;
use Admin\Controller;

class CustomerController extends BaseController{ 

	public function index(){
		header("Content-type: text/html; charset= utf-8"); 
		$act = M("activity");
		$act_lists = $act->order("id desc")->select();
		$this->assign("act_lists",$act_lists);
		$this->display();
	}
	
	
	public function lists(){
		
		$cus = M("customer");
		$lists = $cus->order("id desc")->select();
		$this->assign("lists",$lists);
		$this->display();
	}
	
	
	public function set(){
		
		if(isset($_POST['subs'])){
			array_pop($_POST);
			$customer_preorder = M("customer_preorder");
			
			//首选查询表中是否存在lottery_id相同的设置
			$lottery_id = $_POST['lottery_id'];
			$where = array();
			$where['lottery_id'] = $lottery_id;
			$counts = $customer_preorder->where($where)->count();
			
			//插入操作
			if($counts == 0){
				$_POST['updatetime'] = time();
				if($customer_preorder->add($_POST)){
					
					echo "<script>alert('设置成功!');history.go(-1);</script>";
					
				}
			}
			
			//更新操作
			if($counts > 0){
				
				array_shift($_POST);
				$_POST['updatetime'] = time();
				$where = array();
				$where['lottery_id'] = $lottery_id;
				//去除值为空的元素
				foreach($_POST as $k => $v){
					
					if($v == ''){
						
						unset($_POST[$k]);
					}
				}
				if($customer_preorder->where($where)->save($_POST)){
					
					echo "<script>alert('设置成功!');history.go(-1);</script>";
				}
				
				
			}
		}
		
	}

}


?>