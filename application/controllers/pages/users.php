<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('pages/users_helper');
	}
	public function index(){
		$data = $this->syter->spawn('users');
		$data['code'] = listPage(fa('fa-users')." Users",'users','users/form','grid','all',true);
		$this->load->view('list',$data);
	}
	public function form($id=null){
		$data = $this->syter->spawn('users');
		$data['page_title'] = fa('fa-users')." Users";
		$data['page_subtitle'] = "Add New User";
		$det = array();
		$img = array();
		if($id != null){
			$details = $this->site_model->get_tbl('users',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit user ".$det->fname." ".$det->mname." ".$det->lname." ".$det->suffix;
				$resultIMG = $this->site_model->get_image(null,$det->id,'users');
				if(count($resultIMG) > 0){
				    $img = $resultIMG[0];
				}
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'users"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = usersPage($det,$img);
		$data['load_js'] = 'pages/users';
		$data['use_js'] = 'usersFormJs';
		$this->load->view('page',$data);
	}
	public function db($id=null){
		$items = array(
		    "username"=>$this->input->post('username'),
		    "fname"=>$this->input->post('fname'),
		    "mname"=>$this->input->post('mname'),
		    "lname"=>$this->input->post('lname'),
		    "role"=>$this->input->post('role'),
		    "suffix"=>$this->input->post('suffix'),
		    "contact_no"=>$this->input->post('contact_no'),
		    "email"=>$this->input->post('email'),
		    "inactive"=>(int)$this->input->post('inactive'),
		);
		$error = 0;
		$msg = "";
		if(!$this->input->post('id')){
		    $items["password"] = md5($this->input->post('password'));
			$id = $this->site_model->add_tbl('users',$items,array('reg_date'=>'NOW()'));
			$msg = "Added New User ".$items['fname']." ".$items['mname']." ".$items['lname']." ".$items['suffix'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('users','id',$items,$id);
			$msg = "Updated User ".$items['fname']." ".$items['mname']." ".$items['lname']." ".$items['suffix'];
		}
		$image = null;
		$ext = null;
		if(isset($_FILES['fileUpload'])){
		    if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
		        $this->site_model->delete_tbl('images',array('img_tbl'=>'users','img_ref_id'=>$id));
		        $info = pathinfo($_FILES['fileUpload']['name']);
		        if(isset($info['extension']))
		            $ext = $info['extension'];
		        $newname = $id.".png";            
		        $res_id = $id;
		        if (!file_exists("uploads/users/")) {
		            mkdir("uploads/users/", 0777, true);
		        }
		        $target = 'uploads/users/'.$newname;
		        if(!move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
		            $msg = "Image Upload failed";
		            $error = 1;
		        }
		        else{
		            $new_image = $target;
		            $result = $this->site_model->get_image(null,$this->input->post('id'),'users');
		            $items = array(
		                "img_path" => $new_image,
		                "img_file_name" => $newname,
		                "img_ref_id" => $id,
		                "img_tbl" => 'users',
		            );
		            if(count($result) > 0){
		                $this->site_model->update_tbl('images','id',$items,$result[0]->img_id);
		            }
		            else{
		                $imgid = $this->site_model->add_tbl('images',$items,array('datetime'=>'NOW()'));
		            }
		        }
		        ####
		    }
		}
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg));
	}
	public function profile(){
		$data = $this->syter->spawn('users');
		$user = $data['user'];
		$data['page_title'] = fa('fa-user')." ".ucwords(strtolower($user['full_name']));
		$data['code'] = usersProfile($user);
		$data['load_js'] = 'pages/users';
		$data['use_js'] = 'usersProfileJs';
		$this->load->view('page',$data);
	}
	public function pic_upload(){
		$error = 0;
		$msg = "";
		$id = $this->input->post('id');
		if($id){
			if(isset($_FILES['fileUpload'])){
			    if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){

			        $this->site_model->delete_tbl('images',array('img_tbl'=>'users','img_ref_id'=>$id));
			        $info = pathinfo($_FILES['fileUpload']['name']);
			        if(isset($info['extension']))
			            $ext = $info['extension'];
			        $newname = $id.".png";            
			        $res_id = $id;
			        if (!file_exists("uploads/users/")) {
			            mkdir("uploads/users/", 0777, true);
			        }
			        $target = 'uploads/users/'.$newname;
			        if(!move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
			            $msg = "Image Upload failed";
			            $error = 1;
			        }
			        else{
			            $new_image = $target;
			            $result = $this->site_model->get_image(null,$this->input->post('id'),'users');
			            $items = array(
			                "img_path" => $new_image,
			                "img_file_name" => $newname,
			                "img_ref_id" => $id,
			                "img_tbl" => 'users',
			            );
			            if(count($result) > 0){
			                $this->site_model->update_tbl('images','id',$items,$result[0]->img_id);
			            }
			            else{
			                $imgid = $this->site_model->add_tbl('images',$items,array('datetime'=>'NOW()'));
			            }
			            $msg = "Profile Picture Uploaded Successfully.";
			        }
			        ####
			    }
			}
			###################################################
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg));
		###################################################
	}
}
