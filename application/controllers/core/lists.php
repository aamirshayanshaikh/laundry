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
    public function customers($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        $join = array();
        $args = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('name')){
            $lk  =$this->input->post('name');
            $args["(customers.name '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        $order = array();
        $cols = array('ID','Name','Contact Person','Contact No.','Email','Reg Date','Inactive','');
        $count = $this->site_model->get_tbl('customers',$args,$order,$join,true,'customers.*',null,null,true);
        $page = paginate('lists/customers',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('customers',$args,$order,$join,true,'customers.*',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg'),base_url().'customers/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>ucwords(strtolower($res->name)),   
                    "desc"=>ucwords(strtolower($res->contact_person)),   
                    "subtitle"=>$res->contact_no,   
                    "email"=>$res->email,   
                    "reg_date"=>sql2Date($res->reg_date),
                    "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    "link"=>$link
                );
                $ids[] = $res->id;
            }
            $images = $this->site_model->get_image(null,null,'customers',array('images.img_ref_id'=>$ids)); 
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
    public function customers_filter(){
        $this->html->sForm();
            $this->html->inputPaper('Name:','name','');
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
    public function item_categories(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/item_categories';
        $cols = array('ID','Name','UOM',' ');
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
                    "name"=>ucFix($res->name),   
                    "uom"=>strtoupper($res->uom),   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function items(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/items';
        $cols = array('ID','Code','Name','Category','UOM','Reg. Date',' ');
        $table = 'items';
        $select = 'items.*,item_categories.name as cat_name';
        $join['item_categories'] = "items.cat_id = item_categories.id";

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
                    "code"=>strtoupper($res->code),   
                    "name"=>ucFix($res->name),   
                    "cat_name"=>ucFix($res->cat_name),   
                    "uom"=>strtoupper($res->uom),   
                    "reg_date"=>sql2Date($res->reg_date),                  
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
                $link = "";
                if($res->rcv_qty == 0)
                    $link .= $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'purchase_orders/form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $window = "javascript:window.open('".base_url()."purchase_orders/pdf/".$res->id."','Purchase Order','width=800,height=600')";
                $link .= "&nbsp;&nbsp;".$this->html->A(fa('fa-file-pdf-o fa-lg fa-fw'),$window,array('class'=>'btn btn-sm btn-success btn-flat','return'=>'true'));
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
    public function inventory_moves(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/inventory_moves';
        $cols = array('Date','Reference','Description','Qty In','Qty Out','UOM','Location','Trans Type','Remarks','');
        $table = 'inventory_moves';
        $select = 'inventory_moves.*,locations.name as loc_name,materials.name as mat_name,materials.uom as mat_uom,
                   items.name as item_name,items.uom as item_uom,
                   trans_types.description as trans_type_name';
        $join['locations'] = "inventory_moves.loc_id = locations.id";
        $join['materials'] = array("content"=>"inventory_moves.mat_id = materials.id","mode"=>"left");
        $join['items'] = array("content"=>"inventory_moves.item_id = items.id","mode"=>"left");
        $join['trans_types'] = "inventory_moves.trans_type = trans_types.type_id";
        $order['inventory_moves.trans_date'] = 'desc';
        $order['inventory_moves.id'] = 'desc';
        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);
        
        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $qty_in = 0;
                $qty_out = 0;
                if($res->qty > 0)
                    $qty_in = $res->qty;
                else
                    $qty_out = $res->qty;
                $memo = "";
                if($res->memo)
                    $memo = $res->memo;
                $name = $res->mat_name;
                $uom = $res->mat_uom;
                if($res->item_id != ""){
                    $name = $res->item_name;
                    $uom = $res->item_uom;
                }

                $json[] = array(
                    "date"=> sql2Date($res->trans_date),   
                    "reference"=>strtoupper($res->trans_ref),   
                    "name"=>ucFix($name),   
                    "qty_in"=>num($qty_in),   
                    "qty_out"=>num($qty_out),   
                    "uom"=>strtoupper($uom),   
                    "loc_name"=>ucFix($res->loc_name),   
                    "trans_type_name"=>ucFix($res->trans_type_name),   
                    "memo"=>$memo,                  
                    "link"=>'',                  
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function work_order_stages($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/work_order_stages';
        $cols = array('ID','Name','Description',' ');
        $table = 'work_order_stages';
        $select = 'work_order_stages.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'work_order/stages_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>strtoupper($res->name),   
                    "description"=>ucFix($res->description),   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function work_order_types($tbl=null){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/work_order_types';
        $cols = array('ID','Name','Description',' ');
        $table = 'work_order_types';
        $select = 'work_order_types.*';

        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'work_order/types_form/'.$res->id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "title"=>strtoupper($res->name),   
                    "description"=>ucFix($res->description),   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
    public function work_orders(){
        $total_rows = 30;
        $pagi = null;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
       
        $post = array();
        $args = array();
        $join = array();
        $order = array();
        
        $page_link = 'lists/work_orders';
        $cols = array('ID','Reference','Batch No.','Lot No.','Type','Total Weight','UOM','Work Order Date','Current Stage','Status',' ');
        $table = 'work_orders';
        $select = 'work_orders.*,work_order_types.name as type_name,work_order_stages.name as stage_name';
        $join['work_order_types'] = "work_orders.type_id = work_order_types.id";
        // $join['work_order_stages'] = "work_orders.curr_stage_id = work_order_stages.id";
        $join['work_order_stages'] = array("content"=>"work_orders.curr_stage_id = work_order_stages.id",
                                "mode"=>"left");
        $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,null,true);
        $page = paginate($page_link,$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,null,$page['limit']);

        $json = array();
        if(count($items) > 0){
            $ids = array();
            foreach ($items as $res) {
                $link = "";
                if($res->curr_stage_id != 0)
                    $link = $this->html->A(fa('fa-edit fa-lg fa-fw'),base_url().'work_order/staging/'.$res->id.'/'.$res->curr_stage_id,array('class'=>'btn btn-sm btn-primary btn-flat','return'=>'true'));
                $link .= $this->html->A(fa('fa-table fa-lg fa-fw'),base_url().'work_order/history/'.$res->id,array('style'=>'margin-left:10px;','class'=>'btn btn-sm btn-info btn-flat','return'=>'true'));
                $progress = "In Progress";
                if($res->finished == 1){
                    $progress = "Finished";
                }
                $json[$res->id] = array(
                    "id"=>$res->id,   
                    "reference"=>strtoupper($res->reference),   
                    "batch_no"=>strtoupper($res->reference),   
                    "lot_no"=>strtoupper($res->reference),   
                    "type_name"=>ucFix($res->type_name),   
                    "weight"=>num($res->weight),   
                    "uom"=>$res->uom,   
                    "wo_date"=>sql2Date($res->wo_date),   
                    "stage"=>ucFix($res->stage_name),   
                    "progress"=>$progress,   
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('cols'=>$cols,'rows'=>$json,'pagi'=>$page['code'],'post'=>$post));
    }
}
