<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Print_headers extends CI_Controller {
	public function __construct(){
		parent::__construct();
        $this->load->library('Pdf');
	}	
    public function get_company_details(){
        $prefs = $this->site_model->get_settings(null,"company");
        return $prefs;
    }    
}
