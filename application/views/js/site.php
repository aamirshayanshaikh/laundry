<script>
$(document).ready(function(){
	<?php if($use_js == 'loginJs'): ?>
		$('#username').focus();
		$('#login-btn').click(function(){
			$('#login-form').rOkay({
				btn_load 	: 	$('#login-btn'),
				onComplete 	: 	function(data){
									if(data != ""){
										$.alertMsg({msg:data,type:'error'});
									}
									else{
										window.location = baseUrl+'site';
									}
								}
			});
			return false;
		});
	<?php endif; ?>
});
</script>