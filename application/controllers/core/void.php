<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Void extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}	
  public function form($trans_id,$trans_type){
      $this->html->sForm("void/db","void-form");
         $this->html->H(3,'Are you sure you want to void this transaction?'); 
         $this->html->hidden('trans_type',$trans_type);
         $this->html->hidden('trans_id',$trans_id);
         $this->html->textarea("Reason:","reason",null,null,array('class'=>'rOkay'));
      $this->html->eForm();
      $data['code'] = $this->html->code();
      $this->load->view('load',$data);   
  }
  public function db(){
    $trans_type = VOID;
    $reference = $this->site_model->get_next_ref(VOID);
    $user = sess('user');
    $trans_type = $this->input->post('trans_type');
    $trans_id = $this->input->post('trans_id');
    
    $items = array(
      "trans_ref"=>$reference,
      "src_type"=>$trans_type,
      "src_id"=>$trans_id,
      "reason"=>$this->input->post('reason'),
    );

    $id = $this->site_model->add_tbl('voids',$items,array('reg_date'=>'NOW()','reg_user'=>$user['id']));
    $msg = "Transaction Voided."; 

    if($trans_type == PAYMENT)
      $this->payment_void($trans_id,$trans_type);
    else if($trans_type == ENROLLMENT)
      $this->enrollment_void($trans_id,$trans_type);
     
    $this->site_model->save_ref(VOID,$reference);
    site_alert($msg,'success');
  }
  public function payment_void($trans_id,$trans_type){
    $results = $this->site_model->get_tbl('payment_for',array('pay_id'=>$trans_id));
    foreach ($results as $res) {
      $amount = $res->amount;
      if($res->src_type == ENROLLMENT){
        $args = array('pay_date'=>null);
        $set = array('pay'=>'pay - '.$amount);
        $this->site_model->update_tbl('enroll_payments','id',$args,$res->src_det_id,$set);
      }
    }
    $this->site_model->update_tbl('payments','id',array('inactive'=>1),$trans_id);
  }
  public function enrollment_void($trans_id,$trans_type){
    $this->site_model->update_tbl('enrolls','id',array('inactive'=>1),$trans_id);
  }
}
