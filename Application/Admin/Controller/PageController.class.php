<?php
namespace Admin\Controller;
use Admin\Controller;
/**
 * 单页管理
 */
class PageController extends BaseController
{
    /**
     * 页面列表
     * @return [type] [description]
     */
    public function index()
    {
		/*
		$data = array(0,10);
		$infos = $this->get_list_page($type="range",$data);
		$page_list = $infos['data']['pages'];
		$this->assign("page_list",$page_list);
		*/
		
		$pages = M("pages");
		$page_list = $pages->order("id desc")->select();
		$this->assign("page_list",$page_list);
        $this->display();     
    }
	
	
	
	
	/**
	 * 添加页面 
	 */
	 
	public function add(){
		
		if(isset($_POST['subs'])){
			
			if(isset($_FILES)){
				
				$type = substr($_FILES['icon']['name'],strpos($_FILES['icon']['name'],'.')+1);
			
				$allow = array("jpg","png","jpeg","bmp","gif");
				if(in_array($type,$allow)){
					
					if($_FILES['icon']['error'] == 1 || $_FILES['icon']['error'] == 2){
						
						echo "<script>alert('上传的图片太大 建议上传120px*120px大小的图片!');history.go(-1)</script>";
						
						return;
					}
					
					$kbs = $_FILES['icon'][size]/1024;
					
					if($kbs > 1024){
						
						echo "<script>alert('上传的图片太大 建议上传120px*120px  大小不超过1M的图片!');history.go(-1)</script>";
						
						return ;
					}
					
					if(is_uploaded_file($_FILES['icon']['tmp_name'])){
						
						$upload_path = $_SERVER['DOCUMENT_ROOT'].'/upload/';
						
						$filename = date("YmdHis",time()).'.'.$type;
						
						$newfile = $upload_path.$filename;
						
						if(!move_uploaded_file($_FILES['icon']['tmp_name'],$newfile)){
							
							echo "<script>alert('图片上传失败!');history.go(-1)</script>";
						}
						
				
						
						//增加素材
						$infos = $this->upload($filename); 
						
						$icon_url = $infos['data']['pic_url'];
						
						

						$data = array(
							"title" => $_POST['title'],
							"description" => $_POST['description'],
							"page_url" => $_POST['page_url'],
							"comment" => $_POST['comment'],
							"icon_url" => "$icon_url"
						);
			
						$pageinfos = $this->add_page($data);
						$pageinfos = json_decode($pageinfos,true);
						
	
						if($pageinfos['errcode'] == 0 && isset($pageinfos['data']['page_id'])){
						
							//将页面信息插入到数据表中
							$data['page_id'] = $pageinfos['data']['page_id'];
							$data['device_num'] = 0;
							$data['createtime'] = time();
							
							$pages = M("pages");
							
							if(!$pages->add($data)){
								
								echo "<script>alert('插入数据失败!');history.go(-1)</script>";
							}else {
								
								echo "<script>alert('增加页面成功!!');history.go(-1)</script>";
							}
							
						}else {
							
							echo "<script>alert('".$pageinfos['errmsg']."');history.go(-1);</script>";
						}
						
					}
					
					
					
					
				}else {
					
					echo "不是一个合法的图片文件!";
				}
			
			}
		}
		
	}
	
	
	
	//新增素材
		
	public  function upload($filename){
		
		$access_token = get_access_token();
		$url = "https://api.weixin.qq.com/shakearound/material/add?access_token=$access_token&type=icon";
		
		$file_info=array(
			'filename'=> '/upload/'.$filename,  //国片相对于网站根目录的路径
			'content-type'=>'image/png',  //文件类型
			'filelength'=>filesize($_SERVER['DOCUMENT_ROOT'].'/upload/'.$filename)         //图文大小
		);
		
		$data= array(
			"media" => "@".$_SERVER['DOCUMENT_ROOT'].$file_info['filename'],
			"form-data"=>http_build_query($file_info)
		);
		
		
		
		
		$infos = https_request($url,$data);
		$infos = json_decode($infos,true);
		if(isset($infos['data']['pic_url'])){
			return $infos;
		}
	}
	
	

	
	
	
	
	public function add_page($data){
		
		 $access_token = get_access_token();
		 $url = "https://api.weixin.qq.com/shakearound/page/add?access_token=$access_token";
		 $infos = https_request($url,json_encode($data));
		 return $infos;
	}
	
	
	
	/**
	  *  查看页面列表
	  *  @param $type 指定 是查询指定页面 还是查询一定范围的页面 
	  *  @param $data  要查询的页面编号
	  */
		  
	public function get_list_page($type="",$data = array()){
	  
	 
	 //$access_token = get_access_token();
	 $access_token = S("access_token");
	  $url = "https://api.weixin.qq.com/shakearound/page/search?access_token=$access_token";
	  if($type == 'set' || $type == ''){
		  
		  $data = array(
			'type'=> 1,
			'page_ids' => "[".implode(',',$data)."]"
		  );
	  }
	  
	  if($type == 'range'){
		  
		  $data = array(
			'type' => 2,
			'begin' => $data[0],
			'count' => $data[1]
		  );
	  }
	  
	  $infos = https_request($url,json_encode($data));
	  return json_decode($infos,true);
	  
	  
	}
	
	public function addform(){
		
		$this->display();
	}
	
	
	
	

	//页面编辑
	
	public function edit_page($data){
		
		$access_token = get_access_token();
		$url = "https://api.weixin.qq.com/shakearound/page/update?access_token=$access_token";
		$infos = https_request($url,json_encode($data));
		$infos = json_decode($infos,true);
		return $infos;
		
	}

	
	public function editform(){
		
		if(isset($_POST['subs'])){
			
			if(isset($_FILES)){
				
				$type = substr($_FILES['icon']['name'],strpos($_FILES['icon']['name'],'.')+1);
			
				$allow = array("jpg","png","jpeg","bmp","gif");
				if(in_array($type,$allow)){
					
					if($_FILES['icon']['error'] == 1 || $_FILES['icon']['error'] == 2){
						
						echo "<script>alert('上传的图片太大 建议上传120px*120px大小的图片!');history.go(-1)</script>";
						
						return;
					}
					
					$kbs = $_FILES['icon'][size]/1024;
					
					if($kbs > 1024){
						
						echo "<script>alert('上传的图片太大 建议上传120px*120px  大小不超过1M的图片!');history.go(-1)</script>";
						
						return ;
					}
					
					if(is_uploaded_file($_FILES['icon']['tmp_name'])){
						
						$upload_path = $_SERVER['DOCUMENT_ROOT'].'/upload/';
						
						$filename = date("YmdHis",time()).'.'.$type;
						
						$newfile = $upload_path.$filename;
						
					
						if(!move_uploaded_file($_FILES['icon']['tmp_name'],$newfile)){
							
							echo "<script>alert('图片上传失败!');history.go(-1)</script>";
						}
						
						//增加素材
						$infos = $this->upload($filename); 
						
				
	
						
						$icon_url = $infos['data']['pic_url'];
						
						
						$data = array(
							"page_id" => (int)$_POST['page_id'],
							"title" => $_POST['title'],
							"description" => $_POST['description'],
							"page_url" => $_POST['page_url'],
							"comment" => $_POST['comment'],
							"icon_url" => "$icon_url"
						);
						
		
						$pageinfos = $this->edit_page($data);
	
						if($pageinfos['errcode'] == 0){
						
							//将页面信息更新到数据表中
							$data['createtime'] = time();
							$page_id = $data['page_id'];
							unset($data['page_id']);
							$pages = M("pages");
							
							if(!$pages->where("page_id=".$page_id)->save($data)){
								
								echo "<script>alert('更新数据失败!');history.go(-1)</script>";
							}else {
								
								echo "<script>alert('更新数据成功!!');history.go(-1)</script>";
							}
							
						}else {
							
							echo "<script>alert('".$pageinfos['errmsg']."');history.go(-1);</script>";
						}
						
					}
					
					
					
					
				}else {
					
					echo "不是一个合法的图片文件!";
				}
			
			}

			
		}else {
		
			$page_id = $_GET['page_id'];
			$pages = M("pages");
			$data = $pages->where("page_id=".$page_id)->select();
			$data = $data[0];
			$this->assign("page_id",$page_id);
			$this->assign("data",$data);
			$this->display();
		}
		
	}
	
	//删除页面
	public function del_page(){
		$page_id = $_POST['page_id'];
		$access_token = get_access_token();
		$url = "https://api.weixin.qq.com/shakearound/page/delete?access_token=$access_token";
		$data = array(
			'page_id' => (int)$page_id
		);
		
		//从数据库中删除相关页面
		$pages = M("pages");
		$where = array();
		$where['page_id'] = $page_id;
		if($pages->where($where)->delete()){
			$infos = https_request($url,json_encode($data));
			echo $infos;
		}else {
			$infos = array(
				'errmsg' => 'false.'
			);
			
			echo json_encode($infos);
		}
	}
	

}
