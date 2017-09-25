<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Items extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('work_order/items_helper');
	}
	public function index(){
		$data = $this->syter->spawn('wo_items');
		$data['code'] = listPage(fa('fa-cube')." Items",'items','items/form','list','list',false);
		$this->load->view('list',$data);
	}
	public function form($id=null){
		$data = $this->syter->spawn('wo_items');
		$data['page_title'] = fa('fa-cube')." Items";
		$data['page_subtitle'] = "Add New Item";
		$det = array();
		$img = array();
		$new_ref = $this->site_model->get_next_ref(ITEMS_CODE);
		if($id != null){
			$details = $this->site_model->get_tbl('items',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit Item ".ucwords(strtolower($det->name));
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'items"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = form($det,$new_ref);
		$data['load_js'] = 'work_order/items';
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
		    "uom"=>$this->input->post('uom'),
		);

		$error = 0;
		$msg = "";
		$id = 0;
		if(!$this->input->post('id')){
			$unused = $this->site_model->ref_unused(ITEMS_CODE,$this->input->post('code'));
			if($unused){
				$id = $this->site_model->add_tbl('items',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
				$this->site_model->save_ref(ITEMS_CODE,$this->input->post('code'));			
				$msg = "Added new item ".$items['name'];	
			}	
			else{
				$error = "Item Code ".$this->input->post('code')." is already in use";
			}
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('items','id',$items,$id);
			$msg = "Updated item ".$items['name'];
		}		
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,"id"=>$id));
	}
	public function categories(){
		$data = $this->syter->spawn('wo_items_cat');
		$data['code'] = listPage(fa('fa-cube')." Item Categories",'item_categories','items/categories_form','list','list',false);
		$this->load->view('list',$data);
	}
	public function categories_form($id=null){
		$data = $this->syter->spawn('wo_items_cat');
		$data['page_title'] = fa('fa-cube')." Items Categories";
		$data['page_subtitle'] = "Add New Items Category";
		$det = array();
		$img = array();
		if($id != null){
			$details = $this->site_model->get_tbl('item_categories',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit Items Category ".ucwords(strtolower($det->name));
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'items/categories"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = categories_form($det);
		$data['load_js'] = 'work_order/items';
		$data['use_js'] = 'categories_form';
		$this->load->view('page',$data);
	}
	public function categories_db($id=null){
		$user = sess('user');
		$items = array(
		    "name"=>$this->input->post('name'),
		    "uom"=>$this->input->post('uom'),
		);
		$error = 0;
		$msg = "";
		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('item_categories',$items);
			$msg = "Added new item category ".$items['name'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('item_categories','id',$items,$id);
			$msg = "Updated item category ".$items['name'];
		}
		if(!$this->input->post('rForm')){
			if($error == 0){
				site_alert($msg,'success');
			}
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,'items'=>$items,'id'=>$id));
	}
}
