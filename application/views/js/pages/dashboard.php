<script>
$(document).ready(function(){
	<?php if($use_js == 'dashboardJs'): ?>
		loadCollectibleBox();

		function loadCollectibleBox(){
			load('#box-collectible');
			$.post(baseUrl+'dashboard/collectibles/month',function(data){
				$('#box-collectible-txt').text(data.title);
				$('#box-collectible-num').number(data.amount,2);
				unload('#box-collectible');
			},'json').fail( function(xhr, textStatus, errorThrown) {
	           alert(xhr.responseText);
	        });
		}
		function load(id){
			$(id).children().hide();
			$(id).append('<div class="overlay">'+
					     '<i class="fa fa-refresh fa-spin"></i>'+
						 '</div>');
		}
		function unload(id){
			$(id).find('.overlay').remove();
			$(id).children().show();
		}
	<?php endif; ?>
});
</script>