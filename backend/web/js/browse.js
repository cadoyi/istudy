/**
 * 通用的树状结构.
 *
 * 
 */
;(function( $ ) {

    var Folder = function( node ) {
        this.node = node;
        this.id = CADO.tools.compose('_', 'x_tree_node', node.number);
        if(!node.rendered) {
            this.render();
            node.rendered = true;
        }
        this._ = $('#' + this.id);
        this._nameLink = null;
        this._childLink = null;
        this._childContainer = null;

    }
    Folder.prototype = {
        nameLink : function() {
            if(!this._nameLink) {
                this._nameLink = this._.children('.folder-name');
            }
            return this._nameLink;
        },
        childLink : function() {
            if(!this._childLink) {
                this._childLink = this._.children('.open-subdir');
            }
            return this._childLink;
        },
        childContainer : function() {
            if(!this._childContainer) {
                this._childContainer = this._.children('.subdirs');
            }
            return this._childContainer;
        },
        render : function() {
            var template = '<li id="{{id}}">' + 
                '<a class="folder-name" href="#" style="padding-left: {{level}}rem">' + 
                    '<span class="fa fa-fw fa-folder"></span>' + 
                    '<span class="name">{{name}}</span>' +
                '</a>' +
                '<a class="open-subdir" href="#" style="display:{{display}}">'+
                    '<span class="fa fa-fw fa-caret-left"></span>' +
                '</a>' +
                '<div class="subdirs"><ul></ul></div>' + 
            '</li>';
            var node = this.node;

            var placeholder = {
                id : this.id,
                level : node.level,
                name  : node.label,
                display: node.has_child ? 'inline-block' : 'none'
            };
            var html = CADO.tools.parseTemplate(template, placeholder);
            if(node.isRoot()) {
                html = '<ul>' + html + '</ul>';
               var element = $('#x_tree_node');
            } else {
                var element = new Folder(node.parentNode);

            }
            element.append( html );
        },
        append : function( html ) {
            if(!this.childContainer().children('ul').length) {
                html = '<ul>' + html + '</ul>';
                this.childContainer().append( html );
            } else {
                this.childContainer().children('ul').append( html );
            }
            
            
        },
        openFolder : function() {
            this.nameLink()
                .children('.fa')
                .removeClass('fa-folder')
                .addClass('fa-folder-open');  
        },
        closeFolder : function() {
            this.nameLink()
                .children('.fa')
                .removeClass('fa-folder-open')
                .addClass('fa-folder');
        },
        updateName : function( name ) {
            this.nameLink().children('.name').text(name);
        },
        hideChildLink : function() {
            this.childLink().hide();
        },
        showChildLink : function() {
            this.childLink().show();
        },
        open : function() {
            this.childLink()
                .children('.fa')
                .removeClass('fa-caret-left')
                .addClass('fa-caret-down');
            this.openFolder();
            this.childContainer().show();
        },
        close : function() {
            this.childLink()
                .children('.fa')
                .removeClass('fa-caret-down')
                .addClass('fa-caret-left');
            this.closeFolder();
            this.childContainer().hide();
        },
        select : function() {
            this._.addClass('selected');
        },
        cancelSelect : function() {
            this._.removeClass('selected');
        },
        remove : function() {
            this._.remove();
            this._ = null;            
            this._nameLink = null;
            this._childLink = null;
            this._childContainer = null;            
        }
    };



    UI = function( options ) {
        this.createFolderUrl = null;
        this.renameFolderUrl = null;
        this.removeFolderUrl = null;

        this.tree = new CADO.Tree();
        $.extend( this, options );

        this.bindEvents();
        this.selectedNode = null;
        this.fileTemplate = '<a class="image pull-left" data-name="{{name}}" href="{{url}}">' +
                '<span class="imgspan">' + 
                    '<img  alt="{{filename}}" src="{{thumb-url}}">' +
                '</span>' + 
                '<span class="fa fa-fw fa-check"></span>' + 
                '<span>{{size}}</span>' +
                '<span>{{filename}}</span>' +
            '</a>';
    }

    UI.prototype = {
        setRootNode : function( label ) {
            this.tree.setRootNode({ id : "root", label : label, has_child : true});
            return this.tree.rootNode;
        },
        renderFolder : function( node ) {
            return new Folder( node );
        },
        getFolder : function( node ) {
            return new Folder( node );
        },
        render : function( path ) {
            var me = this;
            var node = this.tree.rootNode;
            this.renderFolder( node );
            this.openSubdir( node ).then( function() {
                if(!path.length) {
                    me.select( node );
                } else {
                    me.renderPath( path );
                }
            });

        },
        renderPath : function( path ) {
            var me = this, 
                tree = this.tree;
            if(!path.length) {
                return;
            }
            if(typeof path === 'string') {
                path = path.split('/');
            }
            var id = path.shift();
            var node = tree.findNode(id);
            if(!node) {
                return;
            }
            me.openSubdir( node ).then( function() {
                if(path.length) {
                    me.renderPath( path );
                } else {
                    me.select( node );
                }
            });
        },
        bindEvents : function() {
            var handler = new Handler( this );
            handler.processEvents();
        },
        openSubdir : function( node ) {
            var me = this;
            if( node.isOpened ) return;
            if( !node.isLoaded ) {
                var defer = $.Deferred();
                $.post(this.loadFolderUrl, {
                    node : node.id
                }).then( function(res) {
                    var data = CADO.tools.checkResponse(res);
                    $.each(data, function(_, options) {
                        var child = me.tree.createNode( options );
                        node.addChild( child );
                        me.renderFolder( child );
                    });
                    node.isLoaded = true;
                    me.openSubdir( node );
                    defer.resolve();
                });
                return defer.promise();
            }
            var folder = me.getFolder( node );
            folder.open();
            node.isOpened = true;
        },
        closeSubdir : function( node ) {
            var me = this;
            if( !node.isOpened ) return;
            var ele = me.getFolder( node );
            ele.close();
            node.isOpened = false;
        },
        select : function( node ) {
            var me = this;
            $('#file_uploader').show();
            if( node === this.selectedNode ) {
                   // 显示选中状态
                var folder = this.getFolder( node );
                folder.select();   
                return;
            }
            

            var prev = this.selectedNode;
            this.selectedNode = node;
            if(prev) {
               var prevFolder = this.getFolder( prev );
               prevFolder.cancelSelect();
            }

            // 显示选中状态
            var folder = this.getFolder( node );
            folder.select();

            // 加载图片
            $.post(this.loadFilesUrl, {
                node : node.id,
            }).then( function( res ) {
                var data = CADO.tools.checkResponse(res);
                me.showImage( data );
            });

        },
        renderImage : function( file ) {
            var me = this;
            var container = $('#images_area');
            var html = CADO.tools.parseTemplate( me.fileTemplate, file);
            container.append( html );
        },
        showImage : function( data ) {
            var me = this;
            var container = $('#images_area');
            container.html('');
            if( data.length ) {
                $.each( data, function(_, file) {
                    me.renderImage( file );
                });
            } else {
                container.html('<p>There are no files</p>');
            }
        },
        getNode : function( element ) {
            var id = $(element).attr('id').replace('x_tree_node_', '');
            return this.tree.findNodeByNumber( id );
        },
        createFolderAction : function( target ) {
            var me = this;
            var node = this.getNode( $(target).parent() );

            var dialog = new CADO.Dialog({
                title : '新建文件夹',
                size : 'small',
            });
            dialog.append('input', {
                id : 'create_folder_input',
                class : 'form-control',
            });
            dialog.addButton({
                class : "btn btn-sm btn-success",
            }).text('确定').on('click', function( e ) {
                e.preventDefault();
                e.stopPropagation();
                var value = $('#create_folder_input').val();
                if(value === '' || value === null || value === undefined) {
                    return;
                }
                $.post(me.createFolderUrl, {
                    node : node.id,
                    name : value,
                }).then( function( res ) {
                    if(res.error) {
                        alert(res.message);
                        return;
                    }
                    var data = res.data;
                    var folder = me.getFolder( node );
                    folder.showChildLink();
                    if(!node.isLoaded) {
                        return;
                    }
                    var child = me.tree.createNode( data );
                    node.addChild( child );
                    me.renderFolder( child );
                });
                dialog.hide();
            });
            dialog.show();
            
        },
        renameFolderAction : function( target ) {
            var me = this,
            node = this.getNode( $(target).parent() );
            
        },
        removeFolderAction : function( target ) {
            var me = this,
            node = this.getNode( $(target).parent() );
            if(node.isRoot()) {
                alert('不能删除根节点');
                return;
            }
            if(confirm('确定要删除它吗?')) {
                $.post(me.removeFolderUrl, {
                    node : node.id
                }).then( function(res) {
                    console.log(res);
                    CADO.tools.checkResponse( res , true);
                    console.log(res);
                    var folder = me.getFolder( node );
                    folder.remove();
                    var parent = node.parentNode;
                    node.remove();
                    if(parent.childNodes.length < 1) {
                        var parentFolder = me.getFolder( parent );
                        parentFolder.hideChildLink();
                    }
                });
            }
        },
        selectFileAction : function( target ) {
            if(window.opener) {
                var url = target.href;
                var func = CADO.parseUrl().getParam('CKEditorFuncNum');
                window.opener.CKEDITOR.tools.callFunction(func, url);
                window.close();
            } else {
                console.log('file selected');
            }
        },
        renameFileAction : function( target ) {
            var me = this;
            var dialog = new CADO.Dialog({
                title : "重命名文件",
                size : 'small',
            });
            dialog.append('input', {
                id : 'rename_file_input',
                class : 'form-control',
                type  : 'text',
                value : function() {
                    return $(target).data('name');
                }
            });
            dialog.addButton({
                class : 'btn btn-sm btn-success',
            }).text('确定').on('click', function(e) {
                var value = $('#rename_file_input').val();
                if(value === '' || 
                    value === null || 
                    value === undefined ||
                    value == $(target).data('name')) {
                    return;
                }
                $.post(me.renameFileUrl, {
                    node : me.selectedNode.id,
                    file : $(target).data('name'),
                    name : value
                }).then( function( res ) {
                    var data = CADO.tools.checkResponse( res );
                    var html = CADO.tools.parseTemplate(me.fileTemplate, data);
                    $(target).replaceWith(html);
                    dialog.hide();
                });
            });
            dialog.show();
        },
        removeFileAction : function( target ) {
            var me = this;
            if(!confirm('确定要删除它吗?')) {
                return;
            }
            $.post(me.removeFileUrl, {
                node : me.selectedNode.id,
                name : $(target).data('name') 
            }).then( function(res) {
                if(res.error) {
                    alert(res.message);
                    return;
                }
                $(target).remove();
            });
        }

    }


    var Handler = function( ui ) {
        this.ui = ui;
        this.container = $('body');
    }
    Handler.prototype = {
        processEvents : function() {
            this.processFolderEvents();
            this.processFileEvents();
            this.processActions();
        },
        processFileEvents : function() {
            this.container.click({
                selector : 'a.image',
                handler : function( e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if($(this).hasClass('active')) {
                        $(this).removeClass('active');
                    } else {
                        $(this).siblings('.image').removeClass('active');
                        $(this).addClass('active');
                    }
                }
            }).dblclick({
                selector : 'a.image',
                handler : function(e) {
                    console.log('dblclick');
                    e.preventDefault();
                    e.stopPropagation();
                    if(window.opener) {
                        var url = this.href;
                        var func = CADO.parseUrl().getParam('CKEditorFuncNum');
                        window.opener.CKEDITOR.tools.callFunction(func, url);
                        window.close();
                    }
                }
            }).on('contextmenu', 'a.image', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var menu = $('#fileMenu');
                menu.show();
                menu.children('ul').css({
                    left : e.pageX,
                    top : e.pageY,
                });
                menu.height($('#x_tree_container').height());
                menu.data('target', this);
            });
        },
        processFolderEvents : function() {
            var me = this, 
                ui = this.ui;

            this.container.click({
                selector : 'a.open-subdir',
                handler : function( e ) {
                    me.stopEvent( e );
                    var node = me.getNode( $(this).parent() );
                    if( node.isOpened ) {
                        ui.closeSubdir( node );
                    } else {
                        ui.openSubdir( node );
                    }
                }
            }).click({
                selector : 'a.folder-name',
                handler : function( e ) {
                    me.stopEvent( e );
                    var node = me.getNode( $(this).parent() );
                    ui.select( node );
                    
                } 
            }).on('contextmenu', 'a.folder-name', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var menu = $('#folderMenu');
                menu.children('ul').css({
                    left : e.pageX,
                    top : e.pageY,
                });
                menu.show();
                menu.height($('#x_tree_container').height());
                menu.data('target', this);
            });
            $('.popup-contextmenu').click(function( e ) {
                $(this).hide();
            }).on('contextmenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).hide();
            });
        },
        stopEvent : function( e ) {
            e.preventDefault();
            e.stopPropagation();
        },
        getNode : function( nodeElement ) {
            return this.ui.getNode( nodeElement );
        },
        processActions : function() {
            var me = this;
            $('.popup-contextmenu').click({
                selector : 'a[data-action]',
                handler : function( e ) {
                    e.preventDefault();
                    e.stopPropagation();
                    var menu = $(this).closest('.popup-contextmenu');
                    var action = $(this).data('action');
                    var method = action + 'Action';
                    if(me.ui[method]) {
                        //var target = menu.data('target');
                        //var node = me.getNode( $(target).parent());
                        me.ui[method](menu.data('target'));
                    }
                    menu.hide();
                }
            });
        }
    }

})(window.jQuery);