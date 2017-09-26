<?php
function stages_form($det=array()){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(6,'left',3);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("work_order/stages_db","general-form");
						$CI->html->hidden('id',iSetObj($det,'id'));
						$CI->html->sDivRow();
							$CI->html->sDivCol(12);
								$CI->html->inputPaper('Name:','name',iSetObj($det,'name'),null,array('class'=>'rOkay'));
							$CI->html->eDivCol();
							$CI->html->sDivCol(12);
								$CI->html->textareaPaper('Description:','description',iSetObj($det,'description'),null,array('class'=>'rOkay'));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
					$CI->html->eForm();
				$CI->html->eBoxBody();
			$CI->html->eBox();
		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}
function types_form($det=array(),$stages=array()){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(8,'left',2);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("work_order/types_db","general-form");
						$CI->html->hidden('id',iSetObj($det,'id'));
						$CI->html->sDivRow();
							$CI->html->sDivCol(10);
								$CI->html->inputPaper(null,'name',iSetObj($det,'name'),'Name',array('class'=>'rOkay input-lg'));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->sTab(array('class'=>'paper-tab','style'=>'margin-top:5px;'));
							$tabs = array( 
										  fa('fa-info-circle').' General Details'=>array('href'=>'#general-pane'),
										  fa('fa-cubes').' Materials'=>array('href'=>'#mats-pane'),
										  fa('fa-refresh').' Stages'=>array('href'=>'#stages-pane'),
										 );
							$CI->html->tabHead($tabs,null,array());
							$CI->html->sTabBody();
								# GENERAL START
								$CI->html->sTabPane(array('id'=>'general-pane','class'=>'tab-pane active'));
									$CI->html->sDivRow();
										$CI->html->sDivCol(8);
											$CI->html->textareaPaper('Description','description',iSetObj($det,'description'),'Description',array('class'=>'rOkay','style'=>'height:60px;'));
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
								$CI->html->eTabPane();
								# GENERAL END
								# ITEMS START
								$CI->html->sTabPane(array('id'=>'mats-pane','class'=>'tab-pane'));
									$CI->html->sTable(array('class'=>'table paper-table','id'=>'type-mats'));
										$CI->html->sTablehead();
											$CI->html->sRow();
												$CI->html->th('Material');
												$CI->html->th('Order Qty');
												$CI->html->th('Cost');
												$CI->html->th('Total Cost');
												$CI->html->th('');
											$CI->html->eRow();
										$CI->html->eTablehead();
										$CI->html->sTableBody();
											$CI->html->sRow(array('class'=>'input-row'));
												$matDrop = $CI->html->materialsDrop('','mat_id',null,null,array('class'=>'paper-select','return'=>true));
												$matName = $CI->html->hidden('mat_name','',array('return'=>true));
												$CI->html->td($matDrop." ".$matName,array('style'=>'width:30%;'));
												$ordInput = $CI->html->decimal('','ord_qty',null,null,2,array('return'=>true,'style'=>'width:100px;'));
												$CI->html->td($ordInput,array('style'=>'text-align:center'));
												$costInput = $CI->html->decimal('','cost',num(0),null,2,array('return'=>true,'style'=>'width:100px;'));
												$CI->html->td($costInput,array('style'=>'text-align:center'));
												$costTotal = $CI->html->span(num(0),array('id'=>'cost_total','return'=>true));
												$costTotalhid = $CI->html->hidden('cost_total_hid','',array('return'=>true));
												$CI->html->td($costTotal." ".$costTotalhid);
												$CI->html->td();
											$CI->html->eRow();
										$CI->html->eTableBody();
									$CI->html->eTable();
								$CI->html->eTabPane();
								# ITEMS END
								# STAGES START
								$CI->html->sTabPane(array('id'=>'stages-pane','class'=>'tab-pane'));
									$CI->html->sDivRow(array('style'=>'margin-bottom:20px;'));
										$CI->html->sDivCol();
											$CI->html->sDivRow();
												$CI->html->sDivCol(8);
													$CI->html->workOrderStagesDropPaper(null,'stage_id',iSetObj($det,'stage_id'),null,array());
												$CI->html->eDivCol();
												$CI->html->sDivCol(2);
													$CI->html->button('Add',array('id'=>'add-stage','class'=>'btn-flat btn-block btn-sm'),'info');
												$CI->html->eDivCol();
											$CI->html->eDivRow();
										$CI->html->eDivCol();
									$CI->html->eDivRow();
									$CI->html->sDivRow();
										$CI->html->sDivCol(6);
											$CI->html->sUl(array('id'=>'stage-list','class'=>'draggable'));
												foreach ($stages as $line => $row) {
													$CI->html->sLi(array('id'=>'stage-'.$row['stage_id'],'ref'=>$row['stage_id']));
														$CI->html->span("",array('class'=>'fa fa-bars icon-move'));
														$CI->html->span($row['stage_name']);
														$CI->html-> A(fa('fa-times fa-lg'),'#',array('class'=>'pull-right','style'=>'margin-top:1px;'));													
													$CI->html->eLi();
												}
											$CI->html->eUl();
										$CI->html->eDivCol();
									$CI->html->eDivRow();
								$CI->html->eTabPane();
								# STAGES END
							$CI->html->eTabBody();
					$CI->html->eForm();
				$CI->html->eBoxBody();
			$CI->html->eBox();
		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}
function receive_form($new_ref="",$today=""){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(10,'left',1);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("work_order/receive_db","general-form");
						$CI->html->sDivRow();
							$CI->html->sDivCol(6);
								$params = array('class'=>'rOkay','ro-msg'=>'Reference must not be empty');
								$CI->html->inputPaper('Reference:','reference',$new_ref,null,$params);
							$CI->html->eDivCol();
							$CI->html->sDivCol(6);
								$CI->html->inputPaper('Receive Date:','rcv_date',$today,null,array('class'=>'rOkay pick-date','ro-msg'=>'Receive date must not be empty'));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->sDivRow();
							$CI->html->sDivCol(6);
								$CI->html->customerDropPaper('Customer:','customer_id','',null,array('class'=>'rOkay','ro-msg'=>'Customer must not be empty'));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->H(4,"",array('class'=>'page-header'));
						$CI->html->sDivRow();
							$CI->html->sDivCol(12);
								$CI->html->sTable(array('class'=>'table paper-table','id'=>'rcv-items'));
									$CI->html->sTablehead();
										$CI->html->sRow();
											$CI->html->th('Item');
											$CI->html->th('UOM');
											$CI->html->th('Receive Qty');
											$CI->html->th('');
										$CI->html->eRow();
									$CI->html->eTablehead();
									$CI->html->sTableBody();
										$CI->html->sRow(array('class'=>'input-row'));
											$drop = $CI->html->woItemsDrop('','item_id',null,null,array('class'=>'paper-select','return'=>true));
											$name = $CI->html->hidden('item_name','',array('return'=>true));
											$CI->html->td($drop." ".$name,array('style'=>'width:30%;'));
											$txt = $CI->html->span('',array('id'=>'uom_txt','return'=>true));
											$txthid = $CI->html->hidden('uom','',array('return'=>true));
											$CI->html->td($txt." ".$txthid);
											$input = $CI->html->decimal('','rcv_qty',null,null,2,array('return'=>true,'style'=>'width:100px;'));
											$CI->html->td($input,array('style'=>'text-align:center'));											
											$CI->html->td();
										$CI->html->eRow();
									$CI->html->eTableBody();
								$CI->html->eTable();
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->sDivRow();
							$CI->html->sDivCol();
								$CI->html->textarea("","memo",'',"Add Remarks Here...");			
							$CI->html->eDivCol();
						$CI->html->eDivRow();
					$CI->html->eForm();
				$CI->html->eBoxBody();
			$CI->html->eBox();
		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}