<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Site extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	##############################
	## DASHBOARD
		public function index(){
			$data = $this->syter->spawn('dashboard');
			$user = sess('user');
			$data['code'] = '';
			$this->load->view('page',$data);
		}
	##############################
	## LOGIN
		public function login(){
			$this->load->helper('login_helper');
			$prefs = $this->site_model->get_settings(null,"company");
			$data = $this->syter->spawn(null,false);
			$data['comp_name'] = $prefs['comp_name'];
			$data['code'] = loginPage($prefs);
			$data['load_js'] = 'site.php';
			$data['use_js'] = 'loginJs';
			$this->load->view('login',$data);
		}
		public function login_db(){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$user = $this->site_model->get_user_details(null,$username,$password);
			$error_msg = "";
			if(count($user) > 0){
				$img = base_url().'dist/img/avatar.png';
				$result = $this->site_model->get_image(null,$user->id,'users');
	            if(count($result) > 0){
	                $img = base_url().$result[0]->img_path;
	            }
				$session_details['user'] = array(
					"id"=>$user->id,
					"username"=>$user->username,
					"fname"=>$user->fname,
					"lname"=>$user->lname,
					"mname"=>$user->mname,
					"suffix"=>$user->suffix,
					"full_name"=>$user->fname." ".$user->mname." ".$user->lname." ".$user->suffix,
					"role_id"=>$user->user_role_id,
					"role"=>$user->user_role,
					"access"=>$user->access,
					"img"=>$img,
				);
				$this->session->set_userdata($session_details);
				$prefs = $this->site_model->get_settings(null,"company");
				$session_company['company'] = $prefs;
				$this->session->set_userdata($session_company);
			}
			else{
				$error_msg = "Username and password doesn't match.";
			}
			echo $error_msg;
		}
		public function logout(){
			$this->session->sess_destroy();
			redirect(base_url()."login",'refresh');
		}
	##############################	
		public function site_alerts(){
			$site_alerts = array();
			$alerts = array();
			if($this->session->userdata('site_alerts')){
				$site_alerts = $this->session->userdata('site_alerts');
			}

			foreach ($site_alerts as $alert) {
				$alerts[] = $alert;
			}
			echo json_encode(array("alerts"=>$alerts));
		}
		public function clear_site_alerts(){
			if($this->session->userdata('site_alerts'))
				$this->session->unset_userdata('site_alerts');
		}
}
