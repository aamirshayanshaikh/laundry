<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_orders extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('inventory/purchase_orders_helper');
	}
	public function index(){
		$data = $this->syter->spawn('purch_orders');
		$data['code'] = listPage(fa('fa-inbox')." Purchase Orders",'purchase_orders','purchase_orders/form','list','list',false);
		$this->load->view('list',$data);
	}
	public function form($id=null){
		$data = $this->syter->spawn('purch_orders');
		$data['page_title'] = fa('fa-inbox')." Purchase Orders";
		$data['page_subtitle'] = "Add New Purchase order";
		$det = array();
		$img = array();
		$details = array();
		// sess_initialize('details',$details);
		$new_ref = $this->site_model->get_next_ref(PURCHASE_ORDER_CODE);
		$today = $this->site_model->get_db_now('php',true);
		if($id != null){
			$result = $this->site_model->get_tbl('purchase_orders',array('id'=>$id));
			if($result){
				$det = $result[0];
				$data['page_subtitle'] = "Edit Purchase order ".ucwords(strtolower($det->reference));
				$join['materials'] = "purchase_order_details.mat_id = materials.id";
				$select = "purchase_order_details.*,materials.name as mat_name";
				$result_details = $this->site_model->get_tbl('purchase_order_details',array('order_id'=>$id),array(),$join,true,$select);
				foreach ($result_details as $res) {
					$details[] = array('cost' => $res->cost,	
					'cost_total_hid' => numInt($res->cost * $res->order_qty),	
					'ord_qty' => $res->order_qty,	
					'mat_id' => $res->mat_id,	
					'mat_name' => $res->mat_name);	
				}
			}			
		}
		sess_initialize('details',$details);
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-info btn-flat" href="'.base_url().'purchase_orders"','text'=>"<i class='fa fa-fw fa-table'></i> List");
		$data['code'] = form($det,$new_ref,$today);
		$data['load_js'] = 'inventory/purchase_orders';
		$data['use_js'] = 'form';
		$this->load->view('page',$data);
	}
	public function db(){
		$reference = $this->input->post('reference');
		$user = sess('user');
		$details = sess('details');
		if(count($details) == 0){
			echo json_encode(array('error'=>1,'msg'=>'Please add materials.',"id"=>''));
			return false;
		}
		if(!$this->input->post('id')){
			$check = $this->site_model->ref_unused(PURCHASE_ORDER_CODE,$reference);
			if(!$check){
				echo json_encode(array('error'=>1,'msg'=>'Reference '.$reference.' is already in use.',"id"=>''));
				return false;			
			}
		}

		$error = 0;
		$msg = "";
		$id = 0;
		$total_amount = 0;
		$total_qty = 0;
		foreach ($details as $lid => $row) {
			$total_amount += $row['ord_qty'] * $row['cost'];
			$total_qty += $row['ord_qty'];
		}
		$items = array(
			"reference" 	=> $reference,
			"order_date" 	=> date2Sql($this->input->post('order_date')),
			"total_amount" 	=> $total_amount,
			"total_qty" 	=> $total_qty,
			"supplier_id" 	=> $this->input->post('supplier_id'),
			"rcv_loc_id" 	=> $this->input->post('rcv_loc_id'),
			"memo"  		=> $this->input->post('memo'),
		);
		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('purchase_orders',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
			$this->site_model->save_ref(PURCHASE_ORDER_CODE,$reference);	
			$msg = "Added new Purchase Order Reference #".$reference;	
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('purchase_orders','id',$items,$id);
			$msg = "Updated Purchase Order Reference #".$reference;	
		}
		if($id){
			$this->site_model->delete_tbl('purchase_order_details',array('order_id'=>$id));
			$rows = array();
			foreach ($details as $lid => $row) {
				$rows[] = array(
					'order_id'	=>	$id,
					'mat_id'	=>	$row['mat_id'],
					'order_qty'	=>	$row['ord_qty'],
					'cost'		=>	$row['cost'],
					'total_cost'=>	$row['ord_qty'] * $row['cost'],
				);
			}
			$this->site_model->add_tbl_batch('purchase_order_details',$rows);
		}
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,"id"=>$id));
	}
}
