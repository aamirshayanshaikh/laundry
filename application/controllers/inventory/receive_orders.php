<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Receive_orders extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('inventory/receive_orders_helper');
	}
	public function index(){
		$data = $this->syter->spawn('receive_orders_inq');
		$data['code'] = listPage(fa('fa-inbox')." Receive Orders",'receive_orders','','list','list',false);
		$data['load_js'] = 'inventory/receive_orders';
		$data['use_js'] = 'inquiry';
		$this->load->view('list',$data);
	}
	public function rec_view($id=null){
		$data = $this->syter->spawn('receive_orders_inq');
		$data['page_title'] = fa('fa-inbox')." Receive Order";
		$det = array();
		$det_rows = array();
		$img = array();
		$details = array();
		if($id != null){
			$join = array();
			$join['purchase_orders'] = "receive_orders.order_id = purchase_orders.id";
			$select = "receive_orders.*,purchase_orders.memo as order_memo,purchase_orders.supplier_id as supplier_id,purchase_orders.order_date as order_date";
			$result = $this->site_model->get_tbl('receive_orders',array('receive_orders.id'=>$id),array(),$join,true,$select);
			if($result){
				$det = $result[0];
				$join = array();
				$join['materials'] = "receive_order_details.mat_id = materials.id";
				$select = "receive_order_details.*,materials.name as mat_name";
				$det_rows = $this->site_model->get_tbl('receive_order_details',array('rcv_id'=>$id),array(),$join,true,$select);
			}			
		}
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-info btn-flat" href="'.base_url().'receive_orders"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = rec_view($det,$det_rows);
		$this->load->view('page',$data);
	}
	public function lists(){
		$data = $this->syter->spawn('receive_orders');
		$data['code'] = listPage(fa('fa-inbox')." Recieve Orders",'purchase_orders_receive','','list','list',false);
		$this->load->view('list',$data);
	}
	public function form($id=null){
		$data = $this->syter->spawn('receive_orders');
		$data['page_title'] = fa('fa-inbox')." Receive Orders";
		$det = array();
		$det_rows = array();
		$img = array();
		$details = array();
		$new_ref = $this->site_model->get_next_ref(RECEIVE_ORDER_CODE);
		$today = $this->site_model->get_db_now('php',true);
		if($id != null){
			$result = $this->site_model->get_tbl('purchase_orders',array('id'=>$id));
			if($result){
				$det = $result[0];
				$join['materials'] = "purchase_order_details.mat_id = materials.id";
				$select = "purchase_order_details.*,materials.name as mat_name";
				$det_rows = $this->site_model->get_tbl('purchase_order_details',array('order_id'=>$id),array(),$join,true,$select);
			}			
		}
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-download'></i> Receive");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-info btn-flat" href="'.base_url().'receive_orders/lists"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = form($det,$det_rows,$new_ref,$today);
		$data['load_js'] = 'inventory/receive_orders';
		$data['use_js'] = 'form';
		$this->load->view('page',$data);
	}
	public function db(){
		$this->load->model('inventory_model');
		$reference = $this->input->post('reference');
		$user = sess('user');
		
		if(!$this->input->post('id')){
			$check = $this->site_model->ref_unused(RECEIVE_ORDER_CODE,$reference);
			if(!$check){
				echo json_encode(array('error'=>1,'msg'=>'Reference '.$reference.' is already in use.',"id"=>''));
				return false;			
			}
		}
		$rcv_qty = $this->input->post('rcv_qtys');
		$rem_qtys = $this->input->post('rem_qtys');
		$ord_qtys = $this->input->post('ord_qtys');
		$total_rcv = 0;
		foreach ($rcv_qty as $mat_id => $val) {
			$total_rcv += $val;
		}
		if($total_rcv == 0){
			echo json_encode(array('error'=>1,'msg'=>'No qty to receive',"id"=>''));
			return false;
		}

		$error = 0;
		$msg = "";
		$id = 0;
		$order_id = $this->input->post('order_id');
		$loc_id = $this->input->post('rcv_loc_id');

		$items = array(
			"reference" 	=> $reference,
			"order_ref" 	=> $this->input->post('order_ref'),
			"order_id" 		=> $order_id,
			"loc_id" 		=> $loc_id,
			"total_rcv" 	=> $total_rcv,
			"receive_date" 	=> date2Sql($this->input->post('receive_date')),
			"memo"  		=> $this->input->post('memo'),
		);
		$id = $this->site_model->add_tbl('receive_orders',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
		if(!$this->input->post('id')){
			$this->site_model->save_ref(RECEIVE_ORDER_CODE,$reference);	
			$msg = "Added new Receive Order Reference #".$reference;	
			$this->site_model->update_tbl('purchase_orders',array('id'=>$order_id),array(),null,array('rcv_qty'=>'rcv_qty+'.$total_rcv));
			$rows = array();
			foreach ($rcv_qty as $mat_id => $val) {
				$rows[] = array(
					'rcv_id'	=>	$id,
					'mat_id'	=>	$mat_id,
					'rcv_qty'	=>	$val,
					'remain_qty'=>	$rem_qtys[$mat_id],
					'order_qty'	=>	$ord_qtys[$mat_id],
				);
				$this->site_model->update_tbl('purchase_order_details',array('order_id'=>$order_id,'mat_id'=>$mat_id),array(),null,array('rcv_qty'=>'rcv_qty+'.$val));
			}
			$this->site_model->add_tbl_batch('receive_order_details',$rows);
			foreach ($rcv_qty as $mat_id => $val) {
				$this->inventory_model->move_qty($mat_id,$loc_id,$val,RECEIVE_ORDER_CODE,$reference,date2Sql($this->input->post('receive_date')));
			}
		}
		
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,"id"=>$id));
	}
}
