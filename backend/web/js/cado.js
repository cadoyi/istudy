/**
 * require jquery.js
 * require bootstrap.js
 * 
 */
;(function( $ ) {
    var 
    number = 0,
    version = "1.0.0"

    ;

    CADO = {};

    CADO.tools = {

        /**
         * 生成全局唯一的 number 号码.
         */
        nextNumber : function() {
            return number++;
        },

        /**
         * 检查 ajax 响应是否成功,
         * 
         * @param  object response ajax 的响应
         *    error : 布尔值, 是否有错误
         *    message : 错误消息
         *    data : 附加数据(可选)
         * @throws 如果 error 为 true ,则抛出错误消息.
         * @return 返回附加数据.
         */
        checkResponse : function( response , show) {
            if(response.error) {
                if(show) {
                    alert(response.message);
                }
                throw response.message;
            }
            return response['data'];
        },


        /**
         * 进行模板替换, 可以建立 HTML 模板.
         *
         * @param {string} template 模板字符串
         * @param {object} data 模板数据
         * @param {regexp} 自定义模式,至少要有一个括号.
         */
        parseTemplate : function( template, data , pattern ) {
            if(!( pattern instanceof RegExp )) {
                pattern = /\{\{(.+?)\}\}/g;
            }
            return template.replace(pattern, function(match, key) {
                if( typeof data[key] == "undefined") {
                    return match;
                }
                return String(data[key]);
            });
        },
 
        /**
         * 组合字符串
         * 
         * @return {[type]} [description]
         */
        compose : function( sep ) {
            if(arguments.length < 3) {
                throw 'arguments too few';
            }
            var args = Array.prototype.slice.call(arguments, 1),
            str = args.shift(),
            last = args.pop();
            last = last === true ? this.nextNumber() : last;

            $.each( args, function(_, arg) {
                str += sep + arg;
            });
            str += sep + last;
            return str;
        },
        /**
         * 异步执行,传入一个函数,生成一个新的函数,调用新的函数会异步执行.
         *  var func = CADO.tools.defer( function() { console.log(arguments); }, 300);
         *  func('abc');
         * @param  {[type]} func [description]
         * @return {[type]}      [description]
         */
        defer : function( func, timer) {
            timer = timer || 0;
            return function() {
                var args = arguments, me = this;
                setTimeout( function() {
                    func.apply( me, args);
                }, timer );
            };
        },
    };

    CADO.objectHelper = {
        hasKey : function( options, key) {
            return typeof options[key] != 'undefined';
        },
        remove : function( options , key , defaultValue) {
            if(!this.hasKey(options, key)) {
                return defaultValue; 
            }
            var value = options[key];
            delete options[key];
            return value;
        },
        getValue : function( options, key, defaultValue) {
            return this.hasKey(options, key) ? options[key] : defaultValue;
        },
        clone : function(options, deep) {
           if(deep) {
               return $.extend(true, {}, options);
           }
           return $.extend({}, options);
        }
    }


    var Url = function(url) {
        this._anchor = document.createElement('a');
        this._anchor.href = url;
        this._url = this._anchor.href;
        this._params = {};
        this._baseUrl = null;
        this.init();
    }

    Url.location = null;

    Url.prototype = {
        init : function() {
            var anchor = this._anchor;
            var search = anchor.search.replace('?', "");
            if(search === "") {
                return;
            }
            var arr = search.split('&');
            var me = this;
            $.each(arr, function(_, item) {
                var param = item.split('=');
                me._params[param[0]] = param.length > 1 ? param[1] : "";
            });

            var prefix = this._url.split("?")[0];
            if(anchor.pathname) {
                this._baseUrl = prefix.substr(0, prefix.indexOf(anchor.pathname));
            } else {
                this._baseUrl = prefix;
            }
        },
        getParams : function() {
            return this._params;
        },
        getParam : function( k , decoded ) {
            if(k in this._params) {
                var value = this._params[k];
                return decoded ? decodeURIComponent(value) : value;
            }
            return null;
        },
        getBaseUrl : function( trim ) {
            return trim ? this._baseUrl : this._baseUrl + '/';
        }
    };

    CADO.parseUrl = function( url ) {
        if( ! url ) {
            return Url.location || ( Url.location = new Url( location.href ));
        }
        return new Url( url );
    }


    CADO.Element = function(tag, attrs) {
        var _attrs = {};

        $.each( attrs || {}, function( attr, value) {
            if($.isPlainObject( value )) {
                $.each(value, function( k, v) {
                    _attrs[attr + '-' + k] = v;
                });
            } else if($.isArray( value )) {
                _attrs[attr] = value.join(' ');
            } else {
                _attrs[attr] = value;
            }
        });

        this._ = $('<' + tag + '>', _attrs);
    }

    CADO.Element.prototype = {
        append : function(tag, attrs) {
            var child = new CADO.Element(tag, attrs);
            this._.append(child._);
            return child;
        },
        appendText : function( text ) {
            return this.append('span').text(text);
        },
        text : function(text) {
            this._.text(text);
            return this;
        },
        html : function(html) {
            this._.html(html);
            return this;
        },
        on : function() {
            this._.on.apply(this._, arguments);
            return this;
        },
        off : function() {
            this._.off.apply(this._, arguments);
            return this;
        },
        data : function() {
            this._.data.apply(this._, arguments);
        },
    }

    CADO.createElement = function(tag, attrs) {
        return new CADO.Element(tag, attrs);
    }

    
    CADO.Dialog = ( function() {
        var template = '<div class="modal fade">' +
            '<div class="modal-dialog">' + 
                '<div class="modal-content">' + 
                     '<div class="modal-header" style="padding:8px 15px;">' +
                         '<button type="button" class="close" data-dismiss="modal">' + 
                              '<span aria-hidden="true">&times;</span>' + 
                         '</button>' +
                         '<h4 class="modal-title"></h4>' + 
                     '</div>' +
                     '<div class="modal-body"></div>' + 
                     '<div class="modal-footer" style="padding:8px 15px;"></div>' +
                '</div>' +
            '</div>' +
        '</div>';

        var defaultOptions = {
            container     : 'body',
            destoryOnHide : true,            //隐藏的时候删除.
            size          : 'small',         //窗口尺寸, large, small, normal
            id            : true,            //true 表示自动生成, false 表示不设置 id, 其他表示设置自定义 id
            showClose     : true,            //是否显示关闭按钮
            title         : '',              //标题
            data          : {                //数据属性
                backdrop : "static",
                keyboard : false,
            },              
        };

        var Dialog = function( options ) {
            this.options = s = $.extend(true, {}, defaultOptions, options || {});
            this.modal = $(template);
            $(s.container).append(this.modal);

            this._title  = this.modal.find('.modal-title');
            this._body   = this.modal.find('.modal-body');
            this._footer = this.modal.find('.modal-footer');
            this._dialog = this.modal.find('.modal-dialog');
            this.init();

            var me = this;
            this.modal.on('hide.bs.modal', function() {
                if(s.destoryOnHide && me.modal) {
                    me.modal.one('hidden.bs.modal', function() {
                        me.modal.remove();
                        me.modal = null;
                    });
                }
            });
        }

        Dialog.prototype = {
            init : function() {
                var me = this, s = this.options;

                // 设置 id
                if(s.id === true) {
                    this.modal.attr('id', 'dialog_modal_' + CADO.tools.nextNumber());
                } else if(s.id !== false) {
                    this.modal.attr('id', s.id);
                }

                // 设置 data 属性
                $.each(s.data, function(k, v) {
                    me.modal.attr('data-' + k, v);
                    me.modal.data(k, v);
                });

                this.resize( s.size );
                this.title( s.title );

                if(s.showClose) {
                    this.addButton({
                        class : ["btn", "btn-sm", "btn-default"],
                        data : {
                            dismiss : "modal",
                        }
                    }).text('关闭');
                }
            },
            title : function( title ) {
                this._title.text( title );
                return this;
            },
            resize : function( size ) {
                size = size.toLowerCase();
                switch( size ) {
                    case "small":
                    case "sm":
                    case "modal-sm":
                        this._dialog.removeClass('modal-lg').addClass('modal-sm');
                        break;
                    case "large":
                    case "big":
                    case "lg":
                    case "modal-lg":
                        this._dialog.removeClass('modal-sm').addClass('modal-lg');
                        break;
                    default:
                        this._dialog.removeClass('modal-lg').removeClass('modal-sm');
                }
            },
            append : function(tag, attrs) {
                var child = CADO.createElement(tag, attrs);
                this._body.append(child._);
                return child;
            },
            addButton : function(tag, attrs) {
                if($.isPlainObject( tag )) {
                    attrs = tag;
                    tag = 'button';
                }
                var button = CADO.createElement(tag, attrs);
                this._footer.append( button._ );
                return button;
            },
            show : function() {
                if(this.modal) {
                   this.modal.modal('show'); 
                }
            },
            hide : function() {
                this.modal.modal('hide');
            }
        }
        return Dialog;
    })();

    CADO.dialog = function( options ) {
        return new CADO.Dialog( options );
    }


/**************** Tree ********************************/

    /**
     * 
     * @param object options  配置选项
     *     id        : 当前 node 的 id
     *     label     : 当前 node 的 label 文本
     *     has_child : 是否有子项目
     *      
     * @param Tree tree   CADO.Tree 对象.
     */
    CADO.TreeNode = function( options, tree ) {
        this.tree       = tree;
        this.id         = options['id'];
        this.label      = options['label'];
        this.has_child  = options['has_child'] || false;
        this.options    = options;
        this.parentNode = null;                      // 父节点
        this.childNodes = [];                        // 子节点
        this.level      = 0;                         // 树的级别
        this.number     = CADO.tools.nextNumber();   // 唯一号码
    }

    CADO.TreeNode.prototype = {
        /**
         * 是否是 root 节点
         * 
         * @return boolean
         */
        isRoot : function() {  
            return this === this.tree.rootNode;
        },
        /**
         * 增加子节点.
         * 
         * @param TreeNode node
         */
        addChild : function( node ) {
            this.has_child = true;
            node.parentNode = this;
            this.childNodes.push( node );
            node.level = this.level + 1;
            this.tree.nodeHash[ node.id ] = node;
            this.tree.numberHash[ node.number ] = node;
        },
        /**
         * 移除子节点. 包括子节点的子节点
         * 
         * @param  TreeNode node 
         */
        removeChild : function( node ) {
            var me = this, index = false;
            if( node.childNodes.length ) {
                node.removeChilds();
            }
            this.tree.removeNode(node);
            $.each(this.childNodes, function(idx, child) {
                if(child === node) {
                    me.childNodes.splice(idx, 1);
                    if(me.childNodes.length < 1) {
                        me.has_child = false;
                    }
                    return false;
                }
            });
        },
        removeChilds : function() {
            var me = this;
            if(this.has_child) {
                $.each(this.childNodes, function(_, node) {
                    me.tree.removeNode( node );
                    node.removeChilds();
                });
            }
            this.has_child = false;
            this.childNodes = [];
        },
        remove : function() {
            if(this.parentNode) {
                this.parentNode.removeChild(this);
            }
        }

    }

    CADO.Tree = function() {
        this.rootNode    = null;   // 根节点
        this.numberHash  = {};     // number 对应 node
        this.nodeHash    = {};     // id  对应 node
    }

    CADO.Tree.prototype = {
        setRootNode : function( options ) {
            var node = this.createNode( options );
            this.nodeHash[ node.id ]       = node;
            this.numberHash[ node.number ] = node;
            this.rootNode                  = node;
        },
        createNode : function( options ) {
            var node = new CADO.TreeNode( options , this);
            return node;
        },
        updateNode : function( node, options ) {
            var id = node.id;
            $.each(options, function(k, v) {
                node[k] = $.isFunction(v) ? v( node ) : v;
            });
            node.options = options;
            if(id != node.id) {
                this.nodeHash[ node.id ] = node;
                delete this.nodeHash[ id ];
            }
            return node;
        },
        removeNode : function( node ) {
            if( node.isRoot() ) {
                throw '不能移除根节点';
            }
            delete this.nodeHash[ node.id ];
            delete this.nodeHash[ node.number ];
        },
        findNode : function( id ) {
            if( id in this.nodeHash ) {
                return this.nodeHash[ id ];
            }
            return null;
        },
        findNodeByNumber : function( number ) {
            if( number in this.numberHash ) {
                return this.numberHash[ number ];
            }
            return null;
        }
    }

})( window.jQuery );