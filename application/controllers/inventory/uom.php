<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Uom extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('inventory/uom_helper');
	}
	public function index(){
		$data = $this->syter->spawn('uom');
		$data['code'] = listPage(fa('fa-flask')." Unit Of Measures",'uom','uom/form','list','list',false);
		$this->load->view('list',$data);
	}
	public function form($id=null){
		$data = $this->syter->spawn('uom');
		$data['page_title'] = fa('fa-flask')." Unit Of Measures";
		$data['page_subtitle'] = "Add New UOM";
		$det = array();
		$img = array();
		if($id != null){
			$details = $this->site_model->get_tbl('uom',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit UOM ".ucwords(strtolower($det->name));
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'uom"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = uom_form($det);
		$data['load_js'] = 'inventory/uom';
		$data['use_js'] = 'uom_form';
		$this->load->view('page',$data);
	}
	public function db($id=null){
		$user = sess('user');
		$items = array(
		    "name"=>$this->input->post('name'),
		    "abbrev"=>$this->input->post('abbrev'),
		);
		$error = 0;
		$msg = "";
		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('uom',$items);
			$msg = "Added New UOM ".$items['name'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('uom','id',$items,$id);
			$msg = "Updated UOM ".$items['name'];
		}
		if(!$this->input->post('rForm')){
			if($error == 0){
				site_alert($msg,'success');
			}
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,'items'=>$items,'id'=>$id));
	}
}
