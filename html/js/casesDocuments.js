 //
//Scripts for documents panel on cases tab
//

/* global escape, escapeHtml, unescape, notify, rte_toolbar, qq , isUrl */

function createTrail(path) {
    var pathArray = path.split('/');
    var pathString = '';
    var pathS = '';
    $.each(pathArray, function(i, v) {
        pathS += '/' + v;
        var pathSa = pathS.substr(1);
        var pathItem = '<a class="doc_trail_item" href="#" path="' + pathSa + '">' + escapeHtml(unescape(v)) + '</a>/';
        pathString += pathItem;
    });
    return pathString;
}

function createDragDrop() {
    //Destroy any previously created draggables and droppables
    $('.item, .folder').draggable('destroy');
    $('.folder').droppable('destroy');

    $('.item').draggable({
        revert: 'invalid',
        helper: 'clone'
    });
    $('.folder').droppable({activeClass: 'ui-state-highlight',drop: function(event, ui) {
            var docType = null;
            if (ui.draggable.hasClass('folder')) {
                docType = 'folder';
            } else {
                docType = 'item';
            }

            var caseId = ui.draggable.closest('.case_detail_panel').data('CaseNumber');
            $.post('lib/php/data/cases_documents_process.php', {
                'action': 'cut',
                'item_id': ui.draggable.attr('data-id'),
                'target_path': $(event.target).attr('path'),
                'selection_path': ui.draggable.attr('path'),
                'doc_type': docType,
                'case_id': caseId
            }, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.wait){
                    notify(serverResponse.message,true,'error');
                } else {
                    notify(serverResponse.message);
                    ui.draggable.fadeOut();
                }
            });
        }}).draggable({revert: 'invalid',containment: 'div.case_detail_panel_casenotes'});
}

function createTextEditor(target, action, permission, title, content, id, owner, locked) {
    var editor = '<div class="text_editor_bar" data-id="">' +
    '<div class="text_editor_title" tabindex="0">' + title +
    '</div><div class="text_editor_status"><span class= "status">Unchanged</span>' +
    '</div></div><textarea class="text_editor"></textarea>';

    //Add title area and textarea
    target.html(editor);

    //Define variables
    var ccdTitleArea = target.find('.text_editor_title');
    var ccdStatusArea = target.find('.text_editor_status');
    var ccdTitle = target.find('.text_editor_title').html();
    var caseId = target.closest('.case_detail_panel').data('CaseNumber');
    var currentPath = target.closest('.case_detail_panel').data('CurrentPath');
    var docIdArea = target.find('.text_editor_bar');
    var tools = target.siblings('.case_detail_panel_tools');

    //Define current path. Db leaves folder field blank for documents in root directory, so send empty value
    if (currentPath === 'Home') {
        currentPath = '';
    }

    //Create lwrte
    var arr = target.find('.text_editor').rte({
        css: ['lib/javascripts/lwrte/default2.css'],
        width: 900,
        height: 400,
        controls_rte: rte_toolbar
    });

    //If this is not a new document, then set the editor content from the db
    if (action === 'view') {
        arr[0].set_content(content);
        ccdTitleArea.html(escapeHtml(unescape(title)));
        docIdArea.attr('data-id', id);
    }

    //If the user doesn't have permission to edit, make read only
    if (permission === 'no' && owner !== '1') {
        $(arr[0].iframe_doc).keydown(function(event) {
            return false;
        });
        ccdStatusArea.html('<span class="readonly">Read Only</status>');
        target.find('.rte-toolbar a').not('.print').css({'opacity': '.3'});
        target.find('.rte-toolbar select').css({'opacity': '.3'});
    }

    if (owner === '1'){
        var permSelect = '<select name="ccd_permission">';
        if (locked === 'no'){
            permSelect += '<option value="no" selected=selected>Unlocked</option>' +
            '<option value="yes">Locked</option>';
        } else {
            permSelect += '<option value="no" >Unlocked</option><option value="yes"' +
            'selected=selected>Locked</option>';
        }
        permSelect += '</select>';
        ccdStatusArea.append(permSelect);
    }

    //If this is a new document, create new ccd (ClinicCases Document) in db
    if (action === 'new') {
        $.post('lib/php/data/cases_documents_process.php', {
            'action': 'new_ccd',
            'ccd_name': escape(ccdTitle),
            'local_file_name': 'New Document.ccd',
            'path': currentPath,
            'case_id': caseId
        }, function(data) {
            var serverResponse = $.parseJSON(data);
            docIdArea.attr('data-id', serverResponse.ccd_id);
            ccdTitleArea.html(escapeHtml(unescape(serverResponse.ccd_title)));
        });
    }

    //hide main buttons, initialize new one
    tools.find('button').hide();
    tools.find('input').hide();
    tools.siblings('.case_documents_submenu').hide();
    //Need to hide the search and the path now!!!! TODO
    tools.find('.case_detail_panel_tools_right').append('<button class="closer">Close</button>');
    tools.find('button.closer').button({icons: {primary: 'fff-icon-cross'},text: true});
    tools.find('button.closer').click(function() {
        var returnToFiles = function () {
            if (currentPath === '') {  //the document is not in a subfolder
                target.load('lib/php/data/cases_documents_load.php', {
                    'id': caseId,
                    'update': 'yes',
                    'path': currentPath
                }, function() {
                    tools.find('button').show();
                    tools.find('input').show();
                    tools.find('.documents_search_clear').hide();
                    tools.find('button.closer').remove();
                    tools.siblings('.case_documents_submenu').show();
                    createDragDrop();
                });
            } else {  //document is in a subfolder
                target.load('lib/php/data/cases_documents_load.php', {
                    'id': caseId,
                    'update': 'yes',
                    'path': currentPath,
                    'container': currentPath
                }, function() {
                    tools.find('button').show();
                    tools.find('input').show();
                    tools.find('.documents_search_clear').hide();
                    tools.find('button.closer').remove();
                    tools.siblings('.case_documents_submenu').show();
                    createDragDrop();
                });
            }
        };
        //If the author is closing and both the title and body are empty,
        //probably clicked new documents by mistake.  Kill document.
        //Alert ('this document is empty.  Delete it?')
        if ($('.text_editor').html() === '' && $('.text_editor_title').text() === 'New Document'){
            var warning = 'It appears this document is empty. Do you want to save it anyway?';
            var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Item?">' + warning + '</div>')
            .dialog({
                autoOpen: true,
                resizable: false,
                modal: true,
                buttons: {
                    'Yes': function() {
                        dialogWin.dialog('destroy');
                        returnToFiles();
                    },
                    'No': function() {
                        var itemId = $('.text_editor_bar').attr('data-id');
                        $.post('lib/php/data/cases_documents_process.php',
                        ({'action': 'delete','item_id': itemId,'doc_type': 'ccd'}), function(data) {
                            var serverResponse = $.parseJSON(data);
                            notify(serverResponse.message);
                            dialogWin.dialog('destroy');
                        });
                        $(this).dialog('destroy');
                        returnToFiles();
                    }
                }
            });

        } else {
            returnToFiles();
        }
    });

    //If user has permission to edit, set the editing functions
    if (permission === 'yes') {
        //Change document title
        ccdTitleArea.mouseenter(function() {
            $(this).css({'color': 'red'});
        })
        .click(function() {
            $(this).html('<input type="text" value="" />');
            $(this).find('input').val(escapeHtml(unescape(ccdTitle))).focus().select();
        })
        .bind('focusout keyup', function(e) {
            if (e.type === 'focusout' || e.which === 13) {
                ccdTitle = escape($(this).find('input').val());

                if(ccdTitle === '' || ccdTitle === '\n') {
                    notify('Please give your document a title.',true);
                    $(this).find('input').addClass('ui-state-error').focus();
                    return false;
                } else {
                    $(this).html(escapeHtml(unescape(ccdTitle)));
                    $(this).css({'color': 'black'});
                    var getText = arr[0].get_content();
                    $.post('lib/php/data/cases_documents_process.php', {
                        'action': 'update_ccd',
                        'ccd_name': ccdTitle,
                        'ccd_id': docIdArea.attr('data-id'),
                        'ccd_text': getText
                    }, function(data) {
                        var serverResponse = $.parseJSON(data);
                        notify(serverResponse.message);
                    });
                }
            }

        })
        .mouseleave(function() {
            $(this).css({'color': 'black'});
        });

        //auto-save
        var lastText = '';
        var autoSave = function (lastText, arr) {
            var text = arr[0].get_content();
            var status = 'Saving...';
            if (text !== lastText) {
                ccdStatusArea.find('span.status').html(status);
                $.post('lib/php/data/cases_documents_process.php', {
                    'action': 'update_ccd',
                    'ccd_name': ccdTitleArea.html(),
                    'ccd_id': docIdArea.attr('data-id'),
                    'ccd_text': text
                }, function(data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error) {
                        ccdStatusArea.find('span.status').html(serverResponse.message);
                    } else {
                        ccdTitleArea.html(serverResponse.ccd_title);
                        ccdStatusArea.find('span.status').html(serverResponse.message);
                    }
                });
                lastText = text;
            }

            var t = setTimeout(function() {
                autoSave(lastText, arr);
            }, 3000);
        };

        autoSave(lastText, arr);
    }
}

function clearSearchBox(el){
    //Clear search results, if any
    el.closest('.case_detail_panel').find('input.documents_search').val('Search Titles').css({'color': '#AAA'});
    el.closest('.case_detail_panel').find('.documents_search_clear').hide();

    //If the trail has been previously hidden because we were showing 
    //search results, show it again
    el.closest('.case_detail_panel').find('.case_documents_submenu').show();
}

function openItem(el, itemId, docType, caseId, path, pathDisplay) {
    if ($(el).hasClass('folder')) {
        if ( $(el).hasClass('.ui-draggable-dragging') ) {
            return;
        }

        $(el).closest('.case_detail_panel_casenotes') .load('lib/php/data/cases_documents_load.php', {
            'id': caseId,
            'container': path,
            'path': path,
            'update': 'y'
        }, function() {
            var pathString = createTrail(path);
            pathDisplay.html(pathString);
            pathDisplay.find('a[path="' + path + '"]').addClass('active');
            createDragDrop();

            //Set the current path so that other functions can access it
            $(this).closest('.case_detail_panel').data('CurrentPath', path);
            
            clearSearchBox($(this));

            //Apply shadow on scroll
            $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
                var scrollAmount = $(this).scrollTop();
                if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
                    $(this).removeClass('csenote_shadow');
                } else {
                    $(this).addClass('csenote_shadow');
                }
            });
        });
    } else if ($(el).hasClass('url')) {
        if ( $(el).hasClass('.ui-draggable-dragging') ) {
            return;
        }

        $.post('lib/php/data/cases_documents_process.php', {
            'action': 'open',
            'item_id': itemId,
            'doc_type': 'document'
        }, function(data) {
            var serverResponse = $.parseJSON(data);
            window.open(serverResponse.target_url, '_blank');
        });
    } else if ($(el).hasClass('ccd')) {
        if ( $(el).hasClass('.ui-draggable-dragging') ) {
            return;
        }

        $.post('lib/php/data/cases_documents_process.php', {
            'action': 'open',
            'item_id': itemId,
            'doc_type': 'document'
        }, function(data) {
            var serverResponse = $.parseJSON(data);
            var target = $(el).closest('.case_detail_panel_casenotes');
            createTextEditor(target, 'view', serverResponse.ccd_permissions, serverResponse.ccd_title,
            serverResponse.ccd_content, serverResponse.ccd_id,serverResponse.ccd_owner,serverResponse.ccd_locked);
        });
    } else if ($(el).hasClass('pdf')){
        if (Object.create){ //informal browser check for ie8
            //Show pdfjs viewer
            $('#pdf-viewer').show();
            $('#frme').attr('src', 'lib/javascripts/pdfjs/web/viewer.html?item_id=' + itemId);

            //Add listener to close pdf viewer
            $('#pdf-viewer').click(function(){
                $('#frme').attr('src','');
                $(this).hide();
            });

            //Close pdfviewer on escape key press
            $('body').bind('keyup.pdfViewer', function (e){
                if (e.keyCode === 27){
                    $('#frme').attr('src','');
                    $('#pdf-viewer').hide();
                }
            });
        } else {
            //pdfjs is not supported; revert to download
            $.download('lib/php/data/cases_documents_process.php', {
                'item_id': itemId,
                'action': 'open',
                'doc_type': docType
            });
        }
    } else {
        if ( $(el).hasClass('.ui-draggable-dragging') ) {
            return;
        }

        $.download('lib/php/data/cases_documents_process.php', {
            'item_id': itemId,
            'action': 'open',
            'doc_type': docType
        });
    }
}

//User clicks to open document window
$('.case_detail_nav #item3').live('click', function() {

    var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');

    //Get heights
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var documentsWindowHeight = thisPanelHeight - toolsHeight;

    //Set the current path so that other functions can access it
    thisPanel.data('CurrentPath', 'Home');

    thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId}, function() {
        //Set css
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': documentsWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '20%'});
        $('div.case_detail_panel_tools_right').css({'width': '80%'});
        $('div.case_detail_panel_tools').css({'border-bottom': '1px solid #AAA','margin-bottom':'10px'});

        //Set buttons
        $(this).find('button.doc_new_doc').button({icons: {primary: 'fff-icon-page-add'},text: true});
        $(this).find('button.doc_new_folder').button({icons: {primary: 'fff-icon-folder-add'},text: true});
        $(this).find('button.doc_upload').button({icons: {primary: 'fff-icon-page-white-get'},text: true});
        $(this).find('.documents_view_chooser' ).buttonset();
        $(this).find('.radio_toggle_grid').button({icons:{primary:'fff-icon-application-view-icons'},text:true});
        $(this).find('.radio_toggle_list').button({icons:{primary:'fff-icon-application-view-list'},text:true}).next().addClass('buttonset-inactive');

        //Check to see if list or grid view is set
        if (!$.cookie('cc_doc_view') || $.cookie('cc_doc_view') === 'grid'){
            $(this).find('.radio_toggle_grid').next().removeClass('buttonset-inactive');
            $(this).find('.radio_toggle_list').next().addClass('buttonset-inactive');
        } else {
            $(this).find('.radio_toggle_list').next().removeClass('buttonset-inactive');
            $(this).find('.radio_toggle_grid').next().addClass('buttonset-inactive');
        } 

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
                $(this).removeClass('csenote_shadow');
            } else {
                $(this).addClass('csenote_shadow');
            }
        });
        createDragDrop();

    });

    //Create context menu
    $('.doc_item').contextMenu({menu: 'docMenu'}, function(action, el, pos) {
        var itemId = $(el).attr('data-id');
        var docType = null;
        var caseId = $(el).closest('.case_detail_panel').data('CaseNumber');
        var docName = $(el).find('p').html();
        var pathDisplay = $(el).closest('.case_detail_panel_casenotes')
            .siblings('.case_detail_panel_tools')
            .find('.path_display');
        var path;

        if ($(el).hasClass('folder')) {
            docType = 'folder';
            path = $(el).attr('path');
        } else {
            docType = 'document';
            path = '';
        }

        switch (action) {
            case 'open':
                openItem(el, itemId, docType, caseId, path, pathDisplay);
                break;
            case 'cut':
                $(el).css({'opacity': '.5'});

                //Stash the data about the cut file or folder
                var cutData = new Array(itemId, docType, path, caseId);
                $(el).closest('.case_detail_panel_casenotes').data('cutValue', cutData);

                //Create a new context menu which allows for copying and pasting into a div with no items;
                $('div.case_detail_panel_casenotes').contextMenu({
                    menu: 'docMenu_copy_paste'
                }, function(action, el, pos) {
                    if (action === 'paste') {
                        var caseId = el.data('cutValue')[3];
                        var docType = el.data('cutValue')[1];
                        var targetPath = el.closest('.case_detail_panel').data('CurrentPath');
                        if (targetPath === 'Home') {
                            targetPath = '';
                        }
                        var itemId = el.data('cutValue')[0];
                        var selectionPath = el.data('cutValue')[2];
                        $.post('lib/php/data/cases_documents_process.php', {
                            'action': 'cut',
                            'item_id': itemId,
                            'target_path': targetPath,
                            'selection_path': selectionPath,
                            'doc_type': docType,
                            'case_id': caseId
                        },
                        function(data) {
                            var serverResponse = $.parseJSON(data);
                            if (serverResponse.error){
                                notify(serverResponse.message,true,'error');
                            } else {
                                notify(serverResponse.message);
                            }
                            el.closest('.case_detail_panel_casenotes')
                            .load('lib/php/data/cases_documents_load.php', {
                                'id': caseId,
                                'update': 'yes',
                                'path': targetPath,
                                'container': targetPath
                            }, function() {
                                el.destroyContextMenu();
                                createDragDrop();
                            });
                        });
                    }
                });
                break;
            case 'copy':
                if (docType === 'folder') { //TODO add copying of folders
                    notify('Sorry, copying of folders is not supported.', true);
                } else {
                    $(el).css({'border': '1px solid #AAA'});
                    //Stash the data about the copy file or folder
                    var copyData = new Array(itemId, docType, path, caseId);
                    $(el).closest('.case_detail_panel_casenotes').data('copyValue', copyData);

                    //Create a new context menu which allows for pasting into a div with no items;
                    $('div.case_detail_panel_casenotes').contextMenu({
                        menu: 'docMenu_copy_paste'
                    }, function(action, el, pos) {
                        if (action === 'paste') {
                            var caseId = el.data('copyValue')[3];
                            var docType = el.data('copyValue')[1];
                            var selectionPath = el.data('copyValue')[2];
                            var targetPath = el.closest('.case_detail_panel').data('CurrentPath');
                            if (targetPath === 'Home') {
                                targetPath = '';
                            }
                            var itemId = el.data('copyValue')[0];
                            $.post('lib/php/data/cases_documents_process.php', {
                                'action': 'copy',
                                'item_id': itemId,
                                'target_path': targetPath,
                                'doc_type': docType,
                                'case_id': caseId
                            },
                            function(data) {
                                var serverResponse = $.parseJSON(data);
                                notify(serverResponse.message);
                                el.closest('.case_detail_panel_casenotes')
                                .load('lib/php/data/cases_documents_load.php', {
                                    'id': caseId,
                                    'update': 'yes',
                                    'path': targetPath,
                                    'container': targetPath
                                }, function() {
                                    el.destroyContextMenu();
                                });
                            });
                        }
                    });
                }
                break;

            case 'rename':
                var textVal = $(el).find('p').html(),
                submitChange = function (cb) {
                    var newVal = escape($.trim($(el).find('textarea').val()));
                    //Don't save an empty value
                    if (newVal === ''){
                        return;
                    }
                    $.post('lib/php/data/cases_documents_process.php', ({
                        'action': 'rename',
                        'new_name': newVal,
                        'item_id': itemId,
                        'doc_type': docType,
                        'path': path,
                        'case_id': caseId
                    }), function(data) {
                        var serverResponse = $.parseJSON(data);
                        if (serverResponse.error){
                            notify(serverResponse.message);
                            return;
                        } else {
                            $(el).find('textarea').hide();
                            $(el).find('p').html(escapeHtml(unescape(newVal)));
                            $(el).attr('path', serverResponse.newPath);
                            $(el).find('p').show();
                        }
                        notify(serverResponse.message);
                        cb();
                    });
                };
                $(el).find('p').hide();
                if ($(el).find('textarea').length < 1) {
                    $(el).find('p').after('<br /><textarea>' + textVal + '</textarea>');
                } else {
                    $(el).find('textarea').show().val(textVal);
                }
                $(el).find('textarea').addClass('user_input').focus().select()
                .blur(function(e) {
                    submitChange(function (){
                        $(el).find('textarea').hide();
                        $(el).find('p').show();
                        $(el).find('textarea').unbind('blur keypress');
                    });
                })
                .click(function(event) {
                    event.stopPropagation();
                })
                .keypress(function (e) {
                    e.stopPropagation();
                    if (e.which === 13) {
                        submitChange(function () {
                            $(el).find('textarea').unbind('keypress blur');
                        });
                    }
                });
                break;
            case 'delete':
                var warning = null;
                if ($(el).hasClass('folder')) {
                    warning = 'This folder and all of its contents will be permanently ' +
                    'deleted from the server.' + ' Are you sure?';
                } else {
                    warning = 'This item will be permanently deleted from the server.  Are you sure?';
                }

                var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Item?">' + warning + '</div>')
                .dialog({
                    autoOpen: true,
                    resizable: false,
                    modal: true,
                    buttons: {
                        'Yes': function() {
                            $.post('lib/php/data/cases_documents_process.php', ({'action': 'delete',
                            'item_id': itemId,
                            'doc_type': docType,
                            'path': path,
                            'case_id': caseId
                        }), function(data) {
                                var serverResponse = $.parseJSON(data);
                                notify(serverResponse.message);
                                $(el).remove();
                                dialogWin.dialog('destroy');
                            });
                        },
                        'No': function() {
                            $(this).dialog('destroy');
                        }
                    }
                });
                break;

            case 'properties':
                $(el).css({'border': '1px solid #AAA'})
                    .next('.doc_properties')
                    .addClass('ui-corner-all')
                    .css({'top': '20%','left': '30%'})
                    .show().focus().focusout(function() {
                        $(this).hide();
                        $(el).css({'border': '0px'});
                    });
                break;
        }
    });

    //Expand div to include full file name on mouse enter
    $('div.doc_item').live('mouseenter', function(event) {
        $(this).closest('div').css({'height': 'auto','overflow': 'auto'});
    });

    //Reset on leave
    $('div.doc_item').live('mouseleave', function(event) {
        $(this).closest('div').css({'height': '120px','overflow': 'hidden'});
    });

});

//User clicks a folder or document
$('.doc_item').live('click', function(event) {
    event.preventDefault();
    var path = $(this).attr('path');
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    var pathDisplay = $(this).closest('.case_detail_panel_casenotes')
        .siblings('.case_documents_submenu')
        .find('.path_display');
    var el = $(this);
    var itemId = el.attr('data-id');
    var docType = 'document';
    openItem(el, itemId, docType, caseId, path, pathDisplay);
});

//User clicks new document button
$('button.doc_new_doc').live('click', function() {
    var target = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    createTextEditor(target, 'new', 'yes','New Document',null,null,'1','yes');
});


//User clicks new folder button
$('button.doc_new_folder').live('click', function() {
    var target = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');

    //if this is an empty folder, remove the "No Documents Found" message
    if ($('span.docs_empty')) {
        $('span.docs_empty').remove();
    }

if($.cookie('cc_doc_view') === 'list'){
    target.find('tbody').prepend('<tr class="doc_item folder" path="" data-id="">' + 
    '<td width="10%"><img src="html/ico/folder.png"></td>' +
    '<td><p><textarea rows="1" id="new_folder_name">New Folder</textarea></p></td><td></td><td></td></tr>');
} else {
    target.prepend('<div class="doc_item folder" path="" data-id=""><img src="html/ico/folder.png">' +
    '<p><textarea id="new_folder_name">New Folder</textarea></p></div>');
}

$('#new_folder_name').select();
$('#new_folder_name').addClass('user_input')
.mouseenter(function() {
    $(this).val('').focus().css({'background-color': 'white'});
})
.bind('blur keyup', function(e) {
    if (e.type === 'blur' || e.which === 13) {
        e.preventDefault();
        var container = $(this).closest('.case_detail_panel_casenotes')
            .siblings('.case_documents_submenu')
            .find('a.active')
            .attr('path');
        var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
        var newName = $('#new_folder_name').val();

        if (newName.indexOf('/') !== -1) {
            notify('Sorry, folder names cannot contain a foward slash.',true);
            return false;
        }
        else if (newName ===  '\n'){ //user has only pressed return (inserting
            // a new line character, but no file name)
            notify('Please provde a name for your folder.',true);
            return false;
        } else {
            var newFolder = null;

            if (container === ''  || typeof container === 'undefined') {
                newFolder = escape($.trim(newName.replace(/[\n\r]$/,'')));
                //replace method removes any new line characters that
                //may have been added by the user pressing enter
            } else {
                newFolder = container + '/' + escape($.trim(newName.replace(/[\n\r]$/,'')));
            }
            $.post('lib/php/data/cases_documents_process.php', {
                'case_id': caseId,
                'container': container,
                'new_folder': newFolder,
                'action': 'newfolder'
            }, function(data) {
                var serverResponse = $.parseJSON(data);
                if(serverResponse.error === true) {
                    notify(serverResponse.message,true);
                } else {
                    if($.cookie('cc_doc_view') === 'list'){
                        $('#new_folder_name').closest('tr').find('img').wrap('<a href="#" />');
                        $('#new_folder_name').closest('tr')
                            .attr({'path': newFolder,'data-id': serverResponse.id})
                            .droppable();
                    } else {
                        $('#new_folder_name').parent().siblings('img').wrap('<a href="#" />');
                        $('#new_folder_name')
                            .closest('.folder')
                            .attr({'path': newFolder,'data-id': serverResponse.id})
                            .droppable();
                    }
                    $('#new_folder_name').closest('p').html(escapeHtml(newName));
                    createDragDrop();
                    notify(serverResponse.message);
                }
            });
            }
        }
    });

});

//User clicks on the upload button
$('button.doc_upload').live('click', function() {
    var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

    //if this is an empty folder, remove the "No Documents Found" message
    if ($('span.docs_empty')) {
        $('span.docs_empty').remove();
    }

    //Tells user which directory files will be uploaded to
    var activeDirectory = $(this).parent().siblings().find('a.active').text();
    if (activeDirectory === '') {
        activeDirectory = 'Home';
    }

    //Tells the server which directory to put file in
    var currentPath = $(this).closest('.case_detail_panel').data('CurrentPath');

    //Db leaves folder field blank for documents in root directory, so send empty value
    if (currentPath === 'Home') {
        currentPath = '';
    }

    $(document).find('.upload_dialog').dialog({
        height: 500,
        width: 500,
        modal: true,
        title: 'Upload into ' + escapeHtml(activeDirectory) + ' folder:',
        open: function() {
            $(this).find('div.upload_dialog_url').show();
            $(this).find('div.upload_dialog_file').show();
            $(this).find('div.upload_url_form').hide();
        },
        close: function() {
            $(this).dialog('destroy');
        }
    });

    var uploader = new qq.FileUploader({
        // pass the dom node (ex. $(selector)[0] for jQuery users)
        element: $('.upload_dialog_file')[0],
        // path to server-side upload script
        action: 'lib/php/utilities/file_upload.php',
        params: {path: currentPath,case_id: caseId},
        onComplete: function() {
            thisPanel.load('lib/php/data/cases_documents_load.php', {
                'id': caseId,
                'update': 'yes',
                'path': currentPath,
                'container': currentPath
            }, function() {
                createDragDrop();
            });
        }
    });

    $('div.qq-upload-button').addClass('ui-corner-all').click(function() {
        $(this).closest('.upload_dialog_file').siblings('div.upload_dialog_url').hide();
    });

    $('.upload_url_button').mouseenter(function() {
        $(this).addClass('qq-upload-button-hover');
    }).mouseleave(function() {
        $(this).removeClass('qq-upload-button-hover');
    }).click(function() {
        $(this).siblings('.upload_url_form').show();
        $(this).parents('.upload_dialog_url').siblings('.upload_dialog_file').hide();
    });

    $('button.upload_url_submit').unbind('click').click(function() {
        var url = $(this).siblings('input.url_upload').val();
        var urlName = $(this).siblings('input.url_upload_name').val();
        if (isUrl(url) === false) {
            $(document)
                .find('p.upload_url_form_error_url')
                .html('Sorry, your URL is invalid.  It must begin with http://, https:// or ftp://');
            $(this).siblings('input.url_upload').focus(function(){
                $(document).find('p.upload_url_form_error_url').html('');
            });
            return false;
        } else if (urlName === '') {
            $(document).find('p.upload_url_form_error_name').html('Please give this URL a title.');
            $(this).siblings('input.url_upload_name').focus(function(){
                $(document).find('p.upload_url_form_error_name').html('');
            });
            return false;
        }
        else {
            $.post('lib/php/data/cases_documents_process.php', {
                'url_name': urlName,
                'url': url,
                'case_id': caseId,
                'path': currentPath,
                'action': 'add_url'
            }, function(data) {
                var serverResponse = $.parseJSON(data);
                $('.upload_dialog').find('p.upload_url_notify')
                    .show()
                    .html(serverResponse.message)
                    .fadeOut('slow', function() {
                        $(this).html('');
                    });
                $('.upload_dialog').find('input').val('');
                thisPanel.load('lib/php/data/cases_documents_load.php', {
                    'id': caseId,
                    'update': 'yes',
                    'path': currentPath,
                    'container': currentPath
                }, function() {
                    //unescapeNames();
                });
            });
        }
    });
});


//User clicks the Home link in the directory path
$('a.doc_trail_home').live('click', function(event) {
    event.preventDefault();
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    var thisPanel = $(this).closest('.case_documents_submenu').siblings('.case_detail_panel_casenotes');
    //Set the current path so that other functions can access it
    $(this).closest('.case_detail_panel').data('CurrentPath', 'Home');

    thisPanel.load('lib/php/data/cases_documents_load.php', {
        'id': caseId,
        'update': 'yes'
    }, function() {
        $(this).siblings('.case_documents_submenu').find('.path_display').html('');
        createDragDrop();
        clearSearchBox($(this));
    });
});

//User clicks one of the other links in the directory path
$('a.doc_trail_item').live('click', function(event) {
    event.preventDefault();
    var container = $(this).html();
    var path = $(this).attr('path');
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

    //Set the current path so that other functions can access it
    $(this).closest('.case_detail_panel').data('CurrentPath', path);
    var thisPanel = $(this).closest('.case_documents_submenu').siblings('.case_detail_panel_casenotes');
    var pathDisplay = $(this).parent();

    thisPanel.load('lib/php/data/cases_documents_load.php', {
        'id': caseId,
        'update': 'yes',
        'path': path,
        'container': path
    }, function() {
        $(this).siblings('.case_detail_panel_tools').find('.path_display').html('');
        var pathString = createTrail(path);
        pathDisplay.html(pathString);
        pathDisplay.find('a[path="' + path + '"]').addClass('active');

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
                $(this).removeClass('csenote_shadow');
            } else {
                $(this).addClass('csenote_shadow');
            }
        });
        createDragDrop();
    });
});

//Owner can lock the document for editing by others
$('select[name="ccd_permission"]').live('change', function () {
    var lockStatus = $(this).val();
    var ccdId = $(this).closest('.text_editor_bar').attr('data-id');
    $.post('lib/php/data/cases_documents_process.php', {'action': 'change_ccd_permissions',
    'ccd_id': ccdId, 'ccd_lock': lockStatus}, function (data){
        var serverResponse = $.parseJSON(data);
        notify(serverResponse.message);
    });
});


//handle search
$('input.documents_search').live('focusin', function() {
    $(this).val('');
    $(this).css({'color': 'black'});
    $(this).next('.documents_search_clear').show();
});

$('input.documents_search').live('keyup', function() {
    if ($(this).val() !== ''){
        var resultTarget = $(this).closest('div.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
        var search = $(this).val();
        var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
        $(this).closest('.case_detail_panel_tools').siblings('.case_documents_submenu').hide();
        resultTarget.load('lib/php/data/cases_documents_load.php', {
            'id': caseId,
            'search': search,
            'update': 'yes'
        }, function() {
            resultTarget.scrollTop(0);
            if (search.length) {
                resultTarget.highlight(search);
                $('thead').removeHighlight();
            }
        });
    } else {
        $('.documents_search_clear').trigger('click');
    }
});

$('.documents_search_clear').live('click', function() {
    $(this).prev().val('Search Titles');
    $(this).prev().css({'color': '#AAA'});
    $(this).prev().blur();
    $(this).closest('.case_detail_panel').find('.doc_trail_home').trigger('click');
    $(this).hide();
    $(this).closest('.case_detail_panel_tools').siblings('.case_documents_submenu').show();
});

//User changes view for documents
$('.radio_toggle_grid').live('click', function(){
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    var currentPath = $(this).closest('.case_detail_panel').data('CurrentPath');
    var sendPath ;
    if (currentPath === 'Home'){
        sendPath = '';
    } else {
        sendPath = currentPath;
    }
    var clickedButton = $(this);
    //Set the current path so that other functions can access it
    //$(this).closest('.case_detail_panel').data('CurrentPath', 'Home');

    $.cookie('cc_doc_view','grid');
    thisPanel.load('lib/php/data/cases_documents_load.php', {
        'id': caseId,
        'update': 'yes',
        'path': sendPath,
        'container': sendPath
    }, function() {
        createDragDrop();
        clearSearchBox($(this));

        //Set correct shading of buttons
        clickedButton.next().toggleClass('buttonset-inactive');
        clickedButton.siblings('input').next().toggleClass('buttonset-inactive');
    });
});

$('.radio_toggle_list').live('click', function(){
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    var currentPath = $(this).closest('.case_detail_panel').data('CurrentPath');
    var sendPath ;
    if (currentPath === 'Home'){
        sendPath = '';
    } else {
        sendPath = currentPath;
    }
    var clickedButton = $(this);

    $.cookie('cc_doc_view','list');
    thisPanel.load('lib/php/data/cases_documents_load.php', {
        'id': caseId,
        'list_view': 'yes',
        'update': 'yes',
        'path': sendPath,
        'container': sendPath
    }, function() {
        createDragDrop();
        clearSearchBox($(this));

        //Set correct shading of buttons
        clickedButton.next().toggleClass('buttonset-inactive');
        clickedButton.prev().toggleClass('buttonset-inactive');
    });
});

