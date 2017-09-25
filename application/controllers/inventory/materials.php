<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Materials extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('inventory/materials_helper');
	}
	public function index(){
		$data = $this->syter->spawn('mats');
		$data['code'] = listPage(fa('fa-cube')." Materials",'materials','materials/form','list','list',false);
		$this->load->view('list',$data);
	}
	public function form($id=null){
		$data = $this->syter->spawn('uom');
		$data['page_title'] = fa('fa-cube')." Materials";
		$data['page_subtitle'] = "Add New Material";
		$det = array();
		$img = array();
		$new_ref = $this->site_model->get_next_ref(MATERIAL_CODE);
		if($id != null){
			$details = $this->site_model->get_tbl('materials',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit Material ".ucwords(strtolower($det->name));
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'materials"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = form($det,$new_ref);
		$data['load_js'] = 'inventory/materials';
		$data['use_js'] = 'form';
		$this->load->view('page',$data);
	}
	public function db(){
		$user = sess('user');
		$items = array(
		    "code"=>$this->input->post('code'),
		    "name"=>$this->input->post('name'),
		    "description"=>$this->input->post('description'),
		    "cat_id"=>$this->input->post('cat_id'),
		    "type"=>$this->input->post('type'),
		    "uom"=>$this->input->post('uom'),
		    "cost"=>$this->input->post('cost'),
		    "tax_type_id"=>$this->input->post('tax_type_id'),
		);

		$error = 0;
		$msg = "";
		$id = 0;
		if(!$this->input->post('id')){
			$unused = $this->site_model->ref_unused(MATERIAL_CODE,$this->input->post('code'));
			if($unused){
				$id = $this->site_model->add_tbl('materials',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
				$this->site_model->save_ref(MATERIAL_CODE,$this->input->post('code'));			
				$msg = "Added new material ".$items['name'];	
			}	
			else{
				$error = "Material Code ".$this->input->post('code')." is already in use";
			}
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('materials','id',$items,$id);
			$msg = "Updated material ".$items['name'];
		}		
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,"id"=>$id));
	}
	public function categories(){
		$data = $this->syter->spawn('mat_cat');
		$data['code'] = listPage(fa('fa-cube')." Material Categories",'material_categories','materials/categories_form','list','list',false);
		$this->load->view('list',$data);
	}
	public function categories_form($id=null){
		$data = $this->syter->spawn('uom');
		$data['page_title'] = fa('fa-cube')." Material Categories";
		$data['page_subtitle'] = "Add New Material Category";
		$det = array();
		$img = array();
		if($id != null){
			$details = $this->site_model->get_tbl('material_categories',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit Material Category ".ucwords(strtolower($det->name));
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'materials/categories"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = categories_form($det);
		$data['load_js'] = 'inventory/materials';
		$data['use_js'] = 'categories_form';
		$this->load->view('page',$data);
	}
	public function categories_db($id=null){
		$user = sess('user');
		$items = array(
		    "name"=>$this->input->post('name'),
		    "uom"=>$this->input->post('uom'),
		    "type"=>$this->input->post('type'),
		    "tax_type_id"=>$this->input->post('tax_type_id'),
		);
		$error = 0;
		$msg = "";
		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('material_categories',$items);
			$msg = "Added new material category ".$items['name'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('material_categories','id',$items,$id);
			$msg = "Updated material category ".$items['name'];
		}
		if(!$this->input->post('rForm')){
			if($error == 0){
				site_alert($msg,'success');
			}
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,'items'=>$items,'id'=>$id));
	}
}
