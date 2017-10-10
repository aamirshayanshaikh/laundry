<script>
$(document).ready(function(){
	<?php if($use_js == 'types_form'): ?>
		$('#save-btn').click(function(){
			var btn = $(this);
            var stages = [];
            $('#stage-list li').each(function(){
                stages.push($(this).attr('ref'));
            });
            var items = [];
            $('#item-list li').each(function(){
                items.push($(this).attr('ref'));
            });
			var noError = $('#general-form').rOkay({
                btn_load        :   btn,
    			addData		    : 	"stages="+JSON.stringify(stages)+"&items="+JSON.stringify(items),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		: 	function(data){
										if(data.error == 0){
											location.reload();
										}
										else{
											$.alertMsg({msg:data.msg,type:'error'});
										}
									},
    		});
			return false;
    	});	
    	$('#create-uom-pop').rForm(function(data){
    		var items = data.items;
    		var id = data.id;
    		$('#uom').append('<option value="'+id+'">'+items['name']+'</option>');
    		$('#uom').selectpicker('refresh');
    		$('#uom').selectpicker('val',id);
    	});
    	///-------------------------------------------------
    	$('#mat_id').change(function(){
    		var val = $(this).val();
    		var selected = $(this).find("option:selected");
    		if(val != ""){
    			$('#cost').val(selected.attr('cost'));
    			$('#mat_name').val(selected.text());
    		}
    		var total = 0;
    		$('#ord_qty').val('').focus();
    		$('#cost_total').html(total.toFixed(2));
    		$('#cost_total_hid').val(total.toFixed(2));
    	});
    	$('#ord_qty,#cost').blur(function(){
    		totalLine();
    	});
    	function totalLine(){
    		var total = 0;
    		if(parseFloat($('#cost').val()) > 0 && parseFloat($('#ord_qty').val()) > 0){
    			var total = parseFloat($('#cost').val()) * parseFloat($('#ord_qty').val());
    		}
    		$('#cost_total').html(total.toFixed(2));
    		$('#cost_total_hid').val(total.toFixed(2));
    	}
    	///-------------------------------------------------
    	$('#type-mats').rCart({
    		'columns'	: 	['mat_name','ord_qty','cost','cost_total_hid'],
    		'beforeAdd' : 	function(){
    							var goAdd = true;
    							if(parseFloat($('#ord_qty').val()) <= 0){
    								goAdd = false;
    								$.alertMsg({msg:'Invalid Qty',type:'error'});
    							}
	    						// 	else{
	    						// 		$.post(baseUrl+'cart/check_cart/type-mats/mat_id/'+$('#mat_id').val(),function(data){
								   //        	if(data.error != ""){
											// 	$.alertMsg({msg:data.error,type:'error'});
											// 	goAdd = false;
											// }
								   //      },'json').fail( function(xhr, textStatus, errorThrown) {
								   //        console.log(xhr.responseText);
								   //      });
	    						// 	}
    							return goAdd;
    						},
    		'afterAdd'	:   function(){
    							var total = 0;
    							$('#ord_qty').val('').focus();
    							$('#cost_total').html(total.toFixed(2));
    							$('#cost_total_hid').val(total.toFixed(2));
    							$('#mat_id').val('').trigger('change');
    						}
    	});
        $("#stage-list").sortable({handle: '.icon-move'});
        $('#stage-list li a').each(function(){
            $(this).click(function(){
                $(this).parent().remove();
                $("#stage-list").sortable("refresh");
                return false;
            });
        });
        $('#add-stage').click(function(){
            var stage_id   = $('#stage_id').val();
            if(stage_id == ""){
                return false;
            }
            var stage_name = $('#stage_id').find("option:selected").text();
            var add = true;
            $('#stage-list li').each(function(){
                if($(this).attr('id') == 'stage-'+stage_id){
                    add = false;
                    $.alertMsg({msg:stage_name+' is already in the list',type:'error'});
                    return false;
                }
            });
            if(add){
                var li = $('<li id="stage-'+stage_id+'" ref="'+stage_id+'"><span class="fa fa-bars icon-move"></span><span>'+stage_name+'</span></li>');
                var remove = $('<a href="#" class="pull-right" style="margin-top:1px;"><i class="fa fa-times fa-lg"></i></a>');
                li.append(remove);
                $("#stage-list").append(li);
                $("#stage-list").sortable("refresh");
                remove.click(function(){
                    $(this).parent().remove();
                    $("#stage-list").sortable("refresh");
                    return false;
                });
                $('#stage_id').val('').trigger('change');
            }
            return false;
        });
        $('#item-list li a').each(function(){
            $(this).click(function(){
                $(this).parent().remove();
                return false;
            });
        });
        $('#add-item').click(function(){
            var item_id   = $('#item_id').val();
            if(item_id == ""){
                return false;
            }
            var item_name = $('#item_id').find("option:selected").text();
            var add = true;
            $('#item-list li').each(function(){
                if($(this).attr('id') == 'item-'+item_id){
                    add = false;
                    $.alertMsg({msg:item_name+' is already in the list',type:'error'});
                    return false;
                }
            });
            if(add){
                var li = $('<li id="item-'+item_id+'" ref="'+item_id+'"><span class="fa fa-bars icon-move"></span><span>'+item_name+'</span></li>');
                var remove = $('<a href="#" class="pull-right" style="margin-top:1px;"><i class="fa fa-times fa-lg"></i></a>');
                li.append(remove);
                $("#item-list").append(li);
                remove.click(function(){
                    $(this).parent().remove();
                    return false;
                });
                $('#item_id').val('').trigger('change');
            }
            return false;
        });
    <?php elseif($use_js == 'stages_form'): ?>
    <?php elseif($use_js == 'receive_form'): ?>
        $('#save-btn').click(function(){
            var btn = $(this);
            var noError = $('#general-form').rOkay({
                btn_load        :   btn,
                bnt_load_remove :   true,
                asJson          :   true,
                onComplete      :   function(data){
                                        if(data.error == 0){
                                            location.reload();
                                        }
                                        else{
                                            $.alertMsg({msg:data.msg,type:'error'});
                                        }
                                    },
            });
            return false;
        }); 
        $('#item_id').change(function(){
            var val = $(this).val();
            var selected = $(this).find("option:selected");
            if(val != ""){
                $('#uom').val(selected.attr('uom'));
                $('#uom_txt').html(selected.attr('uom'));
                $('#item_name').val(selected.text());
            }
            $('#ord_qty').val('').focus();
        });
        $('#rcv-items').rCart({
            'columns'   :   ['item_name','uom','rcv_qty'],
            'beforeAdd' :   function(){
                                var goAdd = true;
                                if(parseFloat($('#rcv_qty').val()) <= 0){
                                    goAdd = false;
                                    $.alertMsg({msg:'Invalid Qty',type:'error'});
                                }
                                //  else{
                                //      $.post(baseUrl+'cart/check_cart/type-mats/mat_id/'+$('#mat_id').val(),function(data){
                                   //           if(data.error != ""){
                                            //  $.alertMsg({msg:data.error,type:'error'});
                                            //  goAdd = false;
                                            // }
                                   //      },'json').fail( function(xhr, textStatus, errorThrown) {
                                   //        console.log(xhr.responseText);
                                   //      });
                                //  }
                                return goAdd;
                            },
            'afterAdd'  :   function(){
                                $('#rcv_qty').val('').focus();
                                $('#uom_txt').html('');
                                $('#uom').val('');
                                $('#item_id').val('').trigger('change');
                            }
        });
    <?php elseif($use_js == 'dispatch_form'): ?>
        $('#save-btn').click(function(){
            var btn = $(this);
            var noError = $('#general-form').rOkay({
                btn_load        :   btn,
                bnt_load_remove :   true,
                asJson          :   true,
                onComplete      :   function(data){
                                        if(data.error == 0){
                                            location.reload();
                                        }
                                        else{
                                            $.alertMsg({msg:data.msg,type:'error'});
                                        }
                                    },
            });
            return false;
        });
	<?php elseif($use_js == 'create_form'): ?>
		$('#save-btn').click(function(){
			var btn = $(this);
			var noError = $('#general-form').rOkay({
                btn_load        :   btn,
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		: 	function(data){
										if(data.error == 0){
											location.reload();
										}
										else{
											$.alertMsg({msg:data.msg,type:'error'});
										}
									},
    		});
			return false;
    	});
		$('#type_id').change(function(){
			loadMaterials();
			$('#weight').focus();
		});
		$('#weight').blur(function(){
			loadMaterials();
		});
		function loadMaterials(){
			var type_id = $('#type_id').val();
			var weight = $('#weight').val();
			var items_tbl = $('#wo-mats tbody');
			// items_tbl.html('');
			// if(type_id != ""){
				var formData = 'id='+type_id+'&weight='+weight;
				$.post(baseUrl+'work_order/compute_types_details',formData,function(data){
					var details = data.details;
					// var items = data.items;
					var materials = data.materials;
					// $.each(items,function(ictr,itm){
					// 	var tr = $('<tr class="tbl-line-row"></tr>');
					// 	tr.append(
					// 				'<td>'+itm['rcv_date']+'<input type="hidden" name="rcvs['+ictr+']" value="'+itm['rcv_id']+'"></td>'+
					// 				'<td>'+itm['ref']+'</td>'+
					// 				'<td>'+itm['cust_name']+'<input type="hidden" name="custs['+ictr+']" value="'+itm['cust_id']+'"></td>'+
					// 				'<td>'+itm['item_name']+'<input type="hidden" name="items['+ictr+']" value="'+itm['item_id']+'"></td>'+
					// 				'<td>'+itm['rcv_total']+'</td>'+
					// 				'<td>'+itm['uom']+'</td>'+
					// 				'<td><div class="form-group"><input style="width:100px;" type="text" name="woqty['+ictr+']" value="0.00" class=" form-control numbers-only" decimal="2"></div></td>'
					// 			 );
					// 	items_tbl.append(tr);
					// });
					if(materials.length > 0){
						$('#weight-uom').text(details.main_uom);
						$('#main_uom').val(details.main_uom);
						$('#wo-mats').rCartLoad({'columns':['mat_name','ord_qty','uom','wo_qty','cost','cost_total_hid']});	
					}
					else{
						items_tbl.find('.rcart-rows').remove();
					}
				},'json').fail( function(xhr, textStatus, errorThrown) {
		          console.log(xhr.responseText);
		        });
			// }
			// else{
			// }
		}
		$('#mat_id').change(function(){
    		var val = $(this).val();
    		var selected = $(this).find("option:selected");
    		if(val != ""){
    			$('#cost').val(selected.attr('cost'));
    			$('#mat_name').val(selected.text());
    			$('#uom_txt').html(selected.attr('uom'));
    			$('#uom').val(selected.attr('uom'));
    		}
    		var total = 0;
    		$('#ord_qty').val('').focus();
    		$('#cost_total').html(total.toFixed(2));
    		$('#cost_total_hid').val(total.toFixed(2));
    	});
    	$('#ord_qty,#cost').blur(function(){
    		totalLine();
    	});
    	function totalLine(){
    		var total = 0;
    		if(parseFloat($('#cost').val()) > 0 && parseFloat($('#ord_qty').val()) > 0){
    			var total = parseFloat($('#cost').val()) * parseFloat($('#ord_qty').val());
    		}
    		$('#cost_total').html(total.toFixed(2));
    		$('#cost_total_hid').val(total.toFixed(2));
    	}
    	///-------------------------------------------------
    	$('#wo-mats').rCart({
    		'columns'	: 	['mat_name','ord_qty','uom','wo_qty','cost','cost_total_hid'],
    		'beforeAdd' : 	function(){
    							var goAdd = true;
    							if(parseFloat($('#ord_qty').val()) <= 0){
    								goAdd = false;
    								$.alertMsg({msg:'Invalid Qty',type:'error'});
    							}
	    						// 	else{
	    						// 		$.post(baseUrl+'cart/check_cart/type-mats/mat_id/'+$('#mat_id').val(),function(data){
								   //        	if(data.error != ""){
											// 	$.alertMsg({msg:data.error,type:'error'});
											// 	goAdd = false;
											// }
								   //      },'json').fail( function(xhr, textStatus, errorThrown) {
								   //        console.log(xhr.responseText);
								   //      });
	    						// 	}
    							return goAdd;
    						},
    		'afterAdd'	:   function(){
    							var total = 0;
    							$('#ord_qty').val('').focus();
    							$('#cost_total').html(total.toFixed(2));
    							$('#cost_total_hid').val(total.toFixed(2));
    							$('#mat_id').val('').trigger('change');
    						}
    	});
	<?php elseif($use_js == 'staging_form'): ?>
		$('#save-btn').click(function(){
			var btn = $(this);
			var noError = $('#general-form').rOkay({
                btn_load        :   btn,
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		: 	function(data){
										if(data.error == 0){
											window.location.href = baseUrl+'work_order/staging';
										}
										else{
											$.alertMsg({msg:data.msg,type:'error'});
										}
									},
    		});
			return false;
    	});
		$('#mat_id').change(function(){
    		var val = $(this).val();
    		var selected = $(this).find("option:selected");
    		if(val != ""){
    			$('#cost').val(selected.attr('cost'));
    			$('#mat_name').val(selected.text());
    			$('#uom_txt').html(selected.attr('uom'));
    			$('#uom').val(selected.attr('uom'));
    		}
    		var total = 0;
    		$('#add_on_qty').val('').focus();
    		$('#cost_total').html(total.toFixed(2));
    		$('#cost_total_hid').val(total.toFixed(2));
    	});
    	$('#add_on_qty,#cost').blur(function(){
    		totalLine();
    	});
    	function totalLine(){
    		var total = 0;
    		if(parseFloat($('#cost').val()) > 0 && parseFloat($('#add_on_qty').val()) > 0){
    			var total = parseFloat($('#cost').val()) * parseFloat($('#add_on_qty').val());
    		}
    		$('#cost_total').html(total.toFixed(2));
    		$('#cost_total_hid').val(total.toFixed(2));
    	}
    	///-------------------------------------------------
    	$('#add-mats').rCart({
    		'columns'	: 	['mat_name','uom','add_on_qty','cost','cost_total_hid'],
    		'beforeAdd' : 	function(){
    							var goAdd = true;
    							if(parseFloat($('#add_on_qty').val()) <= 0){
    								goAdd = false;
    								$.alertMsg({msg:'Invalid Qty',type:'error'});
    							}
	    						// 	else{
	    						// 		$.post(baseUrl+'cart/check_cart/type-mats/mat_id/'+$('#mat_id').val(),function(data){
								   //        	if(data.error != ""){
											// 	$.alertMsg({msg:data.error,type:'error'});
											// 	goAdd = false;
											// }
								   //      },'json').fail( function(xhr, textStatus, errorThrown) {
								   //        console.log(xhr.responseText);
								   //      });
	    						// 	}
    							return goAdd;
    						},
    		'afterAdd'	:   function(){
    							var total = 0;
    							$('#add_on_qty').val('').focus();
    							$('#cost_total').html(total.toFixed(2));
    							$('#cost_total_hid').val(total.toFixed(2));
    							$('#mat_id').val('').trigger('change');
    						}
    	});
	<?php endif; ?>
});
</script>