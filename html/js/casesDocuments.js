//
//Scripts for documents panel on cases tab
//

/* global escape, escapeHtml, unescape, notify, rte_toolbar, qq , isUrl */
import { live } from './live.js';
import {
  getDocuments,
  processDocuments,
  uploadFile,
} from '../../lib/javascripts/axios.js';
import {
  setFormValues,
  checkFormValidity,
  getFormValues,
  resetForm,
} from '../js/forms.js';
import { setCookie } from '../../lib/javascripts/cookies.js';

function createTrail(path) {
  let pathArray = path.split('/').map((p) => decodeURI(p));
  var pathString = '';
  pathArray.forEach((v, i) => {
    const pathName = decodeURI(v);
    const fullPath = pathArray.slice(0, i + 1).join('/');
    var pathItem = `> <a class="doc_trail_item" href="#" data-path="${fullPath}">${pathName}</a>`;
    pathString += pathItem;
  });

  return pathString;
}

// function createTextEditor(target, action, permission, title, content, id, owner, locked) {
//     var editor = '<div class="text_editor_bar" data-id="">' +
//     '<div class="text_editor_title" tabindex="0">' + title +
//     '</div><div class="text_editor_status"><span class= "status">Unchanged</span>' +
//     '</div></div><textarea class="text_editor"></textarea>';

//     //Add title area and textarea
//     target.html(editor);

//     //Define variables
//     var ccdTitleArea = target.find('.text_editor_title');
//     var ccdStatusArea = target.find('.text_editor_status');
//     var ccdTitle = target.find('.text_editor_title').html();
//     var caseId = target.closest('.case_detail_panel').data('CaseNumber');
//     var currentPath = target.closest('.case_detail_panel').data('CurrentPath');
//     var docIdArea = target.find('.text_editor_bar');
//     var tools = target.siblings('.case_detail_panel_tools');

//     //Define current path. Db leaves folder field blank for documents in root directory, so send empty value
//     if (currentPath === 'Home') {
//         currentPath = '';
//     }

//     //Create lwrte
//     var arr = target.find('.text_editor').rte({
//         css: ['lib/javascripts/lwrte/default2.css'],
//         width: 900,
//         height: 400,
//         controls_rte: rte_toolbar
//     });

//     //If this is not a new document, then set the editor content from the db
//     if (action === 'view') {
//         arr[0].set_content(content);
//         ccdTitleArea.html(escapeHtml(unescape(title)));
//         docIdArea.attr('data-id', id);
//     }

//     //If the user doesn't have permission to edit, make read only
//     if (permission === 'no' && owner !== '1') {
//         $(arr[0].iframe_doc).keydown(function(event) {
//             return false;
//         });
//         ccdStatusArea.html('<span class="readonly">Read Only</status>');
//         target.find('.rte-toolbar a').not('.print').css({'opacity': '.3'});
//         target.find('.rte-toolbar select').css({'opacity': '.3'});
//     }

//     if (owner === '1'){
//         var permSelect = '<select name="ccd_permission">';
//         if (locked === 'no'){
//             permSelect += '<option value="no" selected=selected>Unlocked</option>' +
//             '<option value="yes">Locked</option>';
//         } else {
//             permSelect += '<option value="no" >Unlocked</option><option value="yes"' +
//             'selected=selected>Locked</option>';
//         }
//         permSelect += '</select>';
//         ccdStatusArea.append(permSelect);
//     }

//     //If this is a new document, create new ccd (ClinicCases Document) in db
//     if (action === 'new') {
//         $.post('lib/php/data/cases_documents_process.php', {
//             'action': 'new_ccd',
//             'ccd_name': escape(ccdTitle),
//             'local_file_name': 'New Document.ccd',
//             'path': currentPath,
//             'case_id': caseId
//         }, function(data) {
//             var serverResponse = $.parseJSON(data);
//             docIdArea.attr('data-id', serverResponse.ccd_id);
//             ccdTitleArea.html(escapeHtml(unescape(serverResponse.ccd_title)));
//         });
//     }

//     //hide main buttons, initialize new one
//     tools.find('button').hide();
//     tools.find('input').hide();
//     tools.siblings('.case_documents_submenu').hide();
//     //Need to hide the search and the path now!!!! TODO
//     tools.find('.case_detail_panel_tools_right').append('<button class="closer">Close</button>');
//     tools.find('button.closer').button({icons: {primary: 'fff-icon-cross'},text: true});
//     tools.find('button.closer').click(function() {
//         var returnToFiles = function () {
//             if (currentPath === '') {  //the document is not in a subfolder
//                 target.load('lib/php/data/cases_documents_load.php', {
//                     'id': caseId,
//                     'update': 'yes',
//                     'path': currentPath
//                 }, function() {
//                     tools.find('button').show();
//                     tools.find('input').show();
//                     tools.find('.documents_search_clear').hide();
//                     tools.find('button.closer').remove();
//                     tools.siblings('.case_documents_submenu').show();
//                     createDragDrop();
//                 });
//             } else {  //document is in a subfolder
//                 target.load('lib/php/data/cases_documents_load.php', {
//                     'id': caseId,
//                     'update': 'yes',
//                     'path': currentPath,
//                     'container': currentPath
//                 }, function() {
//                     tools.find('button').show();
//                     tools.find('input').show();
//                     tools.find('.documents_search_clear').hide();
//                     tools.find('button.closer').remove();
//                     tools.siblings('.case_documents_submenu').show();
//                     createDragDrop();
//                 });
//             }
//         };
//         //If the author is closing and both the title and body are empty,
//         //probably clicked new documents by mistake.  Kill document.
//         //Alert ('this document is empty.  Delete it?')
//         if ($('.text_editor').html() === '' && $('.text_editor_title').text() === 'New Document'){
//             var warning = 'It appears this document is empty. Do you want to save it anyway?';
//             var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Item?">' + warning + '</div>')
//             .dialog({
//                 autoOpen: true,
//                 resizable: false,
//                 modal: true,
//                 buttons: {
//                     'Yes': function() {
//                         dialogWin.dialog('destroy');
//                         returnToFiles();
//                     },
//                     'No': function() {
//                         var itemId = $('.text_editor_bar').attr('data-id');
//                         $.post('lib/php/data/cases_documents_process.php',
//                         ({'action': 'delete','item_id': itemId,'doc_type': 'ccd'}), function(data) {
//                             var serverResponse = $.parseJSON(data);
//                             notify(serverResponse.message);
//                             dialogWin.dialog('destroy');
//                         });
//                         $(this).dialog('destroy');
//                         returnToFiles();
//                     }
//                 }
//             });

//         } else {
//             returnToFiles();
//         }
//     });

//     //If user has permission to edit, set the editing functions
//     if (permission === 'yes') {
//         //Change document title
//         ccdTitleArea.mouseenter(function() {
//             $(this).css({'color': 'red'});
//         })
//         .click(function() {
//             $(this).html('<input type="text" value="" />');
//             $(this).find('input').val(escapeHtml(unescape(ccdTitle))).focus().select();
//         })
//         .bind('focusout keyup', function(e) {
//             if (e.type === 'focusout' || e.which === 13) {
//                 ccdTitle = escape($(this).find('input').val());

//                 if(ccdTitle === '' || ccdTitle === '\n') {
//                     notify('Please give your document a title.',true);
//                     $(this).find('input').addClass('ui-state-error').focus();
//                     return false;
//                 } else {
//                     $(this).html(escapeHtml(unescape(ccdTitle)));
//                     $(this).css({'color': 'black'});
//                     var getText = arr[0].get_content();
//                     $.post('lib/php/data/cases_documents_process.php', {
//                         'action': 'update_ccd',
//                         'ccd_name': ccdTitle,
//                         'ccd_id': docIdArea.attr('data-id'),
//                         'ccd_text': getText
//                     }, function(data) {
//                         var serverResponse = $.parseJSON(data);
//                         notify(serverResponse.message);
//                     });
//                 }
//             }

//         })
//         .mouseleave(function() {
//             $(this).css({'color': 'black'});
//         });

//         //auto-save
//         var lastText = '';
//         var autoSave = function (lastText, arr) {
//             var text = arr[0].get_content();
//             var status = 'Saving...';
//             if (text !== lastText) {
//                 ccdStatusArea.find('span.status').html(status);
//                 $.post('lib/php/data/cases_documents_process.php', {
//                     'action': 'update_ccd',
//                     'ccd_name': ccdTitleArea.html(),
//                     'ccd_id': docIdArea.attr('data-id'),
//                     'ccd_text': text
//                 }, function(data) {
//                     var serverResponse = $.parseJSON(data);
//                     if (serverResponse.error) {
//                         ccdStatusArea.find('span.status').html(serverResponse.message);
//                     } else {
//                         ccdTitleArea.html(serverResponse.ccd_title);
//                         ccdStatusArea.find('span.status').html(serverResponse.message);
//                     }
//                 });
//                 lastText = text;
//             }

//             var t = setTimeout(function() {
//                 autoSave(lastText, arr);
//             }, 3000);
//         };

//         autoSave(lastText, arr);
//     }
// }

// function clearSearchBox(el){
//     //Clear search results, if any
//     el.closest('.case_detail_panel').find('input.documents_search').val('Search Titles').css({'color': '#AAA'});
//     el.closest('.case_detail_panel').find('.documents_search_clear').hide();

//     //If the trail has been previously hidden because we were showing
//     //search results, show it again
//     el.closest('.case_detail_panel').find('.case_documents_submenu').show();
// }

// function openItem(el, itemId, docType, caseId, path, pathDisplay) {
//     if ($(el).hasClass('folder')) {
//         if ( $(el).hasClass('.ui-draggable-dragging') ) {
//             return;
//         }

//         $(el).closest('.case_detail_panel') .load('lib/php/data/cases_documents_load.php', {
//             'id': caseId,
//             'container': path,
//             'path': path,
//             'update': 'y'
//         }, function() {
//             var pathString = createTrail(path);
//             pathDisplay.html(pathString);
//             pathDisplay.find('a[path="' + path + '"]').addClass('active');
//             createDragDrop();

//             //Set the current path so that other functions can access it
//             $(this).closest('.case_detail_panel').data('CurrentPath', path);

//             clearSearchBox($(this));

//             //Apply shadow on scroll
//             $(this).children('.case_detail_panel').bind('scroll', function() {
//                 var scrollAmount = $(this).scrollTop();
//                 if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
//                     $(this).removeClass('csenote_shadow');
//                 } else {
//                     $(this).addClass('csenote_shadow');
//                 }
//             });
//         });
//     } else if ($(el).hasClass('url')) {
//         if ( $(el).hasClass('.ui-draggable-dragging') ) {
//             return;
//         }

//         $.post('lib/php/data/cases_documents_process.php', {
//             'action': 'open',
//             'item_id': itemId,
//             'doc_type': 'document'
//         }, function(data) {
//             var serverResponse = $.parseJSON(data);
//             window.open(serverResponse.target_url, '_blank');
//         });
//     } else if ($(el).hasClass('ccd')) {
//         if ( $(el).hasClass('.ui-draggable-dragging') ) {
//             return;
//         }

//         $.post('lib/php/data/cases_documents_process.php', {
//             'action': 'open',
//             'item_id': itemId,
//             'doc_type': 'document'
//         }, function(data) {
//             var serverResponse = $.parseJSON(data);
//             var target = $(el).closest('.case_detail_panel');
//             createTextEditor(target, 'view', serverResponse.ccd_permissions, serverResponse.ccd_title,
//             serverResponse.ccd_content, serverResponse.ccd_id,serverResponse.ccd_owner,serverResponse.ccd_locked);
//         });
//     } else if ($(el).hasClass('pdf')){
//         if (Object.create){ //informal browser check for ie8
//             //Show pdfjs viewer
//             $('#pdf-viewer').show();
//             $('#frme').attr('src', 'lib/javascripts/pdfjs/web/viewer.html?item_id=' + itemId);

//             //Add listener to close pdf viewer
//             $('#pdf-viewer').click(function(){
//                 $('#frme').attr('src','');
//                 $(this).hide();
//             });

//             //Close pdfviewer on escape key press
//             $('body').bind('keyup.pdfViewer', function (e){
//                 if (e.keyCode === 27){
//                     $('#frme').attr('src','');
//                     $('#pdf-viewer').hide();
//                 }
//             });
//         } else {
//             //pdfjs is not supported; revert to download
//             $.download('lib/php/data/cases_documents_process.php', {
//                 'item_id': itemId,
//                 'action': 'open',
//                 'doc_type': docType
//             });
//         }
//     } else {
//         if ( $(el).hasClass('.ui-draggable-dragging') ) {
//             return;
//         }

//         $.download('lib/php/data/cases_documents_process.php', {
//             'item_id': itemId,
//             'action': 'open',
//             'doc_type': docType
//         });
//     }
// }

//User clicks to open document window
// $('.case_detail_nav #item3').live('click', function() {

//     var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
//     var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');

//     //Get heights
//     var toolsHeight = $(this).outerHeight();
//     var thisPanelHeight = $(this).closest('.case_detail_nav').height();
//     var documentsWindowHeight = thisPanelHeight - toolsHeight;

//     //Set the current path so that other functions can access it
//     thisPanel.data('CurrentPath', 'Home');

//     thisPanel.load('lib/php/data/cases_documents_load.php', {'id': caseId}, function() {
//         //Set css
//         $('div.case_detail_panel_tools').css({'height': toolsHeight});
//         $('div.case_detail_panel').css({'height': documentsWindowHeight});
//         $('div.case_detail_panel_tools_left').css({'width': '20%'});
//         $('div.case_detail_panel_tools_right').css({'width': '80%'});
//         $('div.case_detail_panel_tools').css({'border-bottom': '1px solid #AAA','margin-bottom':'10px'});

//         //Set buttons
//         $(this).find('button.doc_new_doc').button({icons: {primary: 'fff-icon-page-add'},text: true});
//         $(this).find('button.doc_new_folder').button({icons: {primary: 'fff-icon-folder-add'},text: true});
//         $(this).find('button.doc_upload').button({icons: {primary: 'fff-icon-page-white-get'},text: true});
//         $(this).find('.documents_view_chooser' ).buttonset();
//         $(this).find('.radio_toggle_grid').button({icons:{primary:'fff-icon-application-view-icons'},text:true});
//         $(this).find('.radio_toggle_list').button({icons:{primary:'fff-icon-application-view-list'},text:true}).next().addClass('buttonset-inactive');

//         //Check to see if list or grid view is set
//         if (!$.cookie('cc_doc_view') || $.cookie('cc_doc_view') === 'grid'){
//             $(this).find('.radio_toggle_grid').next().removeClass('buttonset-inactive');
//             $(this).find('.radio_toggle_list').next().addClass('buttonset-inactive');
//         } else {
//             $(this).find('.radio_toggle_list').next().removeClass('buttonset-inactive');
//             $(this).find('.radio_toggle_grid').next().addClass('buttonset-inactive');
//         }

//         //Apply shadow on scroll
//         $(this).children('.case_detail_panel').bind('scroll', function() {
//             var scrollAmount = $(this).scrollTop();
//             if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
//                 $(this).removeClass('csenote_shadow');
//             } else {
//                 $(this).addClass('csenote_shadow');
//             }
//         });
//         createDragDrop();

//     });

//     //Create context menu
//     $('.doc_item').contextMenu({menu: 'docMenu'}, function(action, el, pos) {
//         var itemId = $(el).attr('data-id');
//         var docType = null;
//         var caseId = $(el).closest('.case_detail_panel').data('CaseNumber');
//         var docName = $(el).find('p').html();
//         var pathDisplay = $(el).closest('.case_detail_panel')
//             .siblings('.case_detail_panel_tools')
//             .find('.path_display');
//         var path;

//         if ($(el).hasClass('folder')) {
//             docType = 'folder';
//             path = $(el).attr('path');
//         } else {
//             docType = 'document';
//             path = '';
//         }

//         switch (action) {
//             case 'open':
//                 openItem(el, itemId, docType, caseId, path, pathDisplay);
//                 break;
//             case 'cut':
//                 $(el).css({'opacity': '.5'});

//                 //Stash the data about the cut file or folder
//                 var cutData = new Array(itemId, docType, path, caseId);
//                 $(el).closest('.case_detail_panel').data('cutValue', cutData);

//                 //Create a new context menu which allows for copying and pasting into a div with no items;
//                 $('div.case_detail_panel').contextMenu({
//                     menu: 'docMenu_copy_paste'
//                 }, function(action, el, pos) {
//                     if (action === 'paste') {
//                         var caseId = el.data('cutValue')[3];
//                         var docType = el.data('cutValue')[1];
//                         var targetPath = el.closest('.case_detail_panel').data('CurrentPath');
//                         if (targetPath === 'Home') {
//                             targetPath = '';
//                         }
//                         var itemId = el.data('cutValue')[0];
//                         var selectionPath = el.data('cutValue')[2];
//                         $.post('lib/php/data/cases_documents_process.php', {
//                             'action': 'cut',
//                             'item_id': itemId,
//                             'target_path': targetPath,
//                             'selection_path': selectionPath,
//                             'doc_type': docType,
//                             'case_id': caseId
//                         },
//                         function(data) {
//                             var serverResponse = $.parseJSON(data);
//                             if (serverResponse.error){
//                                 notify(serverResponse.message,true,'error');
//                             } else {
//                                 notify(serverResponse.message);
//                             }
//                             el.closest('.case_detail_panel')
//                             .load('lib/php/data/cases_documents_load.php', {
//                                 'id': caseId,
//                                 'update': 'yes',
//                                 'path': targetPath,
//                                 'container': targetPath
//                             }, function() {
//                                 el.destroyContextMenu();
//                                 createDragDrop();
//                             });
//                         });
//                     }
//                 });
//                 break;
//             case 'copy':
//                 if (docType === 'folder') { //TODO add copying of folders
//                     notify('Sorry, copying of folders is not supported.', true);
//                 } else {
//                     $(el).css({'border': '1px solid #AAA'});
//                     //Stash the data about the copy file or folder
//                     var copyData = new Array(itemId, docType, path, caseId);
//                     $(el).closest('.case_detail_panel').data('copyValue', copyData);

//                     //Create a new context menu which allows for pasting into a div with no items;
//                     $('div.case_detail_panel').contextMenu({
//                         menu: 'docMenu_copy_paste'
//                     }, function(action, el, pos) {
//                         if (action === 'paste') {
//                             var caseId = el.data('copyValue')[3];
//                             var docType = el.data('copyValue')[1];
//                             var selectionPath = el.data('copyValue')[2];
//                             var targetPath = el.closest('.case_detail_panel').data('CurrentPath');
//                             if (targetPath === 'Home') {
//                                 targetPath = '';
//                             }
//                             var itemId = el.data('copyValue')[0];
//                             $.post('lib/php/data/cases_documents_process.php', {
//                                 'action': 'copy',
//                                 'item_id': itemId,
//                                 'target_path': targetPath,
//                                 'doc_type': docType,
//                                 'case_id': caseId
//                             },
//                             function(data) {
//                                 var serverResponse = $.parseJSON(data);
//                                 notify(serverResponse.message);
//                                 el.closest('.case_detail_panel')
//                                 .load('lib/php/data/cases_documents_load.php', {
//                                     'id': caseId,
//                                     'update': 'yes',
//                                     'path': targetPath,
//                                     'container': targetPath
//                                 }, function() {
//                                     el.destroyContextMenu();
//                                 });
//                             });
//                         }
//                     });
//                 }
//                 break;

//             case 'rename':
//                 var textVal = $(el).find('p').html(),
//                 submitChange = function (cb) {
//                     var newVal = escape($.trim($(el).find('textarea').val()));
//                     //Don't save an empty value
//                     if (newVal === ''){
//                         return;
//                     }
//                     $.post('lib/php/data/cases_documents_process.php', ({
//                         'action': 'rename',
//                         'new_name': newVal,
//                         'item_id': itemId,
//                         'doc_type': docType,
//                         'path': path,
//                         'case_id': caseId
//                     }), function(data) {
//                         var serverResponse = $.parseJSON(data);
//                         if (serverResponse.error){
//                             notify(serverResponse.message);
//                             return;
//                         } else {
//                             $(el).find('textarea').hide();
//                             $(el).find('p').html(escapeHtml(unescape(newVal)));
//                             $(el).attr('path', serverResponse.newPath);
//                             $(el).find('p').show();
//                         }
//                         notify(serverResponse.message);
//                         cb();
//                     });
//                 };
//                 $(el).find('p').hide();
//                 if ($(el).find('textarea').length < 1) {
//                     $(el).find('p').after('<br /><textarea>' + textVal + '</textarea>');
//                 } else {
//                     $(el).find('textarea').show().val(textVal);
//                 }
//                 $(el).find('textarea').addClass('user_input').focus().select()
//                 .blur(function(e) {
//                     submitChange(function (){
//                         $(el).find('textarea').hide();
//                         $(el).find('p').show();
//                         $(el).find('textarea').unbind('blur keypress');
//                     });
//                 })
//                 .click(function(event) {
//                     event.stopPropagation();
//                 })
//                 .keypress(function (e) {
//                     e.stopPropagation();
//                     if (e.which === 13) {
//                         submitChange(function () {
//                             $(el).find('textarea').unbind('keypress blur');
//                         });
//                     }
//                 });
//                 break;
//             case 'delete':
//                 var warning = null;
//                 if ($(el).hasClass('folder')) {
//                     warning = 'This folder and all of its contents will be permanently ' +
//                     'deleted from the server.' + ' Are you sure?';
//                 } else {
//                     warning = 'This item will be permanently deleted from the server.  Are you sure?';
//                 }

//                 var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Item?">' + warning + '</div>')
//                 .dialog({
//                     autoOpen: true,
//                     resizable: false,
//                     modal: true,
//                     buttons: {
//                         'Yes': function() {
//                             $.post('lib/php/data/cases_documents_process.php', ({'action': 'delete',
//                             'item_id': itemId,
//                             'doc_type': docType,
//                             'path': path,
//                             'case_id': caseId
//                         }), function(data) {
//                                 var serverResponse = $.parseJSON(data);
//                                 notify(serverResponse.message);
//                                 $(el).remove();
//                                 dialogWin.dialog('destroy');
//                             });
//                         },
//                         'No': function() {
//                             $(this).dialog('destroy');
//                         }
//                     }
//                 });
//                 break;

//             case 'properties':
//                 $(el).css({'border': '1px solid #AAA'})
//                     .next('.doc_properties')
//                     .addClass('ui-corner-all')
//                     .css({'top': '20%','left': '30%'})
//                     .show().focus().focusout(function() {
//                         $(this).hide();
//                         $(el).css({'border': '0px'});
//                     });
//                 break;
//         }
//     });

//     //Expand div to include full file name on mouse enter
//     $('div.doc_item').live('mouseenter', function(event) {
//         $(this).closest('div').css({'height': 'auto','overflow': 'auto'});
//     });

//     //Reset on leave
//     $('div.doc_item').live('mouseleave', function(event) {
//         $(this).closest('div').css({'height': '120px','overflow': 'hidden'});
//     });

// });

// $('.doc_item').live('click', function(event) {
//     var path = $(this).attr('path');
//     var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
//     var pathDisplay = $(this).closest('.case_detail_panel')
//         .siblings('.case_documents_submenu')
//         .find('.path_display');
//     var el = $(this);
//     var itemId = el.attr('data-id');
//     var docType = 'document';
//     openItem(el, itemId, docType, caseId, path, pathDisplay);
// });

// //User clicks new document button
// $('button.doc_new_doc').live('click', function () {
//   var target = $(this)
//     .closest('.case_detail_panel_tools')
//     .siblings('.case_detail_panel');
//   createTextEditor(
//     target,
//     'new',
//     'yes',
//     'New Document',
//     null,
//     null,
//     '1',
//     'yes',
//   );
// });

// //User clicks new folder button
// $('button.doc_new_folder').live('click', function () {
//   var target = $(this)
//     .closest('.case_detail_panel_tools')
//     .siblings('.case_detail_panel');

//   //if this is an empty folder, remove the "No Documents Found" message
//   if ($('span.docs_empty')) {
//     $('span.docs_empty').remove();
//   }

//   if ($.cookie('cc_doc_view') === 'list') {
//     target
//       .find('tbody')
//       .prepend(
//         '<tr class="doc_item folder" path="" data-id="">' +
//           '<td width="10%"><img src="html/ico/folder.png"></td>' +
//           '<td><p><textarea rows="1" id="new_folder_name">New Folder</textarea></p></td><td></td><td></td></tr>',
//       );
//   } else {
//     target.prepend(
//       '<div class="doc_item folder" path="" data-id=""><img src="html/ico/folder.png">' +
//         '<p><textarea id="new_folder_name">New Folder</textarea></p></div>',
//     );
//   }

//   $('#new_folder_name').select();
//   $('#new_folder_name')
//     .addClass('user_input')
//     .mouseenter(function () {
//       $(this).val('').focus().css({ 'background-color': 'white' });
//     })
//     .bind('blur keyup', function (e) {
//       if (e.type === 'blur' || e.which === 13) {
//         e.preventDefault();
//         var container = $(this)
//           .closest('.case_detail_panel')
//           .siblings('.case_documents_submenu')
//           .find('a.active')
//           .attr('path');
//         var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
//         var newName = $('#new_folder_name').val();

//         if (newName.indexOf('/') !== -1) {
//           notify('Sorry, folder names cannot contain a foward slash.', true);
//           return false;
//         } else if (newName === '\n') {
//           //user has only pressed return (inserting
//           // a new line character, but no file name)
//           notify('Please provde a name for your folder.', true);
//           return false;
//         } else {
//           var newFolder = null;

//           if (container === '' || typeof container === 'undefined') {
//             newFolder = escape($.trim(newName.replace(/[\n\r]$/, '')));
//             //replace method removes any new line characters that
//             //may have been added by the user pressing enter
//           } else {
//             newFolder =
//               container + '/' + escape($.trim(newName.replace(/[\n\r]$/, '')));
//           }
//           $.post(
//             'lib/php/data/cases_documents_process.php',
//             {
//               case_id: caseId,
//               container: container,
//               new_folder: newFolder,
//               action: 'newfolder',
//             },
//             function (data) {
//               var serverResponse = $.parseJSON(data);
//               if (serverResponse.error === true) {
//                 notify(serverResponse.message, true);
//               } else {
//                 if ($.cookie('cc_doc_view') === 'list') {
//                   $('#new_folder_name')
//                     .closest('tr')
//                     .find('img')
//                     .wrap('<a href="#" />');
//                   $('#new_folder_name')
//                     .closest('tr')
//                     .attr({ path: newFolder, 'data-id': serverResponse.id })
//                     .droppable();
//                 } else {
//                   $('#new_folder_name')
//                     .parent()
//                     .siblings('img')
//                     .wrap('<a href="#" />');
//                   $('#new_folder_name')
//                     .closest('.folder')
//                     .attr({ path: newFolder, 'data-id': serverResponse.id })
//                     .droppable();
//                 }
//                 $('#new_folder_name').closest('p').html(escapeHtml(newName));
//                 createDragDrop();
//                 notify(serverResponse.message);
//               }
//             },
//           );
//         }
//       }
//     });
// });

// //User clicks on the upload button
// $('button.doc_upload').live('click', function () {
//   var thisPanel = $(this)
//     .closest('.case_detail_panel_tools')
//     .siblings('.case_detail_panel');
//   var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

//   //if this is an empty folder, remove the "No Documents Found" message
//   if ($('span.docs_empty')) {
//     $('span.docs_empty').remove();
//   }

//   //Tells user which directory files will be uploaded to
//   var activeDirectory = $(this).parent().siblings().find('a.active').text();
//   if (activeDirectory === '') {
//     activeDirectory = 'Home';
//   }

//   //Tells the server which directory to put file in
//   var currentPath = $(this).closest('.case_detail_panel').data('CurrentPath');

//   //Db leaves folder field blank for documents in root directory, so send empty value
//   if (currentPath === 'Home') {
//     currentPath = '';
//   }

//   $(document)
//     .find('.upload_dialog')
//     .dialog({
//       height: 500,
//       width: 500,
//       modal: true,
//       title: 'Upload into ' + escapeHtml(activeDirectory) + ' folder:',
//       open: function () {
//         $(this).find('div.upload_dialog_url').show();
//         $(this).find('div.upload_dialog_file').show();
//         $(this).find('div.upload_url_form').hide();
//       },
//       close: function () {
//         $(this).dialog('destroy');
//       },
//     });

//   var uploader = new qq.FileUploader({
//     // pass the dom node (ex. $(selector)[0] for jQuery users)
//     element: $('.upload_dialog_file')[0],
//     // path to server-side upload script
//     action: 'lib/php/utilities/file_upload.php',
//     params: { path: currentPath, case_id: caseId },
//     onComplete: function () {
//       thisPanel.load(
//         'lib/php/data/cases_documents_load.php',
//         {
//           id: caseId,
//           update: 'yes',
//           path: currentPath,
//           container: currentPath,
//         },
//         function () {
//           createDragDrop();
//         },
//       );
//     },
//   });

//   $('div.qq-upload-button')
//     .addClass('ui-corner-all')
//     .click(function () {
//       $(this)
//         .closest('.upload_dialog_file')
//         .siblings('div.upload_dialog_url')
//         .hide();
//     });

//   $('.upload_url_button')
//     .mouseenter(function () {
//       $(this).addClass('qq-upload-button-hover');
//     })
//     .mouseleave(function () {
//       $(this).removeClass('qq-upload-button-hover');
//     })
//     .click(function () {
//       $(this).siblings('.upload_url_form').show();
//       $(this)
//         .parents('.upload_dialog_url')
//         .siblings('.upload_dialog_file')
//         .hide();
//     });

//   $('button.upload_url_submit')
//     .unbind('click')
//     .click(function () {
//       var url = $(this).siblings('input.url_upload').val();
//       var urlName = $(this).siblings('input.url_upload_name').val();
//       if (isUrl(url) === false) {
//         $(document)
//           .find('p.upload_url_form_error_url')
//           .html(
//             'Sorry, your URL is invalid.  It must begin with http://, https:// or ftp://',
//           );
//         $(this)
//           .siblings('input.url_upload')
//           .focus(function () {
//             $(document).find('p.upload_url_form_error_url').html('');
//           });
//         return false;
//       } else if (urlName === '') {
//         $(document)
//           .find('p.upload_url_form_error_name')
//           .html('Please give this URL a title.');
//         $(this)
//           .siblings('input.url_upload_name')
//           .focus(function () {
//             $(document).find('p.upload_url_form_error_name').html('');
//           });
//         return false;
//       } else {
//         $.post(
//           'lib/php/data/cases_documents_process.php',
//           {
//             url_name: urlName,
//             url: url,
//             case_id: caseId,
//             path: currentPath,
//             action: 'add_url',
//           },
//           function (data) {
//             var serverResponse = $.parseJSON(data);
//             $('.upload_dialog')
//               .find('p.upload_url_notify')
//               .show()
//               .html(serverResponse.message)
//               .fadeOut('slow', function () {
//                 $(this).html('');
//               });
//             $('.upload_dialog').find('input').val('');
//             thisPanel.load(
//               'lib/php/data/cases_documents_load.php',
//               {
//                 id: caseId,
//                 update: 'yes',
//                 path: currentPath,
//                 container: currentPath,
//               },
//               function () {
//                 //unescapeNames();
//               },
//             );
//           },
//         );
//       }
//     });
// });

// //User clicks the Home link in the directory path
// $('a.doc_trail_home').live('click', function (event) {
//   event.preventDefault();
//   var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
//   var thisPanel = $(this)
//     .closest('.case_documents_submenu')
//     .siblings('.case_detail_panel');
//   //Set the current path so that other functions can access it
//   $(this).closest('.case_detail_panel').data('CurrentPath', 'Home');

//   thisPanel.load(
//     'lib/php/data/cases_documents_load.php',
//     {
//       id: caseId,
//       update: 'yes',
//     },
//     function () {
//       $(this)
//         .siblings('.case_documents_submenu')
//         .find('.path_display')
//         .html('');
//       createDragDrop();
//       clearSearchBox($(this));
//     },
//   );
// });

// //User clicks one of the other links in the directory path
// $('a.doc_trail_item').live('click', function (event) {
//   event.preventDefault();
//   var container = $(this).html();
//   var path = $(this).attr('path');
//   var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

//   //Set the current path so that other functions can access it
//   $(this).closest('.case_detail_panel').data('CurrentPath', path);
//   var thisPanel = $(this)
//     .closest('.case_documents_submenu')
//     .siblings('.case_detail_panel');
//   var pathDisplay = $(this).parent();

//   thisPanel.load(
//     'lib/php/data/cases_documents_load.php',
//     {
//       id: caseId,
//       update: 'yes',
//       path: path,
//       container: path,
//     },
//     function () {
//       $(this)
//         .siblings('.case_detail_panel_tools')
//         .find('.path_display')
//         .html('');
//       var pathString = createTrail(path);
//       pathDisplay.html(pathString);
//       pathDisplay.find('a[path="' + path + '"]').addClass('active');

//       //Apply shadow on scroll
//       $(this)
//         .children('.case_detail_panel')
//         .bind('scroll', function () {
//           var scrollAmount = $(this).scrollTop();
//           if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
//             $(this).removeClass('csenote_shadow');
//           } else {
//             $(this).addClass('csenote_shadow');
//           }
//         });
//       createDragDrop();
//     },
//   );
// });

// //Owner can lock the document for editing by others
// $('select[name="ccd_permission"]').live('change', function () {
//   var lockStatus = $(this).val();
//   var ccdId = $(this).closest('.text_editor_bar').attr('data-id');
//   $.post(
//     'lib/php/data/cases_documents_process.php',
//     { action: 'change_ccd_permissions', ccd_id: ccdId, ccd_lock: lockStatus },
//     function (data) {
//       var serverResponse = $.parseJSON(data);
//       notify(serverResponse.message);
//     },
//   );
// });

// //handle search
// $('input.documents_search').live('focusin', function () {
//   $(this).val('');
//   $(this).css({ color: 'black' });
//   $(this).next('.documents_search_clear').show();
// });

// $('input.documents_search').live('keyup', function () {
//   if ($(this).val() !== '') {
//     var resultTarget = $(this)
//       .closest('div.case_detail_panel_tools')
//       .siblings('.case_detail_panel');
//     var search = $(this).val();
//     var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
//     $(this)
//       .closest('.case_detail_panel_tools')
//       .siblings('.case_documents_submenu')
//       .hide();
//     resultTarget.load(
//       'lib/php/data/cases_documents_load.php',
//       {
//         id: caseId,
//         search: search,
//         update: 'yes',
//       },
//       function () {
//         resultTarget.scrollTop(0);
//         if (search.length) {
//           resultTarget.highlight(search);
//           $('thead').removeHighlight();
//         }
//       },
//     );
//   } else {
//     $('.documents_search_clear').trigger('click');
//   }
// });

// $('.documents_search_clear').live('click', function () {
//   $(this).prev().val('Search Titles');
//   $(this).prev().css({ color: '#AAA' });
//   $(this).prev().blur();
//   $(this)
//     .closest('.case_detail_panel')
//     .find('.doc_trail_home')
//     .trigger('click');
//   $(this).hide();
//   $(this)
//     .closest('.case_detail_panel_tools')
//     .siblings('.case_documents_submenu')
//     .show();
// });

// //User changes view for documents
// $('.radio_toggle_grid').live('click', function () {
//   var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
//   var thisPanel = $(this)
//     .closest('.case_detail_panel_tools')
//     .siblings('.case_detail_panel');
//   var currentPath = $(this).closest('.case_detail_panel').data('CurrentPath');
//   var sendPath;
//   if (currentPath === 'Home') {
//     sendPath = '';
//   } else {
//     sendPath = currentPath;
//   }
//   var clickedButton = $(this);
//   //Set the current path so that other functions can access it
//   //$(this).closest('.case_detail_panel').data('CurrentPath', 'Home');

//   $.cookie('cc_doc_view', 'grid');
//   thisPanel.load(
//     'lib/php/data/cases_documents_load.php',
//     {
//       id: caseId,
//       update: 'yes',
//       path: sendPath,
//       container: sendPath,
//     },
//     function () {
//       createDragDrop();
//       clearSearchBox($(this));

//       //Set correct shading of buttons
//       clickedButton.next().toggleClass('buttonset-inactive');
//       clickedButton.siblings('input').next().toggleClass('buttonset-inactive');
//     },
//   );
// });

// $('.radio_toggle_list').live('click', function () {
//   var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
//   var thisPanel = $(this)
//     .closest('.case_detail_panel_tools')
//     .siblings('.case_detail_panel');
//   var currentPath = $(this).closest('.case_detail_panel').data('CurrentPath');
//   var sendPath;
//   if (currentPath === 'Home') {
//     sendPath = '';
//   } else {
//     sendPath = currentPath;
//   }
//   var clickedButton = $(this);

//   $.cookie('cc_doc_view', 'list');
//   thisPanel.load(
//     'lib/php/data/cases_documents_load.php',
//     {
//       id: caseId,
//       list_view: 'yes',
//       update: 'yes',
//       path: sendPath,
//       container: sendPath,
//     },
//     function () {
//       createDragDrop();
//       clearSearchBox($(this));

//       //Set correct shading of buttons
//       clickedButton.next().toggleClass('buttonset-inactive');
//       clickedButton.prev().toggleClass('buttonset-inactive');
//     },
//   );
// });
// Switch documents to list view
live('click', 'documents_view_chooser--list', async (_event, el) => {
  const caseDetailsRef = el.closest('.case_details_documents');
  caseDetailsRef.dataset.layout = 'List';
  const caseId = caseDetailsRef.dataset.caseid;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  const chooser = el.closest('.documents_view_chooser');
  chooser.classList.remove('grid');
  chooser.classList.add('list');
  const gridImage = chooser.querySelector('.documents_view_chooser--grid img');
  const listImage = chooser.querySelector('.documents_view_chooser--list img');
  gridImage.src = 'html/ico/grid-unselected.png';
  listImage.src = 'html/ico/list-selected.png';
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  const search =
    document.querySelector(`#nav-${caseId}-documents .documents_search`)
      .value || null;
  const html = await getDocuments(caseId, search, true, 'yes', currentPath);

  documentsContainer.innerHTML = html;
  setCookie('cc_docs_view', 'list', 2);
});
// Switch documents to grid view
live('click', 'documents_view_chooser--grid', async (_event, el) => {
  const caseDetailsRef = el.closest('.case_details_documents');
  caseDetailsRef.dataset.layout = 'Grid';
  const caseId = caseDetailsRef.dataset.caseid;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  const chooser = el.closest('.documents_view_chooser');
  chooser.classList.remove('list');
  chooser.classList.add('grid');
  const gridImage = chooser.querySelector('.documents_view_chooser--grid img');
  const listImage = chooser.querySelector('.documents_view_chooser--list img');
  gridImage.src = 'html/ico/grid-selected.png';
  listImage.src = 'html/ico/list-unselected.png';

  const search =
    document.querySelector(`#nav-${caseId}-documents .documents_search`)
      .value || null;
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  const html = await getDocuments(caseId, search, true, null, currentPath);
  documentsContainer.innerHTML = html;
  setCookie('cc_docs_view', 'grid', 2);
});
// Search documents
live('change', 'documents_search', async (event) => {
  const el = event.target;
  const search = el.value;
  const caseId = el.dataset.caseid;
  const caseDetailsRef = el.closest('.case_details_documents');
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  const listView = caseDetailsRef.dataset.currentpath === 'List' ? true : null;

  const html = await getDocuments(caseId, search, true, listView || null);
  documentsContainer.innerHTML = html;
});
//User clicks a folder or document
live('click', 'doc_item_folder', async (event, el) => {
  event.preventDefault();
  const path = el.dataset.path;

  const caseDetailsRef = el.closest('.case_details_documents');
  const caseId = caseDetailsRef.dataset.caseid;
  const pathDisplay = document.querySelector(
    `#nav-${caseId}-documents .path_display`,
  );
  pathDisplay.innerHTML = createTrail(path);
  caseDetailsRef.dataset.currentpath = path;
  // const docType = el.classList.contains('folder') ? 'folder' : 'document';
  // const itemId = el.dataset.id;
  // console.log({ docType,path, caseId, itemId });
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  const isList = caseDetailsRef.dataset.layout === 'List' ? true : null;
  const html = await getDocuments(caseId, null, true, isList || null, path);
  documentsContainer.innerHTML = html;
});

// User drags and drops a file or folder
let draggedItem = null;
// need this to allow drop
live('dragover', 'doc_item_folder', (event) => {
  event.preventDefault();
});
live('drag', 'doc_item', (_event, item) => {
  draggedItem = item;
});
live('dragenter', 'doc_item_folder', (_event, folder) => {
  folder.classList.add('doc_item_folder--active');
});
live('dragend', 'doc_item', () => {
  const folders = document.querySelectorAll('.doc_item_folder');
  folders.forEach((folder) =>
    folder.classList.remove('doc_item_folder--active'),
  );
});
live('drop', 'doc_item_folder', async (event, folder) => {
  event.preventDefault();
  const item_id = draggedItem.dataset.id;
  const caseDetailsRef = folder.closest('.case_details_documents');
  const case_id = caseDetailsRef.caseid;
  const path = folder.dataset.path;
  const selection_path = draggedItem.dataset.path;
  const docType = draggedItem.classList.contains('folder') ? 'folder' : 'item';
  if (path == selection_path) return;
  try {
    await processDocuments({
      case_id,
      action: 'cut',
      item_id,
      target_path: path,
      selection_path,
      doc_type: docType,
    });
    draggedItem.classList.add('fadeOut');
    setTimeout(() => {
      draggedItem.classList.add('hidden');
      draggedItem = null;
    }, 500);
  } catch (err) {
    console.log(err);
  } finally {
    setTimeout(() => {
      draggedItem = null;
    }, 500);
  }
});

// NAVIGATING BETWEEN DIRECTORIES
// user clicks on home directory doc_trail_home
live('click', 'doc_trail_home', async (event, homePanel) => {
  const caseDetailsRef = homePanel.closest('.case_details_documents');
  const caseId = caseDetailsRef?.dataset.caseid;
  caseDetailsRef.dataset.currentpath = 'Home';
  const pathDisplay = homePanel
    .closest('.case_documents_submenu')
    .querySelector('.path_display');
  pathDisplay.innerHTML = '';
  const isList = caseDetailsRef?.dataset.layout === 'List' ? true : null;
  const html = await getDocuments(caseId, null, true, isList, null);
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  documentsContainer.innerHTML = html;
});
// user clicks on another item in path doc_trail_path
live('click', 'doc_trail_item', async (_event, trail) => {
  const caseDetailsRef = trail.closest('.case_details_documents');
  const caseId = caseDetailsRef?.dataset.caseid;
  const path = trail.dataset.path;
  caseDetailsRef.dataset.currentpath = path;
  const pathDisplay = trail
    .closest('.case_documents_submenu')
    .querySelector('.path_display');
  pathDisplay.innerHTML = createTrail(path);
  console.log({ path });
  const isList = caseDetailsRef?.dataset.layout === 'List' ? true : null;
  const html = await getDocuments(caseId, null, true, isList, path);
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  documentsContainer.innerHTML = html;
});
// OPENING DOCUMENTS
live('click', 'docs_new_folder', (_event, button) => {
  const caseDetailsRef = button.closest('.case_details_documents');
  const caseId = caseDetailsRef.dataset.caseid;
  const isList = caseDetailsRef.dataset.layout == 'List' ? true : null;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  const newFolderForm = document.querySelector('#newFolderModal form');
  setFormValues(newFolderForm, { caseId, isList, currentPath });
  const newFolderModal =
    bootstrap.Modal.getInstance('#newFolderModal') ||
    new bootstrap.Modal('#newFolderModal');
  newFolderModal.show();
});
// Adding folder
live('click', 'doc_new_folder_submit', async (event, button) => {
  const modal = document.querySelector('#newFolderModal');
  const newFolderModal = bootstrap.Modal.getInstance(modal);
  const form = modal.querySelector('form');
  const isValid = checkFormValidity(form);
  if (isValid == false) {
    form.classList.add('invalid');
    alertify.error('Please provide a folder name.');
    return;
  }
  const values = getFormValues(form);
  const { folderName, caseId, isList, currentPath } = values;
  try {
    await processDocuments({
      case_id: caseId,
      action: 'newfolder',
      target_path: currentPath,
      selection_path: folderName,
      doc_type: 'folder',
      new_folder: folderName,
      container: currentPath || null,
    });
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    newFolderModal.hide();
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList || null,
      currentPath || null,
    );
    documentsContainer.innerHTML = html;
  } catch (error) {
    alertify.error(error.message);
  } finally {
    resetForm(form);
  }
});

// adding documents
let newDocEditor;
live('click', 'docs_new_document', (_event, button) => {
  const caseDetailsRef = button.closest('.case_details_documents');
  const caseId = caseDetailsRef.dataset.caseid;
  const isList = caseDetailsRef.dataset.layout == 'List' ? true : null;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  if (!newDocEditor) {
    ClassicEditor.create(document.querySelector('#newDocEditor'))
      .then((editor) => (newDocEditor = editor))
      .catch((error) => {
        console.error(error);
      });
  }
  const newDocumentForm = document.querySelector('#newDocumentModal form');
  setFormValues(newDocumentForm, { caseId, isList, currentPath });
  const newDocumentModal =
    bootstrap.Modal.getInstance('#newDocumentModal') ||
    new bootstrap.Modal('#newDocumentModal');
  newDocumentModal.show();
});
live('click', 'doc_new_document_submit', async (event, button) => {
  const modal = document.querySelector('#newDocumentModal');
  const newFolderModal = bootstrap.Modal.getInstance(modal);
  const form = modal.querySelector('form');
  const text = newDocEditor.getData();
  const textarea = modal.querySelector('#newDocEditor');
  textarea.value = text;
  const values = getFormValues(form);
  const errors = checkFormValidity(form);
  const isValid = errors == true;
  if (!isValid) {
    form.classList.add('invalid');
    alertify.error(`Please provide values for ${errors}`);
    return;
  }
  const { folderName, caseId, isList, currentPath, doc_name, locked } = values;
  try {
    await processDocuments({
      case_id: caseId,
      action: 'new_ccd',
      target_path: currentPath,
      selection_path: folderName,
      doc_type: 'item',
      new_folder: folderName,
      container: currentPath || null,
      ccd_name: doc_name,
      ccd_text: text,
      path: currentPath,
      local_file_name: `${doc_name}.ccd`,
      ccd_lock: locked ? 'yes' : null,
    });
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    newFolderModal.hide();
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList || null,
      currentPath || null,
    );
    documentsContainer.innerHTML = html;
  } catch (error) {
    alertify.error(error.message);
  } finally {
    resetForm(form);
    newDocEditor.setData('');
  }
});
// editing documents
let editDocEditor;
live('click', 'ccd', async (_event, ccd) => {
  const caseDetailsRef = ccd.closest('.case_details_documents');
  const caseId = caseDetailsRef.dataset.caseid;
  const isList = caseDetailsRef.dataset.layout == 'List' ? true : null;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  if (!editDocEditor) {
    ClassicEditor.create(document.querySelector('#editDocEditor'))
      .then((editor) => (editDocEditor = editor))
      .catch((error) => {
        console.error(error);
      });
  }
  const editDocumentForm = document.querySelector('#editDocumentModal form');
  const doc_id = ccd.dataset.id;
  const ccdoc = await processDocuments({
    item_id: doc_id,
    action: 'open',
  });
  // if readonly, show doc
  // if not readonly, show edit form
  if (ccdoc.ccd_permissions === 'yes') {
    setFormValues(editDocumentForm, {
      caseId,
      isList,
      currentPath,
      doc_name: ccdoc.ccd_title,
      locked: ccdoc.ccd_locked == 'yes',
      ccd_id: ccdoc.ccd_id,
    });
    editDocEditor.setData(ccdoc.ccd_content);
    const editDocumentModal =
      bootstrap.Modal.getInstance('#editDocumentModal') ||
      new bootstrap.Modal('#editDocumentModal');
    editDocumentModal.show();
  } else {
    const viewCCDModal =
      bootstrap.Modal.getInstance('#viewCCDModal') ||
      new bootstrap.Modal('#viewCCDModal');
    const viewCCDLabel = document.getElementById('viewCCDLabel');
    viewCCDLabel.innerText = ccdoc.ccd_title;
    const viewCCDContent = document.getElementById('viewCCDContent');
    viewCCDContent.innerHTML = ccdoc.ccd_content;

    viewCCDModal.show();
  }
});
live('click', 'doc_edit_document_submit', async (event, button) => {
  const modal = document.querySelector('#editDocumentModal');
  const newFolderModal = bootstrap.Modal.getInstance(modal);
  const form = modal.querySelector('form');
  const text = editDocEditor.getData();
  const textarea = modal.querySelector('#editDocEditor');
  textarea.value = text;
  const values = getFormValues(form);
  const errors = checkFormValidity(form);
  const isValid = errors == true;
  if (!isValid) {
    form.classList.add('invalid');
    alertify.error(`Please provide values for ${errors}`);
    return;
  }
  const { folderName, caseId, isList, currentPath, doc_name, locked, ccd_id } =
    values;
  try {
    const response = await processDocuments({
      case_id: caseId,
      action: 'update_ccd',
      target_path: currentPath,
      selection_path: folderName,
      doc_type: 'item',
      new_folder: folderName,
      container: currentPath || null,
      ccd_name: doc_name,
      ccd_text: text,
      path: currentPath,
      local_file_name: `${doc_name}.ccd`,
      ccd_lock: locked ? 'yes' : null,
      ccd_id,
    });
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    newFolderModal.hide();
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList || null,
      currentPath || null,
    );
    documentsContainer.innerHTML = html;
  } catch (error) {
    alertify.error(error.message);
  } finally {
    resetForm(form);
    editDocEditor.setData('');
  }
});
// uploading files
live('click', 'docs_upload_file', async (_event, el) => {
  const caseDetailsRef = el.closest('.case_details_documents');
  const currentPath = caseDetailsRef.dataset.currentpath;
  const case_id = caseDetailsRef.dataset.caseid;
  const isList = caseDetailsRef.dataset.layout === 'List';
  const label = document.querySelector('#uploadFileLabel span');
  const pathArray = currentPath.split('/');
  label.innerText = pathArray[pathArray.length - 1];
  const newDocumentModal =
    bootstrap.Modal.getInstance('#uploadFileModal') ||
    new bootstrap.Modal('#uploadFileModal');
  newDocumentModal.show();
  const form = document.querySelector(`#uploadFileModal form`);
  setFormValues(form, { caseId: case_id, currentPath, isList });
});
let dropArea = document.querySelector('#dropArea');
live('dragenter', 'file-upload', async (event, el) => {
  event.preventDefault();
  event.stopPropagation();
  dropArea.classList.add('highlight');
});
live('dragleave', 'file-upload', async (event, el) => {
  event.preventDefault();
  event.stopPropagation();
  dropArea.classList.remove('highlight');
});
live('dragover', 'file-upload', async (event, el) => {
  event.preventDefault();
  event.stopPropagation();
  dropArea.classList.add('highlight');
});
live('drop', 'file-upload', async (event, el) => {
  event.preventDefault();
  event.stopPropagation();
  dropArea.classList.remove('highlight');
  let dt = event.dataTransfer;
  let files = dt.files;
  const form = el.closest('form');
  const { currentPath, caseId, isList } = getFormValues(form);
  handleDrop(files, caseId, currentPath, isList);
});
live('change', 'file-upload-input', async (event, input) => {
  let files = input.files;
  const form = input.closest('form');
  const { currentPath, caseId, isList } = getFormValues(form);
  handleDrop(files, caseId, currentPath, isList);
});

const handleDrop = async (files, case_id, path, isList) => {
  if (path === 'Home') {
    path = '';
  }
  for (const file of [...files]) {
    const res = await uploadFile(file, path, case_id);
    if (res.error) {
      alertify.error(res.error);
      return;
    }
    if (res.success) {
      alertify.success('File uploaded successfully!');
      const html = await getDocuments(
        case_id,
        null,
        true,
        isList == 'true' || null,
        path || null,
      );
      const documentsContainer = document.querySelector(
        `#nav-${case_id}-documents .case_detail_panel`,
      );
      documentsContainer.innerHTML = html;
      const newDocumentModal =
        bootstrap.Modal.getInstance('#uploadFileModal') ||
        new bootstrap.Modal('#uploadFileModal');
      newDocumentModal.hide();
    }
  }
};

// upload by url

// toggle form
live('click', 'file_or_url_switch', (_event, el) => {
  const isChecked = el.checked;
  const modal = document.querySelector('#uploadFileModal');
  if (isChecked) {
    modal.classList.add('open');
  } else {
    modal.classList.remove('open');
  }
});
live('click', 'doc_upload_file_submit', async (_event, el) => {
  const form = el.closest('form');
  const errors = checkFormValidity(form);
  const isValid = errors == true;
  if (!isValid) {
    form.classList.add('invalid');
    alertify.error(`Please provide values for ${errors}`);
    return;
  }
  const { caseId, isList, currentPath, url_name, url } = getFormValues(form);
  try {
    const response = await processDocuments({
      action: 'add_url',
      case_id: caseId,
      isList: isList === 'true' || null,
      path: currentPath == 'Home' ? '' : currentPath,
      url_name,
      url,
    });
    alertify.success('File uploaded successfully!');
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList == 'true' || null,
      path || null,
    );
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    documentsContainer.innerHTML = html;
    alertify.success('Web address added!');
  } catch (err) {
    alertify.error(err.message);
  } finally {
    const newDocumentModal =
      bootstrap.Modal.getInstance('#uploadFileModal') ||
      new bootstrap.Modal('#uploadFileModal');
    newDocumentModal.hide();
  }
});

const getFileType = (classList) => {
  if (classList.contains('folder')) {
    return 'folder';
  }
  if (classList.contains('ccd')) {
    return 'ccd';
  }
  return 'download';
};

const openContextMenu = (e) => {
  const target = e.target;
  const doc_item = target.classList.contains('doc_item')
    ? target
    : target.closest('.doc_item');
  const case_detail_panel = target.classList.contains('case_detail_panel')
    ? target
    : target.closest('.case_detail_panel');
  if (doc_item) {
    e.preventDefault();
    const { pageX, pageY } = e;
    const contextMenu = document.getElementById('contextMenu');
    contextMenu.style.display = 'block';
    contextMenu.style.left = `${pageX}px`;
    contextMenu.style.top = `${pageY}px`;
    doc_item.classList.add('selected');
    // Add case details so they're available inside the context menu
    console.log(doc_item);
    const caseDetails = contextMenu.querySelector('.context-menu-details');
    caseDetails.dataset.caseid = doc_item.dataset.caseid;
    caseDetails.dataset.id = doc_item.dataset.id;
    caseDetails.dataset.type = getFileType(doc_item.classList);
    return;
  } else {
    if (case_detail_panel) {
      e.preventDefault();
      const { pageX, pageY } = e;
      const contextMenu = document.getElementById('contextMenu');
      contextMenu.style.display = 'block';
      contextMenu.style.left = `${pageX}px`;
      contextMenu.style.top = `${pageY}px`;
      contextMenu.classList.add('non-doc');
      // Add case details so they're available inside the context menu
      const caseDetails = contextMenu.querySelector('.context-menu-details');
      caseDetails.dataset.caseid = e.target.closest(
        '.case_details_documents',
      ).dataset.caseid;
      console.log(caseDetails.dataset);
      caseDetails.dataset.id = '';
      caseDetails.dataset.type = '';
    }
  }
  // Nina also open when no doc_item if we're in a case_detail_panel
  // for pasting
};
const hideContextMenu = () => {
  const contextMenu = document.getElementById('contextMenu');
  contextMenu.style.display = 'none';
  contextMenu.classList.remove('non-doc');
  const doc_items = document.querySelectorAll('.doc_item');
  doc_items.forEach((item) => item.classList.remove('selected'));
};
document.oncontextmenu = openContextMenu;
document.addEventListener('click', (e) => {
  const target = e.target;
  const contextMenu = target.classList.contains('context-menu')
    ? target
    : target.closest('.context-menu');
  if (!contextMenu) {
    hideContextMenu();
  }
});

// Open file from context menu
live('click', 'context-menu-open', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  let clickItem;
  if (type === 'download') {
    clickItem = document.querySelector(
      `[data-id="${id}"][data-caseid="${caseid}"] a`,
    );
  } else {
    clickItem = document.querySelector(
      `.doc_item[data-id="${id}"][data-caseid="${caseid}"]`,
    );
  }
  if (clickItem) {
    clickItem.click();
  }
});
// Cut file from context menu
live('click', 'context-menu-cut', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  const doc_item = document.querySelector(
    `[data-id="${id}"][data-caseid="${caseid}"]`,
  );
  const { path } = doc_item.dataset;
  const case_details_documents = doc_item.closest('.case_detail_panel');
  // Store cut data
  const cut_data = new Array(id, type, path, caseid);
  case_details_documents.dataset.cutdata = cut_data;
  case_details_documents.dataset.copydata = '';
  hideContextMenu();
});
// Copy file from context menu
live('click', 'context-menu-copy', (e) => {
  // Nina - don't allow this if it's a folder
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  const doc_item = document.querySelector(
    `[data-id="${id}"][data-caseid="${caseid}"]`,
  );
  const { path } = doc_item.dataset;
  const case_details_documents = doc_item.closest('.case_detail_panel');
  // Store cut data
  const cut_data = new Array(id, type, path, caseid);
  case_details_documents.dataset.copydata = cut_data;
  case_details_documents.dataset.cutdata = '';
});
// Paste file from context menu
live('click', 'context-menu-paste', async (e) => {
  const details = e.target.closest('.context-menu-details');
  const { caseid } = details.dataset;
  const caseDetails = document.querySelector(
    `.case_details_documents[data-caseid='${caseid}']`,
  );
  const caseDetailPanel = document.querySelector(
    `.case_details_documents[data-caseid='${caseid}'] .case_detail_panel`,
  );
  const { currentPath, layout } = caseDetails.dataset;
  const { cutdata } = caseDetailPanel.dataset;
  const [item_id, doc_type, selection_path, case_id] = cutdata.split(',');
  try {
    const res = await processDocuments({
      case_id,
      action: 'cut',
      item_id,
      target_path: currentPath,
      selection_path,
      doc_type,
    });
    const isList = layout === 'List' ? true : null;
    const html = await getDocuments(case_id, null, true, isList, null);
    const documentsContainer = document.querySelector(
      `#nav-${case_id}-documents .case_detail_panel`,
    );
    documentsContainer.innerHTML = html;
  } catch (err) {
    alertify.error(err.message);
  }
});
// Rename file from context menu
live('click', 'context-menu-rename', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type: docType, id: itemId, caseid } = details.dataset;
  const renameFileModal =
    bootstrap.Modal.getInstance('#renameFileModal') ||
    new bootstrap.Modal('#renameFileModal');
  const docItem = document.querySelector(`.doc_item[data-id="${itemId}"]`);
  let { filename, type } = docItem.dataset;
  const fileName = filename.replace(`.${type}`, '');
  const renameFileForm = document.querySelector('#renameFileModal form');
  const caseDetailPanel = document.querySelector(
    `.case_details_documents[data-caseid="${caseid}"]`,
  );
  const { currentpath: currentPath, layout } = caseDetailPanel.dataset;
  const isList = layout === 'List';
  setFormValues(renameFileForm, {
    caseId: caseid,
    isList,
    currentPath,
    itemId,
    docType,
    fileName,
    fileType: type,
  });
  renameFileModal.show();
});
// Rename file -- listen to form submit
live('click', 'doc_rename_file_submit', async (e) => {
  const form = document.querySelector('#renameFileModal form');
  const isValid = checkFormValidity(form);
  if (isValid != true) {
    form.classList.add('invalid');
    alertify.error('Please provide a file name.');
    return;
  }
  const { caseId, isList, currentPath, itemId, docType, fileType, fileName } =
    getFormValues(form);
  const new_name =
    docType == 'ccd' || docType == 'url' || docType == 'folder'
      ? fileName
      : `${fileName}.${fileType}`;

  console.log({ new_name });
  try {
    const res = await processDocuments({
      action: 'rename',
      new_name,
      item_id: itemId,
      doc_type: docType,
      path: currentPath,
      case_id: caseId,
    });
    if (res.error) {
      throw new Error(res.message);
    }
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList == 'true' || null,
      null,
    );
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    documentsContainer.innerHTML = html;
    const renameFileModal =
      bootstrap.Modal.getInstance('#renameFileModal') ||
      new bootstrap.Modal('#renameFileModal');
    renameFileModal.hide();
  } catch (err) {
    console.log(err.message);
    alertify.error(err.message);
  }
});

// Delete file from context menu
live('click', 'context-menu-delete', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  const caseDetailPanel = document.querySelector(
    `.case_details_documents[data-caseid="${caseid}"]`,
  );
  const folder = document.querySelector(`.doc_item.folder[data-id="${id}"]`);
  const { path: folderPath } = folder?.dataset || {};
  const { currentpath: currentPath, layout } = caseDetailPanel.dataset;
  const isList = layout === 'List';
  alertify.confirm(
    'Confirm',
    type == 'folder'
      ? 'This folder and all of its contents will be permanently deleted from the server. Are you sure you want to delete it?'
      : `This item will be permanently deleted from the server.  Are you sure?`,
    async function () {
      try {
        await processDocuments({
          action: 'delete',
          item_id: id,
          doc_type: type,
          path: folder ? folderPath : currentPath,
          case_id: caseid,
        });
        const html = await getDocuments(
          caseid,
          null,
          true,
          isList == true || null,
          null,
        );
        const documentsContainer = document.querySelector(
          `#nav-${caseid}-documents .case_detail_panel`,
        );
        documentsContainer.innerHTML = html;
        alertify.success('File deleted');
      } catch (err) {
        alertify.error('Error deleting file.');
      }
    },
    function () {},
  );
});
// Properties file from context menu
live('click', 'context-menu-properties', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
});
// delete file
// drag and drop on list
// save preferred docs view to cookies
// load docs based on cookies
// mobile design
