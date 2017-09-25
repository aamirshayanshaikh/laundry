<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lists extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('pagination_helper');
	}
	public function users($tbl=null){
		$total_rows = 30;
		$pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
		$post = array();
		$args = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(users.fname like '%".$lk."%' OR users.mname like '%".$lk."%' OR users.lname like '%".$lk."%' OR users.suffix like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('role')){
            $args['users.role'] = array('use'=>'where','val'=>$this->input->post('role'));
        }
		$order = array();
        $cols = array('ID','Name','Role','Email','Reg Date','Inactive','');
		$join["user_roles"] = array('content'=>"users.role = user_roles.id");
		$count = $this->site_model->get_tbl('users',$args,$order,$join,true,'users.*,user_roles.role as role_name',null,null,true);
		$page = paginate('lists/users',$count,$total_rows,$pagi);
		$items = $this->site_model->get_tbl('users',$args,$order,$join,true,'users.*,user_roles.role as role_name',null,$page['limit']);
		$json = array();
		if(count($items) > 0){
			$ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg'),base_url().'users/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $name = $res->fname." ".$res->mname." ".$res->lname." ".$res->suffix;
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>ucwords(strtolower($name)),   
                    "desc"=>ucwords(strtolower($res->role_name)),   
                    "subtitle"=>$res->email,   
                    "reg_date"=>sql2Date($res->reg_date),
                    "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    "link"=>$link
                );
                $ids[] = $res->id;
            }
            $images = $this->site_model->get_image(null,null,'users',array('images.img_ref_id'=>$ids)); 
            foreach ($images as $res) {
                if(isset($json[$res->img_ref_id])){
                    $js = $json[$res->img_ref_id];
                    $js['grid-image'] = $res->img_path;
                    $json[$res->img_ref_id] = $js;
                }
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function users_filter(){
        $this->html->sForm();
            $this->html->inputPaper('Name:','name','');
            $this->html->roleDropPaper('Role:','role','',null,array('class'=>'paper-select'));
        $this->html->eForm();
        $data['code'] = $this->html->code();
        $this->load->view('load',$data);   
	}	
    public function roles($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/users';
        $cols = array('ID','Name','Description',' ');
        $table = 'user_roles';
        $select = 'user_roles.*';
        $args['user_roles.id != '] = 1; 

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'admin/roles_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>ucwords(strtolower($res->role)),   
                    "desc"=>ucwords(strtolower($res->description)),   
                    "link"=>$link
                );
            }
        }

        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function uom($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/uom';
        $cols = array('ID','Code','Name',' ');
        $table = 'uom';
        $select = 'uom.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'uom/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>strtoupper($res->abbrev),   
                    "name"=>ucFix($res->name),   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function materials(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/materials';
        $cols = array('ID','Code','Name','Category','UOM','Type','Tax Type','Reg. Date',' ');
        $table = 'materials';
        $select = 'materials.*,material_categories.name as cat_name,tax_types.name as tax_type';
        $join['material_categories'] = "materials.cat_id = material_categories.id";
        $join['tax_types'] = "materials.tax_type_id = tax_types.id";

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'materials/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "code"=>strtoupper($res->code),   
                    "name"=>ucFix($res->name),   
                    "cat_name"=>ucFix($res->cat_name),   
                    "uom"=>strtoupper($res->uom),   
                    "type"=>ucFix($res->type),   
                    "tax_type"=>ucFix($res->tax_type),      
                    "reg_date"=>sql2Date($res->reg_date),                  
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function locations(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/locations';
        $cols = array('ID','Name','Address','Contact No.','Contact Person',' ');
        $table = 'locations';
        $select = 'locations.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'locations/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "name"=>ucFix($res->name),   
                    "address"=>$res->address,   
                    "contact_no"=>$res->contact_no,   
                    "contact_person"=>ucFix($res->contact_person),   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function suppliers(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/suppliers';
        $cols = array('ID','Code','Name','TIN','Contact No.','Address','Reg. Date',' ');
        $table = 'suppliers';
        $select = 'suppliers.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'suppliers/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "code"=>strtoupper($res->code),   
                    "name"=>ucFix($res->name),   
                    "tin"=>strtoupper($res->tin),   
                    "contact_no"=>$res->contact_no,   
                    "address"=>$res->address,   
                    "reg_date"=>sql2Date($res->reg_date),                  
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function material_categories(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/material_categories';
        $cols = array('ID','Name','UOM','Type',' ');
        $table = 'material_categories';
        $select = 'material_categories.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'materials/categories_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "name"=>ucFix($res->name),   
                    "uom"=>strtoupper($res->uom),   
                    "type"=>ucFix($res->type),                       
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function purchase_orders(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/purchase_orders';
        $cols = array('ID','Reference','Supplier','Receive Location','Total Amount','Total Qty','Total Received Qty','Reg. Date',' ');
        $table = 'purchase_orders';
        $select = 'purchase_orders.*,suppliers.name as supplier_name,locations.name as loc_name';
        $join['suppliers'] = "purchase_orders.supplier_id = suppliers.id";
        $join['locations'] = "purchase_orders.rcv_loc_id = locations.id";

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'purchase_orders/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "reference"=>strtoupper($res->reference),   
                    "supplier_name"=>ucFix($res->supplier_name),   
                    "loc_name"=>ucFix($res->loc_name),   
                    "totaol_amt"=>num($res->total_amount),   
                    "total_qty"=>num($res->total_qty),   
                    "total_rcv"=>num($res->rcv_qty),   
                    "reg_date"=>sql2Date($res->reg_date),                  
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function purchase_orders_receive(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/purchase_orders';
        $cols = array('ID','Reference','Supplier','Receive Location','Total Amount','Total Qty','Total Received Qty','Reg. Date',' ');
        $table = 'purchase_orders';
        $select = 'purchase_orders.*,suppliers.name as supplier_name,locations.name as loc_name';
        $join['suppliers'] = "purchase_orders.supplier_id = suppliers.id";
        $join['locations'] = "purchase_orders.rcv_loc_id = locations.id";
        $args["purchase_orders.total_qty > purchase_orders.rcv_qty"] = array('use'=>'where','val'=>"",'third'=>false);
        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-download fa-lg fa-fw'),base_url().'receive_orders/form/'.$res->id,array('class'=>'btn btn-sm btn-success btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "reference"=>strtoupper($res->reference),   
                    "supplier_name"=>ucFix($res->supplier_name),   
                    "loc_name"=>ucFix($res->loc_name),   
                    "totaol_amt"=>num($res->total_amount),   
                    "total_qty"=>num($res->total_qty),   
                    "total_rcv"=>num($res->rcv_qty),   
                    "reg_date"=>sql2Date($res->reg_date),                  
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function receive_orders(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/receive_orders';
        $cols = array('ID','Reference','Order Reference','Location','Total Received','Received Date',' ');
        $table = 'receive_orders';
        $select = 'receive_orders.*,locations.name as loc_name';
        $join['locations'] = "receive_orders.loc_id = locations.id";

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-eye fa-lg fa-fw'),base_url().'receive_orders/rec_view/'.$res->id,array('title'=>'Receive Order #'.$res->reference,'class'=>'btn btn-sm btn-info btn-flat view-pop','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "reference"=>strtoupper($res->reference),   
                    "ord_ref"=>strtoupper($res->order_ref),   
                    "loc_name"=>ucFix($res->loc_name),   
                    "total_qty"=>num($res->total_rcv),   
                    "receive_date"=>sql2Date($res->receive_date),                  
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function qoh(){
        $this->load->model('inventory_model');
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        $page_link = 'lists/receive_orders';

        $cols = array('Code','Material');
        $table = 'materials';
        $select = 'materials.id,materials.name,materials.code';
        $args = array('inactive'=>0);
        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);
        $locations = $this->site_model->get_tbl('locations',array('inactive'=>0));
        $locs = array();
        foreach ($locations as $res) {
            $cols[] = ucFix($res->name);
        }
        $cols[] = '';        
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {            
                $row = array();
                $row['mat_code'] = $res->code; 
                $row['mat_name'] = ucFix($res->name); 
                foreach ($locations as $loc) {
                    $qoh = $this->inventory_model->get_curr_qty($res->id,$loc->id);
                    $row[$loc->name] = num($qoh); 
                }
                $row['link'] = "";   
                $json[$res->id] =  $row;

            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function years($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/roles';
        $cols = array('ID','Name','Start Date','End Date',' ');
        $table = 'academic_years';
        $select = 'academic_years.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'academic/years_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>ucwords(strtolower($res->name)),   
                    "start_date"=>sql2Date($res->start_date),   
                    "end_date"=>sql2Date($res->end_date),   
                    "link"=>$link
                );
            }
        }

        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function courses($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/courses';
        $cols = array('ID','Code','Name','Description',' ');
        $table = 'courses';
        $select = 'courses.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'academic/courses_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>strtoupper($res->code),   
                    "name"=>ucFix($res->name),   
                    "description"=>$res->description,   
                    "link"=>$link
                );
            }
        }

        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function course_batches($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/course_batches';
        $cols = array('ID','Code','Name','Course','Start Date','End Date',' ');
        $table = 'course_batches';
        $select = 'course_batches.*,courses.name as course_name';
        $join['courses'] = "course_batches.id = courses.id";

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'academic/batches_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>strtoupper($res->code),   
                    "name"=>ucFix($res->name),   
                    "course"=>ucFix($res->course_name),   
                    "start_date"=>sql2Date($res->start_date),   
                    "end_date"=>sql2Date($res->end_date),   
                    "link"=>$link
                );
            }
        }

        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function subjects($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/subjects';
        $cols = array('ID','Code','Name','Description',' ');
        $table = 'subjects';
        $select = 'subjects.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'academic/subjects_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>strtoupper($res->code),   
                    "name"=>ucFix($res->name),   
                    "description"=>$res->description,   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function sections($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/sections';
        $cols = array('ID','Code','Name',' ');
        $table = 'sections';
        $select = 'sections.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'academic/sections_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>strtoupper($res->code),   
                    "name"=>ucFix($res->name),   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function students($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        
        $cols = array('ID','Code','Name','Gender','Age','Reg Date','Course','Batch','Section','');
        $table  = 'students';
        $select = 'students.*,
                   enrolls.start_date,enrolls.end_date,
                   courses.name as course_name,
                   course_batches.name as batch_name,
                   sections.name as section_name,
                  ';
        $join['enrolls'] = array("content"=>"students.enroll_id = enrolls.id and enrolls.inactive = 0",
                                 "mode"=>"left");
        $join['courses'] = array("content"=>"enrolls.course_id = courses.id",
                                 "mode"=>"left");
        $join['course_batches'] = array("content"=>"enrolls.batch_id = course_batches.id",
                                 "mode"=>"left");
        $join['sections'] = array("content"=>"enrolls.section_id = sections.id",
                                 "mode"=>"left");

        $post = array();
        $args = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(students.fname like '%".$lk."%' OR students.mname like '%".$lk."%' OR students.lname like '%".$lk."%' OR students.suffix like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }        
        $order = array('students.fname'=>'asc');
        
        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate('lists/students',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);
        // echo $this->site_model->db->last_query();
        // return false;
        
        $json = array();
        if(count($items) > 0){
            $ids = array();
            $now = $this->site_model->get_db_now('sql',true);
            
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg'),base_url().'students/profile/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $name = $res->fname." ".$res->mname." ".$res->lname." ".$res->suffix;
                
                $course = ucFix($res->course_name);
                $batch = ucFix($res->batch_name);
                $section = ucFix($res->section_name);                
                if(strtotime($now) > strtotime($res->end_date)){
                    $course = "";
                    $batch = "";
                    $section = "";
                }

                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "code"=>$res->code,   
                    "title"=>$name,   
                    "desc"=>strtoupper($res->sex),   
                    "subtitle"=>"Age ".age($res->bday),   
                    "reg_date_"=>sql2Date($res->reg_date),
                    "other"=>$course,   
                    "batch"=>$batch,   
                    "section"=>$section,   
                    "inactive"=>$res->inactive,
                    "link"=>$link
                );
                $ids[] = $res->id;
            }
            $images = $this->site_model->get_image(null,null,'students',array('images.img_ref_id'=>$ids)); 
            foreach ($images as $res) {
                if(isset($json[$res->img_ref_id])){
                    $js = $json[$res->img_ref_id];
                    $js['grid-image'] = $res->img_path;
                    $json[$res->img_ref_id] = $js;
                }
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function students_filter(){
        $this->html->sForm();
            $this->html->inputPaper('Name:','name','');
        $this->html->eForm();
        $data['code'] = $this->html->code();
        $this->load->view('load',$data);   
    }
	public function get_menus($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 30;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('menu_name')){
            $lk  =$this->input->post('menu_name');
            $args["(menus.menu_name like '%".$lk."%' OR menus.menu_short_desc like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        // if($this->input->post('email')){
             // $args['users.email'] = array('use'=>'or_like','val'=>$this->input->post('email'));
        // }
        // if($this->input->post('email')){
            // $args['DATE(users.reg_date) = date('.date2Sql($this->input->post('reg_date')).')'] = array('use'=>'where','val'=>"",'third'=>false);
        // }
        if($this->input->post('inactive')){
            $args['menus.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        if($this->input->post('menu_cat_id')){
            $args['menus.menu_cat_id'] = array('use'=>'where','val'=>$this->input->post('menu_cat_id'));
        }
        $join["menu_categories"] = array('content'=>"menus.menu_cat_id = menu_categories.menu_cat_id");
        $count = $this->site_model->get_tbl('menus',$args,array(),$join,true,'menus.*,menu_categories.menu_cat_name',null,null,true);
        $page = paginate('menu/get_menus',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('menus',$args,array(),$join,true,'menus.*,menu_categories.menu_cat_name',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->make->A(fa('fa-edit fa-lg'),base_url().'menu/form/'.$res->menu_id,array('return'=>'true'));
                $json[$res->menu_id] = array(
                    "id"=>$res->menu_id,   
                    "title"=>"[".$res->menu_code."] ".ucwords(strtolower($res->menu_name)),   
                    "desc"=>ucwords(strtolower($res->menu_short_desc)),   
                    "subtitle"=>ucwords(strtolower($res->menu_cat_name)),   
                    "caption"=>"PHP ".num($res->cost),
                    "date_reg"=>sql2Date($res->reg_date),
                    "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    "link"=>$link
                );
                $ids[] = $res->menu_id;
            }
            $images = $this->site_model->get_image(null,null,'menus',array('images.img_ref_id'=>$ids)); 
            foreach ($images as $res) {
                if(isset($json[$res->img_ref_id])){
                    $js = $json[$res->img_ref_id];
                    $js['image'] = $res->img_path;
                    $json[$res->img_ref_id] = $js;
                }
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }    
    public function item_categories($id=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/item_categories';
        $cols = array('ID','Name','UOM','Type',' ');
        $table = 'item_categories';
        $select = 'item_categories.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'items/categories_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>ucFix($res->name),   
                    "uom"=>$res->uom,   
                    "name"=>ucFix($res->type),   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function items($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/items';
        $cols = array('ID','Name','Category','UOM','Type','Price',' ');
        $table = 'items';
        $select = 'items.*,item_categories.name as cat_name';
        $join['item_categories'] = "items.cat_id = item_categories.id";

        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('code')){
            $lk  =$this->input->post('code');
            $args["(items.code like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(items.name like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('cat_id')){
            $args['items.cat_id'] = array('use'=>'where','val'=>$this->input->post('cat_id'));
        }

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'items/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>"[".$res->code."] ".ucFix($res->name),   
                    "desc"=>ucFix($res->cat_name),   
                    "uom"=>$res->uom,   
                    "name"=>ucFix($res->type),   
                    "subtitle"=>num($res->price),   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function items_filter(){
        $this->html->sForm();
            $this->html->inputPaper('Code:','code','');
            $this->html->inputPaper('Name:','name','');
            $this->html->itemCategoriesDropPaper('Category:','cat_id','');
        $this->html->eForm();
        $data['code'] = $this->html->code();
        $this->load->view('load',$data);   
    }
    public function enrolls($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/enrolls';
        $cols = array('Reference','Student','Course','Batch','Section','Start Date','End Date','Trans Date',' ');
        $table = 'enrolls';
        $select[0] = 'enrolls.*,
                   students.fname as std_fname,students.mname as std_mname,students.lname as std_lname,students.suffix as std_suffix,
                   courses.name as course_name,
                   course_batches.name as batch_name,
                   sections.name as section_name,
                   IFNULL(SUM(payment_for.amount),0) as amount_paid 
                   ';
        $select[1] = false;
        $join['students'] = "enrolls.student_id = students.id";
        $join['courses'] = "enrolls.course_id = courses.id";
        $join['course_batches'] = "enrolls.batch_id = course_batches.id";
        $join['sections'] = "enrolls.section_id = sections.id";
        $join['payment_for'] = array("content"=>"enrolls.id = payment_for.src_id AND payment_for.src_type='".ENROLLMENT."'",
                                     "mode"=>"left");

        if($this->input->post('student_name')){
            $lk  =$this->input->post('student_name');
            $args["(students.fname like '%".$lk."%' OR students.mname like '%".$lk."%' OR students.lname like '%".$lk."%' OR students.suffix like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('trans_ref')){
            $lk  =$this->input->post('trans_ref');
            $args["(enrolls.trans_ref like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        $args['enrolls.inactive'] = 0; 
        if($this->input->post('inactive') == 1){
            unset($args['enrolls.inactive']);
        }
        $order = array('enrolls.reg_date'=>'desc');
        $group = "enrolls.id";
        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,$page['limit']);
        
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = "";
                $inactive = $res->inactive;
                if($inactive == 0 && $res->amount_paid == 0){
                    $link .= $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'enrollment/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                }
                if($inactive == 0){
                    $link .= " ".$this->html->A(fa('fa-times fa-lg fa-fw'),'void/form/'.$res->id.'/'.ENROLLMENT,array('class'=>'void-btn btn btn-sm btn-danger btn-flat','ref'=>$res->id,'return'=>'true'));
                }

                $name  = $res->std_fname." ".$res->std_mname." ".$res->std_lname." ".$res->std_suffix;
                $json[] = array(
                    "title"     =>  strtoupper($res->trans_ref),   
                    "name"      =>  ucFix($name),   
                    "course"    =>  ucFix($res->course_name),   
                    "batch"     =>  ucFix($res->batch_name),   
                    "section"   =>  ucFix($res->section_name),   
                    "start_date"=>  sql2Date($res->start_date),   
                    "end_date"  =>  sql2Date($res->end_date),   
                    "trans_date"=>  sql2Date($res->trans_date),   
                    "link"      =>  $link,
                    "inactive"  =>  $inactive,
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function enrolls_filter(){
        $this->html->sForm();
            $this->html->inputPaper('Reference:','trans_ref','');
            $this->html->inputPaper('Student:','student_name','');
            $this->html->selectPaper('Show Inactive','inactive',array('Yes'=>1,'No'=>0),0);
        $this->html->eForm();
        $data['code'] = $this->html->code();
        $this->load->view('load',$data);   
    }
    public function payments($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/payments';
        $cols = array('Reference','Student','Total Amount','Trans Date',' ');
        $table = 'payments';
        $select = 'payments.*,
                   students.fname as std_fname,students.mname as std_mname,students.lname as std_lname,students.suffix as std_suffix';
        $join['students'] = "payments.student_id = students.id";

        if($this->input->post('student_name')){
            $lk  =$this->input->post('student_name');
            $args["(students.fname like '%".$lk."%' OR students.mname like '%".$lk."%' OR students.lname like '%".$lk."%' OR students.suffix like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('trans_ref')){
            $lk  =$this->input->post('trans_ref');
            $args["(payments.trans_ref like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        $args['payments.inactive'] = 0; 
        if($this->input->post('inactive') == 1){
            unset($args['payments.inactive']);
        }
        $order = array('payments.trans_date'=>'desc','payments.id'=>'desc');
        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = "";
                $inactive = 1;
                if($res->inactive == 0){
                    $inactive = 0;
                    $link = $this->html->A(fa('fa-times fa-lg fa-fw'),'void/form/'.$res->id.'/'.PAYMENT,array('class'=>'void-btn btn btn-sm btn-danger btn-flat','ref'=>$res->id,'return'=>'true'));
                }


                $name  = $res->std_fname." ".$res->std_mname." ".$res->std_lname." ".$res->std_suffix;
                $json[] = array(
                    "title"      =>  strtoupper($res->trans_ref),   
                    "name"       =>  ucFix($name),   
                    "total"      =>  num($res->total_amount),   
                    "trans_date" =>  sql2Date($res->trans_date),   
                    "link"       =>  $link,
                    "inactive"   =>  $inactive
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function payments_filter(){
        $this->html->sForm();
            $this->html->inputPaper('Reference:','trans_ref','');
            $this->html->inputPaper('Student:','student_name','');
            $this->html->selectPaper('Show Inactive','inactive',array('Yes'=>1,'No'=>0),0);
        $this->html->eForm();
        $data['code'] = $this->html->code();
        $this->load->view('load',$data);   
    }
    public function balances($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/payments';
        $cols = array('Student','Reference','Total Amount','Total Paid','Remaining Balance',' ');
        $table = 'enroll_payments';
        $select[0] = 'enroll_payments.*,enrolls.trans_ref,
                   IFNULL(SUM(enroll_payments.amount), 0) as total_amount,
                   IFNULL(SUM(enroll_payments.pay), 0) as total_paid,
                   students.fname as std_fname,students.mname as std_mname,students.lname as std_lname,students.suffix as std_suffix';
        $select[1] = false;
        $join['students'] = "enroll_payments.student_id = students.id";
        $join['enrolls'] = 'enroll_payments.enroll_id = enrolls.id';
        $group = 'enroll_payments.student_id';

        $args['enrolls.inactive'] = 0;

        if($this->input->post('student_name')){
            $lk  =$this->input->post('student_name');
            $args["(students.fname like '%".$lk."%' OR students.mname like '%".$lk."%' OR students.lname like '%".$lk."%' OR students.suffix like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('trans_ref')){
            $lk  =$this->input->post('trans_ref');
            $args["(payments.trans_ref like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,$page['limit']);
        
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $name  = $res->std_fname." ".$res->std_mname." ".$res->std_lname." ".$res->std_suffix;
                $balance = $res->total_amount - $res->total_paid;
                $link = "";
                $link = $this->html->A(fa('fa-calendar fa-lg fa-fw'),'students/profile_balance/'.$res->student_id,array(
                                       'class'=>'view-btn btn btn-sm btn-primary btn-flat',
                                       'title'=>fa('fa-mortar-board')." ".$name,
                                       'return'=>'true'));
                $json[] = array(
                    "title"             =>  ucFix($name),   
                    "reference"         =>  strtoupper($res->trans_ref),   
                    "total"             =>  num($res->total_amount),   
                    "paid"              =>  num($res->total_paid),   
                    "balance"           =>  num($balance),   
                    "link"              =>  $link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function balances_filter(){
        $this->html->sForm();
            $this->html->inputPaper('Reference:','trans_ref','');
            $this->html->inputPaper('Student:','student_name','');
        $this->html->eForm();
        $data['code'] = $this->html->code();
        $this->load->view('load',$data);   
    }
    public function billings($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
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
        if($this->input->post('student_name')){
            $lk  =$this->input->post('student_name');
            $args["(students.fname like '%".$lk."%' OR students.mname like '%".$lk."%' OR students.lname like '%".$lk."%' OR students.suffix like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        if($this->input->post('trans_ref')){
            $lk  =$this->input->post('trans_ref');
            $args["(payments.trans_ref like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            $curr = "";
            foreach ($items as $res) {
                $name  = $res->std_fname." ".$res->std_mname." ".$res->std_lname." ".$res->std_suffix;
                $particular = "Monthly Payment";
                if($res->type == 'dp')
                    $particular = "Down Payment";
                $balance = $res->amount - $res->pay;
                $json[] = array(
                    "id"                => $res->id,
                    "tagid"             => $res->id,
                    "student_id"        => $res->student_id,
                    "title"             => ucFix($name),   
                    "desc"              => $particular,   
                    "subtitle"             => "Balance Due: ".num($balance),   
                    "other"             => "Due Date: ".sql2Date($res->due_date),   
                    // "paid"              =>  num($res->total_paid),   
                    // "balance"           =>  num($balance),   
                    // "link"              =>  $link
                );
                $ids[$res->id] = $res->student_id;
            }
            $images = $this->site_model->get_image(null,null,'students',array('images.img_ref_id'=>$ids)); 
            foreach ($json as $ctr => $row) {
                foreach ($images as $res ){
                    if($row['student_id'] == $res->img_ref_id){
                        $row['grid-image'] = $res->img_path;
                        break;
                    }
                }
                $json[$ctr] = $row;
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$pagi,'post'=>$post));
    }
    public function billings_filter(){
        $this->html->sForm();
            // $this->html->inputPaper('Reference:','trans_ref','');
            $this->html->inputPaper('Student:','student_name','');
        $this->html->eForm();
        $data['code'] = $this->html->code();
        $this->load->view('load',$data);   
    }
    public function classes($teacher_id=null,$for="attendance"){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        $group = null;
        $page_link = 'lists/uom';
        $cols = array('Course','Batch','Section','Subject',' ');
        $table =  'course_batch_schedules';
        $select = 'course_batch_schedules.*,
                   subjects.code subj_code,subjects.name as subj_name,
                   sections.name as section_name,
                   course_batches.name as batch_name,
                   courses.name as course_name,
                  ';
        $join['subjects'] = array("content"=>"course_batch_schedules.subject_id = subjects.id","mode"=>"left");
        $join['sections'] = array("content"=>"course_batch_schedules.section_id = sections.id","mode"=>"left");
        $join['course_batches'] = array("content"=>"course_batch_schedules.batch_id = course_batches.id","mode"=>"left");
        $join['courses'] = array("content"=>"course_batch_schedules.course_id = courses.id","mode"=>"left");

        if($teacher_id != null)
            $args['course_batch_schedules.teacher_id'] = $teacher_id;
        $order = array('sections.name'=>'asc','subjects.name'=>'desc');
        $group = "course_batch_schedules.subject_id,course_batch_schedules.section_id";
        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = "";
                if($for == 'attendance')
                    $link .= $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'class_record/attendance_form/'.$res->batch_id.'/'.$res->section_id.'/'.$res->subject_id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                if($for == 'activites')
                    $link .= $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'class_record/activities_list/'.$res->batch_id.'/'.$res->section_id.'/'.$res->subject_id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                
                $json[$res->id] = array(
                    "course"=>ucFix($res->course_name),   
                    "batch"=>ucFix($res->batch_name),   
                    "course"=>ucFix($res->course_name),   
                    "section"=>ucFix($res->section_name),   
                    "subject"=>ucFix($res->subj_name),   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function classes_filter(){
        $this->html->sForm();
            $this->html->sectionsDropPaper('Section:','section');
        $this->html->eForm();
        $data['code'] = $this->html->code();
        $this->load->view('load',$data);   
    }
    public function class_activities($teacher_id=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        $group = null;
        $page_link = 'lists/uom';
        $cols = array('Title','Description','Date',' ');
        $table =  'student_activities';
        $select = 'student_activities.*,
                   subjects.code subj_code,subjects.name as subj_name,
                   sections.name as section_name,
                   course_batches.name as batch_name,
                   courses.name as course_name,
                  ';
        $join['subjects'] = array("content"=>"student_activities.subject_id = subjects.id","mode"=>"left");
        $join['sections'] = array("content"=>"student_activities.section_id = sections.id","mode"=>"left");
        $join['course_batches'] = array("content"=>"student_activities.batch_id = course_batches.id","mode"=>"left");
        $join['courses'] = array("content"=>"course_batches.course_id = courses.id","mode"=>"left");

        // if($teacher_id != null)
        //     $args['student_activities.teacher_id'] = $teacher_id;
        // $order = array('sections.name'=>'asc','subjects.name'=>'desc');
        // $group = "student_activities.subject_id,student_activities.section_id";
        // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        // $page = paginate($page_link,$count,$total_rows,$pagi);
        // $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,$page['limit']);

        // $json = array();
        // if(count($items) > 0){
        //     $ids = array();
        //     foreach ($items as $res) {
        //         $link = "";
        //         if($for == 'attendance')
        //             $link .= $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'class_record/attendance_form/'.$res->batch_id.'/'.$res->section_id.'/'.$res->subject_id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
        //         if($for == 'activites')
        //             $link .= $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'class_record/activities_list/'.$res->batch_id.'/'.$res->section_id.'/'.$res->subject_id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                
        //         $json[$res->id] = array(
        //             "course"=>ucFix($res->course_name),   
        //             "batch"=>ucFix($res->batch_name),   
        //             "course"=>ucFix($res->course_name),   
        //             "section"=>ucFix($res->section_name),   
        //             "subject"=>ucFix($res->subj_name),   
        //             "link"=>$link
        //         );
        //     }
        // }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
}
