<?php
function usersPage($det=array(),$img=array()){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(10,'left',1);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("users/db","users-form");
						$CI->html->hidden('id',iSetObj($det,'id'));
						$CI->html->sDivRow();
							$CI->html->sDivCol(8);
								$CI->html->sDivRow(array('class'=>'div-under-no-spaces','style'=>'margin-top:20px;'));
									$CI->html->sDivCol(3);
										$CI->html->inputPaper(null,'fname',iSetObj($det,'fname'),'First Name',array('class'=>'rOkay input-lg'));
									$CI->html->eDivCol();
									$CI->html->sDivCol(3);
										$CI->html->inputPaper(null,'mname',iSetObj($det,'mname'),'Middle Name',array('class'=>'input-lg'));
									$CI->html->eDivCol();
									$CI->html->sDivCol(3);
										$CI->html->inputPaper(null,'lname',iSetObj($det,'lname'),'Last Name',array('class'=>'rOkay input-lg'));
									$CI->html->eDivCol();
									$CI->html->sDivCol(1);
										$CI->html->inputPaper(null,'suffix',iSetObj($det,'suffix'),'Suffix',array('class'=>'input-lg'));
									$CI->html->eDivCol();
								$CI->html->eDivRow();
								$CI->html->sDivRow(array('class'=>'div-under-no-spaces'));
									$CI->html->sDivCol(5);
										$CI->html->roleDrop(null,'role',iSetObj($det,'role'),null,array('class'=>'rOkay paper-select'));
									$CI->html->eDivCol();
								$CI->html->eDivRow();
							$CI->html->eDivCol();
							$CI->html->sDivCol(4,'right');
								$url = base_url().'dist/img/no-photo.jpg';
								if(iSetObj($img,'img_path') != ""){					
									$url = base_url().$img->img_path;
								}
								$CI->html->img($url,array('style'=>'height:100px;','class'=>'media-object thumbnail pull-right','id'=>'target'));
								$CI->html->file('fileUpload',array('style'=>'display:none;'));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->H(4,"",array('class'=>'page-header'));
						$CI->html->sDivRow();
							$CI->html->sDivCol(5);
								$CI->html->H(4,"Login Information",array('class'=>'form-titler'));
								$CI->html->inputPaper('Username:','username',iSetObj($det,'username'),null,array('class'=>'rOkay'),'fa-user');
								if(iSetObj($det,'password') != "")
									$CI->html->inputPwd('Password:','passwordis',iSetObj($det,'password'),null,array('class'=>'','disabled'=>'disabled'),'fa-lock');
								else
									$CI->html->inputPwd('Password:','password',null,null,array('class'=>'rOkay'),'fa-lock');
							$CI->html->eDivCol();
							$CI->html->sDivCol(5,'left',2);
								$CI->html->H(4,"Contact Information",array('class'=>'form-titler'));
								$CI->html->inputPaper('Contact No. :','contact_no',iSetObj($det,'contact_no'),null,array('class'=>''),'fa-phone');
								$CI->html->inputPaper('Email:','email',iSetObj($det,'email'),null,array('class'=>''),'fa-envelope');
							$CI->html->eDivCol();
						$CI->html->eDivRow();
					$CI->html->eForm();
					$CI->html->H(4,"");
				$CI->html->eBoxBody();

			$CI->html->eBox();
		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}
function usersProfile($data=array()){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(2);
			$CI->html->sBox('solid',array('style'=>'margin-bottom:5px;'));
				$CI->html->sBoxBody();
					$url = $data['img'];
					$CI->html->sDiv(array('style'=>'position:relative;width:100%;background-color:#ddd;'));
						$CI->html->img($url,array('style'=>'width:100%;max-height:300px;','id'=>'profile-pic'));
						$CI->html->sDiv(array('style'=>'position:absolute;bottom:0;left:0;width:100%;height:25px;text-align:right;padding-right:5px;color:#fff'));
							$CI->html->A(fa('fa-camera fa-lg'),'#',array('style'=>'color:#fff;','id'=>'target'));
							$CI->html->sForm("users/pic_upload","pic-form");
								$CI->html->file('fileUpload',array('style'=>'display:none;'));
								$CI->html->hidden('id',$data['id']);
							$CI->html->eForm();
						$CI->html->eDiv();
					$CI->html->eDiv();
					$CI->html->H(5,ucwords(strtolower($data['full_name'])),array('style'=>'text-align:center;'));
					$CI->html->H(6,$data['role'],array('style'=>'text-align:center;'));						
				$CI->html->eBoxBody();
			$CI->html->eBox();
			$CI->html->sDiv(array('class'=>'btn-group-vertical btn-profile-vertical','style'=>'width:100%;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);'));
				$CI->html->button(fa('fa-info-circle').' General Details',array('class'=>'load-btns btn-block btn-flat btn-white','load'=>'students/profile_general'));
			$CI->html->eDiv();
		$CI->html->eDivCol();
		$CI->html->sDivCol(10);
			
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('id'=>'load-div','user'=>""));
				$CI->html->eBoxBody();
			$CI->html->eBox();

		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}
?>