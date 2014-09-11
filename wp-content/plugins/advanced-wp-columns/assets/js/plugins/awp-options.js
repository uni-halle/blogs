(function($) {
	var responsiveElements = ['#smallBreakPoint', '#smallBreakPointContentWidth'];
	
	var enableResponsiveOptions = function(){		
		$.each(responsiveElements, function(index, element){			
			$(element).removeAttr('disabled');
		});
	};
	
	var disableResponsiveOptions = function(){		
		$.each(responsiveElements, function(index, element){			
			$(element).attr('disabled','disabled');
		});
	};
	
    if(!$('#responsiveSupport').is(':checked')){
		disableResponsiveOptions();
	}
	
	$('#responsiveSupport').change(function() {
		console.log(this.checked);
		if(this.checked) {
			enableResponsiveOptions();
		}else{
			disableResponsiveOptions();
		}
	});
})(jQuery);