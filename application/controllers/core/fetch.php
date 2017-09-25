<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Fetch extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}	
    public function item_categories($id=null){
        $args = array();
        $join = array();
        $order = array();
        $table = 'item_categories';
        $select = 'item_categories.*';
        $args['item_categories.id'] = $id;
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select);
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $json = array(
                    "id"=>$res->id,   
                    "type"=>$res->type,   
                    "uom"=>$res->uom,   
                    "tax_type_id"=>$res->tax_type_id,   
                );
            }
        }
        echo json_encode(array('details'=>$json));
    }
    public function items($id=null){
        $args = array();
        $join = array();
        $order = array();
        $table = 'items';
        $select = 'items.*';
        $args['items.id'] = $id;
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select);
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $json = array(
                    "id"=>$res->id,   
                    "type"=>$res->type,   
                    "uom"=>$res->uom,   
                    "price"=>$res->price,   
                    "tax_type_id"=>$res->tax_type_id,   
                );
            }
        }
        echo json_encode(array('details'=>$json));
    }
    public function students($id=null){
        $args = array();
        $join = array();
        $order = array();
        $table = 'students';
        $select = 'students.*';
        $args['students.id'] = $id;
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select);
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $json = array(
                    "id"=>$res->id,   
                    "code"=>$res->code,   
                    "fname"=>$res->fname,   
                    "mname"=>$res->mname,   
                    "lname"=>$res->lname,   
                    "suffix"=>$res->suffix,   
                    "bday"=>$res->bday,   
                    "sex"=>$res->sex,   
                    "image"=>"",   
                );
            }
            $images = $this->site_model->get_image(null,null,'students',array('images.img_ref_id'=>$id)); 
            if(count($images) > 0){
                $img = $images[0];
                $json['image'] = $img->img_path;
            }
        }
        echo json_encode(array('details'=>$json));
    }
    public function course_batches($id=null){
        $args = array();
        $join = array();
        $order = array();
        $table = 'course_batches';
        $select = 'course_batches.name,course_batches.id';
        $args['course_batches.course_id'] = $id;
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select);
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $json[] = array(
                    "id"=>$res->id,   
                    "name"=>ucFix($res->name),   
                );
            }
        }
        echo json_encode(array('details'=>$json));
    }
    public function course_batch_sections($id=null){
        $args = array();
        $join = array();
        $order = array();
        $table = 'course_batch_sections';
        $select = 'sections.name,sections.id';
        $join['sections'] = "course_batch_sections.section_id = sections.id";
        $args['course_batch_sections.batch_id'] = $id;
        $items = $this->site_model->get_tbl($table,$args,$order,$join,true,$select);
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $json[] = array(
                    "id"=>$res->id,   
                    "name"=>ucFix($res->name),   
                );
            }
        }
        echo json_encode(array('details'=>$json));
    }    
}
