<?php
function form($det=array(),$new_ref=""){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(10,'left',1);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("items/db","general-form");
						$CI->html->hidden('id',iSetObj($det,'id'));
						$CI->html->sDivRow();
							$CI->html->sDivCol(6);
								$CI->html->inputPaper('Code:','code',iSetObj($det,'code',$new_ref),null,array('class'=>'rOkay'));
							$CI->html->eDivCol();
							$CI->html->sDivCol(6);
								$CI->html->inputPaper('Name:','name',iSetObj($det,'name'),null,array('class'=>'rOkay'));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->sDivRow();
							$CI->html->sDivCol(6);
								$CI->html->inputPaper('Description:','description',iSetObj($det,'description'),null,array('class'=>'rOkay'));
							$CI->html->eDivCol();
							$CI->html->sDivCol(6);
								$pop = array(
									"href"  => "items/categories_form?viewpop=1",
									"params"=> array(
										"title" => "Create new item categories",
										"id"    => "create-mat-cat-pop"
									),
								);
								$CI->html->itemCategoryDropPaper('Category:','cat_id',iSetObj($det,'cat_id'),null,array('class'=>'rOkay','pop-form'=>$pop));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->sDivRow();
							$CI->html->sDivCol(6);
								$pop = array(
									"href"  => "uom/form?viewpop=1",
									"params"=> array(
										"title" => "Create New UOM",
										"id"    => "create-uom-pop"
									),
								);
								$CI->html->uomDropPaper('UOM:','uom',iSetObj($det,'uom'),null,array('class'=>'rOkay','pop-form'=>$pop));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
					$CI->html->eForm();
				$CI->html->eBoxBody();
			$CI->html->eBox();
		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}
function categories_form($det=array()){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(6,'left',3);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("items/categories_db","general-form");
						$CI->html->hidden('id',iSetObj($det,'id'));
						$CI->html->sDivRow();
							$CI->html->sDivCol(12);
								$CI->html->inputPaper('Name:','name',iSetObj($det,'name'),null,array('class'=>'rOkay'));
							$CI->html->eDivCol();
							$CI->html->sDivCol(12);
								$pop = array(
									"href"  => "uom/form?viewpop=1",
									"params"=> array(
										"title" => "Create New UOM",
										"id"    => "create-uom-pop"
									),
								);
								$CI->html->uomDropPaper('UOM:','uom',iSetObj($det,'uom'),null,array('class'=>'rOkay','pop-form'=>$pop));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
					$CI->html->eForm();
				$CI->html->eBoxBody();
			$CI->html->eBox();
		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}
