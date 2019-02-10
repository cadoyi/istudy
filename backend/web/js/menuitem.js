
var buttonHandlers = {
	'a.plus' : function() { 
		console.log('add'); 
		return false;
	},
	'a.minus' : function() {
		console.log('minus');
		return false;
	},
	'a.arrow-left' : function() {
		console.log('left');
		return false; 
	},
	'a.arrow-right' : function() {
		console.log('right');
		return false;
	},
	'a.arrow-up' : function() {
		console.log('up');
		return false; 
	},
	'a.arrow-down' : function() {
		console.log('down');
		return false; 
	},
}
jQuery(function($) {
	
	// 绑定事件处理器
	$.each(buttonHandlers, function(selector, handler) {
        $('#menu_item_buttons').click({
            selector : selector,
            handler : handler,
        });
	});

   $('#save_menu_item').click(function(){
       

   });

});