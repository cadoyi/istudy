/**
 * layout js
 * @param  {[type]} $) {              } [description]
 * @return {[type]}    [description]
 */
var layoutjs = {  //设置的临时全局变量.
	scroll : 0,
};
jQuery(function($) {
	// alert 框 5秒消失.
	setTimeout(function() {
        $('.alert button.close').alert('close');
	}, 50000);
	
	var scrollHandler = function() {
    	var top = $(document).scrollTop();
    	var old = layoutjs.scroll;
    	layoutjs.scroll = top;
    	if(old > 40 && top > old) {
    		return;
    	} else if(top < old && top > 140) {
    		return;
    	}
        if(top >= 40) {
            $('#header_menus').addClass('fixed-top');
            $('body').addClass('pdtop');
        } else {
            $('#header_menus').removeClass('fixed-top');
            $('body').removeClass('pdtop');
        }
	}
	scrollHandler();
    $(document).scroll(scrollHandler);
});

