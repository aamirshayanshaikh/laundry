<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Work_order extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('work_order/work_order_helper');
	}
	// public function index(){
	// 	$data = $this->syter->spawn('uom');
	// 	$data['code'] = listPage(fa('fa-flask')." Unit Of Measures",'uom','uom/form','list','list',false);
	// 	$this->load->view('list',$data);
	// }
	// public function form($id=null){
	// 	$data = $this->syter->spawn('uom');
	// 	$data['page_title'] = fa('fa-flask')." Unit Of Measures";
	// 	$data['page_subtitle'] = "Add New UOM";
	// 	$det = array();
	// 	$img = array();
	// 	if($id != null){
	// 		$details = $this->site_model->get_tbl('uom',array('id'=>$id));
	// 		if($details){
	// 			$det = $details[0];
	// 			$data['page_subtitle'] = "Edit UOM ".ucwords(strtolower($det->name));
	// 		}
	// 	}
	// 	$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
	// 	$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'uom"','text'=>"<i class='fa fa-fw fa-reply'></i>");
	// 	$data['code'] = uom_form($det);
	// 	$data['load_js'] = 'inventory/uom';
	// 	$data['use_js'] = 'uom_form';
	// 	$this->load->view('page',$data);
	// }
	// public function db($id=null){
	// 	$user = sess('user');
	// 	$items = array(
	// 	    "name"=>$this->input->post('name'),
	// 	    "abbrev"=>$this->input->post('abbrev'),
	// 	);
	// 	$error = 0;
	// 	$msg = "";
	// 	if(!$this->input->post('id')){
	// 		$id = $this->site_model->add_tbl('uom',$items);
	// 		$msg = "Added New UOM ".$items['name'];
	// 	}
	// 	else{
	// 		$id = $this->input->post('id');
	// 		$this->site_model->update_tbl('uom','id',$items,$id);
	// 		$msg = "Updated UOM ".$items['name'];
	// 	}
	// 	if(!$this->input->post('rForm')){
	// 		if($error == 0){
	// 			site_alert($msg,'success');
	// 		}
	// 	}
	// 	echo json_encode(array('error'=>$error,'msg'=>$msg,'items'=>$items,'id'=>$id));
	// }
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
			}
		}
		sess_initialize('type-mats',$materials);
		$data['top_btns'][] = array('tag'=>'button','params'=>'id="save-btn" class="btn-flat btn-flat btn btn-success"','text'=>"<i class='fa fa-fw fa-save'></i> SAVE");
		$data['top_btns'][] = array('tag'=>'a','params'=>'class="btn btn-primary btn-flat" href="'.base_url().'work_order/types"','text'=>"<i class='fa fa-fw fa-reply'></i>");
		$data['code'] = types_form($det);
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
