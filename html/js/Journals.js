//Scripts for journals

/* global notify, elPrint, ColReorder, router, rte_toolbar  */
var oTable;

$(document).ready(function() {
	var tableHeight = $('#content').height() - 120;
	var chooserVal = 'unread';
	oTable = $('#table_journals').dataTable({
		'sAjaxSource': 'lib/php/data/journals_load.php',
		'aoColumns':
        [
            { 'sTitle' : '','bSortable' : false,'sWidth' : '40px'},
            { 'sTitle' : 'Id','bSearchable' : false,'bVisible' : false},
            { 'sTitle' : 'Name'},
            { 'sTitle' : 'Submitted To','bSearchable' : true,'bVisible' : false},
            { 'sTitle' : 'Text','bVisible' : false},
            { 'sTitle' : 'Date Submitted','sType': 'date'},
            { 'sTitle' : 'Archived','bVisible' : false},
            { 'sTitle' : 'Read','bVisible' : false},
            { 'sTitle' : 'Commented','bVisible' : false},
            { 'sTitle' : 'Comments','bSearchable' : false,'bVisible' : false}
        ],
        "aoColumnDefs": [ //escape html
            {
                "fnRender": function ( o ) {
                return String(o.aData[o.iDataColumn])
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
        
                },
                "aTargets": [2,3,4,5,6,7,8,9]
            }
        ],
        'oColVis': {'aiExclude': [0,1],'bRestore': true,'buttonText': 'Columns'},
        'oTableTools': {
            'aButtons': [{
                'sExtends':'text',
                'sButtonText':'Reset',
                'sButtonClass':'DTTT_button_reset',
                'sButtonClassHover':'DTTT_button_reset_hover'
            },
            {
                'sExtends':'text',
                'sButtonText':'New Journal',
                'sButtonClass':'DTTT_button_new_case',
                'sButtonClassHover':'DTTT_button_new_case_hover'
            }]
        },
        'aaSorting': [[5, 'desc']],
        'bDeferRender': true,
		'bAutoWidth':false,
		'bProcessing': true,
		'bJQueryUI': true,
		'bScrollInfinite': true,
		'sScrollY':tableHeight,
		'iDisplayLength': 30,
		'iScrollLoadGap':200,
		'bSortCellsTop': true,
		'sDom': 'R<"H"fTCi>rt<"F"<"journal_action">>',
		'oLanguage': {
            'sInfo': 'Showing <b>_TOTAL_</b> <span id="journalStatus"></span> journals',
            'sZeroRecords':'No <span id="journalStatus"></span> journals found.',
            'sInfoEmpty':'Showing 0 journals',
            'sInfoFiltered': 'from a total of <b>_MAX_</b>',
            'sEmptyTable': 'No journals found.'
        },
		'fnInitComplete':function(){
			//Hide processing div
			$('#processing').hide();

			//Have ColVis and reset buttons pick up the DTTT class
            $('div.ColVis button').removeClass()
            .addClass('DTTT_button DTTT_button_collection ui-button ui-state-default');

             //Add journal action selector
            $('div.journal_action').html('<label>With displayed journals:</label><select id="journal_action_chooser">' +
            '<option value="" selected=selected disabled>Choose Action</option><option value="archive">Archive</option>' +
            '<option value="mark_read">Mark Read</option><option value="mark_unread">Mark Unread</option></select>');

            //Bulk actions
            $('#journal_action_chooser').change(function(){
                var filteredData = oTable.fnGetFilteredData();
                var affectedJournals = [];
                var action = $(this).val();
                var actionText = action.replace('_',' as ');

                //Loop through filtered data to get user ids
                $.each(filteredData, function() {
                    affectedJournals.push($(this)[1]);
                });

                var dialogWin = $('<div title="Are you sure?">This will ' + actionText + ' ' +
                filteredData.length + ' journals.  Are you sure you want to do that?</div>')
                .dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    buttons: {
                        'Yes': function() {
                            $.post('lib/php/data/journals_process.php', {
                                'type': action,
                                'id': affectedJournals
                            }, function(data) {
                                var serverResponse = $.parseJSON(data);
                                if (serverResponse.error === true) {
                                    notify(serverResponse.message, true);
                                } else {
                                    notify(serverResponse.message);
                                    oTable.fnReloadAjax();
                                    fnResetAllFilters();
                                    chooserVal = 'unread';
                                }
                            });
                            $(this).dialog('destroy');
                        },
                        'No': function() {
                            $(this).dialog('destroy');
                        }
                    }
                });
                $(dialogWin).dialog('open');
            });

			//Add view chooser
            $('div.dataTables_filter').append('<select id="chooser"><option value="unread" selected=selected>' +
            'Unread</option><option value="read">Read</option><option value="archived">Archived</option>' +
            '<option value="all">All</option></select>');

            //Change the journal status select
            $('#chooser').live('change', function() {
                switch ($(this).val()) {
                    case 'unread':
                        chooserVal = 'unread';
						oTable.fnFilter('^$', oTable.fnGetColumnIndex('Read'), true, false);
                        oTable.fnFilter('', oTable.fnGetColumnIndex('Archived'));
                        break;

                    case 'read':
                        chooserVal = 'read';
                        oTable.fnFilter('yes', oTable.fnGetColumnIndex('Read'), true, false);
                        oTable.fnFilter('', oTable.fnGetColumnIndex('Archived'));
                        break;

                    case 'archived':
                        chooserVal = 'archived';
                        oTable.fnFilter('yes', oTable.fnGetColumnIndex('Archived'));
                        oTable.fnFilter('', oTable.fnGetColumnIndex('Read'));
                        break;
                    case 'all':
                        chooserVal = 'all';
                        oTable.fnFilter('', oTable.fnGetColumnIndex('Archived'), true, false);
                        oTable.fnFilter('', oTable.fnGetColumnIndex('Read'), true, false);
                        break;
                }
            });

            //Apply default filter - unread and unarchived
            oTable.fnFilter('^$', oTable.fnGetColumnIndex('Read'), true, false);
            oTable.fnFilter('^$', oTable.fnGetColumnIndex('Archived'), true, false);


            //Have ColVis and reset buttons pick up the DTTT class
            $('div.ColVis button').removeClass().addClass('DTTT_button DTTT_button_collection ui-button ui-state-default');

            //Event for reset button
            $('#ToolTables_table_journals_0').click(function() { //reset button
                fnResetAllFilters();
            });

            //Check if user can add journals; if not, remove new journal button
            if (!$('#table_journals').hasClass('can_add')) {
                $('#ToolTables_table_journals_1').remove();
            } else { //add listener
                $('#ToolTables_table_journals_1').click(function(){
                //Add new row to cm_journals table
                    $.post('lib/php/data/journals_process.php',{'type': 'new'},function(data){
                        var serverResponse = $.parseJSON(data);
                        if (serverResponse.error === true) {
                            notify(serverResponse.message, true);
                        } else {
                            var newId = serverResponse.newId;
                            callJournal(newId,true,true);//true for edit,true for new
                        }
                    });
                });
            }

            //Listen for click on table row; open journal
            $('#table_journals tbody').click(function(event) {
                var iPos = oTable.fnGetPosition(event.target.parentNode);
                var aData = oTable.fnGetData(iPos);
                var iId = aData[1];
                callJournal(iId,false,false);

            });

            //resizes the table whenever parent element size changes
			$(window).bind('resize', function() {
				oTable.fnDraw(false);
				oTable.fnAdjustColumnSizing();
			});

            //check hash; see if we need to open a journal
            router();

		},
		'fnDrawCallback':function(){
			$('#journalStatus').text(chooserVal);
		}
    });
});

function callJournal(id,edit,newJournal) {
    //Define html for journal window
    var journalId = [];
    journalId.push(id);

    if ($('div#journal_detail_window').length < 1) {
        var journalDetail = '<div id="journal_detail_window"></div>';
        $('#content').append(journalDetail);
    }

    if (edit === true) {//we are editing or writing a new journal
        $('#journal_detail_window').load('lib/php/data/journals_detail_load.php', {
            'id': journalId,
            'view':'edit'
        }, function() {
            $(this).show('fold', 1000,function(){
                //Create lwrte
                var arr = $(this).find('.journal_edit').rte({
                    css: ['lib/javascripts/lwrte/default2.css'],
                    width: 900,
                    height: 500,
                    controls_rte: rte_toolbar
                });

                //auto-save
                var lastText = '';
                var editor = $('#journal_detail_window');
                function autoSave(lastText, arr) {
                    var text = arr[0].get_content();
                    var readers = $('select[name="reader_select[]"]').val();
                    var status = 'Saving...';
                    if (text !== lastText) {
                        editor.find('span.save_status').html(status);
                        $.post('lib/php/data/journals_process.php', {
                            'type': 'edit',
                            'id': journalId,
                            'text': text,
                            'readers':readers
                        }, function(data) {
                            var serverResponse = $.parseJSON(data);
                            if (serverResponse.error) {
                                editor.find('span.save_status').html(serverResponse.message);
                            } else {
                                editor.find('span.save_status').html(serverResponse.message);
                            }
                        });
                        lastText = text;
                    }
                    var t = setTimeout(function() {
                        autoSave(lastText, arr);
                    }, 3000);
                }
                autoSave(lastText, arr);
            });

            //Add event to prevent submitting journal without a reader
            $(window).bind('beforeunload', function() {
                var rSelect = $('select[name="reader_select[]"]');
                if (rSelect.length > 0 && rSelect.val() === null) {
                    rSelect.parent().find('label').first().addClass('ui-state-error');
                    return 'You haven\'t specified who is supposed to read this journal.' +
                    'Please select a reader in the box below.';
                }
            });

            $('button.journal_close') .button({
                icons: {primary: 'fff-icon-cancel'},
                label: 'Close'
            })
            .click(function() {
                var rSelect = $('select[name="reader_select[]"]');
                if (rSelect.length > 0 && rSelect.val() === null) {
                    notify('<p>Please select the users to whom this journal is to be sent.</p>',true);
                    rSelect.parent().find('label').first().addClass('ui-state-error');
                    return false;
                } else {
                    oTable.fnReloadAjax();
                    $('#journal_detail_window').hide('fold', 1000);
                }
            });

            //Add chosen to select
            $('select[name="reader_select[]"]').chosen().change(function(){
                if ($('input[name="remember_choice"]').is(':checked')) {
                    var choice = $('select[name="reader_select[]"]').val();
                    $.cookie('ClinicCases_journal', choice,{expires:365});
                }

                //Update readers on change
                var readers = $('select[name="reader_select[]"]').val();
                $.post('lib/php/data/journals_process.php', {
                    'type': 'update_readers',
                    'id': journalId,
                    'readers':readers
                });
            });

            //Set reader values if previously remembered
            if (newJournal === true) {
                if ($.cookie('ClinicCases_journal') !== null) {
                    var setVals = $.cookie('ClinicCases_journal').split(',');
                    $('select[name="reader_select[]"]').val(setVals);
                    $('select[name="reader_select[]"]').trigger('liszt:updated');
                    $('input[name = "remember_choice"]').attr('checked','checked');
                }
            }
            else if(newJournal === false && edit === true) {
                if ($.cookie('ClinicCases_journal') !== null) {
                    $('input[name = "remember_choice"]').attr('checked','checked');
                }
            }

            //Remember names of journal readers.
            $('input[name = "remember_choice"]').change(function(){
                var choice = $('select[name="reader_select[]"]').val();
                if ($(this).is(':checked')) {
                    $.cookie('ClinicCases_journal', choice,{expires:365});
                } else {
                    //check for cookie; if exists, delete
                    if ($.cookie('ClinicCases_journal') !== null) {
                        $.cookie('ClinicCases_journal',null);
                    }
                }
            });
        });
    } else {//we are viewing a journal
        $('#journal_detail_window').load('lib/php/data/journals_detail_load.php', {
            'id': journalId
        }, function() {
            $(this).show('fold', 1000);

            //Mark journal as read
            $.post('lib/php/data/journals_process.php',{'id':journalId,'type':'mark_read'},function(data){
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true) {
                    notify(serverResponse.message);
                }
            });

            //Define and listen for window buttons
            if ($('button.journal_delete').length) {
                $('button.journal_delete').button({icons: {primary: 'fff-icon-page-delete'}})
                    .click(function() {
                    var dialogWin = $('<div title="Are you sure?"><p>Permanently delete this journal?</p></div>')
                    .dialog({
                        autoOpen: false,
                        resizable: false,
                        modal: true,
                        buttons: {
                            'Yes': function() {
                                $.post('lib/php/data/journals_process.php',{'id':journalId,'type':'delete'},function(data){
                                    var serverResponse = $.parseJSON(data);
                                    if (serverResponse.error === true) {
                                        notify(serverResponse.message, true);
                                    } else {
                                        notify(serverResponse.message);
                                        oTable.fnReloadAjax();
                                        $('#journal_detail_window').hide('fold', 1000);
                                    }
                                });
                                $(this).dialog('destroy');
                            },
                            'No': function() {
                                $(this).dialog('destroy');
                            }
                        }

                    });
                    $(dialogWin).dialog('open');
                });

            }

            if ($('button.journal_edit').length) {
                $('button.journal_edit').button({icons: {primary: 'fff-icon-page-edit'}})
                .click(function() {
                    callJournal(journalId[0],true,false);
                });
            }

            if ($('button.journal_print').length) {
                $('button.journal_print').button({icons: {primary: 'fff-icon-printer'}})
                .click(function() {
                    elPrint($('div.journal_detail'),'Journal');
                });
            }

            $('button.journal_close').button({
                icons: {primary: 'fff-icon-cancel'},
                label: 'Close'
            })
            .click(function() {
                oTable.fnReloadAjax();
                $('#journal_detail_window').hide('fold', 1000);
            });

            //Handle textareas
            $('textarea.expand').livequery(function(){
                $(this).TextAreaExpander(40,300).css({'color':'#AAA'}).bind('focus',function() {
                    $(this).val('').css({'color':'black'}).unbind('focus');
                });
            });
        });
    }
}


function fnResetAllFilters() {
    var oSettings = oTable.fnSettings();

    //reset the main filter
    oTable.fnFilter('');

    //reset the columns to their original order.
    ColReorder.fnReset(oTable);

    //reset the user display for inputs and selects
    $('input').each(function() {
        this.value = '';
    });

    $('select').each(function() {
        this.selectedIndex = '0';
    });

    //return to default unread filter
    oTable.fnFilter('^$', oTable.fnGetColumnIndex('Read'), true, false);
    var chooserVal = 'open';

    //return to default sort - Date Submitted
    oTable.fnSort([[oTable.fnGetColumnIndex('Date Submitted'), 'desc']]);

    //redraw the table so that all columns line up
    oTable.fnDraw();
}

//Listeners

//Save comments
$('a.comment_save').live('click', function(event){
    event.preventDefault();
    var journalId = [];
    journalId.push($(this).closest('div.journal_body').attr('data-id'));
    var commentText = $(this).siblings('textarea').val();
    $.post('lib/php/data/journals_process.php',{
        'type': 'add_comment',
        'id':journalId,
        'comment_text':commentText
    },function(data){
        var serverResponse = $.parseJSON(data);
        if (serverResponse.error === true) {
            notify(serverResponse.message);
        } else {
            $('div.journal_comments').load('lib/php/data/journals_detail_load.php div.journal_comments', {'id': journalId});
            notify(serverResponse.message);
        }
    });
});

//Delete comments
$('a.comment_delete').live('click',function(event){
    event.preventDefault();
    var commentId = $(this).parent().attr('data-id');
    var journalId = [];
    journalId.push($(this).closest('div.journal_body').attr('data-id'));
    var dialogWin = $('<div title="Are you sure?"><p>Delete this comment?</p></div>').dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        buttons: {
            'Yes': function() {
                $.post('lib/php/data/journals_process.php',{
                    'type': 'delete_comment',
                    'id':journalId,
                    'comment_id':commentId
                },function(data){
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify(serverResponse.message);
                    } else {
                        $('div.journal_comments').load('lib/php/data/journals_detail_load.php div.journal_comments', {
                            'id': journalId
                        });
                        notify(serverResponse.message);
                    }
                });
                $(this).dialog('destroy');
            },
            'No': function() {
                $(this).dialog('destroy');
            }
        }
    });
    $(dialogWin).dialog('open');
});

//Add delete listener
$(function() {
    $('div.comment').livequery(function(){
        if ($(this).hasClass('can_delete')) {
            $(this).mouseover(function(){
                $(this).find('a.comment_delete').show();
            })
            .mouseout(function(){
                $(this).find('a.comment_delete').hide();
            });
        }
    });
});

