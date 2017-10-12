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
	public function pdf($id=null){
		$this->load->library('Pdf');

		if($id == null){
			redirect(base_url().'receive_orders');
			site_alert('Receiving not found.','error');
		}

		$comp_info = $this->site_model->get_company_info();
		$ro = array();
		$ro_items = array();
		$join = array();
		$join['purchase_orders'] = "receive_orders.order_id = purchase_orders.id";
		$join['suppliers'] = "purchase_orders.supplier_id = suppliers.id";
		$join['locations'] = "receive_orders.loc_id = locations.id";
		$select = "receive_orders.*,suppliers.name as supp_name,suppliers.address as supp_add,suppliers.contact_no as supp_contact_no,locations.name as loc_name,locations.address as loc_add";
		$result = $this->site_model->get_tbl('receive_orders',array('receive_orders.id'=>$id),array(),$join,true,$select);
		if($result){
			$ro = $result[0];
			$join = array();
			$select ="";
			$join['materials'] = "receive_order_details.mat_id = materials.id";
			$select = "receive_order_details.*,materials.name as mat_name,materials.code as mat_code,materials.uom as mat_uom";
			$result_details = $this->site_model->get_tbl('receive_order_details',array('rcv_id'=>$id),array(),$join,true,$select);
			foreach ($result_details as $res) {
				$ro_items[] = $res;	
			}
		}
		else{
			redirect(base_url().'receive_orders');
			site_alert('Receiving not found.','error');
		}

		$title = "Receiving";
		$fileName = "RO#".$ro->reference;

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle($title);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(5,5,5);
		$pdf->SetAutoPageBreak(true);
		$pdf->AddPage();

		$pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(100, 6, $comp_info['comp_name'], 0, 0, 'L', 0);
        $pdf->Cell(100, 6, $title, 0, 0, 'R', 0);
        $pdf->Ln(6);
		$pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(100, 6, $comp_info['comp_address'], 0, 0, 'L', 0);
		$pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(100, 6, $ro->reference, 0, 0, 'R', 0);
        $pdf->Ln(4);
		$pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(100, 6, $comp_info['comp_contact_no'].' - '.$comp_info['comp_email'], 0, 0, 'L', 0);
        $pdf->Cell(100, 6, sql2Date($ro->receive_date), 0, 0, 'R', 0);
        $pdf->Ln(4);
		$pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(100, 6, $comp_info['comp_tin'], 0, 0, 'L', 0);
        $pdf->Cell(100, 6, '', 0, 0, 'R', 0);
        
        $pdf->Ln(10);
		$pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(100, 6, 'Supplier:', 0, 0, 'L', 0);
        $pdf->Cell(100, 6, 'To:', 0, 0, 'L', 0);
        $pdf->Ln(6);
		$pdf->SetFont('helvetica', '', 9);
		$supp_text = $ro->supp_name."\n";
		$supp_text .= $ro->supp_add."\n";
		$supp_text .= $ro->supp_contact_no."\n";
		$loc_text = $ro->loc_name."\n";
		$loc_text .= $ro->loc_add."\n";
		$pdf->setCellPaddings(5,0,0,0);
        $pdf->MultiCell(100, 0, $supp_text, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $pdf->MultiCell(100, 0, $loc_text, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $pdf->Ln(20);

        
        $header  = array('Code', 'Material', 'Order Qty', 'UOM', 'Received Qty');
        $w = array(30, 75, 35, 25, 35);
        $num_headers = count($header);
        $pdf->SetTextColor(0);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->setCellPaddings(3,0,3,0);
        for($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 0);
        }
        $pdf->Ln();
        $total_qty = 0;
        $total_cost = 0;
		$pdf->SetFont('helvetica', '', 9);
		$pdf->SetFillColor(238, 238, 238);
        $fill = 0;
        foreach($ro_items as $res) {
            $pdf->Cell($w[0], 6, $res->mat_code, 'LR', 0, 'L', $fill);
            $pdf->Cell($w[1], 6, $res->mat_name, 'LR', 0, 'L', $fill);
            $pdf->Cell($w[2], 6, num($res->order_qty), 'LR', 0, 'R', $fill);
            $pdf->Cell($w[3], 6, strtoupper($res->mat_uom), 'LR', 0, 'C', $fill);
            $pdf->Cell($w[4], 6, num($res->rcv_qty), 'LR', 0, 'R', $fill);
            $pdf->Ln();
        	$total_qty += $res->rcv_qty;
        	$fill=!$fill;
        }
        $fill = 0;
        $pdf->Cell(105, 6, ' ', 'T', 0, 'R', $fill);
		$pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 6, 'Total Qty Received', 1, 0, 'R', $fill);
        $pdf->Cell($w[4], 6, num($total_qty), 1, 0, 'R', $fill);
 
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->setCellPaddings(0,0,0,0);
        $pdf->Cell(200, 6, 'Remarks', 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 9);
        $pdf->setCellPaddings(3,0,0,0);
        $pdf->Cell(200, 20, $ro->memo, 0, 0, 'L', 1, '', 0, false, 'T');
		$pdf->Output($fileName,'I');
	}
}
