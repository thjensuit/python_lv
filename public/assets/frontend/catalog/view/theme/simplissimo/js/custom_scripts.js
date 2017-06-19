(function($){	
	
	$(document).ready(function(){
		
		$(function() {

    $('.boxover').each(function() {        
        var tip = $(this).find('.boxover_info');

        $(this).hover(
            function() { tip.appendTo('body'); },
            function() { tip.appendTo(this); }
        ).mousemove(function(e) {
            var x = e.pageX + 60,
                y = e.pageY - 40,
                w = tip.width(),
                h = tip.height(),
                dx = $(window).width() - (x + w),
                dy = $(window).height() - (y + h);

            if ( dx < 50 ) x = e.pageX - w - 60;
            if ( dy < 50 ) y = e.pageY - h + 50;

            tip.css({ left: x, top: y });
        });         
    });

});


		});
	
})(window.jQuery);
	
		