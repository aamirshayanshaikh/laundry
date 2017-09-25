<?php
function listPage($title="",$tbl_name="",$form="",$default_view="grid",$view="all",$filter=false,$urlAddOns=null){
	$CI =& get_instance();
	$CI->html->sDiv(array('class'=>'list-page default-list'));
		$CI->html->sDiv(array('class'=>'list-page-head'));
			$CI->html->sDivRow();
				$CI->html->sDivCol(6);
					$CI->html->H(3,$title,array('class'=>'list-page-title'));
				$CI->html->eDivCol();
				$CI->html->sDivCol(6);
				$CI->html->eDivCol();
			$CI->html->eDivRow();
			$CI->html->sDivRow();
				$CI->html->sDivCol(5);
					if($form != "")
						$CI->html->button(fa('fa-plus fa-fw').' CREATE',array('class'=>'btn-create btn-flat pull-left'),'success');
				$CI->html->eDivCol();
				$CI->html->sDivCol(3,'left');
					$CI->html->sDiv(array('class'=>'list-page-pagi pull-left'));
					$CI->html->eDiv();
				$CI->html->eDivCol();
				$CI->html->sDivCol(4,'right');
					$grid = "";
					$list = "";
					// $CI->html->button(fa('fa-filter'),array('class'=>'btn-flat pull-right'),'primary');
					$fil = "";
					if($filter)
						$fil = '<button type="button" class="btn-view-filter btn btn-info btn-flat" style="margin-right:5px;">'.fa('fa-filter').'</button>';
					$CI->html->append($fil);
					if($view == "all"){
						$g = "";
						if($default_view == 'grid')
							$g = "active";
						$grid = '<button type="button" class="btn-views btn-view-grid btn btn-default btn-flat '.$g.'">'.fa('fa-table').'</button>';
						
						$l = "";
						if($default_view == 'list')
							$l = "active";
						$list = '<button type="button" class="btn-views btn-view-list btn btn-default btn-flat '.$l.'">'.fa('fa-list').'</button>';
					}

					$CI->html->append('
						<div class="btn-group pull-right" data-toggle="btn-toggle" role="group" >'.$grid.$list.'</div>
					');
				$CI->html->eDivCol();
			$CI->html->eDivRow();
		$CI->html->eDiv();
		$CI->html->sDiv(array('class'=>'list-page-body'));
			$param = array('class'=>'list-load','form'=>$form,'table'=>$tbl_name,'view'=>$view,'dflt-view'=>$default_view);
			if($filter)
				$param['table_search'] = $tbl_name."_filter";
			$CI->html->sDiv($param);

			$CI->html->eDiv();		
		$CI->html->eDiv();
	$CI->html->eDiv();
	return $CI->html->code();
}
function listPageCustom($id,$title="",$tbl_name="",$form="",$default_view="grid",$view="all",$filter=false){
	$CI =& get_instance();
	$CI->html->sDiv(array('class'=>'list-page','id'=>$id));
		$CI->html->sDiv(array('class'=>'list-page-head'));
			$CI->html->sDivRow();
				$CI->html->sDivCol(6);
					$CI->html->H(3,$title,array('class'=>'list-page-title'));
				$CI->html->eDivCol();
				$CI->html->sDivCol(6);
				$CI->html->eDivCol();
			$CI->html->eDivRow();
			$CI->html->sDivRow();
				$CI->html->sDivCol(5);
					if($form != "")
						$CI->html->button(fa('fa-plus fa-fw').' CREATE',array('class'=>'btn-create btn-flat pull-left'),'success');
				$CI->html->eDivCol();
				$CI->html->sDivCol(3,'left');
					$CI->html->sDiv(array('class'=>'list-page-pagi pull-left'));
					$CI->html->eDiv();
				$CI->html->eDivCol();
				$CI->html->sDivCol(4,'right');
					$grid = "";
					$list = "";
					// $CI->html->button(fa('fa-filter'),array('class'=>'btn-flat pull-right'),'primary');
					$fil = "";
					if($filter)
						$fil = '<button type="button" class="btn-view-filter btn btn-info btn-flat" style="margin-right:5px;">'.fa('fa-filter').'</button>';
					$CI->html->append($fil);
					if($view == "all"){
						$g = "";
						if($default_view == 'grid')
							$g = "active";
						$grid = '<button type="button" class="btn-views btn-view-grid btn btn-default btn-flat '.$g.'">'.fa('fa-table').'</button>';
						
						$l = "";
						if($default_view == 'list')
							$l = "active";
						$list = '<button type="button" class="btn-views btn-view-list btn btn-default btn-flat '.$l.'">'.fa('fa-list').'</button>';
					}

					$CI->html->append('
						<div class="btn-group pull-right" data-toggle="btn-toggle" role="group" >'.$grid.$list.'</div>
					');
				$CI->html->eDivCol();
			$CI->html->eDivRow();
		$CI->html->eDiv();
		$CI->html->sDiv(array('class'=>'list-page-body'));
			$param = array('class'=>'list-load','form'=>$form,'table'=>$tbl_name,'view'=>$view,'dflt-view'=>$default_view);
			if($filter)
				$param['table_search'] = $tbl_name."_filter";
			$CI->html->sDiv($param);

			$CI->html->eDiv();		
		$CI->html->eDiv();
	$CI->html->eDiv();
	return $CI->html->code();
}
?>