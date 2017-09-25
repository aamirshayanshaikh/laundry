<script>
$(document).ready(function(){
	<?php if($use_js == 'form'): ?>
		$('#save-btn').click(function(){
			var btn = $(this);
			var noError = $('#general-form').rOkay({
    			btn_load		: 	btn,
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
		$('#details').rCart({
			'columns'	: 	['mat_name','ord_qty','cost','cost_total_hid'],
			'afterAdd'	:   function(){
								var total = 0;
								$('#ord_qty').val('').focus();
								$('#cost_total').html(total.toFixed(2));
								$('#cost_total_hid').val(total.toFixed(2));
								$('#mat_id').val('').trigger('change');
							}
		});
		// var cart_name = 'details';
		// var inputRow = $('#details-tbl .input-row');
		// var inputCols = ['mat_name','ord_qty','cost','cost_total_hid'];
		// inputRow.hide();
		// var addRow = $('<tr></tr>');
		// var addCell = $('<td colspan="100%" style="text-align:right"></td>');
		// var addRowbtn = $('<a href="#">Add Item</a>'); 
		// var addBtn = $('<a href="#"><i class="fa fa-check fa-lg fa-fw"></i></a>'); 
		// var cancelBtn = $('<a href="#"><i class="fa fa-times fa-lg fa-fw"></i></a>'); 		
		// addCell.append(addRowbtn);
		// addRow.append(addCell);
		// $('#details-tbl .input-row').before(addRow);
  //   	$('#details-tbl .input-row td:last-child').append(addBtn);
  //   	$('#details-tbl .input-row td:last-child').append(cancelBtn);
  //   	addRowbtn.click(function(){
  //   		addRowbtn.parent().parent().hide();
  //   		inputRow.show();
  //   		return false;
  //   	});
  //   	addBtn.click(function(){
  //   		var formData = inputRow.serializeAnything();
  //   		$.post(baseUrl+'cart/add/'+cart_name,formData,function(data){
		// 		var tr = $('<tr id="'+cart_name+'-'+data.id+'" ></tr>');
		// 		var row = data.row;
		// 		var id = data.id;
		// 		$.each(inputCols,function(ctr,col){
		// 			tr.append('<td>'+row[col]+'</td>');
		// 		});
		// 		var tdLast = $('<td style="text-align:right"></td>');
		// 		// var editBtn = $('<a href="#"><i class="fa fa-edit fa-lg fa-fw"></i></a>'); 
		// 		var removeBtn = $('<a href="#"><i class="fa fa-times fa-lg fa-fw"></i></a>');
		// 		// tdLast.append(editBtn);
		// 		tdLast.append(removeBtn);
		// 		tr.append(tdLast);
		// 		tr.prependTo("#details-tbl tbody");	    		
		// 		removeBtn.click(function(){
		// 			$.post(baseUrl+'cart/remove/'+cart_name+'/'+id,function(data){
		// 				$('#details-tbl tbody #'+cart_name+'-'+id).remove();
		// 			});
		// 			return false;
		// 		});
  //   		},'json');
  //   		return false;
  //   	});
  //   	cancelBtn.click(function(){
  //   		addRowbtn.parent().parent().show();
  //   		inputRow.hide();
  //   		return false;
  //   	});
	<?php endif; ?>
});
</script>