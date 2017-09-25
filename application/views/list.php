<?php
	$this->load->view('parts/head');
	$this->load->view('parts/topNav');
	$this->load->view('parts/listbody');
	if(isset($load_js))
		$this->load->view('js/'.$load_js);
	$this->load->view('parts/foot');
?>