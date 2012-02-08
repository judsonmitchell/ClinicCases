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

//User clicks to open document window
$('.case_detail_nav #item3').live('click', function() {

    var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');

    //Get heights
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var documentsWindowHeight = thisPanelHeight - toolsHeight;

    //Set the current path so that other functions can access it
    thisPanel.data('CurrentPath','Home');

    thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId}, function() {

        //Set css
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': caseNotesWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '40%'});
        $('div.case_detail_panel_tools_right').css({'width': '60%'});


        //Set buttons
        $('button.doc_new_doc').button({icons: {primary: "fff-icon-page-add"},text: true}).next().button({icons: {primary: "fff-icon-folder-add"},text: true}).next().button({icons: {primary: "fff-icon-page-white-get"},text: true});

        //Unescape folder names
        $('.folder p, .doc_properties h3').each(function() {
            var t = unescape($(this).html());
            $(this).html(t);
        });

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

    //Create context menu

    $("div.doc_item").contextMenu({menu: 'docMenu'}, function(action, el, pos) {
        //TODO fix the problem where the context menu tries to open outside the viewport

        var itemId = $(el).attr('data-id');
        var docType = null;
        var caseId = $(el).closest('.case_detail_panel').data('CaseNumber');
        if ($(el).hasClass('folder'))
        {
            docType = 'folder';
            var path = $(el).attr('path');
        }
        else
        {
            docType = 'document';
        }

        switch (action)
        {
            case 'open':
                var thisUrl = $(el).find('a').attr('href');
                window.open(thisUrl, '_new');
                break;

            case 'cut':
                $(el).css({'opacity': '.5'});
                break;

            case 'copy':
                $(el).css({'border': '1px solid #AAA'});
                break;

             case 'rename':
                $(el).css({'border': '1px solid #AAA'});
                var textVal = $(el).find('p').html();
                $(el).find('p').hide();
                if ($(el).find('textarea').length < 1)
					{$(el).find('a').after('<textarea>' + textVal + '</textarea>');}
					else
					{$(el).find('textarea').show().val(textVal);}
                $(el).find('textarea').addClass('user_input')
					.mouseenter(function(){
						$(this).focus().removeClass('user_input');
						$(el).css({'border':'0px'});
					})
					.mouseleave(function(){
						$(el).find('textarea').hide();
						$(el).find('p').show();

					})
					.keypress(function(e) {
						if (e.which == 13) {
							event.preventDefault();
							var newVal = $(el).find('textarea').val();
							$.post('lib/php/data/cases_documents_process.php',({'action':'rename','new_name':newVal,'item_id':itemId,'doc_type':docType,'path':path,'case_id':caseId}),function(data){
                                    var serverResponse = $.parseJSON(data);

                                    $(el).find('textarea').hide();
                                    $(el).find('p').html(newVal);
                                    $(el).attr('path',serverResponse.newPath);
                                    $(el).find('p').show();
									notify(serverResponse.message);
                                });

							}
					});
                break;

            case 'delete':

                var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Document?">This document will be permanently deleted from the server.  Are you sure?</div>').dialog({
                    autoOpen: true,
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            //insert delete code here
                            $(this).dialog("destroy");
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

    // alert(
    //     'Action: ' + action + '\n\n' +
    //     'Element ID: ' + $(el).attr('id') + '\n\n' +
    //     'X: ' + pos.x + '  Y: ' + pos.y + ' (relative to element)\n\n' +
    //     'X: ' + pos.docX + '  Y: ' + pos.docY+ ' (relative to document)'
    //     );
    });

    //Expand div to include full file name on mouse enter
    $('div.doc_item > a').live('mouseenter', function(event) {
        $(this).closest('div').css({'height': 'auto','overflow': 'auto'});

        if ($(this).closest('div').hasClass('doc'))
        {
            $(this).closest('div').draggable({revert: 'invalid'});
        }
        else
        {
            $(this).closest('div').droppable().draggable({revert: 'invalid'});
        }

    });

    //Reset on leave
    $('div.doc_item > a').live('mouseleave', function(event) {
        $(this).closest('div').css({'height': '120px','overflow': 'hidden'});
    });


});

//User clicks a folder or document
$('div.doc_item > a').live('click', function(event) {
    event.preventDefault();

	if ($(this).closest('div').hasClass('folder'))
    {
        var path = $(this).closest('div').attr('path');
        var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
        var pathDisplay = $(this).closest('.case_detail_panel_casenotes').siblings('.case_detail_panel_tools').find('.path_display');

        $(this).closest('.case_detail_panel_casenotes').load('lib/php/data/cases_documents_load.php', {'id': caseId,'container': path,'path': path,'update': 'y'}, function() {
            var pathString = createTrail(path);
            pathDisplay.html(pathString);
            pathDisplay.find("a[path='" + path + "']").addClass('active');
            //Unescape folder names
            $('.folder p, .doc_properties h3').each(function() {
                var t = unescape($(this).html());
                $(this).html(t);
            });

            //Set the current path so that other functions can access it
            $(this).closest('.case_detail_panel').data('CurrentPath',path);

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

    else

    { // TODO insert document actions

    }

});

//User clicks new document button
$('button.doc_new_doc').live('click', function(){
    var target = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    var editor = '<div class="text_editor_bar" data-id=""><div class="text_editor_title" tabindex="0">New Document</div></div><textarea class="text_editor"></textarea>';
    target.html(editor);
    var arr = target.find('.text_editor').rte({
        css: ['lib/javascripts/lwrte/default2.css'],
        width: 900,
        height: 400,
        controls_rte: rte_toolbar
    });

    //Define variables
    var ccdTitleArea = target.find('.text_editor_title');
    var ccdTitle = target.find('.text_editor_title').html();
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    var currentPath = $(this).closest('.case_detail_panel').data('CurrentPath');
    var docIdArea = target.find('.text_editor_bar');

    //Db leaves folder field blank for documents in root directory, so send empty value
    if (currentPath === 'Home')
        {currentPath = '';}

    //Create new ccd (ClinicCases Document) in db
    $.post('lib/php/data/cases_documents_process.php',{'action':'new_ccd','ccd_name':escape(ccdTitle),'local_file_name':'New Document.ccd','path':currentPath,'case_id':caseId},function(data){
        var serverResponse = $.parseJSON(data);
        docIdArea.attr('data-id',serverResponse.ccd_id);
        ccdTitleArea.html(unescape(serverResponse.ccd_title));

    });

    //Change document title
    ccdTitleArea.mouseenter(function(){$(this).css({'color':'red'});
        })
        .click(function(){
        $(this).css({'color':'red'});
        $(this).html('<input type="text" value="">');
        $(this).find('input').val(unescape(ccdTitle)).focus();
        })
        .keypress(function(e) {
            if (e.which == 13) {
            e.preventDefault();
            ccdTitle = escape($(this).find('input').val());
            $(this).text(unescape(ccdTitle));
            $(this).css({'color':'black'});
            var getText = arr[0].get_content();
            $.post('lib/php/data/cases_documents_process.php',{'action':'update_ccd','ccd_name':ccdTitleArea.html(),'ccd_id':docIdArea.attr('data-id'),'ccd_text':getText},function(data){
                var serverResponse = $.parseJSON(data);
                notify(serverResponse.message);
                });
            }
        })
        .mouseleave(function(){$(this).css({'color':'black'});
        });
       

    //auto-save
    var lastText = "";
    function autoSave(lastText,arr)
    {
        var text = arr[0].get_content();
        if (text != lastText)
        {
            $.post('lib/php/data/cases_documents_process.php',{'action':'update_ccd','ccd_name':ccdTitleArea.html(),'ccd_id':docIdArea.attr('data-id'),'ccd_text':text},function(data){
                var serverResponse = $.parseJSON(data);
                ccdTitleArea.html(serverResponse.ccd_title);
            });
            lastText = text;
        }
        
        var t = setTimeout(function(){
            autoSave(lastText,arr);},3000);
    }

    autoSave(lastText,arr);

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
            event.preventDefault();
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
                notify(serverResponse.message);
            });
        }
    });

});

//User clicks on the upload button
$('button.doc_upload').live('click', function(){

    var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');

    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

    //Tells user which directory files will be uploaded to
    var activeDirectory = $(this).parent().siblings().find('a.active').text();
    if (activeDirectory === '')
        {activeDirectory = 'Home';}

    //Tells the server which directory to put file in
    var currentPath = $(this).closest('.case_detail_panel').data('CurrentPath');
        
    //Db leaves folder field blank for documents in root directory, so send empty value
    if (currentPath === 'Home')
        {currentPath = '';}

        thisPanel.find('.upload_dialog').dialog({
            height:500,
            width:500,
            modal:true,
            title:"Upload into " + activeDirectory + " folder:"
        });

    var uploader = new qq.FileUploader({
        // pass the dom node (ex. $(selector)[0] for jQuery users)
        element: $('.upload_dialog_file')[0],
        // path to server-side upload script
        action: 'lib/php/utilities/file_upload.php',
        params: {path:currentPath,case_id:caseId},
        onComplete: function(){
            thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': currentPath}, function() {
                    //notify('Upload Complete');
                });
        }
    });

    $('div.qq-upload-button').addClass('ui-corner-all').click(function(){
            $(this).closest('.upload_dialog_file').siblings('div.upload_dialog_url ').hide();
            //$(this).hide();
        });

    $('.upload_url_button').mouseenter(function(){$(this).addClass('qq-upload-button-hover');}).mouseleave(function(){$(this).removeClass('qq-upload-button-hover');}).click(function(){
            $(this).next().show();
            $(this).parents('.upload_dialog_url').siblings('.upload_dialog_file').hide();
            //$(this).hide();
        });

    $('button.upload_url_submit').click(function(){
        var url = $(this).siblings('input.url_upload').val();
        var urlName = $(this).siblings('input.url_upload_name').val();
        $.post('lib/php/data/cases_documents_process.php', {'url_name':urlName,'url':url,'case_id': caseId,'path': currentPath,'action': 'add_url'},function(){
            thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': currentPath}, function() {

                });
        });
    });
});


//User clicks the Home link in the directory path
$('a.doc_trail_home').live('click', function(event) {
    event.preventDefault();
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    //Set the current path so that other functions can access it
    $(this).closest('.case_detail_panel').data('CurrentPath','Home');

    thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes'}, function() {
        $(this).siblings('.case_detail_panel_tools').find('.path_display').html('');
        //Unescape folder names
        $('.folder p, .doc_properties h3').each(function() {
            var t = unescape($(this).html());
            $(this).html(t);
        });
    });
});

//User clicks one of the other links in the directory path
$('a.doc_trail_item').live('click', function(event) {
    event.preventDefault();
    var container = $(this).html();
    var path = $(this).attr('path');
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    //Set the current path so that other functions can access it
    $(this).closest('.case_detail_panel').data('CurrentPath',path);
    var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    var pathDisplay = $(this).parent();

    thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId,'update': 'yes','path': path,'container': path}, function() {
        $(this).siblings('.case_detail_panel_tools').find('.path_display').html('');
        var pathString = createTrail(path);
        pathDisplay.html(pathString);
        pathDisplay.find("a[path='" + path + "']").addClass('active');
        //Unescape folder names
        $('.folder p, .doc_properties h3').each(function() {
            var t = unescape($(this).html());
            $(this).html(t);
        });

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
});
