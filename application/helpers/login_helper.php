<?php
function loginPage($prefs=array()){
	$CI =& get_instance();
	$CI->html->sDiv(array('class'=>'login-box'));
		$CI->html->sDiv(array('class'=>'login-logo'));
			// $CI->html->span('<b>Admin</b>RTJ');
			$CI->html->span('<b>'.$prefs['comp_name'].'</b>');
		$CI->html->eDiv();
		$CI->html->sDiv(array('class'=>'login-box-body'));
			$CI->html->sForm("site/login_db",'login-form');
				$CI->html->input('','username',null,'Username',array('class'=>'rOkay'),'','<i class="fa fa-user"></i>');		
				$CI->html->pwd('','password',null,'Password',array('class'=>'rOkay'),'','<i class="fa fa-lock"></i>');
				$CI->html->sDivRow();
					$CI->html->sDivCol(4,'right',8);
						$CI->html->button('Sign In',array('id'=>'login-btn','class'=>'btn-flat btn-block'),'primary');	
					$CI->html->eDivCol();	
				$CI->html->eDivRow();	
			$CI->html->eForm();		
		$CI->html->eDiv();
	$CI->html->eDiv();
	return $CI->html->code();
}
?>