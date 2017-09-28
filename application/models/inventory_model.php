<?php
require_once(APPPATH.'models/site_model.php');
class Inventory_model extends Site_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_curr_qty($mat_id,$loc_id){
		$curr_qty = 0;
		$curr_res = $this->site_model->get_tbl('inventory_moves',array('mat_id'=>$mat_id,'loc_id'=>$loc_id),array('trans_date'=>'DESC','id'=>'DESC'),null,true,'*',null,1);
		if($curr_res){
			$curr_qty = $curr_res[0]->curr_qty;
		}
		return $curr_qty;
	}
	public function move_qty($mat_id,$loc_id,$qty,$ref_type,$ref,$date,$memo=null){
		$curr_qty = $this->get_curr_qty($mat_id,$loc_id);
		// echo $curr_qty." - ".$qty;
		// if($qty < 0){
		// 	$curr_qty -= $qty;			
		// }
		// else
			$curr_qty += $qty;			

		$items = array(
			'trans_type' 	=> $ref_type,
			'trans_ref' 	=> $ref,
			'loc_id' 		=> $loc_id,
			'trans_date' 	=> date2Sql($date),
			'mat_id' 		=> $mat_id,
			'qty' 			=> $qty,
			'curr_qty' 		=> $curr_qty,
			'memo' 			=> $memo,
		);
		$id = $this->site_model->add_tbl('inventory_moves',$items,array('reg_date'=>'NOW()'));
	}
}
?>