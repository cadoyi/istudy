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
	}, 5000);


    $('#footermenus > li').addClass('flex1');
    
    var fixFooterPosition = function() {
        var footer =  $('#footer').outerHeight();
        var win = $(window).height();
        $('#page').css({'min-height' : win - footer - 70 + 'px'});
    }
    
    fixFooterPosition();
    $(window).resize(fixFooterPosition);
});

