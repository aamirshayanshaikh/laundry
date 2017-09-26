<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cart extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}	
    public function initial($name=null){
        sess_initialize($name);
    }    
    public function add($name=null){
        $post = $this->input->post();
        $sess = sess_add($name,$post);   
        echo json_encode($sess);
    }
    public function remove($name=null,$id=null){
        $sess = sess_delete($name,$id);   
    }
    public function all($name=null){
        $sess = sess($name);
        echo json_encode($sess);
    }
    public function check_cart($name=null,$col,$val){
        $sess = sess($name);
        $error = "";
        if(count($sess) > 0){
            foreach ($sess as $lid => $row) {
                if(isset($row[$col])){
                    // echo var_dump($row[$col],$val);
                    if($row[$col] == $val){
                        // echo "ere";
                        $error = "Invalid. Already in Cart";
                        break;
                    }
                }
                else{
                    $error = "Invalid. Column not found";
                }
            }
        }
        echo json_encode(array('error'=>$error));
    }
}
