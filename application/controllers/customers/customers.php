<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customers extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('customers/customers_helper');
	}
	public function index(){
		$data = $this->syter->spawn('customers');
		$data['code'] = listPage(fa('fa-users')." Customers",'customers','customers/form','grid','all',true);
		$this->load->view('list',$data);
	}
	public function form($id=null){
		$data = $this->syter->spawn('customers');
		$data['page_title'] = fa('fa-users')." Customers";
		$data['page_subtitle'] = "Add New Customers";
		$det = array();
		$img = array();
		$new_ref = $this->site_model->get_next_ref(CUSTOMER_CODE);
		if($id != null){
			$details = $this->site_model->get_tbl('customers',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit Customer ".$det->name;
				$resultIMG = $this->site_model->get_image(null,$det->id,'customers');
				if(count($resultIMG) > 0){
				    $img = $resultIMG[0];
				}
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'customers"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = form($det,$img,$new_ref);
		$data['load_js'] = 'customers/customers';
		$data['use_js'] = 'form';
		$this->load->view('page',$data);
	}
	public function db(){
		$user = sess('user');
		$items = array(
		    "code"=>$this->input->post('code'),
		    "name"=>$this->input->post('name'),
		    "contact_person"=>$this->input->post('contact_person'),
		    "contact_no"=>$this->input->post('contact_no'),
		    "email"=>$this->input->post('email'),
		    "address"=>$this->input->post('address'),
		);

		if(!$this->input->post('id')){
			$check = $this->site_model->ref_unused(CUSTOMER_CODE,$this->input->post('code'));
			if(!$check){
				echo json_encode(array('error'=>1,'msg'=>'Customer code '.$reference.' is already in use.',"id"=>''));
				return false;			
			}
		}

		$error = 0;
		$msg = "";
		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('customers',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
			$this->site_model->save_ref(CUSTOMER_CODE,$this->input->post('code'));
			$msg = "Added New Customer ".$items['name'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('customers','id',$items,$id);
			$msg = "Updated Customer ".$items['name'];
		}
		$image = null;
		$ext = null;
		if(isset($_FILES['fileUpload'])){
		    if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
		        $this->site_model->delete_tbl('images',array('img_tbl'=>'customers','img_ref_id'=>$id));
		        $info = pathinfo($_FILES['fileUpload']['name']);
		        if(isset($info['extension']))
		            $ext = $info['extension'];
		        $newname = $id.".png";            
		        $res_id = $id;
		        if (!file_exists("uploads/customers/")) {
		            mkdir("uploads/customers/", 0777, true);
		        }
		        $target = 'uploads/customers/'.$newname;
		        if(!move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
		            $msg = "Image Upload failed";
		            $error = 1;
		        }
		        else{
		            $new_image = $target;
		            $result = $this->site_model->get_image(null,$this->input->post('id'),'customers');
		            $items = array(
		                "img_path" => $new_image,
		                "img_file_name" => $newname,
		                "img_ref_id" => $id,
		                "img_tbl" => 'customers',
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
	// public function profile(){
	// 	$data = $this->syter->spawn('users');
	// 	$user = $data['user'];
	// 	$data['page_title'] = fa('fa-user')." ".ucwords(strtolower($user['full_name']));
	// 	$data['code'] = usersProfile($user);
	// 	$data['load_js'] = 'pages/users';
	// 	$data['use_js'] = 'usersProfileJs';
	// 	$this->load->view('page',$data);
	// }
	// public function pic_upload(){
	// 	$error = 0;
	// 	$msg = "";
	// 	$id = $this->input->post('id');
	// 	if($id){
	// 		if(isset($_FILES['fileUpload'])){
	// 		    if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){

	// 		        $this->site_model->delete_tbl('images',array('img_tbl'=>'users','img_ref_id'=>$id));
	// 		        $info = pathinfo($_FILES['fileUpload']['name']);
	// 		        if(isset($info['extension']))
	// 		            $ext = $info['extension'];
	// 		        $newname = $id.".png";            
	// 		        $res_id = $id;
	// 		        if (!file_exists("uploads/users/")) {
	// 		            mkdir("uploads/users/", 0777, true);
	// 		        }
	// 		        $target = 'uploads/users/'.$newname;
	// 		        if(!move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
	// 		            $msg = "Image Upload failed";
	// 		            $error = 1;
	// 		        }
	// 		        else{
	// 		            $new_image = $target;
	// 		            $result = $this->site_model->get_image(null,$this->input->post('id'),'users');
	// 		            $items = array(
	// 		                "img_path" => $new_image,
	// 		                "img_file_name" => $newname,
	// 		                "img_ref_id" => $id,
	// 		                "img_tbl" => 'users',
	// 		            );
	// 		            if(count($result) > 0){
	// 		                $this->site_model->update_tbl('images','id',$items,$result[0]->img_id);
	// 		            }
	// 		            else{
	// 		                $imgid = $this->site_model->add_tbl('images',$items,array('datetime'=>'NOW()'));
	// 		            }
	// 		            $msg = "Profile Picture Uploaded Successfully.";
	// 		        }
	// 		        ####
	// 		    }
	// 		}
	// 		###################################################
	// 	}
	// 	echo json_encode(array('error'=>$error,'msg'=>$msg));
	// 	###################################################
	// }
}
