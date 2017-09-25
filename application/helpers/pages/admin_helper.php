<?php
function rolesForm($det=array(),$navs=array()){
	$CI =& get_instance();
		$CI->html->sForm("admin/roles_db","roles-form");
		$CI->html->hidden('id',iSetObj($det,'id'));
		$CI->html->sBox('solid');
			$CI->html->sBoxBody(array('class'=>'paper'));
				$CI->html->sDivRow();
					$CI->html->sDivCol(3);
						$CI->html->input('Role name','role_name',iSetObj($det,'role'),null,array('class'=>'rOkay'));
					$CI->html->eDivCol();
					$CI->html->sDivCol(7);
						$CI->html->input('Description','description',iSetObj($det,'description'),null,array('class'=>'rOkay'));
					$CI->html->eDivCol();
					$CI->html->sDivCol(2,'left',0,array('style'=>'padding-top:23px;'));
						$CI->html->button(fa('fa-save')." SAVE ",array('id'=>'save-btn','class'=>'btn-flat','style'=>'margin-right:10px;'),'success');
						$CI->html->A(fa('fa-reply'),base_url()."admin/roles",array('class'=>'btn btn-primary btn-flat'));
					$CI->html->eDivCol();
				$CI->html->eDivRow();
			$CI->html->eBoxBody();
		$CI->html->eBox();
		$CI->html->sBox('solid');
			$CI->html->sBoxBody(array('class'=>'paper'));
				$access = explode(',',iSetObj($det,'access'));
				foreach ($navs as $code => $row) {
					if($row['exclude'] == 0){
						$CI->html->sDivRow();
							$CI->html->sDivCol();
								$check = false;
		                    	if(in_array($code, $access))
			                    	$check = true;
			                    $checkbox = $CI->html->checkbox($row['title'],'roles[]',$code,array('return'=>true,'id'=>$code,'class'=>'check'),$check);
								$CI->html->H(4,$checkbox);
								if(is_array($row['path'])){	
									$CI->html->append(underRoles($row['path'],$access,$code));
								}	
							$CI->html->eDivCol();
						$CI->html->eDivRow();
								
					}
				}
			$CI->html->eBoxBody();
		$CI->html->eBox();
		$CI->html->eForm();
	return $CI->html->code();
}
function underRoles($nav=array(),$access=array(),$main=null){
	$CI =& get_instance();	
	foreach ($nav as $code => $nv) {
		$CI->html->sDivRow(array('style'=>'margin-left:10px;'));
			$CI->html->sDivCol();
				$check = false;
            	if(in_array($code, $access))
                	$check = true;
				$CI->html->checkbox($nv['title'],'roles[]',$code,array('class'=>$main." check",'parent'=>$main,'id'=>$code),$check);
				if(is_array($nv['path'])){
					$CI->html->append(underRoles($nv['path'],$access,$main." ".$code." "));
				}
			$CI->html->eDivCol();
		$CI->html->eDivRow();	
	}
	return $CI->html->code();
}
function companyPage($prefs=array()){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(10,'left',1);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("admin/company_db","general-form");
						$CI->html->sDivRow();
							$CI->html->sDivCol(8);
								$CI->html->sDivRow(array('class'=>'div-under-no-spaces','style'=>'margin-top:20px;'));
									$CI->html->sDivCol(6);
										$CI->html->inputPaper(null,'comp_name',$prefs['comp_name'],'Compnay Name',array('class'=>'rOkay input-lg'));
									$CI->html->eDivCol();
								$CI->html->eDivRow();
							$CI->html->eDivCol();
							$CI->html->sDivCol(4,'right');
								$url = base_url().'dist/img/no-photo.jpg';
								if($prefs['comp_logo'] != ""){					
									$url = base_url().$prefs['comp_logo'];
								}
								$CI->html->img($url,array('style'=>'height:100px;','class'=>'media-object thumbnail pull-right','id'=>'target'));
								$CI->html->file('fileUpload',array('style'=>'display:none;'));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->H(4,"",array('class'=>'page-header'));
						$CI->html->sDivRow();
							$CI->html->sDivCol(6);
								$CI->html->H(4,"General Information",array('class'=>'form-titler'));
								$CI->html->inputPaper('TIN:','comp_tin',$prefs['comp_tin'],null,array());
								$CI->html->inputPaper('Email:','comp_email',$prefs['comp_email'],null,array(),'fa-envelope');
								$CI->html->inputPaper('Contact Number:','comp_contact_no',$prefs['comp_contact_no'],null,array(),'fa-phone');
								$CI->html->inputPaper('Address:','comp_address',$prefs['comp_address'],null,array(),'fa-home');
							$CI->html->eDivCol();
						$CI->html->eDivRow();
					$CI->html->eForm();
					$CI->html->H(4,"",array('class'=>'page-header'));
				$CI->html->eBoxBody();
			$CI->html->eBox();
		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}
?>