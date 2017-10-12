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
	public function pdf($id=null){
		$this->load->library('Pdf');

		if($id == null){
			redirect(base_url().'purchase_orders');
			site_alert('PO not found.','error');
		}

		$comp_info = $this->site_model->get_company_info();
		$po = array();
		$po_items = array();
		$join = array();
		$join['suppliers'] = "purchase_orders.supplier_id = suppliers.id";
		$join['locations'] = "purchase_orders.rcv_loc_id = locations.id";
		$select = "purchase_orders.*,suppliers.name as supp_name,suppliers.address as supp_add,suppliers.contact_no as supp_contact_no,locations.name as loc_name,locations.address as loc_add";
		$result = $this->site_model->get_tbl('purchase_orders',array('purchase_orders.id'=>$id),array(),$join,true,$select);
		if($result){
			$po = $result[0];
			$join = array();
			$select ="";
			$join['materials'] = "purchase_order_details.mat_id = materials.id";
			$select = "purchase_order_details.*,materials.name as mat_name,materials.code as mat_code,materials.uom as mat_uom";
			$result_details = $this->site_model->get_tbl('purchase_order_details',array('order_id'=>$id),array(),$join,true,$select);
			foreach ($result_details as $res) {
				$po_items[] = $res;	
			}
		}
		else{
			redirect(base_url().'purchase_orders');
			site_alert('PO not found.','error');
		}

		$title = "Purchase Order";
		$fileName = "PO#".$po->reference;

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
        $pdf->Cell(100, 6, $po->reference, 0, 0, 'R', 0);
        $pdf->Ln(4);
		$pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(100, 6, $comp_info['comp_contact_no'].' - '.$comp_info['comp_email'], 0, 0, 'L', 0);
        $pdf->Cell(100, 6, sql2Date($po->order_date), 0, 0, 'R', 0);
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
		$supp_text = $po->supp_name."\n";
		$supp_text .= $po->supp_add."\n";
		$supp_text .= $po->supp_contact_no."\n";
		$loc_text = $po->loc_name."\n";
		$loc_text .= $po->loc_add."\n";
		$pdf->setCellPaddings(5,0,0,0);
        $pdf->MultiCell(100, 0, $supp_text, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $pdf->MultiCell(100, 0, $loc_text, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $pdf->Ln(20);

        
        $header  = array('Code', 'Material', 'Order Qty', 'UOM', 'Cost', 'Total Cost');
        $w = array(30, 60, 25, 25, 25, 34);
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
        foreach($po_items as $res) {
            $pdf->Cell($w[0], 6, $res->mat_code, 'LR', 0, 'L', $fill);
            $pdf->Cell($w[1], 6, $res->mat_name, 'LR', 0, 'L', $fill);
            $pdf->Cell($w[2], 6, num($res->order_qty), 'LR', 0, 'R', $fill);
            $pdf->Cell($w[3], 6, strtoupper($res->mat_uom), 'LR', 0, 'C', $fill);
            $pdf->Cell($w[4], 6, num($res->cost), 'LR', 0, 'R', $fill);
            $pdf->Cell($w[5], 6, num($res->total_cost), 'LR', 0, 'R', $fill);
            $pdf->Ln();
        	$total_qty += $res->order_qty;
        	$total_cost += $res->total_cost;
        	$fill=!$fill;
        }
        $fill = 0;
        $pdf->Cell(140, 6, ' ', 'T', 0, 'R', $fill);
		$pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(25, 6, 'Total Cost', 1, 0, 'R', $fill);
        $pdf->Cell($w[5], 6, num($total_cost), 1, 0, 'R', $fill);
        $pdf->Ln();
        $pdf->Cell(140, 6, ' ', 0, 0, 'R', $fill);
		$pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(25, 6, 'Total Qty', 'LRB', 0, 'R', $fill);
        $pdf->Cell($w[5], 6, num($total_qty), 'LRB', 0, 'R', $fill);
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->setCellPaddings(0,0,0,0);
        $pdf->Cell(200, 6, 'Remarks', 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 9);
        $pdf->setCellPaddings(3,0,0,0);
        $pdf->Cell(200, 20, $po->memo, 0, 0, 'L', 1, '', 0, false, 'T');
		$pdf->Output($fileName,'I');
	}
}
