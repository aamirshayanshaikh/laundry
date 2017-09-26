<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Work_order extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('work_order/work_order_helper');
	}
	public function receive(){
		$data = $this->syter->spawn('wo_rcv');
		$data['page_title'] = fa('fa-inbox')." Receive Items";		
		$items = array();
		$new_ref = $this->site_model->get_next_ref(WORK_ORDER_RECEIVE_CODE);
		$today = $this->site_model->get_db_now('php',true);

		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		// $data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'uom"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		sess_initialize('rcv-items',$items);
		$data['code'] = receive_form($new_ref,$today);
		$data['load_js'] = 'work_order/work_order';
		$data['use_js'] = 'receive_form';
		$this->load->view('page',$data);
	}
	public function receive_db(){
		$reference = $this->input->post('reference');
		$user = sess('user');
		$details = sess('rcv-items');
		if(count($details) == 0){
			echo json_encode(array('error'=>1,'msg'=>'Please add items.',"id"=>''));
			return false;
		}
		if(!$this->input->post('id')){
			$check = $this->site_model->ref_unused(WORK_ORDER_RECEIVE_CODE,$reference);
			if(!$check){
				echo json_encode(array('error'=>1,'msg'=>'Reference '.$reference.' is already in use.',"id"=>''));
				return false;			
			}
		}

		$error = 0;
		$msg = "";
		$id = 0;
		$rcv_qty = 0;
		foreach ($details as $lid => $row) {
			$rcv_qty += $row['rcv_qty'];
		}
		$items = array(
			"reference" 	=> $reference,
			"rcv_date" 		=> date2Sql($this->input->post('rcv_date')),
			"rcv_qty" 		=> $rcv_qty,
			"customer_id" 	=> $this->input->post('customer_id'),
			"memo"  		=> $this->input->post('memo'),
		);
		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('work_order_receives',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
			$this->site_model->save_ref(WORK_ORDER_RECEIVE_CODE,$reference);	
			$msg = "Added new Work Order Receive Items Reference #".$reference;	
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('work_order_receives','id',$items,$id);
			$msg = "Updated Work Order Receive Items Reference #".$reference;	
		}
		if($id){
			$this->site_model->delete_tbl('work_order_receive_items',array('rcv_id'=>$id));
			$rows = array();
			foreach ($details as $lid => $row) {
				$rows[] = array(
					'rcv_id'	=>	$id,
					'item_id'	=>	$row['item_id'],
					'rcv_qty'	=>	$row['rcv_qty'],
				);
			}
			$this->site_model->add_tbl_batch('work_order_receive_items',$rows);
		}
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,"id"=>$id));
	}	
	public function types(){
		$data = $this->syter->spawn('wo_types');
		$data['code'] = listPage(fa('fa-tags')." Types",'work_order_types','work_order/types_form','list','list',false);
		$this->load->view('list',$data);
	}
	public function types_form($id=null){
		$data = $this->syter->spawn('wo_types');
		$data['page_title'] = fa('fa-tags')." Stages";
		$data['page_subtitle'] = "Add New Type";
		$det = array();
		$img = array();
		$materials = array();
		$stages = array();
		$items = array();
		if($id != null){
			$details = $this->site_model->get_tbl('work_order_types',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit Type ".ucwords(strtolower($det->name));
				$join['materials'] = "work_order_type_materials.mat_id = materials.id";
				$select = "work_order_type_materials.*,materials.name as mat_name";
				$result_details = $this->site_model->get_tbl('work_order_type_materials',array('type_id'=>$id),array(),$join,true,$select);
				foreach ($result_details as $res) {
					$materials[] = array('cost' => $res->cost,	
					'cost_total_hid' => numInt($res->cost * $res->order_qty),	
					'ord_qty' => $res->order_qty,	
					'mat_id' => $res->mat_id,	
					'mat_name' => $res->mat_name);	
				}
				$join = array();
				$join['work_order_stages'] = "work_order_type_stages.stage_id = work_order_stages.id";
				$select = "work_order_type_stages.*,work_order_stages.name as stage_name";
				$result_details = $this->site_model->get_tbl('work_order_type_stages',array('type_id'=>$id),array(),$join,true,$select);
				foreach ($result_details as $res) {
					$stages[] = array(
									  'stage_id' => $res->stage_id,	
									  'stage_name' =>  $res->stage_name,	
									 );	
				}
				$join = array();
				$join['items'] = "work_order_type_items.item_id = items.id";
				$select = "work_order_type_items.*,items.name as item_name";
				$result_details = $this->site_model->get_tbl('work_order_type_items',array('type_id'=>$id),array(),$join,true,$select);
				foreach ($result_details as $res) {
					$items[] = array(
									  'item_id' => $res->item_id,	
									  'item_name' =>  $res->item_name,	
									 );	
				}

			}
		}
		sess_initialize('type-mats',$materials);
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'work_order/types"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = types_form($det,$stages,$items);
		$data['load_js'] = 'work_order/work_order';
		$data['use_js'] = 'types_form';
		$this->load->view('page',$data);
	}
	public function types_db($id=null){
		$user = sess('user');
		$materials = sess('type-mats');
		$items = array(
		    "name"=>$this->input->post('name'),
		    "uom"=>$this->input->post('uom'),
		    "description"=>$this->input->post('description'),
		);
		$error = 0;
		$msg = "";

		$stages = json_decode($_POST['stages']);
		$woitems = json_decode($_POST['items']);

		if(count($stages) == 0){
			echo json_encode(array('error'=>1,'msg'=>'Please add stages.',"id"=>''));
			return false;
		}
		if(count($woitems) == 0){
			echo json_encode(array('error'=>1,'msg'=>'Please add items.',"id"=>''));
			return false;
		}

		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('work_order_types',$items);
			$msg = "Added New Type ".$items['name'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('work_order_types','id',$items,$id);
			$msg = "Updated Type ".$items['name'];
		}
		if($id){
			$this->site_model->delete_tbl('work_order_type_materials',array('type_id'=>$id));
			$rows = array();
			foreach ($materials as $lid => $row) {
				$rows[] = array(
					'type_id'	=>	$id,
					'mat_id'	=>	$row['mat_id'],
					'order_qty'	=>	$row['ord_qty'],
					'cost'		=>	$row['cost'],
					'total_cost'=>	$row['ord_qty'] * $row['cost'],
				);
			}
			$this->site_model->add_tbl_batch('work_order_type_materials',$rows);
			$this->site_model->delete_tbl('work_order_type_stages',array('type_id'=>$id));
			$stgrows = array();
			foreach ($stages as $line => $stage_id) {
				$stgrows[] = array(
					'type_id'	=>	$id,
					'order'		=>	$line,
					'stage_id'	=>	$stage_id,
				);
			}
			$this->site_model->add_tbl_batch('work_order_type_stages',$stgrows);
			$this->site_model->delete_tbl('work_order_type_items',array('type_id'=>$id));
			$itrows = array();
			foreach ($woitems as $line => $item_id) {
				$itrows[] = array(
					'type_id'	=>	$id,
					'item_id'	=>	$item_id,
				);
			}
			$this->site_model->add_tbl_batch('work_order_type_items',$itrows);
		}
		if(!$this->input->post('rForm')){
			if($error == 0){
				site_alert($msg,'success');
			}
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,'items'=>$items,'id'=>$id));
	}
	public function stages(){
		$data = $this->syter->spawn('wo_stages');
		$data['code'] = listPage(fa('fa-refresh')." Stages",'work_order_stages','work_order/stages_form','list','list',false);
		$this->load->view('list',$data);
	}
	public function stages_form($id=null){
		$data = $this->syter->spawn('wo_stages');
		$data['page_title'] = fa('fa-refresh')." Stages";
		$data['page_subtitle'] = "Add New Stage";
		$det = array();
		$img = array();
		if($id != null){
			$details = $this->site_model->get_tbl('work_order_stages',array('id'=>$id));
			if($details){
				$det = $details[0];
				$data['page_subtitle'] = "Edit Stage ".ucwords(strtolower($det->name));
			}
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'work_order/stages"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = stages_form($det);
		$data['load_js'] = 'work_order/work_order';
		$data['use_js'] = 'stages_form';
		$this->load->view('page',$data);
	}
	public function stages_db($id=null){
		$user = sess('user');
		$items = array(
		    "name"=>$this->input->post('name'),
		    "description"=>$this->input->post('description'),
		);
		$error = 0;
		$msg = "";
		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('work_order_stages',$items);
			$msg = "Added New Stage ".$items['name'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('work_order_stages','id',$items,$id);
			$msg = "Updated Stage ".$items['name'];
		}
		if(!$this->input->post('rForm')){
			if($error == 0){
				site_alert($msg,'success');
			}
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,'items'=>$items,'id'=>$id));
	}
}
