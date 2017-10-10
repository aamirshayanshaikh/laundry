<?php
class Site_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_image($img_id=null,$img_ref_id=null,$img_tbl=null,$args=array(),$result=true){
		$this->db->select('*');
		$this->db->from('images');
		if (!is_null($img_id)){
			if (is_array($img_id))
				$this->db->where_in('images.img_id',$img_id);
			else
				$this->db->where('images.img_id',$img_id);
		}
		if (!is_null($img_ref_id)){
			if (is_array($img_id))
				$this->db->where_in('images.img_ref_id',$img_ref_id);
			else
				$this->db->where('images.img_ref_id',$img_ref_id);
		}
		if (!is_null($img_tbl)){
			if (is_array($img_tbl))
				$this->db->where_in('images.img_tbl',$img_tbl);
			else
				$this->db->where('images.img_tbl',$img_tbl);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}

		$query = $this->db->get();
		if($result){
			return $query->result();
		}
		else{
			return $query;
		}
	}
	public function get_tbl($table=null,$args=array(),$order=array(),$joinTables=null,$result=true,$select='*',$group=null,$limit=null,$count_only=false){
		if(is_array($select))
			$this->db->select($select[0],$select[1]);
		else
			$this->db->select($select);
		$this->db->from($table);
		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				if(is_array($v)){
					$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
				}
				else{
					$this->db->join($k,$v);
				}
			}
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		if($group != null){
			$this->db->group_by($group);
		}
		if($limit != null){
			if(is_array($limit))
				$this->db->limit($limit[0],$limit[1]);
			else
				$this->db->limit($limit);
		}
		if(count($order) > 0){
			foreach ($order as $col => $val) {
				$this->db->order_by($col,$val);
			}
		}
		if($count_only){
			return $this->db->count_all_results();
		}
		else{
			$query = $this->db->get();
			if($result){
				return $query->result();
			}
			else{
				return $query;
			}
		}
	}
	public function get_settings($prefs=null,$category=null){
		$args = array();
		$prefs = array();
		if($category != null){
			$args['settings.category'] = $category;
		}
		else{
			$args['settings.code'] = $prefs;  
		}
		$result = $this->get_tbl('settings',$args);
		foreach ($result as $res) {
			$prefs[$res->code] = $res->value;
		}
		return $prefs;
	}
	public function get_tbl_cols($tbl=null){
		$cols = null;
		if($tbl != null){
			$cols = $this->db->list_fields($tbl);
		}
		return $cols;
	}
	public function add_tbl_batch($table_name,$items){
		$this->db->insert_batch($table_name,$items);
		return $this->db->insert_id();
	}
	public function add_tbl($table_name,$items,$set=array()){
		if(!empty($set)){
			foreach ($set as $key => $val) {
				$this->db->set($key, $val, FALSE);
			}
		}
		$this->db->insert($table_name,$items);
		return $this->db->insert_id();
	}
	public function update_tbl($table_name,$table_key,$items,$id=null,$set=array()){
		if(is_array($table_key)){
			foreach ($table_key as $key => $val) {
				if(is_array($val)){
					$this->db->where_in($key,$val);
				}
				else
					$this->db->where($key,$val);
			}
		}
		else{
			if(is_array($id)){
				$this->db->where_in($table_key,$id);
			}
			else
				$this->db->where($table_key,$id);
		}
		if(!empty($set)){
			foreach ($set as $key => $val) {
				$this->db->set($key, $val, FALSE);
			}
		}
		$this->db->update($table_name,$items);
		return $this->db->last_query();
	}
	public function delete_tbl_batch($table_name=null,$args=null){
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->delete($table_name);
	}
	public function delete_tbl($table_name=null,$args=null){
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->delete($table_name);
	}
	public function get_user_details($id=null,$username=null,$password=null,$pin=null){
		$this->db->trans_start();
			$this->db->select('users.*, user_roles.role as user_role,user_roles.access as access, user_roles.id as user_role_id');
			$this->db->from('users');
			$this->db->join('user_roles','users.role = user_roles.id','LEFT');
			if($id != null){
				$this->db->where('users.id',$id);
			}
			if($pin != null){
				$this->db->where('users.pin',$pin);
			}
			if($username != null && $password != null){
				$this->db->where('users.username',$username);
				$this->db->where('users.password',md5($password));
			}
			$this->db->where('users.inactive',0);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();


		if(count($result) > 0){
			if(count($result) == 1){
				return $result[0];
			}
			else{
				return $result;
			}
		}
		else{
			return array();
		}
	}
	public function get_db_now($format='php',$dateOnly=false){
		if($dateOnly)
			$query = $this->db->query("SELECT DATE(now()) as today");
		else
			$query = $this->db->query("SELECT now() as today");
		$result = $query->result();
		foreach($result as $val){
			$now = $val->today;
		}
		if($format=='php'){
			if($dateOnly)
				return date('m/d/Y',strtotime($now));
			else
				return date('m/d/Y H:i:s',strtotime($now));
		}
		else
			return $now;
	}
	public function get_last_code($tbl,$col='code'){
		$code = "";
		$query = $this->db->query("SELECT max(".$col.") as code from ".$tbl,false);
		$result = $query->result();
		foreach($result as $val){
			$code = $val->code;
		}	
		return $code;
	}
	public function update_language($items,$id){
		$this->db->trans_start();
		$this->db->where('id',$id);
		$this->db->update('users',$items);
		$this->db->trans_complete();
		return $this->db->last_query();
	}
	public function get_company_profile(){
		$this->db->trans_start();
			$this->db->select('company_profile.*');
			$this->db->from('company_profile');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result[0];
	}
	public function get_custom_val($tbl,$col,$where=null,$val=null,$returnAll=false){
		if(is_array($col)){
			$colTxt = "";
			foreach ($col as $col_txt) {
				$colTxt .= $col_txt.",";
			}
			$colTxt = substr($colTxt,0,-1);
			$this->db->select($tbl.".".$colTxt);
		}
		else{
			$this->db->select($tbl.".".$col);
		}
		$this->db->from($tbl);

		if($where != '' || $val != ''){
			if(is_array($where)){
				foreach ($where as $k => $v) {
					$this->db->where($k,$v);
				}
			}
			else
				$this->db->where($tbl.".".$where,$val);
		}
		
		$query = $this->db->get();
		$result = $query->result();
		if($returnAll){
			return $result;
		}
		else{
			if(count($result) > 0){
				if(count($result) == 1)
					return $result[0];
				else
					return $result;
			}
			else
				return "";
		}
	}
	public function update_profile($user,$id){
		$this->db->where('id', $id);
		$this->db->update('users', $user);

		return $this->db->last_query();
	}
	public function get_trans_types($id=null,$ref=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('trans_types');
			if($id != null){
				$this->db->where('trans_types.type_id',$id);
			}
			if($ref != null){
				$this->db->where('trans_types.reference',$ref);
			}
			$this->db->order_by('type_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_next_ref($type=null){
		$this->db->trans_start();
			$this->db->select('next_ref');
			$this->db->from('trans_types');
			if($type != null){
				$this->db->where('trans_types.type_id',$type);
			}
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();

		$ref = $result[0]->next_ref;
		if(!$this->ref_unused($type,$ref)){
			$nr = $this->get_right_ref($type,$ref);
			$unused = $nr['unused'];
			$next_re = $nr['nr'];
			while ($unused == false) {
				$arr = $this->get_right_ref($type,$next_re);
				$unused = $arr['unused'];
				$next_re = $arr['nr'];
			}
			$ref = $next_re;
		}
		return $ref;
	}
	function ref_unused($trans_type,$ref){
		$this->db->from('trans_refs');
		$this->db->where('type_id',$trans_type);
		$this->db->where('trans_ref',$ref);
		$query=$this->db->get();
		return ($query->num_rows()>0)?false:true;		
	}
	public function save_ref($type=null,$ref=null){
		if($this->ref_unused($type,$ref)){
			$user = $this->session->userdata('user');
			$refs=$this->write_ref($type,$ref,$user['id']);
			$this->update_next_ref($type,$refs['ref']);
			// echo "here";
		}
		else{
			$nr = $this->get_right_ref($type,$ref);
			$unused = $nr['unused'];
			$next_re = $nr['nr'];
			while ($unused == false) {
				$arr = $this->get_right_ref($type,$next_re);
				$unused = $arr['unused'];
				$next_re = $arr['nr'];
			}
			$user = $this->session->userdata('user');
			$refs=$this->write_ref($type,$next_re,$user['id']);
			$this->update_next_ref($type,$refs['ref']);
		}
	}
	public function get_right_ref($type=null,$ref=null){
		$nr = $this->on_next_ref($type,$ref);
		$unused = $this->ref_unused($type,$nr);
		return array('unused'=>$unused,'nr'=>$nr);
	}
	public function on_next_ref($trans_type,$ref){
	    if (preg_match('/^(\D*?)(\d+)(.*)/', $ref, $result) == 1) 
	    {
	        list($all, $prefix, $number, $postfix) = $result;
	        $dig_count = strlen($number); // How many digits? eg. 0003 = 4
	        $fmt = '%0' . $dig_count . 'd'; // Make a format string - leading zeroes
	        $nextval =  sprintf($fmt, intval($number + 1)); // Add one on, and put prefix back on

	        $new_ref=$prefix.$nextval.$postfix;
	    }
	    else 
	        $new_ref=$ref;
	    return $new_ref;
	}
	public function write_ref($trans_type,$ref=null,$user_id=null){
		$this->db->trans_start();			
			if($ref==null)
				$ref=$this->get_next_ref($trans_type);
			$items = array(
				'type_id'=>$trans_type,
				'trans_ref'=>$ref,
				'user_id'=>$user_id
			);
			$this->db->insert('trans_refs',$items);
		$this->db->trans_complete();
		return array('ref'=>$ref);		
	}
	public function update_next_ref($trans_type,$ref){
        if (preg_match('/^(\D*?)(\d+)(.*)/', $ref, $result) == 1) 
        {
			list($all, $prefix, $number, $postfix) = $result;
			$dig_count = strlen($number); // How many digits? eg. 0003 = 4
			$fmt = '%0' . $dig_count . 'd'; // Make a format string - leading zeroes
			$nextval =  sprintf($fmt, intval($number + 1)); // Add one on, and put prefix back on

			$new_ref=$prefix.$nextval.$postfix;
        }
        else 
            $new_ref=$ref;		
		$this->db->update('trans_types',array('next_ref'=>$new_ref),array('type_id'=>$trans_type));
	}
	public function get_company_info(){
		$prefs = $this->site_model->get_settings(null,"company");
		return $prefs;
	}
}
?>