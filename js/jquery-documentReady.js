$(document).ready(function(){
	$('.parallax-window').parallax({
		speed: 0.1
	});
	
	$("#slide-1").css({ 
		minHeight: $(window).innerHeight() + 'px' 
	});
});