
var buttonHandlers = {
	'a.plus' : function() {
		var item = $('#menutree a.menu.active');
		if(!item.length) {
			// 增加主菜单
			  var ul = document.getElementById('menutree');
		} else {
        var ul = item.next('ul');
        if(!ul.length) {
           ul = document.createElement('ul');
           item.parent().append(ul);
        } else {
        	ul = ul.get(0);
        }
		}
		$(ul).append('<li><a class="menu btn" title="#" data-title="新标签" href="#">新标签</a></li>');
		return false;
	},
	'a.minus' : function() {
		var item = $('#menutree a.menu.active');
		if(item.length) {
			item.parent().remove();
		}
		return false;
	},
	'a.modify' : function() {
		var item = $('#menutree a.menu.active');
		if(item.length) {
            var title = item.attr('data-title');
            var href = item.attr('href');
            $('#menu_item_title').val(title);
            $('#menu_item_link').val(href);
			$('#form_modal').modal('show');
		}
		return false; 
	},
	'a.save' : function() {
        var ul = $('#menutree');
        var id = 0;
        var printall = function(ul, level, parent) {
            var items = [];
            if(!level && level !== 0) {
                level = 0;
            } 
            var as = ul.children('li').children('a.menu');
		    $.each(as, function(index, item) {
		        var $item = $(item);
		        var _item = {
		           level : level,
		           position : index,
		           title    : $(item).attr('data-title'),
		           url      : $(item).attr('href'),
		        }
		        
		        var li = $item.parent('li');
		        var childul = li.children('ul');
		        if(childul.length) {
		            _item.childs = printall(childul, level+1, _item.index);
		        }
		        items.push(_item);
		    });

             return items;
        }
        var items = printall(ul, 0, null);
        var postData = {'items' : JSON.stringify(items)};
        var url = menu.item_save_url;
        $.post(url, postData).then(function(response) {
        	if(response.error) {
        		alert(response.message);
        		return;
        	} else {
        		alert('保存成功');
        	}
        });
		return false;
	}
}
jQuery(function($) {
	
	// 绑定事件处理器
	$.each(buttonHandlers, function(selector, handler) {
        $('#menu_control').click({
            selector : selector,
            handler : handler,
        });
	});

   $('#menutree').click({
   	  selector : 'a.menu',
   	  handler : function(e) {
   	  	var $this = $(this);
   	  	if($this.hasClass('active')) {
   	  		$this.removeClass('active');
   	  	} else {
   	  		$('#menutree').find('a.menu').removeClass('active');
   	  		$this.addClass('active');
   	  	}
        return false;
   	  }
   });

   $('#save_menu_item').click(function() {
       var title = $('#menu_item_title').val() || '';
       var href = $('#menu_item_link').val() || '';
       var isEmpty = function(value) {
       	   return value === '' ||  value === null || value === undefined || (typeof value == 'string' && value.trim() === '');
       }
       var isUrl = function(value) {
       	   if(value === '#') return true;
       	   var reg = /^(https?:)?\/\/[a-z0-9\-\_]+\.[a-z0-9\-\_]+(\.[a-z0-9\-\_]+)?/i;
       	   return reg.test(value);
       }
       if(isEmpty(title)) {
           $('#menu_item_title').siblings('.help-block').text('标签不能为空');
           return;
       }
       if(isEmpty(href) || !isUrl(href)) {
           $('#menu_item_link').siblings('.help-block').text('必须是可用的链接或者#号');
           return;
       }
       $('#menu_item_title').siblings('.help-block').text('');
       $('#menu_item_link').siblings('.help-block').text('');
       var item = $('#menutree a.menu.active');
       item.attr('title', href).attr('href', href).attr('data-title', title).text(title);
       $('#form_modal').modal('hide');
   });

});