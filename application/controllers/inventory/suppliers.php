<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Suppliers extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('inventory/suppliers_helper');
	}
	public function index(){
		$data = $this->syter->spawn('suppliers');
		$data['code'] = listPage(fa('fa-building-o')." Suppliers",'suppliers','suppliers/form','list','list',false);
		$this->load->view('list',$data);
	}
	public function form($id=null){
		$data = $this->syter->spawn('suppliers');
		$data['page_title'] = fa('fa-building-o')." Suppliers";
		$data['page_subtitle'] = "Add New Supplier";
		$det = array();
		$img = array();
		$new_ref = $this->site_model->get_next_ref(SUPPLIER_CODE);
		if($id != null){
			$details = $this->site_model->get_tbl('suppliers',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit Supplier ".ucwords(strtolower($det->name));
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'suppliers"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = supplier_form($det,$new_ref);
		$data['load_js'] = 'inventory/suppliers';
		$data['use_js'] = 'supplier_form';
		$this->load->view('page',$data);
	}
	public function db(){
		$user = sess('user');
		$items = array(
		    "code"=>$this->input->post('code'),
		    "name"=>$this->input->post('name'),
		    "tin"=>$this->input->post('tin'),
		    "contact_no"=>$this->input->post('contact_no'),
		    "address"=>$this->input->post('address'),
		);

		$error = 0;
		$msg = "";
		$id = 0;
		if(!$this->input->post('id')){
			$unused = $this->site_model->ref_unused(SUPPLIER_CODE,$this->input->post('code'));
			if($unused){
				$id = $this->site_model->add_tbl('suppliers',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
				$this->site_model->save_ref(SUPPLIER_CODE,$this->input->post('code'));			
				$msg = "Added supplier ".$items['name'];	
			}	
			else{
				$error = "Material Code ".$this->input->post('code')." is already in use";
			}
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('suppliers','id',$items,$id);
			$msg = "Updated supplier ".$items['name'];
		}		
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,"id"=>$id));
	}
}
