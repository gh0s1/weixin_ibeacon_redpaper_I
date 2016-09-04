<?php
namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function _initialize(){
        $sid = session('adminId');
		
        //判断用户是否登陆
        if(!isset($sid ) ) {
            //redirect(U('Login/index'));
		
        }else {
			
			$cache = S("access_token");
			if(!$cache){
				
				S("access_token",get_access_token(),array('type'=>'file','expire'=>7200));
			}
		}

    }

}