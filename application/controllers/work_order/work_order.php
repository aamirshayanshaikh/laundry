<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Work_order extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('work_order/work_order_helper');
	}
	public function index(){
		$data = $this->syter->spawn('wo_staging');
		$data['code'] = listPage(fa('fa-ticket')." Work Orders",'work_orders','work_order/create','list','list',false);
		$this->load->view('list',$data);
	}
	public function history($wo_id=null){
		$data = $this->syter->spawn('wo_staging');
		$wo = array();
		$select = 'work_orders.*,work_order_types.name as type_name';
		$join = array('work_order_types'=>"work_orders.type_id = work_order_types.id");
		$wo_order = $this->site_model->get_tbl('work_orders',array('work_orders.id'=>$wo_id),array(),$join,true,$select);	
		if($wo_order)
			$wo = $wo_order[0];
		else{
			redirect(base_url().'work_order');
			site_alert('Work Order not found.','error');
		}
		$select = "work_order_materials.*,materials.name as mat_name,materials.uom as mat_uom";
		$join = array('materials'=>"work_order_materials.mat_id = materials.id");
		$wo_mats = $this->site_model->get_tbl('work_order_materials',array('wo_id'=>$wo_id),array(),$join,true,$select);	

		$join = array();
		$select = "work_orders_staging.*,work_order_stages.name as stage_name,";
		$join['work_order_stages'] = "work_orders_staging.stage_id = work_order_stages.id";
		$stagings = $this->site_model->get_tbl('work_orders_staging',array('wo_id'=>$wo_id),array('order'=>'desc'),$join,true,$select);
		$stg_ids = array();
		foreach ($stagings as $stg) {
			$stg_ids[] = $stg->id;
		}
		$stagings_mats = array();
		if(count($stg_ids) > 0){
			$join = array();
			$select = "work_orders_staging_materials.*,materials.name as mat_name,materials.uom as mat_uom";
			$join = array('materials'=>"work_orders_staging_materials.mat_id = materials.id");
			$stagings_mats = $this->site_model->get_tbl('work_orders_staging_materials',array('wo_stg_id'=>$stg_ids),array(),$join,true,$select);
		}
		$stagings_produced = array();
		if(count($stg_ids) > 0){
			$join = array();
			$select = "work_orders_produced.*,items.name as item_name,items.uom as item_uom";
			$join = array('items'=>"work_orders_produced.item_id = items.id");
			$stagings_produced = $this->site_model->get_tbl('work_orders_produced',array('wo_stg_id'=>$stg_ids),array(),$join,true,$select);
		}

		$data['page_title'] = fa('fa-table')." Work Order Ref# ".$wo->reference." history";	
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'work_order"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = history_page($wo,$wo_mats,$stagings,$stagings_mats,$stagings_produced);
		$this->load->view('page',$data);
	}
	public function staging($wo_id=null,$stage_id=null){
		$data = $this->syter->spawn('wo_staging');
		$stgd = array();
		$wod = array();
		$stage_res = $this->site_model->get_tbl('work_order_stages',array('id'=>$stage_id));
		if($stage_res){
			$stgd = $stage_res[0];
		}
		else{
			redirect(base_url().'work_order');
			site_alert('Stage not found.','error');
		}
		$wo_res = $this->site_model->get_tbl('work_orders',array('id'=>$wo_id));
		if($wo_res){
			$wod = $wo_res[0];
		}
		else{
			redirect(base_url().'work_order');
			site_alert('Work Order not found.','error');
		}

		$data['page_title'] = fa('fa-level-up')." Ref#".$wod->reference." - ".ucFix($stgd->name);		
		$materials = array();
		$today = $this->site_model->get_db_now('php',true);

		$join['materials'] = "work_order_materials.mat_id = materials.id";
		$select = "work_order_materials.*,materials.name as mat_name,materials.uom as mat_uom";
		$result_details = $this->site_model->get_tbl('work_order_materials',array('wo_id'=>$wo_id),array(),$join,true,$select);
		foreach ($result_details as $res) {
			$materials[] = array(
				'wo_id'		=>	$wo_id,
				'mat_name'	=>	$res->mat_name,
				'uom'		=>	$res->mat_uom,
				'mat_id'	=>	$res->mat_id,
				'min_qty'	=>	$res->min_qty,
				'wo_qty'	=>	$res->wo_qty,
				'cost'		=>	$res->cost,
				'total_cost'=>	$res->total_cost,
			);
		}
		sess_initialize('stage-mats',$materials);
		sess_initialize('add-mats',array());


		$curr_stage_id = $stage_id;
		$last = 0;
		$check_stage = $this->site_model->get_tbl('work_orders_staging',array('wo_id'=>$wo_id),array('order'=>'asc'));
		if($check_stage){
			$csid_res = $this->site_model->get_tbl('work_order_type_stages',array('type_id'=>$wod->type_id,'stage_id'=>$curr_stage_id),array('order'=>'asc'));
			if($csid_res){
				$next_order = (int)$csid_res[0]->order+1;
				$csid_ress = $this->site_model->get_tbl('work_order_type_stages',array('type_id'=>$wod->type_id,'order'=>$next_order),array('order'=>'asc'));
				if(!$csid_ress){
					$last = 1;
				}
			}
		}

		$produce = array();
		if($last != 0){
			$join = array('items' => "work_order_type_items.item_id = items.id");
			$select = "work_order_type_items.*,items.name as item_name,items.uom as item_uom";
			$result_details = $this->site_model->get_tbl('work_order_type_items',array('type_id'=>$wod->type_id),array(),$join,true,$select);
			foreach ($result_details as $res) {
				$produce[] = array(
					'id'		=>	$res->item_id,
					'name'		=>	$res->item_name,
					'uom'		=>	$res->item_uom,
				);
			}
		}

		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'work_order"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = staging_form($wod,$stgd,$materials,$today,$last,$produce);
		$data['load_js'] = 'work_order/work_order';
		$data['use_js'] = 'staging_form';
		$this->load->view('page',$data);
	}
	public function staging_db($id=null){
		$this->load->model('inventory_model');
		$user = sess('user');
		$materials = sess('stage-mats');
		$addons = sess('add-mats');

		$items = array(
		    "wo_id"=>$this->input->post('wo_id'),
		    "order"=>$this->input->post('order'),
		    "stage_id"=>$this->input->post('stage_id'),
		    "weight"=>$this->input->post('weight'),
		    "damage"=>$this->input->post('damage'),
		    "uom"=>$this->input->post('main_uom'),
		    "stage_date"=>date2Sql($this->input->post('stg_date')),
		    "memo"=>$this->input->post('memo'),
		);

		$error = 0;
		$msg = "";

		$id = $this->site_model->add_tbl('work_orders_staging',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
		$msg = "Ref# ".$this->input->post('ref')." Stage Updated";

		if($id){
			if($this->input->post('stg_last') == 0){
				$curr_stage_id = null;
				$order = $this->input->post('order')+1;
				$csid_res = $this->site_model->get_tbl('work_order_type_stages',array('type_id'=>$this->input->post('type_id'),'stage_id'=>$this->input->post('stage_id')),array('order'=>'asc'));
				if($csid_res){
					$next_order = (int)$csid_res[0]->order+1;
					$csid_ress = $this->site_model->get_tbl('work_order_type_stages',array('type_id'=>$this->input->post('type_id'),'order'=>$next_order),array('order'=>'asc'));
					if($csid_ress){
						$curr_stage_id = $csid_ress[0]->stage_id;
					}
					else{
						$curr_stage_id = 0;
					}
				}
				$this->site_model->update_tbl('work_orders','id',array('curr_stage_id'=>$curr_stage_id),$this->input->post('wo_id'));
			}
			else{
				$this->site_model->update_tbl('work_orders','id',array('curr_stage_id'=>0,'finished'=>1),$this->input->post('wo_id'));
			}
			$rows = array();
			foreach ($this->input->post('used_qty') as $ctr => $val) {
				$used = $val;
				if($val == "")
					$used = 0;
				$row = $materials[$ctr];
				$rows[] = array(
					'wo_stg_id'	=>	$id,
					'mat_id'	=>	$row['mat_id'],
					'min_qty'	=>	$row['min_qty'],
					'wo_qty'	=>	$row['wo_qty'],
					'cost'		=>	$row['cost'],
					'total_cost'=>	$row['wo_qty'] * $row['cost'],
					'used_qty'	=>	$used,
				);
			}
			$this->site_model->add_tbl_batch('work_orders_staging_materials',$rows);
			if(count($addons) > 0){
				$rows = array();
				foreach ($addons as $ctr => $row) {
					$rows[] = array(
						'wo_stg_id'	=>	$id,
						'mat_id'	=>	$row['mat_id'],
						'min_qty'	=>	0,
						'wo_qty'	=>	0,
						'cost'		=>	$row['cost'],
						'total_cost'=>	$row['add_on_qty'] * $row['cost'],
						'used_qty'	=>	$row['add_on_qty'],
						'additional'=>	1
					);
				}
				$this->site_model->add_tbl_batch('work_orders_staging_materials',$rows);

				foreach ($addons as $lid => $row) {
					$this->inventory_model->move_qty($row['mat_id'],1,($row['add_on_qty'] * -1),WORK_ORDER_ISSUE_CODE,$this->input->post('ref'),date2Sql($this->input->post('stg_date')),'Additional - '.$this->input->post('stg_name'));
				}
			}
			if($this->input->post('itm')){
				$uoms = $this->input->post('itmuom');
				$produce = array();
				foreach ($this->input->post('itm') as $item_id => $qty) {
					$produce[] = array(
						'wo_id'		=>	$this->input->post('wo_id'),
						'wo_stg_id'	=>	$id,
						'item_id'	=>	$item_id,
						'qty'		=>	$qty,
						'uom'		=>	$uoms[$item_id],
					);
				}
				if(count($produce) > 0){
					$this->site_model->add_tbl_batch('work_orders_produced',$produce);
					foreach ($produce as $row) {
						$this->inventory_model->move_qty_item($row['item_id'],1,$row['qty'],WORK_ORDER_ISSUE_CODE,$this->input->post('ref'),date2Sql($this->input->post('stg_date')),'Produced Items - '.$this->input->post('stg_name'));
					}
				}
			}
		}

		if(!$this->input->post('rForm')){
			if($error == 0){
				site_alert($msg,'success');
			}
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,'items'=>$items,'id'=>$id));
	}
	public function create(){
		$data = $this->syter->spawn('wo_issue');
		$data['page_title'] = fa('fa-ticket')." Create Work Order";		
		$materials = array();
		$new_ref = $this->site_model->get_next_ref(WORK_ORDER_ISSUE_CODE);
		$lot_no = $this->site_model->get_next_ref(WORK_ORDER_BATCH_CODE);
		$batch_no = $this->site_model->get_next_ref(WORK_ORDER_LOT_CODE);
		$today = $this->site_model->get_db_now('php',true);

		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		// $data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'uom"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		sess_initialize('wo-mats',$materials);
		$data['code'] = create_form($new_ref,$lot_no,$batch_no,$today);
		$data['load_js'] = 'work_order/work_order';
		$data['use_js'] = 'create_form';
		$this->load->view('page',$data);
	}
	public function create_db($id=null){
		$user = sess('user');
		$materials = sess('wo-mats');
		if(count($materials) == 0){
			echo json_encode(array('error'=>1,'msg'=>'Please add materials.',"id"=>''));
			return false;
		}
		$reference = $this->input->post('reference');
		if(!$this->input->post('id')){
			$check = $this->site_model->ref_unused(WORK_ORDER_ISSUE_CODE,$reference);
			if(!$check){
				echo json_encode(array('error'=>1,'msg'=>'Reference '.$reference.' is already in use.',"id"=>''));
				return false;			
			}
		}
		$batch_no = $this->input->post('batch_no');
		if(!$this->input->post('id')){
			$check = $this->site_model->ref_unused(WORK_ORDER_BATCH_CODE,$batch_no);
			if(!$check){
				echo json_encode(array('error'=>1,'msg'=>'Batch No. '.$batch_no.' is already in use.',"id"=>''));
				return false;			
			}
		}
		$lot_no = $this->input->post('lot_no');
		if(!$this->input->post('id')){
			$check = $this->site_model->ref_unused(WORK_ORDER_LOT_CODE,$lot_no);
			if(!$check){
				echo json_encode(array('error'=>1,'msg'=>'Lot No. '.$lot_no.' is already in use.',"id"=>''));
				return false;			
			}
		}

		$curr_stage_id = null;
		$csid_res = $this->site_model->get_tbl('work_order_type_stages',array('type_id'=>$this->input->post('type_id')),array('order'=>'asc'));
		if($csid_res){
			$curr_stage_id = $csid_res[0]->stage_id;
		}
		$items = array(
		    "reference"	=>$reference,
		    "batch_no"	=>$batch_no,
		    "lot_no"	=>$lot_no,
		    "type_id"	=>$this->input->post('type_id'),
		    "weight"	=>$this->input->post('weight'),
		    "uom"		=>$this->input->post('main_uom'),
		    "memo"		=>$this->input->post('memo'),
		    "curr_stage_id"		=>$curr_stage_id,
		    "wo_date"			=>date2Sql($this->input->post('wo_date')),
		);

		$error = 0;
		$msg = "";

		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('work_orders',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
			$this->site_model->save_ref(WORK_ORDER_ISSUE_CODE,$reference);
			$this->site_model->save_ref(WORK_ORDER_BATCH_CODE,$batch_no);
			$this->site_model->save_ref(WORK_ORDER_LOT_CODE,$lot_no);
			$msg = "Added New Work Order Ref# ".$items['reference'];
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('work_orders','id',$items,$id);
			$msg = "Updated Work Order Ref# ".$items['reference'];
		}
		if($id){
			$this->site_model->delete_tbl('work_order_materials',array('wo_id'=>$id));
			$rows = array();
			foreach ($materials as $lid => $row) {
				$rows[] = array(
					'wo_id'		=>	$id,
					'mat_id'	=>	$row['mat_id'],
					'min_qty'	=>	$row['ord_qty'],
					'wo_qty'	=>	$row['wo_qty'],
					'cost'		=>	$row['cost'],
					'total_cost'=>	$row['wo_qty'] * $row['cost'],
				);
			}
			$this->site_model->add_tbl_batch('work_order_materials',$rows);
			$this->load->model('inventory_model');
			foreach ($materials as $lid => $row) {
				$this->inventory_model->move_qty($row['mat_id'],1,($row['wo_qty'] * -1),WORK_ORDER_ISSUE_CODE,$reference,date2Sql($this->input->post('wo_date')));
			}
		}
		if(!$this->input->post('rForm')){
			if($error == 0){
				site_alert($msg,'success');
			}
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,'items'=>$items,'id'=>$id));
	}
	public function dispatch(){
		$data = $this->syter->spawn('wo_dispatch');
		$data['page_title'] = fa('fa-truck')." Dispatch";		
		$items = array();
		$new_ref = $this->site_model->get_next_ref(WORK_ORDER_DISPATCH);
		$today = $this->site_model->get_db_now('php',true);

		$args = array();
		$join = array();
		$select = "work_orders_produced.*,items.name as item_name,work_orders.reference";
		$join['items'] = "work_orders_produced.item_id = items.id";
		$join['work_orders'] = "work_orders_produced.wo_id = work_orders.id";
		$args["work_orders_produced.qty > work_orders_produced.dispatched"] = array('use'=>'where','val'=>"",'third'=>false);
		$items = $this->site_model->get_tbl('work_orders_produced',$args,array('work_orders.wo_date'=>'desc'),$join,true,$select);
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		// $data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'uom"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		// sess_initialize('dispatch-items',$items);
		$data['code'] = dispatch_form($new_ref,$today,$items);
		$data['load_js'] = 'work_order/work_order';
		$data['use_js'] = 'dispatch_form';
		$this->load->view('page',$data);
	}
	public function dispatch_db(){
		$this->load->model('inventory_model');
		$reference = $this->input->post('reference');
		$user = sess('user');
		
		if(!$this->input->post('id')){
			$check = $this->site_model->ref_unused(WORK_ORDER_DISPATCH,$reference);
			if(!$check){
				echo json_encode(array('error'=>1,'msg'=>'Reference '.$reference.' is already in use.',"id"=>''));
				return false;			
			}
		}

		$dispatch_qty = $this->input->post('dispatch_qty'); 
		$total_qty = 0;
		$disp_qty = array();
		foreach ($dispatch_qty as $prd_id => $qty) {
			if($qty > 0){
				$total_qty += $qty;
				$disp_qty[$prd_id] = $qty;
			}
		}

		if($total_qty == 0){
			echo json_encode(array('error'=>1,'msg'=>'No inputed qty.',"id"=>''));
			return false;			
		}

		$error = 0;
		$msg = "";
		$id = 0;
	
		$items = array(
			"reference" 			=> $reference,
			"dispatch_date" 		=> date2Sql($this->input->post('dispatch_date')),
			"total_qty" 			=> $total_qty,
			"customer_id" 			=> $this->input->post('customer_id'),
			"memo"  				=> $this->input->post('memo'),
		);

		if(!$this->input->post('id')){
			$id = $this->site_model->add_tbl('work_order_dispatch',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
			$this->site_model->save_ref(WORK_ORDER_DISPATCH,$reference);	
			$msg = "Added new Work Order Dispatch Items Reference #".$reference;	
		}
		else{
			$id = $this->input->post('id');
			$this->site_model->update_tbl('work_order_dispatch','id',$items,$id);
			$msg = "Updated Work Order Dispatch Items Reference #".$reference;	
		}
		if($id){
			$this->site_model->delete_tbl('work_order_dispatch_items',array('dispatch_id'=>$id));
			$rows = array();
			foreach ($disp_qty as $pid => $qty) {
				$wo = $this->input->post('dispatch_wo_id');
				$stg = $this->input->post('dispatch_stg_id');
				$itm = $this->input->post('dispatch_item_id');
				$rows[] = array(
					'dispatch_id'	=>	$id,
					'item_id'		=>	$itm[$pid],
					'wo_id'			=>	$wo[$pid],
					'wo_stg_id'		=>	$stg[$pid],
					'qty'			=>	$qty,
				);
			}
			$this->site_model->add_tbl_batch('work_order_dispatch_items',$rows);
			foreach ($disp_qty as $pid => $qty) {
				$itm = $this->input->post('dispatch_item_id');
				$this->site_model->update_tbl('work_orders_produced',array('id'=>$pid),array(),null,array('dispatched'=>'dispatched+'.$qty));
				$this->inventory_model->move_qty_item($itm[$pid],1,($qty * -1),WORK_ORDER_DISPATCH,$reference,date2Sql($this->input->post('dispatch_date')),'Dispatched Items');
			}
		}
		if($error == 0){
			site_alert($msg,'success');
		}
		echo json_encode(array('error'=>$error,'msg'=>$msg,"id"=>$id));
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
	public function compute_types_details(){
		$details   = array();
		$materials = array();
		$items 	   = array();
		$id = $this->input->post('id');
		$weight = $this->input->post('weight');
		if(!$this->input->post('weight'))
			$weight = 0;
		// echo var_dump($id);
		if($id != ""){
			$res_details = $this->site_model->get_tbl('work_order_types',array('id'=>$id));
			$details['main_uom'] = $res_details[0]->uom;
			$join = array('materials'=>"work_order_type_materials.mat_id = materials.id");
			$select = "work_order_type_materials.*,materials.name as mat_name,materials.uom as mat_uom";
			$res_materials = $this->site_model->get_tbl('work_order_type_materials',array('type_id'=>$id),array(),$join,true,$select);
			foreach ($res_materials as $res) {
				$wo_qty = $res->order_qty * $weight;
				$materials[] = array(
									 'cost' 		  => $res->cost,	
									 'cost_total_hid' => numInt($res->cost * $wo_qty),	
									 'ord_qty' 		  => $res->order_qty,	
									 'wo_qty' 		  => $wo_qty,	
									 'mat_id' 		  => $res->mat_id,	
									 'mat_name' 	  => $res->mat_name,
									 'uom' 	  		  => $res->mat_name,
									);	
			}
		}
		sess_initialize('wo-mats',$materials);
		// $select = "work_order_receive_items.*,work_order_receives.reference as rec_ref,work_order_receives.rcv_date as rcv_date,
		// 		   work_order_receives.customer_id as cust_id,customers.name as cust_name,
		// 		   items.name as item_name,items.uom as item_uom,";
		// $join = array(
		// 			  'work_order_receives'=>"work_order_receive_items.rcv_id = work_order_receives.id",
		// 			  'customers'=>"work_order_receives.customer_id = customers.id",
		// 			  'work_order_type_items'=>"work_order_receive_items.item_id = work_order_type_items.item_id",
		// 			  'items'=>"work_order_receive_items.item_id = items.id",
		// 			 );
		// $args = array('work_order_type_items.type_id'=>$id);
		// $order = array('work_order_receives.rcv_date'=>'ASC');
		// $res_items = $this->site_model->get_tbl('work_order_receive_items',$args,$order,$join,true,$select);
		// foreach ($res_items as $itm) {
		// 	$items[] = array(
		// 		'rcv_id' 	=> $itm->rcv_id,
		// 		'rcv_date'	=> sql2Date($itm->rcv_date),
		// 		'ref' 		=> $itm->rec_ref,
		// 		'cust_id' 	=> $itm->cust_id,
		// 		'cust_name' => $itm->cust_name,
		// 		'item_id' 	=> $itm->item_id,
		// 		'item_name'	=> $itm->item_name,
		// 		'rcv_total'	=> num($itm->rcv_qty),
		// 		'uom'		=> $itm->item_uom,
		// 	);
		// }
		echo json_encode(array('details'=>$details,'materials'=>$materials,'items'=>$items));	
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
