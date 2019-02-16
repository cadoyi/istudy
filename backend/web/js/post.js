;(function($) {
    var defaultOptions = {
        'input'       : '#tags_input',   //输入值的地方
        'unselected'  : '#unselected_tags', //未选择的按钮标签
        'selected'    : '#selected_tags',   //已选择的按钮标签
        'unselectBtn' : function(id, title) {
            var html = '<a class="btn btn-default btn-sm" href="#" data-id="'+id+'" data-title="'+title+'">';
            html += title;
            html += ' <span class="glyphicon glyphicon-plus"></span>';
            html += '</a>';
            return html;
        },
        'selectBtn' : function(id, title) {
            var html = '<a class="btn btn-primary btn-sm" href="#" data-id="'+id+'" data-title="'+title+'">';
            html += title;
            html += ' <span class="glyphicon glyphicon-minus"></span>';
            html += '</a>';
            return html;
        }
    };

    /**
     * var t = $('div')
     * @return {[type]} [description]
     */
    $.postTag = function(options) {
        var s = $.extend({}, defaultOptions, options);
        var unswrap = $(s.unselected);
        var swrap = $(s.selected);
        var input = $(s.input);
        var old_value = {};

        var pub = {
            init : function(all, selected) {
                all = all || {};
                selected = selected || {};
                $.each(selected, function(_,id) {
                    old_value[id] = id;
                });
                console.log(old_value);
                $.each(all, function(id, title) {
                    var ut = s.unselectBtn(id, title);
                    var st = s.selectBtn(id, title);
                    unswrap.append(ut);
                    swrap.append(st);
                    if(id in old_value) {
                        pub.add(id);
                    } else {
                        pub.remove(id);
                    }
                });

                unswrap.on('click', 'a.btn', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    pub.add($(this).data('id'));
                });
                swrap.on('click', 'a.btn', function(e) {
                    e.preventDefault();
                    e.stopPropagation();         
                    pub.remove($(this).data('id'));   
                });

            },
            add : function(id) {
                var selector = 'a[data-id="' + id+ '"]';
                unswrap.find(selector).hide();
                swrap.find(selector).show();
                pub.addValue(id);
            },
            remove : function(id) {
                var selector = 'a[data-id="' + id+ '"]';
                unswrap.find(selector).show();
                swrap.find(selector).hide();
                pub.removeValue(id);
            },
            addValue : function(v) {
                var o = pub.getValue();
                o[v] = v;
                pub.setValue(o);
            },
            removeValue: function(v) {
                var o = pub.getValue();
                delete o[v];
                pub.setValue(o);
            },
            getValue : function() {
               var v = input.val() || '{}';
               return JSON.parse(v);
            },
            setValue : function(v) {
                v = v || {};
                value = JSON.stringify(v);
                input.val(value);
            }
        }
        return pub;
    }
})(window.jQuery);

jQuery(function($) {
    var editor = CKEDITOR.replace(contentid);
    $("#" + formid).on("beforeValidate", function(event,messages,defereds) {
        var html = editor.document.getBody().getHtml();
        $("#" + contentid).text(html);
    });

    var postTag = $.postTag();
    postTag.init(tags, postTags);
});