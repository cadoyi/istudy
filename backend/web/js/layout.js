
jQuery(function($){


    $('#menus').on('click', 'li:has(ul)', function(e){
        var ul = $(this).children('ul');
        if(ul.length) {
            if(this == e.target) {
                ul.toggle();
                return false;
            }   
        }
    });


    $('#menu_switcher').click(function(e){
           var
            left = 'glyphicon-arrow-left',
            right = 'glyphicon-arrow-right',
            showText = '',
            hideText = '隐藏',
            $this = $(this),
            glyphicon = $this.find('.glyphicon'),
            texter = $this.find('.switcher-text'),
            pm = $this.closest('.page-menus');

        if(pm.hasClass('menus-hide')) {  // 显示隐藏
            glyphicon.addClass(left).removeClass(right);
            texter.text(hideText);
            pm.removeClass('menus-hide');
           
        } else {  //显示呼出界面
            glyphicon.addClass(right).removeClass(left);
            texter.text(showText);
            pm.addClass('menus-hide');
        }
        return false;
    });

});