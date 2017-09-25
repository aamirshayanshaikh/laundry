<?php
function dashboardPage(){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(3,'left','0',array('class'=>'col-sm-6 col-xs-12'));
			$CI->html->append('
				<div class="info-box">
				  <span class="info-box-icon bg-green-gradient"><i class="fa fa-money"></i></span>
				  <div class="info-box-content"   id="box-collectible">
				    <span class="info-box-text"   id="box-collectible-txt">Total Collectibles</span>
				    <span class="info-box-number" id="box-collectible-num">'.num(0).'</span>
				  </div>
				</div>
			');
		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}
?>