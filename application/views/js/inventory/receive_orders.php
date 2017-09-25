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
											window.location.href = baseUrl+'receive_orders/lists';
										}
										else{
											$.alertMsg({msg:data.msg,type:'error'});
										}
									},
    		});
			return false;
    	});
	<?php elseif($use_js == 'inquiry'): ?>
		$('.view-pop').each(function(){
			$(this).click(function(){
				alert('ere');
				return false;
			});
		});
	<?php endif; ?>
});
</script>