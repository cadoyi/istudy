
jQuery(function($){
    $('#menus li > ul').each(function() {
        $(this).addClass('child-items');
        $(this).parent().addClass('has-childs');
    });

    $('#menus').on('click', 'li.has-childs', function(e){
        var ul = $(this).children('ul');
        if(ul.length) {
            if(this == e.target || this == $(e.target).closest('li').get(0)) {
                ul.toggleClass('open');
                $(this).toggleClass('opened');
                return false;
            }
        }
    });

    $('#switchmenu').on('click', function(e){
        $('#page_menus').toggleClass('full');
        $('#page_content').toggleClass('full');
        $('body').toggleClass('menu-full');
        return false;
    });
});