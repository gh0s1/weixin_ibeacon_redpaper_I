<?php
	/*
		�ͻ������Ѽ�����ҳ��
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
			
			//��ѡ��ѯ�����Ƿ����lottery_id��ͬ������
			$lottery_id = $_POST['lottery_id'];
			$where = array();
			$where['lottery_id'] = $lottery_id;
			$counts = $customer_preorder->where($where)->count();
			
			//�������
			if($counts == 0){
				$_POST['updatetime'] = time();
				if($customer_preorder->add($_POST)){
					
					echo "<script>alert('���óɹ�!');history.go(-1);</script>";
					
				}
			}
			
			//���²���
			if($counts > 0){
				
				array_shift($_POST);
				$_POST['updatetime'] = time();
				$where = array();
				$where['lottery_id'] = $lottery_id;
				//ȥ��ֵΪ�յ�Ԫ��
				foreach($_POST as $k => $v){
					
					if($v == ''){
						
						unset($_POST[$k]);
					}
				}
				if($customer_preorder->where($where)->save($_POST)){
					
					echo "<script>alert('���óɹ�!');history.go(-1);</script>";
				}
				
				
			}
		}
		
	}

}


?>