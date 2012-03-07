 //
//Scripts for documents panel on cases tab
//

function createTrail(path)
{

    var pathArray = path.split('/');
    var pathString = '';
    var pathS = '';
    $.each(pathArray, function(i, v) {
        pathS += '/' + v;
        pathSa = pathS.substr(1);
        pathItem = '<a class="doc_trail_item" href="#" path="' + pathSa + '">' + unescape(v) + '</a>/';
        pathString += pathItem;
    });

    return pathString;
}

function createDragDrop()
{
    //Destroy any previously created draggables and droppables
    $('div.item, div.folder').draggable("destroy");
    $('div.folder').droppable("destroy");

    $('div.item').draggable({revert: 'invalid',containment: 'div.case_detail_panel_casenotes'});
    $('div.folder').droppable({activeClass: "ui-state-highlight",drop: function(event, ui) {
            var docType = null;
            if (ui.draggable.hasClass('folder'))
            {
                docType = 'folder';
            }
            else
            {
                docType = 'item';
            }

            var caseId = ui.draggable.closest('.case_detail_panel').data('CaseNumber');

            $.post('lib/php/data/cases_documents_process.php', {'action': 'cut','item_id': ui.draggable.attr('data-id'),'target_path': $(event.target).attr('path'),'selection_path': ui.draggable.attr('path'),'doc_type': docType,'case_id': caseId}, function(data) {
                var serverResponse = $.parseJSON(data);
                notify(serverResponse.message);
                ui.draggable.fadeOut();
            });
        }}).draggable({revert: 'invalid',containment: 'div.case_detail_panel_casenotes'});
}

function createTextEditor(target, action, permission, title, content, id)
{
    var editor = '<div class="text_editor_bar" data-id=""><div class="text_editor_title" tabindex="0">New Document</div><div class="text_editor_status"><span class= "status">Unchanged</span></div></div><textarea class="text_editor"></textarea>';

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
    if (currentPath === 'Home')
    {
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
    if (action === 'view')
    {
        arr[0].set_content(content);
        ccdTitleArea.html(title);
        docIdArea.attr('data-id', id);
    }

    //If the user doesn't have permission to edit, make read only
    if (permission === 'no')
    {
        $(arr[0].iframe_doc).keydown(function(event) {
            return false;
        });
        ccdStatusArea.html('<span class="readonly">Read Only</status>');
        target.find('.rte-toolbar a').not('.print').css({'opacity': '.3'});
        target.find('.rte-toolbar select').css({'opacity': '.3'});
    }

    //If this is a new document, create new ccd (ClinicCases Document) in db
    if (action == 'new')
    {
        $.post('lib/php/data/cases_documents_process.php', {'action': 'new_ccd','ccd_name': escape(ccdTitle),'local_file_name': 'New Document.ccd','path': currentPath,'case_id': caseId}, function(data) {
            var serverResponse = $.parseJSON(data);
            docIdArea.attr('data-id', serverResponse.ccd_id);
            ccdTitleArea.html(unescape(serverResponse.ccd_title));

        });
    }

    //hide main buttons, initialize new one
    tools.find('button').hide();
    tools.find('.case_detail_panel_tools_right').append('<button class="closer">Close</button>');
    tools.find('button.closer').button({icons: {primary: "fff-icon-cross"},text: true});
    tools.find('button.closer').click(function() {

        if (currentPath === '')  //the document is not in a subfolder
        {
            target.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': currentPath}, function() {
                tools.find('button').show();
                tools.find('button.closer').remove();
                unescapeNames();
                createDragDrop();
            });
        }
        else  //document is in a subfolder
        {
            target.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': currentPath,'container': currentPath}, function() {
                tools.find('button').show();
                tools.find('button.closer').remove();
                unescapeNames();
                createDragDrop();
            });
        }
    });

    //If user has permission to edit, set the editing functions

    if (permission === 'yes')
    {
        //Change document title
        ccdTitleArea.mouseenter(function() {
            $(this).css({'color': 'red'});
        })
        .click(function() {
            $(this).css({'color': 'red'});
            $(this).html('<input type="text" value="">');
            $(this).find('input').val(unescape(ccdTitle)).focus();
        })
        .keydown(function(e) {
            if (e.which == 13 || e.which == 9) {
                e.preventDefault();
                ccdTitle = escape($(this).find('input').val());
                $(this).text(unescape(ccdTitle));
                $(this).css({'color': 'black'});
                var getText = arr[0].get_content();
                $.post('lib/php/data/cases_documents_process.php', {'action': 'update_ccd','ccd_name': ccdTitleArea.html(),'ccd_id': docIdArea.attr('data-id'),'ccd_text': getText}, function(data) {
                    var serverResponse = $.parseJSON(data);
                    notify(serverResponse.message);
                });
            }
        })
        .mouseleave(function() {
            $(this).css({'color': 'black'});
        });

        //auto-save
        var lastText = "";
        function autoSave(lastText, arr)
        {
            var text = arr[0].get_content();
            var status = 'Saving...';
            if (text != lastText)
            {
                ccdStatusArea.find('span.status').html(status);
                $.post('lib/php/data/cases_documents_process.php', {'action': 'update_ccd','ccd_name': ccdTitleArea.html(),'ccd_id': docIdArea.attr('data-id'),'ccd_text': text}, function(data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error)
                    {
                        ccdStatusArea.find('span.status').html(serverResponse.message);
                    }
                    else
                    {
                        ccdTitleArea.html(serverResponse.ccd_title);
                        ccdStatusArea.find('span.status').html(serverResponse.message);
                    }
                });
                lastText = text;
            }

            var t = setTimeout(function() {
                autoSave(lastText, arr);
            }, 3000);
        }

        autoSave(lastText, arr);
    }
}

function openItem(el, itemId, docType, caseId, path, pathDisplay)
{

    if ($(el).hasClass('folder'))
    {
        $(el).closest('.case_detail_panel_casenotes').load('lib/php/data/cases_documents_load.php', {'id': caseId,'container': path,'path': path,'update': 'y'}, function() {
            var pathString = createTrail(path);
            pathDisplay.html(pathString);
            pathDisplay.find("a[path='" + path + "']").addClass('active');
            createDragDrop();
            unescapeNames();

            //Set the current path so that other functions can access it
            $(this).closest('.case_detail_panel').data('CurrentPath', path);

            //Apply shadow on scroll
            $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
                var scrollAmount = $(this).scrollTop();
                if (scrollAmount === 0 && $(this).hasClass('csenote_shadow'))
                {
                    $(this).removeClass('csenote_shadow');
                }
                else
                {
                    $(this).addClass('csenote_shadow');
                }
            });
        });
    }
    else if ($(el).hasClass('url'))
    {
        $.post('lib/php/data/cases_documents_process.php', {'action': 'open','item_id': itemId,'doc_type': 'document'}, function(data) {
            var serverResponse = $.parseJSON(data);
            window.open(serverResponse.target_url, '_blank');
        });
    }
    else if ($(el).hasClass('ccd'))
    {
        $.post('lib/php/data/cases_documents_process.php', {'action': 'open','item_id': itemId,'doc_type': 'document'}, function(data) {
            var serverResponse = $.parseJSON(data);
            var target = $(el).closest('.case_detail_panel_casenotes');
            createTextEditor(target, 'view', serverResponse.ccd_permissions, serverResponse.ccd_title, serverResponse.ccd_content, serverResponse.ccd_id);
        });
    }
    else
    {
        $.download('lib/php/data/cases_documents_process.php', {'item_id': itemId,'action': 'open','doc_type': docType});
    }

}

function unescapeNames()
{
    $('.doc_item p, .doc_properties h3').each(function() {
        var t = unescape($(this).html());
        $(this).html(t);
    });
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
        $('div.case_detail_panel_casenotes').css({'height': caseNotesWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '40%'});
        $('div.case_detail_panel_tools_right').css({'width': '60%'});


        //Set buttons
        $('button.doc_new_doc').button({icons: {primary: "fff-icon-page-add"},text: true}).next().button({icons: {primary: "fff-icon-folder-add"},text: true}).next().button({icons: {primary: "fff-icon-page-white-get"},text: true});

        //Unescape folder names
        unescapeNames();

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow'))
            {
                $(this).removeClass('csenote_shadow');
            }
            else
            {
                $(this).addClass('csenote_shadow');
            }
        });

        createDragDrop();

    });

    //Create context menu

    $("div.doc_item").contextMenu({menu: 'docMenu'}, function(action, el, pos) {

        var itemId = $(el).attr('data-id');
        var docType = null;
        var caseId = $(el).closest('.case_detail_panel').data('CaseNumber');
        var docName = $(el).find('p').html();
        var pathDisplay = $(el).closest('.case_detail_panel_casenotes').siblings('.case_detail_panel_tools').find('.path_display');

        if ($(el).hasClass('folder'))
        {
            docType = 'folder';
            path = $(el).attr('path');
        }
        else
        {
            docType = 'document';
            path = '';
        }

        switch (action)
        {
            case 'open':
                openItem(el, itemId, docType, caseId, path, pathDisplay);
                break;

            case 'cut':
                $(el).css({'opacity': '.5'});

                //Stash the data about the cut file or folder
                var cutData = new Array(itemId, docType, path, caseId);
                $(el).closest('.case_detail_panel_casenotes').data('cutValue', cutData);

                //Create a new context menu which allows for copying and pasting into a div with no items;
                $('div.case_detail_panel_casenotes').contextMenu({menu: 'docMenu_copy_paste'}, function(action, el, pos) {
                    if (action === 'paste')
                    {
                        var caseId = el.data('cutValue')[3];
                        var docType = el.data('cutValue')[1];
                        var targetPath = el.closest('.case_detail_panel').data('CurrentPath');
                        if (targetPath === 'Home')
                        {
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
                            'case_id': caseId},
                        function(data) {
                            var serverResponse = $.parseJSON(data);
                            notify(serverResponse.message);
                            el.closest('.case_detail_panel_casenotes').load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': targetPath,'container': targetPath}, function() {
                                unescapeNames();
                                el.destroyContextMenu();
                            });
                        });
                    }
                });

                break;

            case 'copy':

                if (docType === 'folder')  //TODO add copying of folders
                {
                    notify('Sorry, copying of folders is not yet supported.', true);
                }
                else
                {
                    $(el).css({'border': '1px solid #AAA'});
                    //Stash the data about the copy file or folder
                    var copyData = new Array(itemId, docType, path, caseId);
                    $(el).closest('.case_detail_panel_casenotes').data('copyValue', copyData);

                    //Create a new context menu which allows for pasting into a div with no items;
                    $('div.case_detail_panel_casenotes').contextMenu({menu: 'docMenu_copy_paste'}, function(action, el, pos) {
                        if (action === 'paste')
                        {
                            //console.log(el.data());
                            //console.log(el.closest('.case_detail_panel').data('CurrentPath'));
                            var caseId = el.data('copyValue')[3];
                            var docType = el.data('copyValue')[1];
                            var selectionPath = el.data('copyValue')[2];
                            var targetPath = el.closest('.case_detail_panel').data('CurrentPath');
                            if (targetPath === 'Home')
                            {
                                targetPath = '';
                            }
                            var itemId = el.data('copyValue')[0];
                            $.post('lib/php/data/cases_documents_process.php', {
                                'action': 'copy',
                                'item_id': itemId,
                                'target_path': targetPath,
                                'doc_type': docType,
                                'case_id': caseId},
                            function(data) {
                                var serverResponse = $.parseJSON(data);
                                notify(serverResponse.message);
                                el.closest('.case_detail_panel_casenotes').load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': targetPath,'container': targetPath}, function() {
                                    unescapeNames();
                                    el.destroyContextMenu();
                                });
                            });
                        }
                    });
                }
                break;

            case 'rename':
                $(el).css({'border': '1px solid #AAA'});
                var textVal = $(el).find('p').html();
                $(el).find('p').hide();
                if ($(el).find('textarea').length < 1)
                {
                    $(el).find('p').after('<br /><textarea>' + textVal + '</textarea>');
                }
                else
                {
                    $(el).find('textarea').show().val(textVal);
                }
                $(el).find('textarea').addClass('user_input')
                .mouseenter(function() {
                    $(this).focus().removeClass('user_input');
                    $(el).css({'border': '0px'});
                })
                .mouseleave(function() {
                    $(el).find('textarea').hide();
                    $(el).find('p').show();
                })
                .click(function(event) {
                    event.stopPropagation();
                })
                .keypress(function(e) {
                    if (e.which == 13) {
                        event.preventDefault();
                        var newVal = $(el).find('textarea').val();
                        $.post('lib/php/data/cases_documents_process.php', ({'action': 'rename','new_name': newVal,'item_id': itemId,'doc_type': docType,'path': path,'case_id': caseId}), function(data) {
                            var serverResponse = $.parseJSON(data);

                            $(el).find('textarea').hide();
                            $(el).find('p').html(newVal);
                            $(el).attr('path', serverResponse.newPath);
                            $(el).find('p').show();
                            notify(serverResponse.message);
                        });

                    }
                });
                break;

            case 'delete':

                var warning = null;

                if ($(el).hasClass('folder'))
                {
                    warning = "This folder and all of its contents will be permanently deleted from the server.  Are you sure?";
                }
                else
                {
                    warning = "This item will be permanently deleted from the server.  Are you sure?";
                }

                var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Item?">' + warning + '</div>').dialog({
                    autoOpen: true,
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $.post('lib/php/data/cases_documents_process.php', ({'action': 'delete','item_id': itemId,'doc_type': docType,'path': path,'case_id': caseId}), function(data) {
                                var serverResponse = $.parseJSON(data);
                                notify(serverResponse.message);
                                $(el).remove();
                                dialogWin.dialog("destroy");
                            });
                        },
                        "No": function() {
                            $(this).dialog("destroy");
                        }
                    }
                });
                break;

            case 'properties':
                $(el).css({'border': '1px solid #AAA'}).next('.doc_properties').addClass('ui-corner-all').css({'top': '20%','left': '30%'}).show().focus().focusout(function() {
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
$('div.doc_item').live('click', function(event) {
    var path = $(this).attr('path');
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    var pathDisplay = $(this).closest('.case_detail_panel_casenotes').siblings('.case_detail_panel_tools').find('.path_display');
    var el = $(this);
    var itemId = el.attr('data-id');
    var docType = 'document';
    openItem(el, itemId, docType, caseId, path, pathDisplay);
});

//User clicks new document button
$('button.doc_new_doc').live('click', function() {
    var target = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    createTextEditor(target, 'new', 'yes');
});


//User clicks new folder button
$('button.doc_new_folder').live('click', function() {
    var target = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    target.prepend("<div class='doc_item folder' path='' data-id=''><img src='html/ico/folder.png'><p><textarea id='new_folder_name'>New Folder</textarea></p></div>");
    $('#new_folder_name').addClass('user_input')
    .mouseenter(function() {
        $(this).val('').focus().css({'background-color': 'white'});
    })
    .keypress(function(e) {
        if (e.which == 13) {
            e.preventDefault();
            var container = $(this).closest('.case_detail_panel_casenotes').siblings('.case_detail_panel_tools').find('a.active').attr('path');
            var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
            var newName = $('#new_folder_name').val();
            var newFolder = null;
            if (container === '')
            {
                newFolder = escape(newName);
            }
            else
            {
                newFolder = container + "/" + escape(newName);
            }
            $.post('lib/php/data/cases_documents_process.php', {'case_id': caseId,'container': container,'new_folder': newFolder,'action': 'newfolder'}, function(data) {
                var serverResponse = $.parseJSON(data);
                $('#new_folder_name').parent().siblings('img').wrap('<a href="#" />');
                $('#new_folder_name').closest('.folder').attr({'path': newFolder,'data-id': serverResponse.id}).droppable();
                $('#new_folder_name').closest('p').html(newName);
                createDragDrop();
                notify(serverResponse.message);

            });
        }
    });

});

//User clicks on the upload button
$('button.doc_upload').live('click', function() {

    var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');

    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

    //Tells user which directory files will be uploaded to
    var activeDirectory = $(this).parent().siblings().find('a.active').text();
    if (activeDirectory === '')
    {
        activeDirectory = 'Home';
    }

    //Tells the server which directory to put file in
    var currentPath = $(this).closest('.case_detail_panel').data('CurrentPath');

    //Db leaves folder field blank for documents in root directory, so send empty value
    if (currentPath === 'Home')
    {
        currentPath = '';
    }

    $(document).find('.upload_dialog').dialog({
        height: 500,
        width: 500,
        modal: true,
        title: "Upload into " + activeDirectory + " folder:",
        open: function() {
            $(this).find('div.upload_dialog_url').show();
            $(this).find('div.upload_dialog_file').show();
            $(this).find('div.upload_url_form').hide();
        },
        close: function() {
            $(this).dialog("destroy");
        }
    });

    //TODO Difficult bug.  If user selects context menu (jquery.contextMenu.js), the file chooser will not open when clicking on the upload button.  The element still gets the click, but no dialog.

    var uploader = new qq.FileUploader({
        // pass the dom node (ex. $(selector)[0] for jQuery users)
        element: $('.upload_dialog_file')[0],
        // path to server-side upload script
        action: 'lib/php/utilities/file_upload.php',
        params: {path: currentPath,case_id: caseId},
        onComplete: function() {

            thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': currentPath,'container': currentPath}, function() {
                createDragDrop();
                unescapeNames();

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

    $('button.upload_url_submit').click(function() {
        var url = $(this).siblings('input.url_upload').val();
        var urlName = $(this).siblings('input.url_upload_name').val();
        if (isUrl(url) === false)
        {
            $(document).find('p.upload_url_form_error_url').html('Sorry, your URL is invalid.  It must begin with http://, https:// or ftp://');
            return false;
        }
        else if (urlName === '')
        {
            $(document).find('p.upload_url_form_error_name').html('Please give this URL a title.');
            return false;
        }
        else
        {
            $.post('lib/php/data/cases_documents_process.php', {'url_name': urlName,'url': url,'case_id': caseId,'path': currentPath,'action': 'add_url'}, function(data) {
                var serverResponse = $.parseJSON(data);
                $('.upload_dialog').find('p.upload_url_notify').show().html(serverResponse.message).fadeOut('slow', function() {
                    $(this).html('');
                });
                $('.upload_dialog').find('input').val('');
                thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': currentPath}, function() {
                    unescapeNames();
                });
            });
        }
    });
});


//User clicks the Home link in the directory path
$('a.doc_trail_home').live('click', function(event) {
    event.preventDefault();
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    //Set the current path so that other functions can access it
    $(this).closest('.case_detail_panel').data('CurrentPath', 'Home');

    thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes'}, function() {
        $(this).siblings('.case_detail_panel_tools').find('.path_display').html('');
        unescapeNames();
        createDragDrop();
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
    var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    var pathDisplay = $(this).parent();

    thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': path,'container': path}, function() {
        $(this).siblings('.case_detail_panel_tools').find('.path_display').html('');
        var pathString = createTrail(path);
        pathDisplay.html(pathString);
        pathDisplay.find("a[path='" + path + "']").addClass('active');
        unescapeNames();

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow'))
            {
                $(this).removeClass('csenote_shadow');
            }
            else
            {
                $(this).addClass('csenote_shadow');
            }
        });

        createDragDrop();

    });
});
