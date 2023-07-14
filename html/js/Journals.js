// //Scripts for journals

import {
  fetchJournals,
  loadJournal,
  processJournal,
} from '../../lib/javascripts/axios.js';
import { getModal } from '../../lib/javascripts/modal.js';
import { checkFormValidity, getFormValues, resetForm } from './forms.js';
import { getClosest, live } from './live.js';
// /* global notify, elPrint, ColReorder, router, rte_toolbar  */
// var oTable;

// $(document).ready(function() {
// 	var tableHeight = $('#content').height() - 120;
// 	var chooserVal = 'unread';
// 	oTable = $('#table_journals').dataTable({
// 		'sAjaxSource': 'lib/php/data/journals_load.php',
// 		'aoColumns':
//         [
//             { 'sTitle' : '','bSortable' : false,'sWidth' : '40px'},
//             { 'sTitle' : 'Id','bSearchable' : false,'bVisible' : false},
//             { 'sTitle' : 'Name'},
//             { 'sTitle' : 'Submitted To','bSearchable' : true,'bVisible' : false},
//             { 'sTitle' : 'Text','bVisible' : false},
//             { 'sTitle' : 'Date Submitted','sType': 'date'},
//             { 'sTitle' : 'Archived','bVisible' : false},
//             { 'sTitle' : 'Read','bVisible' : false},
//             { 'sTitle' : 'Commented','bVisible' : false},
//             { 'sTitle' : 'Comments','bSearchable' : false,'bVisible' : false}
//         ],
//         "aoColumnDefs": [ //escape html
//             {
//                 "fnRender": function ( o ) {
//                 return String(o.aData[o.iDataColumn])
//                     .replace(/&/g, '&amp;')
//                     .replace(/"/g, '&quot;')
//                     .replace(/'/g, '&#39;')
//                     .replace(/</g, '&lt;')
//                     .replace(/>/g, '&gt;');

//                 },
//                 "aTargets": [2,3,4,5,6,7,8,9]
//             }
//         ],
//         'oColVis': {'aiExclude': [0,1],'bRestore': true,'buttonText': 'Columns'},
//         'oTableTools': {
//             'aButtons': [{
//                 'sExtends':'text',
//                 'sButtonText':'Reset',
//                 'sButtonClass':'DTTT_button_reset',
//                 'sButtonClassHover':'DTTT_button_reset_hover'
//             },
//             {
//                 'sExtends':'text',
//                 'sButtonText':'New Journal',
//                 'sButtonClass':'DTTT_button_new_case',
//                 'sButtonClassHover':'DTTT_button_new_case_hover'
//             }]
//         },
//         'aaSorting': [[5, 'desc']],
//         'bDeferRender': true,
// 		'bAutoWidth':false,
// 		'bProcessing': true,
// 		'bJQueryUI': true,
// 		'bScrollInfinite': true,
// 		'sScrollY':tableHeight,
// 		'iDisplayLength': 30,
// 		'iScrollLoadGap':200,
// 		'bSortCellsTop': true,
// 		'sDom': 'R<"H"fTCi>rt<"F"<"journal_action">>',
// 		'oLanguage': {
//             'sInfo': 'Showing <b>_TOTAL_</b> <span id="journalStatus"></span> journals',
//             'sZeroRecords':'No <span id="journalStatus"></span> journals found.',
//             'sInfoEmpty':'Showing 0 journals',
//             'sInfoFiltered': 'from a total of <b>_MAX_</b>',
//             'sEmptyTable': 'No journals found.'
//         },
// 		'fnInitComplete':function(){
// 			//Hide processing div
// 			$('#processing').hide();

// 			//Have ColVis and reset buttons pick up the DTTT class
//             $('div.ColVis button').removeClass()
//             .addClass('DTTT_button DTTT_button_collection ui-button ui-state-default');

//              //Add journal action selector
//             $('div.journal_action').html('<label>With displayed journals:</label><select id="journal_action_chooser">' +
//             '<option value="" selected=selected disabled>Choose Action</option><option value="archive">Archive</option>' +
//             '<option value="mark_read">Mark Read</option><option value="mark_unread">Mark Unread</option></select>');

//             //Bulk actions
//             $('#journal_action_chooser').change(function(){
//                 var filteredData = oTable.fnGetFilteredData();
//                 var affectedJournals = [];
//                 var action = $(this).val();
//                 var actionText = action.replace('_',' as ');

//                 //Loop through filtered data to get user ids
//                 $.each(filteredData, function() {
//                     affectedJournals.push($(this)[1]);
//                 });

//                 var dialogWin = $('<div title="Are you sure?">This will ' + actionText + ' ' +
//                 filteredData.length + ' journals.  Are you sure you want to do that?</div>')
//                 .dialog({
//                     autoOpen: false,
//                     resizable: false,
//                     modal: true,
//                     buttons: {
//                         'Yes': function() {
//                             $.post('lib/php/data/journals_process.php', {
//                                 'type': action,
//                                 'id': affectedJournals
//                             }, function(data) {
//                                 var serverResponse = $.parseJSON(data);
//                                 if (serverResponse.error === true) {
//                                     notify(serverResponse.message, true);
//                                 } else {
//                                     notify(serverResponse.message);
//                                     oTable.fnReloadAjax();
//                                     fnResetAllFilters();
//                                     chooserVal = 'unread';
//                                 }
//                             });
//                             $(this).dialog('destroy');
//                         },
//                         'No': function() {
//                             $(this).dialog('destroy');
//                         }
//                     }
//                 });
//                 $(dialogWin).dialog('open');
//             });

// 			//Add view chooser
//             $('div.dataTables_filter').append('<select id="chooser"><option value="unread" selected=selected>' +
//             'Unread</option><option value="read">Read</option><option value="archived">Archived</option>' +
//             '<option value="all">All</option></select>');

//             //Change the journal status select
//             $('#chooser').live('change', function() {
//                 switch ($(this).val()) {
//                     case 'unread':
//                         chooserVal = 'unread';
// 						oTable.fnFilter('^$', oTable.fnGetColumnIndex('Read'), true, false);
//                         oTable.fnFilter('', oTable.fnGetColumnIndex('Archived'));
//                         break;

//                     case 'read':
//                         chooserVal = 'read';
//                         oTable.fnFilter('yes', oTable.fnGetColumnIndex('Read'), true, false);
//                         oTable.fnFilter('', oTable.fnGetColumnIndex('Archived'));
//                         break;

//                     case 'archived':
//                         chooserVal = 'archived';
//                         oTable.fnFilter('yes', oTable.fnGetColumnIndex('Archived'));
//                         oTable.fnFilter('', oTable.fnGetColumnIndex('Read'));
//                         break;
//                     case 'all':
//                         chooserVal = 'all';
//                         oTable.fnFilter('', oTable.fnGetColumnIndex('Archived'), true, false);
//                         oTable.fnFilter('', oTable.fnGetColumnIndex('Read'), true, false);
//                         break;
//                 }
//             });

//             //Apply default filter - unread and unarchived
//             oTable.fnFilter('^$', oTable.fnGetColumnIndex('Read'), true, false);
//             oTable.fnFilter('^$', oTable.fnGetColumnIndex('Archived'), true, false);

//             //Have ColVis and reset buttons pick up the DTTT class
//             $('div.ColVis button').removeClass().addClass('DTTT_button DTTT_button_collection ui-button ui-state-default');

//             //Event for reset button
//             $('#ToolTables_table_journals_0').click(function() { //reset button
//                 fnResetAllFilters();
//             });

//             //Check if user can add journals; if not, remove new journal button
//             if (!$('#table_journals').hasClass('can_add')) {
//                 $('#ToolTables_table_journals_1').remove();
//             } else { //add listener
//                 $('#ToolTables_table_journals_1').click(function(){
//                 //Add new row to cm_journals table
//                     $.post('lib/php/data/journals_process.php',{'type': 'new'},function(data){
//                         var serverResponse = $.parseJSON(data);
//                         if (serverResponse.error === true) {
//                             notify(serverResponse.message, true);
//                         } else {
//                             var newId = serverResponse.newId;
//                             callJournal(newId,true,true);//true for edit,true for new
//                         }
//                     });
//                 });
//             }

//             //Listen for click on table row; open journal
//             $('#table_journals tbody').click(function(event) {
//                 var iPos = oTable.fnGetPosition(event.target.parentNode);
//                 var aData = oTable.fnGetData(iPos);
//                 var iId = aData[1];
//                 callJournal(iId,false,false);

//             });

//             //resizes the table whenever parent element size changes
// 			$(window).bind('resize', function() {
// 				oTable.fnDraw(false);
// 				oTable.fnAdjustColumnSizing();
// 			});

//             //check hash; see if we need to open a journal
//             router();

// 		},
// 		'fnDrawCallback':function(){
// 			$('#journalStatus').text(chooserVal);
// 		}
//     });
// });

// function callJournal(id,edit,newJournal) {
//     //Define html for journal window
//     var journalId = [];
//     journalId.push(id);

//     if ($('div#journal_detail_window').length < 1) {
//         var journalDetail = '<div id="journal_detail_window"></div>';
//         $('#content').append(journalDetail);
//     }

//     if (edit === true) {//we are editing or writing a new journal
//         $('#journal_detail_window').load('lib/php/data/journals_detail_load.php', {
//             'id': journalId,
//             'view':'edit'
//         }, function() {
//             $(this).show('fold', 1000,function(){
//                 //Create lwrte
//                 var arr = $(this).find('.journal_edit').rte({
//                     css: ['lib/javascripts/lwrte/default2.css'],
//                     width: 900,
//                     height: 500,
//                     controls_rte: rte_toolbar
//                 });

//                 //auto-save
//                 var lastText = '';
//                 var editor = $('#journal_detail_window');
//                 function autoSave(lastText, arr) {
//                     var text = arr[0].get_content();
//                     var readers = $('select[name="reader_select[]"]').val();
//                     var status = 'Saving...';
//                     if (text !== lastText) {
//                         editor.find('span.save_status').html(status);
//                         $.post('lib/php/data/journals_process.php', {
//                             'type': 'edit',
//                             'id': journalId,
//                             'text': text,
//                             'readers':readers
//                         }, function(data) {
//                             var serverResponse = $.parseJSON(data);
//                             if (serverResponse.error) {
//                                 editor.find('span.save_status').html(serverResponse.message);
//                             } else {
//                                 editor.find('span.save_status').html(serverResponse.message);
//                             }
//                         });
//                         lastText = text;
//                     }
//                     var t = setTimeout(function() {
//                         autoSave(lastText, arr);
//                     }, 3000);
//                 }
//                 autoSave(lastText, arr);
//             });

//             //Add event to prevent submitting journal without a reader
//             $(window).bind('beforeunload', function() {
//                 var rSelect = $('select[name="reader_select[]"]');
//                 if (rSelect.length > 0 && rSelect.val() === null) {
//                     rSelect.parent().find('label').first().addClass('ui-state-error');
//                     return 'You haven\'t specified who is supposed to read this journal.' +
//                     'Please select a reader in the box below.';
//                 }
//             });

//             $('button.journal_close') .button({
//                 icons: {primary: 'fff-icon-cancel'},
//                 label: 'Close'
//             })
//             .click(function() {
//                 var rSelect = $('select[name="reader_select[]"]');
//                 if (rSelect.length > 0 && rSelect.val() === null) {
//                     notify('<p>Please select the users to whom this journal is to be sent.</p>',true);
//                     rSelect.parent().find('label').first().addClass('ui-state-error');
//                     return false;
//                 } else {
//                     oTable.fnReloadAjax();
//                     $('#journal_detail_window').hide('fold', 1000);
//                 }
//             });

//             //Add chosen to select
//             $('select[name="reader_select[]"]').chosen().change(function(){
//                 if ($('input[name="remember_choice"]').is(':checked')) {
//                     var choice = $('select[name="reader_select[]"]').val();
//                     $.cookie('ClinicCases_journal', choice,{expires:365});
//                 }

//                 //Update readers on change
//                 var readers = $('select[name="reader_select[]"]').val();
//                 $.post('lib/php/data/journals_process.php', {
//                     'type': 'update_readers',
//                     'id': journalId,
//                     'readers':readers
//                 });
//             });

//             //Set reader values if previously remembered
//             if (newJournal === true) {
//                 if ($.cookie('ClinicCases_journal') !== null) {
//                     var setVals = $.cookie('ClinicCases_journal').split(',');
//                     $('select[name="reader_select[]"]').val(setVals);
//                     $('select[name="reader_select[]"]').trigger('liszt:updated');
//                     $('input[name = "remember_choice"]').attr('checked','checked');
//                 }
//             }
//             else if(newJournal === false && edit === true) {
//                 if ($.cookie('ClinicCases_journal') !== null) {
//                     $('input[name = "remember_choice"]').attr('checked','checked');
//                 }
//             }

//             //Remember names of journal readers.
//             $('input[name = "remember_choice"]').change(function(){
//                 var choice = $('select[name="reader_select[]"]').val();
//                 if ($(this).is(':checked')) {
//                     $.cookie('ClinicCases_journal', choice,{expires:365});
//                 } else {
//                     //check for cookie; if exists, delete
//                     if ($.cookie('ClinicCases_journal') !== null) {
//                         $.cookie('ClinicCases_journal',null);
//                     }
//                 }
//             });
//         });
//     } else {//we are viewing a journal
//         $('#journal_detail_window').load('lib/php/data/journals_detail_load.php', {
//             'id': journalId
//         }, function() {
//             $(this).show('fold', 1000);

//             //Mark journal as read
//             $.post('lib/php/data/journals_process.php',{'id':journalId,'type':'mark_read'},function(data){
//                 var serverResponse = $.parseJSON(data);
//                 if (serverResponse.error === true) {
//                     notify(serverResponse.message);
//                 }
//             });

//             //Define and listen for window buttons
//             if ($('button.journal_delete').length) {
//                 $('button.journal_delete').button({icons: {primary: 'fff-icon-page-delete'}})
//                     .click(function() {
//                     var dialogWin = $('<div title="Are you sure?"><p>Permanently delete this journal?</p></div>')
//                     .dialog({
//                         autoOpen: false,
//                         resizable: false,
//                         modal: true,
//                         buttons: {
//                             'Yes': function() {
//                                 $.post('lib/php/data/journals_process.php',{'id':journalId,'type':'delete'},function(data){
//                                     var serverResponse = $.parseJSON(data);
//                                     if (serverResponse.error === true) {
//                                         notify(serverResponse.message, true);
//                                     } else {
//                                         notify(serverResponse.message);
//                                         oTable.fnReloadAjax();
//                                         $('#journal_detail_window').hide('fold', 1000);
//                                     }
//                                 });
//                                 $(this).dialog('destroy');
//                             },
//                             'No': function() {
//                                 $(this).dialog('destroy');
//                             }
//                         }

//                     });
//                     $(dialogWin).dialog('open');
//                 });

//             }

//             if ($('button.journal_edit').length) {
//                 $('button.journal_edit').button({icons: {primary: 'fff-icon-page-edit'}})
//                 .click(function() {
//                     callJournal(journalId[0],true,false);
//                 });
//             }

//             if ($('button.journal_print').length) {
//                 $('button.journal_print').button({icons: {primary: 'fff-icon-printer'}})
//                 .click(function() {
//                     elPrint($('div.journal_detail'),'Journal');
//                 });
//             }

//             $('button.journal_close').button({
//                 icons: {primary: 'fff-icon-cancel'},
//                 label: 'Close'
//             })
//             .click(function() {
//                 oTable.fnReloadAjax();
//                 $('#journal_detail_window').hide('fold', 1000);
//             });

//             //Handle textareas
//             $('textarea.expand').livequery(function(){
//                 $(this).TextAreaExpander(40,300).css({'color':'#AAA'}).bind('focus',function() {
//                     $(this).val('').css({'color':'black'}).unbind('focus');
//                 });
//             });
//         });
//     }
// }

// function fnResetAllFilters() {
//     var oSettings = oTable.fnSettings();

//     //reset the main filter
//     oTable.fnFilter('');

//     //reset the columns to their original order.
//     ColReorder.fnReset(oTable);

//     //reset the user display for inputs and selects
//     $('input').each(function() {
//         this.value = '';
//     });

//     $('select').each(function() {
//         this.selectedIndex = '0';
//     });

//     //return to default unread filter
//     oTable.fnFilter('^$', oTable.fnGetColumnIndex('Read'), true, false);
//     var chooserVal = 'open';

//     //return to default sort - Date Submitted
//     oTable.fnSort([[oTable.fnGetColumnIndex('Date Submitted'), 'desc']]);

//     //redraw the table so that all columns line up
//     oTable.fnDraw();
// }

// //Listeners

// //Save comments
// $('a.comment_save').live('click', function(event){
//     event.preventDefault();
//     var journalId = [];
//     journalId.push($(this).closest('div.journal_body').attr('data-id'));
//     var commentText = $(this).siblings('textarea').val();
//     $.post('lib/php/data/journals_process.php',{
//         'type': 'add_comment',
//         'id':journalId,
//         'comment_text':commentText
//     },function(data){
//         var serverResponse = $.parseJSON(data);
//         if (serverResponse.error === true) {
//             notify(serverResponse.message);
//         } else {
//             $('div.journal_comments').load('lib/php/data/journals_detail_load.php div.journal_comments', {'id': journalId});
//             notify(serverResponse.message);
//         }
//     });
// });

// //Delete comments
// $('a.comment_delete').live('click',function(event){
//     event.preventDefault();
//     var commentId = $(this).parent().attr('data-id');
//     var journalId = [];
//     journalId.push($(this).closest('div.journal_body').attr('data-id'));
//     var dialogWin = $('<div title="Are you sure?"><p>Delete this comment?</p></div>').dialog({
//         autoOpen: false,
//         resizable: false,
//         modal: true,
//         buttons: {
//             'Yes': function() {
//                 $.post('lib/php/data/journals_process.php',{
//                     'type': 'delete_comment',
//                     'id':journalId,
//                     'comment_id':commentId
//                 },function(data){
//                     var serverResponse = $.parseJSON(data);
//                     if (serverResponse.error === true) {
//                         notify(serverResponse.message);
//                     } else {
//                         $('div.journal_comments').load('lib/php/data/journals_detail_load.php div.journal_comments', {
//                             'id': journalId
//                         });
//                         notify(serverResponse.message);
//                     }
//                 });
//                 $(this).dialog('destroy');
//             },
//             'No': function() {
//                 $(this).dialog('destroy');
//             }
//         }
//     });
//     $(dialogWin).dialog('open');
// });

// //Add delete listener
// $(function() {
//     $('div.comment').livequery(function(){
//         if ($(this).hasClass('can_delete')) {
//             $(this).mouseover(function(){
//                 $(this).find('a.comment_delete').show();
//             })
//             .mouseout(function(){
//                 $(this).find('a.comment_delete').hide();
//             });
//         }
//     });
// });

let table;
let ckEditor;
let editCkEditor;
let readerEl;
let editReaderEl = document.querySelector('.edit_reader_select');
let readerSlimSelect = new SlimSelect({
  select: '.reader_select',
});
let editReaderSlimSelect = editReaderEl
  ? new SlimSelect({
      select: '.edit_reader_select',
    })
  : null;
ClassicEditor.create(document.querySelector('#editor'))
  .then((editor) => {
    ckEditor = editor;
  })
  .catch((error) => {
    console.error(error);
  });

ClassicEditor.create(document.querySelector('#editEditor'))
  .then((editor) => {
    editCkEditor = editor;
  })
  .catch((error) => {
    console.error(error);
  });
const resetAltInputs = () => {
  readerSlimSelect.setSelected([]);
  ckEditor.setData('');
};
const reloadJournals = async () => {
  const table_journals = document.querySelector('#table_journals');
  table_journals.innerHTML = '';
  initJournalsTable();
};

const setUpRowClick = () => {
  table.onRowClick((e) => {
    const dataset = e.target.dataset;
    // if the td is a header, we don't want to  perform
    // this action
    if (!dataset.header) {
      const urlParams = new URLSearchParams(window.location.search);
      urlParams.set(
        'journal_id',
        getClosest(e.target, 'table__cell')?.dataset?.journalid,
      );
      window.location.search = urlParams.toString();
    }
  });
};
const initJournalsTable = async () => {
  const journals = await fetchJournals();
  const canAddButton = createCanAddJournalButton();

  const aoColumns = [
    { name: 'Id', hidden: true, type: 'text', fieldName: 'id' },
    {
      name: 'Name',
      type: 'text',
      fieldName: 'username',
    },
    {
      name: 'Date Submitted',
      type: 'date',
      fieldName: 'date_added',
    },
    { name: 'Archived', hidden: true, type: 'text', fieldName: 'archived' },
    { name: 'Commented', hidden: true, type: 'text', fieldName: 'commented' },
    { name: 'Comments', hidden: true, type: 'text', fieldName: 'comments' },
    { name: 'Read', hidden: true, type: 'text', fieldName: 'read' },
    { name: 'Reader', hidden: true, type: 'text', fieldName: 'reader' },
    { name: 'Text', hidden: true, type: 'text', fieldName: 'text' },
  ];

  //     const bulkActionsEl = document.createElement('div');
  //     bulkActionsEl.innerHTML = `<div class="actions">
  //     <div class="form__control form__control--select">
  //       <select name="" class="bulk_action" id="">
  //         <option value="" selected>With displayed users</option>
  //         <option value="activate">Activate</option>
  //         <option value="deactivate">Deactivate</option>
  //       </select>
  //     </div>
  //   </div>`;

  // Custom table plugin initiation
  table = new Table({
    columns: aoColumns,
    data: journals.aaData,
    containerId: '#table_journals',
    facets: [
      {
        label: 'Unread',
        value: 'active',
        field: 'status',
        filter: (item) => {
          console.log(item.read == false);
          return item.read == false;
        },
        default: true,
      },
      {
        label: 'Read',
        value: 'inactive',
        field: 'status',
        filter: (item) => {
          return item.read == true;
        },
      },
      {
        label: 'Archived',
        value: 'all',
        field: 'status',
        filter: () => {
          return item.archived == true;
        },
      },
      {
        label: 'All',
        value: 'all',
        field: 'status',
        filter: () => {
          return true;
        },
      },
    ],
    tableName: 'Journals',
    tableNameSingular: 'Journal',
    canAddButton,
    // customActions: [bulkActionsEl],
  });
  setUpRowClick();
};

const cancelJournalButton = document.querySelector('.new_journal_cancel');
cancelJournalButton.addEventListener('click', (e) => {
  e.preventDefault();
  const id = 'newJournalModal';
  const modal = getModal(`#${id}`);
  const form = document.querySelector(`#${id} form`);

  alertify.confirm(
    'Confirm',
    'Are you sure you want to cancel? You will lose all of your data.',
    () => {
      resetForm(form);
      resetAltInputs();
      modal.hide();
    },
    null,
  );
});

const submitJournalButton = document.querySelector('.new_journal_submit');
submitJournalButton.addEventListener('click', async (e) => {
  e.preventDefault();
  const form = document.querySelector('#newJournalModal form');
  const isValid = checkFormValidity(form);
  const content = ckEditor.getData();
  const editorEl = document.querySelector('#newJournalModal .ck-editor');
  const readers = readerSlimSelect.selected();
  if (isValid != true || !content || !readers.length) {
    form.classList.add('invalid');
    if (!content) {
      editorEl.classList.add('invalid');
    }
    if (!readers.length) {
      readerEl.classList.add('invalid');
    }
  } else {
    form.classList.remove('invalid');
    editorEl.classList.remove('invalid');
    readerEl.classList.remove('invalid');
  }
  const values = getFormValues(form);
  values.text = content;
  try {
    const res = await processJournal({ type: 'new', ...values });
    if (res.error) {
      throw new Error(res.message || 'Error creating journal.');
    }
    const { newId } = res;
    const res2 = await processJournal({
      type: 'update_readers',
      readers: Array.isArray(readers) ? `${readers},` : [readers],
      id: newId,
    });

    if (res2.error) {
      throw new Error(res.message || 'Error creating journal.');
    }
    alertify.success('Journal created!');
    await reloadJournals();
    const modal = getModal('#newJournalModal');
    modal.hide();
    resetForm(form);
    resetAltInputs();
  } catch (err) {
    alertify.error(err.message);
  }
});

const createCanAddJournalButton = () => {
  const button = document.createElement('button');
  button.setAttribute('data-bs-toggle', 'modal');
  button.setAttribute('data-bs-target', '#newJournalModal');
  button.classList.add('primary-button');
  button.setAttribute('type', 'button');
  button.setAttribute('id', 'addButton');
  button.innerText = '+ Add Journal';

  return button;
};
document.addEventListener('DOMContentLoaded', async () => {
  initJournalsTable();
  readerEl = document.querySelector('.reader_select');
  const journalId = new URLSearchParams(window.location.search).get(
    'journal_id',
  );
  if (journalId) {
    const res = await processJournal({ type: 'mark_read', id: [journalId] });
  }
});

const backToJournals = () => {
  const urlParams = new URLSearchParams(window.location.search);
  urlParams.delete('journal_id');
  window.location.search = urlParams.toString();
};
live('click', 'comment_save', async (_e, el) => {
  const formSelector = el.dataset.target;
  const journalId = el.dataset.id;
  const form = document.querySelector(formSelector);
  const isValid = form.validate();
  if (!isValid) return;

  const { comment_text } = getFormValues(form);
  try {
    const res = await processJournal({
      type: 'add_comment',
      comment_text,
      id: journalId,
    });
    if (res.error) {
      throw new Error('Error saving comment.');
    }

    alertify.success('Comment saved!');
    // TODO figure out how to inject single new comment
    window.location.reload();
  } catch (err) {
    alertify.error(err.message);
  }
});

live('click', 'journal_delete', (e, el) => {
  e.preventDefault();
  alertify.confirm(
    'Confirm',
    'Are you sure you want to delete this journal? You cannot undo this action.',
    async () => {
      try {
        const id = el.dataset.id;
        const res = await processJournal({
          type: 'delete',
          id,
        });
        if (res.error) {
          throw new Error(res.message);
        }
        backToJournals();
      } catch (err) {
        alertify.error(err.message || 'Error deleting journal');
      }
    },
    null,
  );
});

live('click', 'back_to_journals', backToJournals);

live('click', 'comment_delete', (e, el) => {
  e.preventDefault();
  alertify.confirm(
    'Confirm',
    'Are you sure you want to delete this comment? You cannot undo this action.',
    async () => {
      try {
        const comment_id = el.dataset.comment_id;
        const journal_id = el.dataset.journal_id;
        const res = await processJournal({
          type: 'delete_comment',
          comment_id,
          id: journal_id,
        });
        if (res.error) {
          throw new Error(res.message);
        }
        alertify.success(res.message);
        window.location.reload();
      } catch (err) {
        alertify.error(err.message || 'Error deleting journal');
      }
    },
    null,
  );
});

live('click', 'journal_edit', () => {
  const urlParams = new URLSearchParams(window.location.search);
  urlParams.set('view', 'edit');
  window.location.search = urlParams.toString();
});

const cancelEdit = () => {
  const urlParams = new URLSearchParams(window.location.search);
  urlParams.delete('view');
  window.location.search = urlParams.toString();
};
live('click', 'edit_journal_cancel', (e) => {
  e.preventDefault();
  alertify.confirm(
    'Confirm',
    'Are you sure you want to cancel? You will lose your progress',
    cancelEdit,
    null,
  );
});

const submitEditJournalButton = document.querySelector('.edit_journal_submit');
submitEditJournalButton?.addEventListener('click', async (e) => {
  e.preventDefault();
  const form = document.querySelector('#editJournalForm');
  const content = editCkEditor.getData();
  const editorEl = document.querySelector('#editJournalForm .ck-editor');
  const readers = editReaderSlimSelect.selected();
  const isValid = form.validate([
    { value: content, name: 'text', el: editorEl },
    { value: readers, name: 'readers', el: editReaderEl },
  ]);

  if (!isValid) return;
  const values = getFormValues(form);
  values.text = content;
  values.readers = readers;
  try {
    const res = await processJournal({ type: 'edit', ...values });
    console.log({ res });
    if (res.error) {
      throw new Error(res.message || 'Error editing journal.');
    }

    alertify.success('Journal edited!');
    cancelEdit();
  } catch (err) {
    alertify.error(err.message);
  }
});
