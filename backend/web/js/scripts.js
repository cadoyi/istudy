
jQuery(function( $ ) {
    var addBodyClass = function() {
        var size = $(window).width();
        var old = ($('body').attr('class') || '').split(' ');
        var exists = {};
        var diff = [];
        $.each(['b-xs', 'b-xs-w', 'b-xs-h', 'b-pc', 'b-pc-sm', 'b-pc-md', 'b-pc-lg'], function(_, value) {
            exists[value] = true;
        });
        $.each(old, function(_, value) {
            if(value && value.length && !(value in exists)) {
                diff.push($.trim(value));
            }
        });
        if(size < 768) {
            diff.push('b-xs');
            diff.push( size < 467 ? 'b-xs-w' : 'b-xs-h');
        } else {
            diff.push('b-pc');
            if(size < 992) {
                diff.push('b-pc-sm');
            } else if(size < 1120) {
                diff.push('b-pc-md');
            } else {
                diff.push('b-pc-lg');
            }
        }
        $('body').attr('class', diff.join(' '));
    }
    addBodyClass();

    $(window).resize(function(){
        addBodyClass();
    });



});