// //Scripts for utilities page

import {
  loadReportsActvities,
  loadTimeReports,
} from '../../lib/javascripts/axios.js';
import { getFormValues } from './forms.js';

// /* global loadCaseNotes, notify */

// var oTable;

// function convertToHours(totalTime, unit) {
//     //This is a javascript port of the php function
//     //convert_to_hours in convert_times.php
//     var hours = Math.floor(totalTime / 3600);
//     var minutes = totalTime - (hours * 3600);
//     var minutes2 = minutes / 60;
//     var minFormat = (Math.round(minutes2 / unit) * unit) / 100;
//     var minFormatFixed = minFormat.toFixed(2);
//     var minVal = (minFormatFixed + '').split('.');
//     return hours + '.' + minVal[1];
// }

// $(document).ready(function () {

//     //set header widget
//     $('#utilities_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

//     //Add navigation buttons
//     $('.utilities_nav_choices').buttonset();

//     //Add navigation actions
//     var target = $('div#utilities_panel');

//     //User clicks reports button
//     $('#reports_button').click(function () {

//         $('#utilities_panel').load('index.php?i=Utilities.php #report_chooser', function () {
//             //Add chosen
//             $('select[name="type"]').chosen();

//             //Add datepickers
//             $('input[name="date_start_display"]').datepicker({
//                 dateFormat : 'DD, MM d, yy',
//                 altField: $('input[name = "date_start"]'),
//                 altFormat: 'yy-mm-dd'
//             });

//             $('input[name="date_start_display"]').datepicker('setDate', '-7');

//             $('input[name="date_end_display"]').datepicker({
//                 dateFormat : 'DD, MM d, yy',
//                 altField: $('input[name = "date_end"]'),
//                 altFormat: 'yy-mm-dd'
//             });

//             $('input[name="date_end_display"]').datepicker('setDate', new Date());

//             //Create a table
//             $('#report_chooser').after('<table cellpadding="0" cellspacing="0" border="0" ' +
//             'class="display" id="table_reports"><tfoot><tr></tr></tfoot></table>');

//             //Dynamically create dataTable, load data
//             $('button.report_submit').live('click', function (event) {
//                 event.preventDefault();
//                 var tableHeight = $('#utilities_panel').height() - $('#report_chooser').height() - 150;
//                 if ($('#table_reports tfoot th').length) {
//                     $('#table_reports').dataTable().fnDestroy();
//                     $('#table_reports tfoot tr').empty();
//                 }

//                 if ($('thead th').length) {
//                     $('thead th').remove();
//                 }

//                 var q_type = $('select[name="type"]').val();
//                 var start = $('input[name="date_start"]').val();
//                 var end = $('input[name="date_end"]').val();
//                 var detail = $('input[name="detail"]').val();
//                 var query = [];

//                 if (q_type.indexOf('_grp_') !== -1) { //user groups
//                     query.push('grp',q_type);
//                 } else if (q_type.indexOf('_spv_') !== -1) { //supervisor groups
//                     query.push('supvsr_grp',q_type);
//                 } else if(q_type.indexOf('_cse_') !== -1) { //case
//                     query.push('case',q_type);
//                 } else {
//                     query.push('user',q_type);//single user
//                 }

//                 //Load data from server
//                 $.ajax({
//                     url: 'lib/php/data/utilities_reports_load.php?type=' + query[0] +
//                                 '&columns_only=true',
//                     dataType: 'json',
//                     type: 'get',
//                     error: function () {
//                         return true;
//                     },
//                     success: function (data) {
//                         if (data) {
//                             //Add footer to html
//                             for (var i = 0; i < data.aoColumns.length; i++) {
//                                 $('#table_reports tfoot tr').append('<th></th>');
//                             }

//                             oTable = $('#table_reports').dataTable({
//                                 'sAjaxSource': 'lib/php/data/utilities_reports_load.php?type=' + query[0] +
//                                 '&val=' + query[1] + '&date_start=' + start + '&date_end=' + end,
//                                 'aoColumns': data.aoColumns,
//                                 'bAutoWidth': false,
//                                 'bProcessing': true,
//                                 'bDestroy': true,
//                                 'bScrollInfinite': true,
//                                 'sScrollY': tableHeight,
//                                 'iDisplayLength': 150,
//                                 'bSortCellsTop': true,
//                                 'oLanguage': {
//                                     'sEmptyTable': 'No data found.'
//                                 },
//                                 'oTableTools':
//                                 {
//                                     'sSwfPath': 'lib/DataTables-1.8.2/extras/TableTools/media/swf/copy_cvs_xls_pdf.swf',
//                                     'aButtons': [
//                                         {
//                                             'sExtends': 'collection',
//                                             'sButtonText': 'Print/Export',
//                                             'aButtons': [
//                                                 {'sExtends': 'copy',
//                                                     'mColumns': 'visible'
//                                                 },

//                                                 {'sExtends': 'csv',
//                                                     'mColumns': 'visible'
//                                                 },

//                                                 {'sExtends': 'xls',
//                                                     'mColumns': 'visible'
//                                                 },

//                                                 {'sExtends': 'pdf',
//                                                     'mColumns': 'visible',
//                                                     'sTitle': 'Report',
//                                                     'bFooter': 'visible'

//                                                 },

//                                                 {'sExtends': 'print',
//                                                     'mColumns': 'visible'
//                                                 }
//                                             ]
//                                         }
//                                     ]
//                                 },
//                                 'sDom': 'frTt',
//                                 'fnInitComplete': function () {
//                                     $('#table_reports').addClass('print_content');
//                                     $('#ToolTables_table_reports_5').live('click', function () {
//                                         //the dataTables default print dialog is not working, so
//                                         //add our own
//                                         var dialogWin = $('<div class="dialog-casenote-delete" title="Print">' +
//                                         'Please use your browser\'s print function to print this table.' +
//                                         'Press escape when finished.</div>').dialog({
//                                             autoOpen: false,
//                                             resizable: false,
//                                             modal: true,
//                                             buttons: {'OK': function () {
//                                                 $(this).dialog('destroy');
//                                             }
//                                             }
//                                         });

//                                         $(dialogWin).dialog('open');

//                                     });
//                                 },
//                                 'fnFooterCallback': function (nRow, aaData, iStart, iEnd, aiDisplay) {

//                                         if (aaData.length > 0) { //no need for footer if no data.
//                                             var totalTime = 0;
//                                             var colIndex = oTable.fnGetColumnIndex('Seconds');
//                                             var filteredData = oTable.fnGetFilteredData();
//                                             for (var a=0 ; a<filteredData.length ; a++) {
//                                                 totalTime += parseFloat(filteredData[a][colIndex]);
//                                             }
//                                             var nCells = nRow.getElementsByTagName('th');

//                                             //problem has to do with adding a hidden column
//                                             var previousCell = colIndex - 2;
//                                             nCells[previousCell].innerHTML = 'Total Hours';
//                                             var unit = $('#utilities_panel').attr('data-unit');
//                                             nCells[colIndex - 1].innerHTML = convertToHours(totalTime, unit);
//                                         }
//                                     }
//                             });
//                         }}
//                 });
//             });
//         });
//     });

//     //User clicks configuration button
//     $('#config_button').click(function () {
//         target.load('lib/php/data/utilities_configuration_load.php');
//     });

//     //User clicks non-case time button
//     $('#non_case_button').click(function () {
//         //$('#utilities_panel').load('lib/php/data/cases_casenotes_load.php .case_detail_panel_casenotes',
//         //{case_id: 'NC', non_case: '1'});
//         $('#utilities_panel').html('<div id="noncase_panel"></div>');
//         loadCaseNotes($('#noncase_panel'), 'NC');
//     });

//     //Set default load
//     $('#reports_button').trigger('click');
// });

// //
// //Listeners
// //

// //Toggle
// $('a.config_item_link').live('click', function (event) {
//     event.preventDefault();

//     // Toggle open/closed state
//     if ($(this).hasClass('closed')) {
//         $(this).removeClass('closed').addClass('opened');
//     } else {
//         $(this).removeClass('opened').addClass('closed');
//     }

//     $('div.config_item > a').not($(this)).removeClass('opened').addClass('closed');

//     // Show/hide the form
//     $(this).next().toggle();
//     $('form.config_form').not($(this).next()).hide();

// });

// //Submit changes
// $('a.change_config').live('click', function (event) {
//     event.preventDefault();
//     var formTarget = $(this).closest('form');
//     var formParent = $(this).closest('div.config_item');
//     var target = $(this).closest('div').attr('id');

//     //If clicking delete button, remove the form element
//     if (!$(this).hasClass('add')) {
//         $(this).parent().remove();
//     }

//     var formVals = formTarget.serializeArray();
//     formVals.push({'name': 'type', 'value': formTarget.attr('data-type')});

//     $.post('lib/php/data/utilities_configuration_process.php', formVals, function (data) {
//         var serverResponse = $.parseJSON(data);
//         if (serverResponse.error === true) {
//             notify(serverResponse.message, true);
//         } else {
//             notify(serverResponse.message);
//             formParent.load('lib/php/data/utilities_configuration_load.php #' + target, function () {
//                 $(this).find('form').show();
//             });
//         }
//     });
// });

let timeReportsTable;
let timeReportsSlimSelect;
let timeReportsSlimSelectEl;

const getType = (val) => {
  if (val.includes('grp')) return 'grp';
  if (val.includes('spv')) return 'supvsr_grp';
  if (val.includes('cse')) return 'case';
  return 'user';
};

const loadCaseTimeActivityFeed = async () => {
  const caseActivity = await loadReportsActvities({ type: 'case' });
  const caseTimeActivityContainer = document.querySelector(
    '#caseTimeActivityFeed',
  );
  caseTimeActivityContainer.innerHTML = caseActivity;
};
const loadNoDataDisplay = () => {
  const tableContainer = document.querySelector('#table_reports');
  tableContainer.innerHTML = `<div><p class="text-center p-2">No reports for match query.</p></div>`;
};
const loadTimeReportsTable = (data, columns) => {
  const aoColumns = [
    { name: 'Name', type: 'text', fieldName: 'name', hidden: false },
    {
      name: 'Time (hours)',
      type: 'string',
      hidden: false,
      fieldName: 'hours',
    },
    {
      name: 'Seconds',
      type: 'number',
      hidden: false,
      fieldName: 'seconds',
    },
  ];
  if (!data?.length) {
    loadNoDataDisplay();
    return;
  }
  // Custom table plugin initiation
  timeReportsTable = new Table({
    columns,
    data: data,
    containerId: '#table_reports',
    tableName: 'Time Reports',
    tableNameSingular: 'Time Report',
    // canAddButton,
    // customActions: [bulkActionsEl],
  });
};
const submitLoadTimeReports = async (e) => {
  e.preventDefault();
  const form = document.querySelector('#timeReportsForm');
  const selected = timeReportsSlimSelect.selected();
  const endDateEl = form.querySelector("[name='date_end']");
  const { date_end, date_start } = getFormValues(form);

  const isValid = form.validate([
    {
      name: 'type',
      value: selected,
      el: timeReportsSlimSelectEl,
      condition: Boolean(selected),
    },
    {
      name: 'end date',
      value: date_end,
      el: endDateEl,
      message: 'End date must be before start date.',
      condition: date_end >= date_start,
    },
  ]);

  if (!isValid) return;

  const val = selected;
  const type = getType(selected);
  try {
    const res = await loadTimeReports({ type, val, date_end, date_start });
    console.log({ res });
    if (res.error) {
      throw new Error(res.message);
    }
    const jsonColumns = res?.aoColumns?.map((col) => JSON.parse(col));
    loadTimeReportsTable(res.aaData, jsonColumns);
  } catch (err) {
    alertify.error(err.message);
  }
};

document.addEventListener('DOMContentLoaded', () => {
  timeReportsSlimSelect = new SlimSelect({
    select: '.time_reports_slim_select',
  });
  timeReportsSlimSelectEl = document.querySelector('.time_reports_slim_select');
  const timeReportsLoadButton = document.querySelector('.time_reports_load');
  timeReportsLoadButton.addEventListener('click', submitLoadTimeReports);
  loadCaseTimeActivityFeed();
});
