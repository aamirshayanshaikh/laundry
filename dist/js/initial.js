function baseUrl() {
	var href = window.location.href.split('/');
	return href[0]+'//'+href[2]+'/'+href[3]+'/';
}
var baseUrl = baseUrl();
$(document).ready(function(){
	$.post(baseUrl+'site/site_alerts',function(data){
	    var alerts = data.alerts;
	    $.each(alerts, function(index,row){
	        $.alertMsg({msg:row['text'],type:row['type']});

	    });
	},"json").promise().done(function() {
	    $.post(baseUrl+'site/clear_site_alerts');
	});
	if($('.list-page').exists()){
		var docHeight = $(document).height();
		$('.list-page').height(docHeight - $('.main-header').outerHeight() - $('.main-footer').outerHeight());
		var listPageH = $('.list-page').outerHeight();
		$('.list-page-body').height(listPageH-80);
		$('.list-page-body').perfectScrollbar();
		if($('.default-list').exists()){
			$('.default-list').rList();
		}
	}

	$('.pick-date').datepicker();
	$('.paper-select').selectpicker();

});
