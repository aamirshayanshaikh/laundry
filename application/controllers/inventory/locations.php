<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Locations extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('inventory/locations_helper');
	}
	public function index(){
		$data = $this->syter->spawn('locs');
		$data['code'] = listPage(fa('fa-home')." Locations",'locations','locations/form','list','list',false);
		$this->load->view('list',$data);
	}
	public function moves(){
		$data = $this->syter->spawn('moves');
		$data['code'] = listPage(fa('fa-recycle')." Inventory Moves",'inventory_moves','','list','list',false);
		$this->load->view('list',$data);
	}
	public function form($id=null){
		$data = $this->syter->spawn('locs');
		$data['page_title'] = fa('fa-home')." Locations";
		$data['page_subtitle'] = "Add New Location";
		$det = array();
		$img = array();
		if($id != null){
			$details = $this->site_model->get_tbl('locations',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit Location ".ucwords(strtolower($det->name));
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'locations"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = location_form($det);
		$data['load_js'] = 'inventory/locations';
		$data['use_js'] = 'locations_form';
		$this->load->view('page',$data);
	}
	public function db($id=null){
		$user = sess('user');
		$items = array(
		    "name"=>$this->input->post('name'),
		    "address"=>$this->input->post('address'),
		    "contact_no"=>$this->input->post('contact_no'),
		    "contact_person"=>$this->input->post('contact_person'),
		);
		$error = 0;
		$msg = "";
		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('locations',$items);
			$msg = "Added New Location ".$items['name'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('locations','id',$items,$id);
			$msg = "Updated Location ".$items['name'];
		}
		if(!$this->input->post('rForm')){
			if($error == 0){
				site_alert($msg,'success');
			}
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,'items'=>$items,'id'=>$id));
	}
	public function qoh(){
		$data = $this->syter->spawn('qoh');
		$data['code'] = listPage(fa('fa-cubes')." Quantity on Hand",'qoh','','list','list',false);
		$this->load->view('list',$data);
	}
}
