
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

    $(".grid-view").on("click", "a.action-view", function(e){
        e.preventDefault();
        e.stopPropagation();
        var tr = function(k,v) {
            return '<tr><th>' + k.toString() + '</th><td>' + v + '</td></tr>';
        }
        var mtrs = function(last) {
            var html = '';
            $.each(last, function(index, model){
                html += '<tr><td colspan="2" style="background-color:#ddd;">&nbsp;</td></tr>';
                html += trs(model);
            });
            return html;
        }
        var trs = function(model) {
            var k, last = [], arr = [], html = '';
            for(k in model) {
                var v = model[k];
                if($.isPlainObject(v)) {
                    last.push(v);
                    continue;
                } else if($.isArray(v)) {
                    arr.push(v);
                    continue;
                }
                html += tr(k,v);
            }
            html += mtrs(last);
            $.each(arr, function(_, a) {
                html += mtrs(a);
            });
            return html;
        }

        $.get(this.href).then(function(model){
            var html = '<table class="table table-hover table-stripped table-bordered">';
            html += trs(model);

            html += '</table>';
            $(".modal-body").html(html);
            $(".modal").modal("show");
        });
    });




});

