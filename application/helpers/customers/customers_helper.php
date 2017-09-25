<?php
function form($det=array(),$img=array(),$new_ref=""){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(10,'left',1);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("customers/db","general-form");
						$CI->html->hidden('id',iSetObj($det,'id'));
						$CI->html->sDivRow();
							$CI->html->sDivCol(8);
								$CI->html->sDivRow(array('class'=>'div-under-no-spaces','style'=>'margin-top:20px;'));
									$CI->html->sDivCol(4);
										$CI->html->inputPaper(null,'code',iSetObj($det,'code',$new_ref),'Customer Code',array('class'=>'rOkay input-lg'));
									$CI->html->eDivCol();
									$CI->html->sDivCol(6);
										$CI->html->inputPaper(null,'name',iSetObj($det,'name'),'Customer Name',array('class'=>'rOkay input-lg'));
									$CI->html->eDivCol();
								$CI->html->eDivRow();
								$CI->html->sDivRow(array('class'=>'div-under-no-spaces'));
									$CI->html->sDivCol(8);
										$CI->html->inputPaper(null,'contact_person',iSetObj($det,'contact_person'),'Contact Person',array('class'=>'rOkay input-lg'));
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
							$CI->html->sDivCol();
								$CI->html->H(4,"Contact Information",array('class'=>'form-titler'));
								$CI->html->inputPaper('Contact No. :','contact_no',iSetObj($det,'contact_no'),null,array('class'=>'rOkay'),'fa-phone');
								$CI->html->inputPaper('Email:','email',iSetObj($det,'email'),null,array('class'=>'rOkay'),'fa-envelope');
								$CI->html->inputPaper('Address:','address',iSetObj($det,'address'),null,array('class'=>'rOkay'),'fa-home');
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
?>