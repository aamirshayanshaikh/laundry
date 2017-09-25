<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}	
    public function collectibles($by="month"){
        $now = $this->site_model->get_db_now();
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/payments';
        $cols = array('Student','Reference','Total Amount','Total Paid','Remaining Balance',' ');
        $table = 'enroll_payments';
        $select[0] = 'enroll_payments.*,enrolls.trans_ref,
                      students.fname as std_fname,students.mname as std_mname,students.lname as std_lname,students.suffix as std_suffix';
        $select[1] = false;
        $join['students'] = "enroll_payments.student_id = students.id";
        $join['enrolls'] = 'enroll_payments.enroll_id = enrolls.id';
        $group = null;
        $args["DATE(enroll_payments.due_date) <= DATE(NOW())"] = array('use'=>'where','val'=>"",'third'=>false);
        $args["enroll_payments.amount > enroll_payments.pay"] = array('use'=>'where','val'=>"",'third'=>false);
        $args['enrolls.inactive'] = 0;
        $title = "";
        if($by == "month"){
            $date  = date('Y-m-t',strtotime($now));    
            $title = date('F',strtotime($now))." Collectibles";
        }
        $by = strtoupper($by);      
        $args["DATE(enroll_payments.due_date) <= DATE('".$date."')"] = array('use'=>'where','val'=>"",'third'=>false);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        $total = 0;
        foreach ($items as $res) {
            $total += $res->amount;
        }

        echo json_encode(array('title'=>$title,'amount'=>$total));
    }
}
