<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('pages/admin_helper');
	}
	public function roles(){
		$data = $this->syter->spawn('roles');
		$data['code'] = listPage(fa('fa-black-tie')." Roles",'roles','admin/roles_form','list',false);
		$this->load->view('list',$data);
	}
	public function roles_form($id=null){
		$data = $this->syter->spawn('roles');
		$data['page_title'] = fa('fa-black-tie')." User Roles";
		$data['page_subtitle'] = "Add New User Role";
		$det = array();
		$img = array();
		if($id != null){
			$details = $this->site_model->get_tbl('user_roles',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit User Role ".$det->role;
			}
		}
		$navs = $this->syter->get_navs();
		$data['code'] = rolesForm($det,$navs);
		$data['load_js'] = 'pages/admin';
		$data['use_js'] = 'rolesFormJs';
		$this->load->view('page',$data);
	}
	public function roles_db(){
		$error = 0;
		$msg = "";

		$items = array(
			"role"		  => $this->input->post('role_name'),
			"description" => $this->input->post('description'),
		);
		$roles = $this->input->post('roles');
		$access = "";
		if(is_array($roles)){		
			foreach ($roles as $li) {
			    $access .= $li.",";
			}
			$access = substr($access,0,-1);
		}
		$items['access'] = $access;

		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('user_roles',$items);
			$msg = "Added New Role ".$items['role'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('user_roles','id',$items,$id);
			$msg = "Updated Role ".$items['role'];
		}
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg));
	}
	public function company($id=null){
		$data = $this->syter->spawn('company');
		$data['page_title'] = fa('fa-building-o')." Company";
		
		$prefs = $this->site_model->get_settings(null,"company");

		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['code'] = companyPage($prefs);
		$data['load_js'] = 'pages/admin';
		$data['use_js'] = 'companyFormJs';
		$this->load->view('page',$data);
	}
	public function company_db($id=null){
		$prefs = array(
		    "comp_name"=>$this->input->post('comp_name'),
		    "comp_tin"=>$this->input->post('comp_tin'),
		    "comp_email"=>$this->input->post('comp_email'),
		    "comp_contact_no"=>$this->input->post('comp_contact_no'),
		    "comp_address"=>$this->input->post('comp_address'),
		);
		$error = 0;
		$msg = "";

		foreach ($prefs as $code => $value) {
			$this->site_model->update_tbl('settings','code',array('value'=>$value),$code);
		}
		$msg = "Updated Company Settings.";
	

		$image = null;
		$ext = null;
		if(isset($_FILES['fileUpload'])){
		    if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
		        $info = pathinfo($_FILES['fileUpload']['name']);
		        if(isset($info['extension']))
		            $ext = $info['extension'];
		       
		        $newname = "logo.png";            
		        if (!file_exists("uploads/company/")) {
		            mkdir("uploads/company/", 0777, true);
		        }
		        $target = 'uploads/company/'.$newname;
		        if(!move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
		            $msg = "Image Upload failed";
		            $error = 1;
		        }
		        else{
		            $new_image = $target;
		            $this->site_model->update_tbl('settings','code',array('value'=>$new_image),'comp_logo');
		        }
		        ####
		    }
		}
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg));
	}
}
