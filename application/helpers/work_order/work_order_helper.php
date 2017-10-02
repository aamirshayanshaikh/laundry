<?php
function history_page($wo=array(),$wo_mats=array(),$stagings=array(),$stagings_mats=array()){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol();
			$CI->html->sUl(array('class'=>'timeline'));
				foreach ($stagings as $stg) {
					$CI->html->sLi(array('class'=>'time-label'));
						$CI->html->span(sql2Date(iSetObjDate($stg,'stage_date')),array('class'=>'bg-blue'));
					$CI->html->eLi();
					$CI->html->sLi();
						$CI->html->span('',array('class'=>'fa fa-table bg-gray'));
						$CI->html->sDiv(array('class'=>'timeline-item'));
							$CI->html->H(3,'<b>'.iSetObj($stg,'stage_name').'</b>',array('class'=>'timeline-header'));
							$CI->html->sDiv(array('class'=>'timeline-body'));
								$CI->html->sDivRow();
									$CI->html->sDivCol(4);
										$CI->html->txtPaper('Total Weight:',iSetObj($stg,'weight')." ".iSetObj($stg,'uom'));
									$CI->html->eDivCol();
									$CI->html->sDivCol(4);
										$CI->html->txtPaper('Damaged Weight:',iSetObj($stg,'damage')." ".iSetObj($stg,'uom'));
									$CI->html->eDivCol();
									$CI->html->sDivCol(4);
										$CI->html->txtPaper('Date:',sql2Date(iSetObjDate($stg,'stage_date')));
									$CI->html->eDivCol();
								$CI->html->eDivRow();
								$CI->html->H(4,"",array('class'=>'page-header'));
								$CI->html->H(4,fa('fa-cubes')." Materials",array('class'=>'form-titler'));
								$CI->html->sDivRow();
									$CI->html->sDivCol();
										$CI->html->sTable(array('class'=>'table paper-table','id'=>'wo-mats'));
											$CI->html->sTablehead();
												$CI->html->sRow();
													$CI->html->th('Material');
													$CI->html->th('Use Qty Per UOM');
													$CI->html->th('UOM');
													$CI->html->th('Cost');
													$CI->html->th('Total Cost');
													$CI->html->th('Issued Qty');
													$CI->html->th('Used Qty');
												$CI->html->eRow();
											$CI->html->eTablehead();
											$CI->html->sTableBody();
												foreach ($stagings_mats as $mat) {
													if($stg->id == $mat->wo_stg_id){
														if($mat->additional == 0){
															$CI->html->sRow();
																$CI->html->td($mat->mat_name);
																$CI->html->td(num($mat->min_qty));
																$CI->html->td($mat->mat_uom);
																$CI->html->td(num($mat->cost));
																$CI->html->td(num($mat->total_cost));
																$CI->html->td(num($mat->wo_qty));
																$CI->html->td(num($mat->used_qty));
															$CI->html->eRow();
														}
													}
												}
											$CI->html->eTableBody();
										$CI->html->eTable();
									$CI->html->eDivCol();
								$CI->html->eDivRow();	
								$CI->html->H(4,fa('fa-plus-circle')."Additional Materials",array('class'=>'form-titler'));
								$CI->html->sDivRow();
									$CI->html->sDivCol();
										$CI->html->sTable(array('class'=>'table paper-table','id'=>'add-mats'));
											$CI->html->sTablehead();
												$CI->html->sRow();
													$CI->html->th('Material');
													$CI->html->th('Used Qty');
													$CI->html->th('UOM');
													$CI->html->th('Cost');
													$CI->html->th('Total Cost');
													$CI->html->th('');
												$CI->html->eRow();
											$CI->html->eTablehead();
											$CI->html->sTableBody();
												$ctr = 0;
												foreach ($stagings_mats as $mat) {
													if($stg->id == $mat->wo_stg_id){
														if($mat->additional != 0){
															$CI->html->sRow();
																$CI->html->td($mat->mat_name);
																$CI->html->td(num($mat->min_qty));
																$CI->html->td($mat->mat_uom);
																$CI->html->td(num($mat->cost));
																$CI->html->td(num($mat->total_cost));
																$CI->html->td(num($mat->wo_qty));
																$CI->html->td(num($mat->used_qty));
															$CI->html->eRow();
															$ctr++;
														}
													}
												}	
												if($ctr == 0){
													$CI->html->sRow(array('class'=>'no-row'));
														$CI->html->td('<center>None</center>',array('colspan'=>'100%'));
													$CI->html->eRow();
												}
											$CI->html->eTableBody();
										$CI->html->eTable();
									$CI->html->eDivCol();
								$CI->html->eDivRow();	
								if(iSetObj($stg,'memo')){
									$CI->html->H(4,'<b>Remarks:</b>');
									$CI->html->span(iSetObj($stg,'memo'));								
								}
							$CI->html->eDiv();
						$CI->html->eDiv();
					$CI->html->eLi();
				}
				#----------- CREATE -----------
				$CI->html->sLi(array('class'=>'time-label'));
					$CI->html->span(sql2Date(iSetObjDate($wo,'wo_date')),array('class'=>'bg-blue'));
				$CI->html->eLi();
				$CI->html->sLi();
					$CI->html->span('',array('class'=>'fa fa-edit bg-gray'));
					$CI->html->sDiv(array('class'=>'timeline-item'));
						$CI->html->H(3,'<b>Work Order Created</b>',array('class'=>'timeline-header'));
						$CI->html->sDiv(array('class'=>'timeline-body'));
							$CI->html->sDivRow();
								$CI->html->sDivCol(3);
									$CI->html->txtPaper('Reference:',iSetObj($wo,'reference'));
								$CI->html->eDivCol();
								$CI->html->sDivCol(3);
									$CI->html->txtPaper('Batch No.:',iSetObj($wo,'batch_no'));
								$CI->html->eDivCol();
								$CI->html->sDivCol(3);
									$CI->html->txtPaper('Lot No.:',iSetObj($wo,'lot_no'));
								$CI->html->eDivCol();
								$CI->html->sDivCol(3);
									$CI->html->txtPaper('Create Date:',iSetObjDate($wo,'wo_date'));
								$CI->html->eDivCol();
							$CI->html->eDivRow();
							$CI->html->sDivRow();
								$CI->html->sDivCol(3);
									$CI->html->txtPaper('Work Order Type:',iSetObj($wo,'type_name'));
								$CI->html->eDivCol();
								$CI->html->sDivCol(3);
									$CI->html->txtPaper('Total Weight:',iSetObj($wo,'weight')." ".iSetObj($wo,'uom'));
								$CI->html->eDivCol();
							$CI->html->eDivRow();
							$CI->html->H(4,"",array('class'=>'page-header'));
							$CI->html->H(4,fa('fa-cubes')." Materials",array('class'=>'form-titler'));
							$CI->html->sDivRow();
								$CI->html->sDivCol();
									$CI->html->sTable(array('class'=>'table paper-table'));
										$CI->html->sTablehead();
											$CI->html->sRow();
												$CI->html->th('Material');
												$CI->html->th('Use Qty Per UOM');
												$CI->html->th('UOM');
												$CI->html->th('Issue Qty');
												$CI->html->th('Cost');
												$CI->html->th('Total Cost');
											$CI->html->eRow();
										$CI->html->eTablehead();
										$CI->html->sTableBody();
											foreach ($wo_mats as $mat) {
												$CI->html->sRow();
													$CI->html->td($mat->mat_name);
													$CI->html->td(num($mat->min_qty));
													$CI->html->td($mat->mat_uom);
													$CI->html->td(num($mat->wo_qty));
													$CI->html->td(num($mat->cost));
													$CI->html->td(num($mat->total_cost));
												$CI->html->eRow();
											}
										$CI->html->eTableBody();
									$CI->html->eTable();
								$CI->html->eDivCol();
							$CI->html->eDivRow();	
							if(iSetObj($wo,'memo')){
								$CI->html->H(4,'<b>Remarks:</b>');
								$CI->html->span(iSetObj($wo,'memo'));								
							}
						$CI->html->eDiv();
					$CI->html->eDiv();
				$CI->html->eLi();
				#------------------------------
				$CI->html->sLi();
					$CI->html->span('',array('class'=>'fa fa-clock-o bg-gray'));
				$CI->html->eLi();
			$CI->html->eUl();
		$CI->html->eDivCol();
	$CI->html->eDivRow();
	return $CI->html->code();
}
function create_form($new_ref="",$lot_no="",$batch_no="",$today=""){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(10,'left',1);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("work_order/create_db","general-form");
						$CI->html->sDivRow();
							$CI->html->sDivCol(4);
								$params = array('class'=>'rOkay','ro-msg'=>'Reference must not be empty');
								$CI->html->inputPaper('Reference:','reference',$new_ref,null,$params);
							$CI->html->eDivCol();
							$CI->html->sDivCol(4);
								$params = array('class'=>'rOkay','ro-msg'=>'Batch No. must not be empty');
								$CI->html->inputPaper('Batch No.:','batch_no',$batch_no,null,$params);
							$CI->html->eDivCol();
							$CI->html->sDivCol(4);
								$params = array('class'=>'rOkay','ro-msg'=>'Reference must not be empty');
								$CI->html->inputPaper('Lot. No.:','lot_no',$lot_no,null,$params);
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->sDivRow();
							$CI->html->sDivCol(4);
								$CI->html->woTypesDropPaper('Work Order Type:','type_id',null,null,array('class'=>'rOkay pick-date','ro-msg'=>'Create date must not be empty'));
							$CI->html->eDivCol();
							$CI->html->sDivCol(4);
							$CI->html->eDivCol();
							$CI->html->sDivCol(4);
								$CI->html->inputPaper('Create Date:','wo_date',$today,null,array('class'=>'rOkay pick-date','ro-msg'=>'Create date must not be empty'));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						$CI->html->sDivRow(array('style'=>'margin-bottom:10px;'));
							$CI->html->sDivCol(4);
								$params = array('class'=>'rOkay','ro-msg'=>'Total Weight must not be empty');
								$CI->html->inputPaper('Total Weight:','weight',null,null,$params);
							$CI->html->eDivCol();
							$CI->html->sDivCol(4);
								$CI->html->hidden('main_uom','');
								$CI->html->span('',array('id'=>'weight-uom','style'=>'font-weight:600;display:block;margin-top:10px;'));
							$CI->html->eDivCol();
						$CI->html->eDivRow();
						# ITEMS START
							// $CI->html->H(4,fa('fa-sticky-note-o')." Items",array('class'=>'form-titler'));
							// $CI->html->sDivRow();
							// 	$CI->html->sDivCol();
							// 		$CI->html->sTable(array('class'=>'table paper-table','id'=>'items-tbl'));
							// 			$CI->html->sTablehead();
							// 				$CI->html->sRow();
							// 					$CI->html->th('Receive Date');
							// 					$CI->html->th('Reference');
							// 					$CI->html->th('Customer');
							// 					$CI->html->th('Item');
							// 					$CI->html->th('Received Total Qty');
							// 					$CI->html->th('Uom');
							// 					$CI->html->th('Work Order Qty');
							// 				$CI->html->eRow();
							// 			$CI->html->eTablehead();
							// 			$CI->html->sTableBody();
							// 				$CI->html->sRow(array('class'=>'no-row'));
							// 					$CI->html->td('<center>Select Type</center>',array('colspan'=>'100%'));
							// 				$CI->html->eRow();
							// 			$CI->html->eTableBody();
							// 		$CI->html->eTable();
							// 	$CI->html->eDivCol();
							// $CI->html->eDivRow();	
						# ITEMS END
						$CI->html->H(4,"",array('class'=>'page-header'));
						# MATERIALS START
							$CI->html->H(4,fa('fa-cubes')." Materials",array('class'=>'form-titler'));
							$CI->html->sDivRow();
								$CI->html->sDivCol();
									$CI->html->sTable(array('class'=>'table paper-table','id'=>'wo-mats'));
										$CI->html->sTablehead();
											$CI->html->sRow();
												$CI->html->th('Material');
												$CI->html->th('Use Qty Per UOM');
												$CI->html->th('UOM');
												$CI->html->th('Issue Qty');
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
													$uomtxt = $CI->html->span(num(0),array('id'=>'uom_txt','return'=>true));
													$uomhid = $CI->html->hidden('uom','',array('return'=>true));
													$CI->html->td($uomtxt." ".$uomhid);
													$woInput = $CI->html->decimal('','wo_qty',null,null,2,array('return'=>true,'style'=>'width:100px;'));
													$CI->html->td($woInput,array('style'=>'text-align:center'));
													$costInput = $CI->html->decimal('','cost',num(0),null,2,array('return'=>true,'style'=>'width:100px;'));
													$CI->html->td($costInput,array('style'=>'text-align:center'));
													$costTotal = $CI->html->span(num(0),array('id'=>'cost_total','return'=>true));
													$costTotalhid = $CI->html->hidden('cost_total_hid','',array('return'=>true));
													$CI->html->td($costTotal." ".$costTotalhid);
													$CI->html->td();
												$CI->html->eRow();
										$CI->html->eTableBody();
									$CI->html->eTable();
								$CI->html->eDivCol();
							$CI->html->eDivRow();	
						# MATERIALS END
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
function types_form($det=array(),$stages=array(),$items=array()){
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
						$CI->html->sTab(array('class'=>'paper-tab','style'=>'margin-top:20px;'));
							$tabs = array( 
										  fa('fa-info-circle').' General Details'=>array('href'=>'#general-pane'),
										  fa('fa-cubes').' Materials'=>array('href'=>'#mats-pane'),
										  // fa('fa-sticky-note-o').' Items'=>array('href'=>'#items-pane'),
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
												$CI->html->th('Use Qty per 1 UOM');
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
									$CI->html->P('Note: hold and drag the handle bars <i class="fa fa-bars"></i> to sort the order.');
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
								# ITEMS START
								// $CI->html->sTabPane(array('id'=>'items-pane','class'=>'tab-pane'));
								// 	$CI->html->sDivRow(array('style'=>'margin-bottom:20px;'));
								// 		$CI->html->sDivCol();
								// 			$CI->html->sDivRow();
								// 				$CI->html->sDivCol(8);
								// 					$CI->html->woItemsDropPaper(null,'item_id',iSetObj($det,'item_id'),null,array());
								// 				$CI->html->eDivCol();
								// 				$CI->html->sDivCol(2);
								// 					$CI->html->button('Add',array('id'=>'add-item','class'=>'btn-flat btn-block btn-sm'),'info');
								// 				$CI->html->eDivCol();
								// 			$CI->html->eDivRow();
								// 		$CI->html->eDivCol();
								// 	$CI->html->eDivRow();
								// 	$CI->html->sDivRow();
								// 		$CI->html->sDivCol(6);
								// 			$CI->html->sUl(array('id'=>'item-list','class'=>'draggable'));
								// 				foreach ($items as $line => $row) {
								// 					$CI->html->sLi(array('id'=>'item-'.$row['item_id'],'ref'=>$row['item_id']));
								// 						$CI->html->span("",array('class'=>'fa fa-bars icon-move'));
								// 						$CI->html->span($row['item_name']);
								// 						$CI->html-> A(fa('fa-times fa-lg'),'#',array('class'=>'pull-right','style'=>'margin-top:1px;'));													
								// 					$CI->html->eLi();
								// 				}
								// 			$CI->html->eUl();
								// 		$CI->html->eDivCol();
								// 	$CI->html->eDivRow();
								// $CI->html->eTabPane();
								# ITEMS END
							$CI->html->eTabBody();
						$CI->html->eTab();
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
function staging_form($wod=array(),$stg=array(),$stg_mats=array(),$today="",$last=0){
	$CI =& get_instance();
	$CI->html->sDivRow();
		$CI->html->sDivCol(10,'left',1);
			$CI->html->sBox('solid');
				$CI->html->sBoxBody(array('class'=>'paper'));
					$CI->html->sForm("work_order/staging_db","general-form");
						$CI->html->hidden('ref',iSetObj($wod,'reference'));
						$CI->html->hidden('wo_id',iSetObj($wod,'id'));
						$CI->html->hidden('type_id',iSetObj($wod,'type_id'));
						$CI->html->hidden('order',iSetObj($wod,'curr_stage_id'));
						$CI->html->hidden('stage_id',iSetObj($stg,'id'));
						$CI->html->hidden('stg_name',iSetObj($stg,'name'));
						$CI->html->hidden('stg_last',$last);
						if($last == 1){
							$CI->html->sDivRow();
								$CI->html->sDivCol(4);
									$params = array('class'=>'rOkay','ro-msg'=>'Total Weight must not be empty');
									$CI->html->inputPaper('Total Weight:','weight',iSetObj($wod,'weight'),null,$params);
								$CI->html->eDivCol();
								$CI->html->sDivCol(4);
									$CI->html->hidden('main_uom',iSetObj($wod,'uom'));
									$CI->html->span(iSetObj($wod,'uom'),array('id'=>'weight-uom','style'=>'font-weight:600;display:block;margin-top:10px;'));
								$CI->html->eDivCol();
								$CI->html->sDivCol(4);
									$CI->html->inputPaper('Date:','stg_date',$today,null,array('class'=>'rOkay pick-date','ro-msg'=>'Create date must not be empty'));
								$CI->html->eDivCol();
							$CI->html->eDivRow();
							$CI->html->sDivRow(array('style'=>'margin-bottom:10px;'));
								$CI->html->sDivCol(4);
									$CI->html->inputPaper('Total Qty(small):','small',null,null,array());
								$CI->html->eDivCol();
								$CI->html->sDivCol(4);
									$CI->html->inputPaper('Total Qty(meduim):','meduim',null,null,array());
								$CI->html->eDivCol();
								$CI->html->sDivCol(4);
									$CI->html->inputPaper('Total Qty(large):','large',null,null,array());
								$CI->html->eDivCol();
								$CI->html->sDivCol(4);
									$CI->html->inputPaper('Total Damage Items:','damage',null,null,array());
								$CI->html->eDivCol();
							$CI->html->eDivRow();
						}
						else{
							$CI->html->sDivRow();
								$CI->html->sDivCol(4);
									$params = array('class'=>'rOkay','ro-msg'=>'Total Weight must not be empty');
									$CI->html->inputPaper('Total Weight:','weight',iSetObj($wod,'weight'),null,$params);
								$CI->html->eDivCol();
								$CI->html->sDivCol(4);
									$CI->html->hidden('main_uom',iSetObj($wod,'uom'));
									$CI->html->span(iSetObj($wod,'uom'),array('id'=>'weight-uom','style'=>'font-weight:600;display:block;margin-top:10px;'));
								$CI->html->eDivCol();
								$CI->html->sDivCol(4);
									$CI->html->inputPaper('Date:','stg_date',$today,null,array('class'=>'rOkay pick-date','ro-msg'=>'Create date must not be empty'));
								$CI->html->eDivCol();
							$CI->html->eDivRow();
							$CI->html->sDivRow(array('style'=>'margin-bottom:10px;'));
								$CI->html->sDivCol(4);
									$CI->html->inputPaper('Damaged Weight:','damage',null,null,array());
								$CI->html->eDivCol();
							$CI->html->eDivRow();
						}

						$CI->html->H(4,"",array('class'=>'page-header'));
						# MATERIALS START
							$CI->html->H(4,fa('fa-cubes')." Materials",array('class'=>'form-titler'));
							$CI->html->sDivRow();
								$CI->html->sDivCol();
									$CI->html->sTable(array('class'=>'table paper-table','id'=>'wo-mats'));
										$CI->html->sTablehead();
											$CI->html->sRow();
												$CI->html->th('Material');
												$CI->html->th('Use Qty Per UOM');
												$CI->html->th('UOM');
												$CI->html->th('Cost');
												$CI->html->th('Total Cost');
												$CI->html->th('Issued Qty');
												$CI->html->th('Used Qty');
											$CI->html->eRow();
										$CI->html->eTablehead();
										$CI->html->sTableBody();
											foreach ($stg_mats as $ctr => $mats) {
												$CI->html->sRow();
													$CI->html->td($mats['mat_name']);
													$CI->html->td($mats['min_qty']);
													$CI->html->td($mats['uom']);
													$CI->html->td($mats['cost']);
													$CI->html->td($mats['total_cost']);
													$CI->html->td($mats['wo_qty']);
													$usedInput = $CI->html->decimal('','used_qty['.$ctr.']',null,null,2,array('return'=>true,'style'=>'width:100px;'));
													$CI->html->td($usedInput);
												$CI->html->eRow();
											}
										$CI->html->eTableBody();
									$CI->html->eTable();
								$CI->html->eDivCol();
							$CI->html->eDivRow();	
						# MATERIALS END
						$CI->html->H(4,"",array('class'=>'page-header'));	
						# MATERIALS START
							$CI->html->H(4,fa('fa-plus-circle')."Additional Materials",array('class'=>'form-titler'));
							$CI->html->sDivRow();
								$CI->html->sDivCol();
									$CI->html->sTable(array('class'=>'table paper-table','id'=>'add-mats'));
										$CI->html->sTablehead();
											$CI->html->sRow();
												$CI->html->th('Material');
												$CI->html->th('Used Qty');
												$CI->html->th('UOM');
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
													$woInput = $CI->html->decimal('','add_on_qty',null,null,2,array('return'=>true,'style'=>'width:100px;'));
													$CI->html->td($woInput,array('style'=>'text-align:center'));
													$uomtxt = $CI->html->span(num(0),array('id'=>'uom_txt','return'=>true));
													$uomhid = $CI->html->hidden('uom','',array('return'=>true));
													$CI->html->td($uomtxt." ".$uomhid);
													$costInput = $CI->html->decimal('','cost',num(0),null,2,array('return'=>true,'style'=>'width:100px;'));
													$CI->html->td($costInput,array('style'=>'text-align:center'));
													$costTotal = $CI->html->span(num(0),array('id'=>'cost_total','return'=>true));
													$costTotalhid = $CI->html->hidden('cost_total_hid','',array('return'=>true));
													$CI->html->td($costTotal." ".$costTotalhid);
													$CI->html->td();
												$CI->html->eRow();
										$CI->html->eTableBody();
									$CI->html->eTable();
								$CI->html->eDivCol();
							$CI->html->eDivRow();	
						# MATERIALS END	
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