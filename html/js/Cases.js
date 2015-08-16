
 /* global adjustedHeight, alert, notify, fnCreateSelect, callCaseWindow, router */
 //init
var oTable, aoColumns;

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

$(document).ready(function() {
    //set the intial value for the caseStatus span on load
    var chooserVal = 'open';

    //Get the column definitions to use in oTable
    $.ajax({
        url: 'lib/php/data/cases_columns_load.php',
        dataType: 'json',
        error: function() {
            alert('Sorry, there is an error in your ClinicCases configuration');
            return true;
        },
        success: function(data) {
            if (data) {
                aoColumns = data.aoColumns;
                oTable = $('#table_cases').dataTable({
                    'bJQueryUI': true,
                    'bProcessing': true,
                    'bScrollInfinite': true,
                    'bScrollCollapse': true,
                    'bSortCellsTop': true,
                    'bStateSave':true,
                    'iCookieDuration':60*60*24*365,
                    'sScrollY': adjustedHeight - 95,
                    'iDisplayLength': 50,
                    'aaSorting': [[4, 'asc']],
                    'aoColumns': aoColumns,
                    'sDom': 'R<\'H\'fTCi>rt',
                    'oColVis': {
                        'aiExclude': [0,6,7],
                        'bRestore': true,
                        'buttonText': 'Columns',
                        'fnStateChange': function(iColumn, bVisible) {
                            $('div.dataTables_scrollHeadInner thead th.addSelects:empty').each(function() {
                                this.innerHTML = fnCreateSelect(oTable.fnGetColumnData(iColumn, true, false, true));
                            });
                        }
                    },
                    'oTableTools': {
                        'sSwfPath': 'lib/DataTables-1.8.2/extras/TableTools/media/swf/copy_cvs_xls_pdf.swf',
                        'aButtons': [ {
                                'sExtends': 'collection',
                                'sButtonText': 'Print/Export',
                                'aButtons': [
                                    {'sExtends': 'copy',
                                        'mColumns': 'visible'
                                    },

                                    {'sExtends': 'csv',
                                        'mColumns': 'visible'
                                    },

                                    {'sExtends': 'xls',
                                        'mColumns': 'visible'
                                    },

                                    {'sExtends': 'pdf',
                                        'mColumns': 'visible'
                                    },

                                    {'sExtends': 'print',
                                        'mColumns': 'visible'
                                    }
                                ]
                            },
                            {
                                'sExtends':'text',
                                'sButtonText':'Reset',
                                'sButtonClass':'DTTT_button_reset',
                                'sButtonClassHover':'DTTT_button_reset_hover'
                            },
                            {
                                'sExtends':'text',
                                'sButtonText':'New Case',
                                'sButtonClass':'DTTT_button_new_case',
                                'sButtonClassHover':'DTTT_button_new_case_hover'
                            }
                        ]
                        },
                        'sAjaxSource': 'lib/php/data/cases_load.php',
                        'bDeferRender': true,
                        "aoColumnDefs": [ //see https://datatables.net/forums/discussion/10189/datatables-does-not-display-character#
                            {
                                "fnRender": function ( o ) {
                                return String(o.aData[o.iDataColumn])
                                    .replace(/&/g, '&amp;')
                                    .replace(/"/g, '&quot;')
                                    .replace(/'/g, '&#39;')
                                    .replace(/</g, '&lt;')
                                    .replace(/>/g, '&gt;');
                        
                                },
                                //"aTargets": [ 0,1,2,3,4,5 ]
                                "aTargets": ['_all']
                            }
                        ],
                        'fnInitComplete': function() {
                        //When page loads, default filter is applied: open cases
                        // (i.e., all cases where the date close field is empty.
                            oTable.fnFilter('^$', oTable.fnGetColumnIndex('Date Close'), true, false);
                            //resizes the table whenever parent element size changes
                            $(window).bind('resize', function() {
                                oTable.fnDraw(false);
                                oTable.fnAdjustColumnSizing();
                            });
                            $('div.dataTables_scrollHeadInner thead th.addSelects').each(function() {
                                //Get the index of the column from its name attribute
                                var columnIndex = oTable.fnGetColumnIndex($(this).attr('name'));
                                this.innerHTML = fnCreateSelect(oTable.fnGetColumnData(columnIndex, true, false, true));
                            });
                            //Important: After the selects have been rendered, set visibilities.
                            //This allows the hidden selects to get the proper values.
                            //See http://datatables.net/forums/comments.php?DiscussionID=3318

                            //Add case status seletctor
                            $('div.dataTables_filter').append('<select id="chooser"><option value="open" '+
                            'selected=selected>Open Cases Only</option><option value="closed">Closed Cases Only' +
                            '</option><option value="all">All Cases</option></select>  <a href="#" id="set_advanced">' +
                            'Advanced Search</a>');

                            //Have ColVis and reset buttons pick up the DTTT class
                            $('div.ColVis button').removeClass()
                            .addClass('DTTT_button DTTT_button_collection ui-button ui-state-default');

                            //Event for reset button
                            $('#ToolTables_table_cases_6').click(function() { //reset button
                                fnResetAllFilters();
                            });

                            //Check if user can add cases; if not, remove new case button
                            if (!$('#table_cases').hasClass('can_add')) {
                                $('#ToolTables_table_cases_7').remove();
                            } else { //add listener
                                $('#ToolTables_table_cases_7').click(function(){
                                    //Add new row to cm_cases_table
                                    $.post('lib/php/utilities/create_new_case.php',function(data){
                                        var serverResponse = $.parseJSON(data);
                                        if (serverResponse.error === true) {
                                            notify(serverResponse.message, true);
                                        } else {
                                            var newId = serverResponse.newId;
                                            callCaseWindow(newId,true);//true for new case
                                        }
                                    });

                                });
                            }

                            //Change the case status select
                            $('#chooser').live('change', function(event) {
                                switch ($(this).val()) {
                                    case 'all':
                                        chooserVal = 'open and closed';
                                        oTable.fnFilter('', oTable.fnGetColumnIndex('Date Close'));
                                        break;
                                    case 'open':
                                        chooserVal = 'open';
                                        oTable.fnFilter('^$', oTable.fnGetColumnIndex('Date Close'), true, false);
                                        break;
                                    case 'closed':
                                        chooserVal = 'closed';
                                        oTable.fnFilter('^.+$', oTable.fnGetColumnIndex('Date Close'), true, false);
                                        break;
                                }
                            });

                            //Set css for advanced date function; make room for the operator selects
                            $('#set_advanced').live('click', function(event) {
                                event.preventDefault();
                                if ($('tr.advanced, tr.advanced_2').css('display') !== 'none') {
                                    $('tr.advanced, tr.advanced_2').css({'display': 'none'});
                                } else {
                                    $('th.ui-state-default').css({'border-bottom': '0px'});
                                    $('.complex').children().css({'display': 'inline','margin-bottom': '0px'});
                                    //$('#date_open , #date_close').css({'width':'65%','margin-top':'18px'});
                                    $('#open_range , #close_range').css({'margin-top': '18px'});
                                    $('thead tr.advanced').toggle('slow');
                                    $('#second_open_cell, #second_close_cell').css({'visibility': 'hidden'});

                                    //Set the big filter to all cases
                                    oTable.fnFilter('', oTable.fnGetColumnIndex('Date Close'), true, false);
                                    $('#chooser').val('all');
                                    chooserVal = 'open and closed';
                                }
                                oTable.fnDraw();
                            });

                            $('#addopenRow').click(function(event) {
                                event.preventDefault();
                                if ($('#second_open_cell').css('visibility') === 'visible') {
                                    $(this).text('Add Condition');
                                    $('#second_open_cell').css({'visibility': 'hidden'});
                                    $('thead tr.advanced_2').hide('slow');
                                } else {
                                    $(this).text('AND IS');
                                    $('#second_open_cell').css({'visibility': 'visible'});
                                    $('#date_open_2 , #date_close_2').css({'width': '60%'});
                                    $('thead tr.advanced_2').show('slow');
                                }
                            });

                            $('#addcloseRow').click(function(event) {
                                event.preventDefault();
                                if ($('#second_close_cell').css('visibility') === 'visible') {
                                    $(this).text('Add Condition');
                                    $('#second_close_cell').css({'visibility': 'hidden'});
                                    $('thead tr.advanced_2').hide('slow');
                                } else {
                                    $(this).text('AND IS');
                                    $('#second_close_cell').css({'visibility': 'visible'});
                                    $('#date_open_2 , #date_close_2').css({'width': '60%'});
                                    $('thead tr.advanced_2').show('slow');
                                }
                            });

                            //Code for advanced search using inputs
                            $('thead input').live('keyup', function() {
                                var colName = $(this).attr('name');
                                var colIndex = oTable.fnGetColumnIndex(colName);
                                oTable.fnFilter(this.value, colIndex);
                            });

                            //Enable search via selects in advanced search
                            $('div.dataTables_scrollHeadInner tr.advanced th.addSelects select').live('change', function() {
                                var Oparent = $(this).parent();
                                var colIndex = oTable.fnGetColumnIndex(Oparent.attr('name'));
                                var val = this.value;
                                //regex needed to avoid, e.g., a search on 'Guilty' from also returning 'Not Guilty
                                var regex = ('^' + val + '$');
                                oTable.fnFilter(regex, colIndex, true, false, false);
                            });

                            //Add datepickers
                            $(function() {
                                $('#date_open , #date_close, #date_open_2, #date_close_2').datepicker({
                                    changeMonth: true,
                                    changeYear: true,
                                    onSelect: function() {
                                        $(this).css({'color': 'black'});
                                        oTable.fnDraw();
                                    }
                                });
                            });

                            //Add trigger for when user changes less/greater/equal

                            $('#open_range, #open_range_2, #close_range, #close_range_2').live('change', function(event) {
                                oTable.fnDraw();
                            });

                            //Listen for click on table row; open case
                            $('#table_cases tbody').click(function(event) {
                                var iPos = oTable.fnGetPosition(event.target.parentNode);
                                var aData = oTable.fnGetData(iPos);
                                var iId = aData[0];
                                callCaseWindow(iId);

                            });

                            //Set table for printing, if user clicks dataTables print
                            $('#table_cases').addClass('print_content');
                            $('tr.advanced, tr.advanced_2').addClass('print_content_no');
                            $('#ToolTables_table_cases_5').live('click',function(){
                                //the dataTables default print dialog is not working, so
                                //add our own
                                var dialogWin = $('<div class="dialog-casenote-delete" title="Print">Please use your ' +
                                'browser\'s print function to print this table. Press escape when finished.</div>')
                                .dialog({
                                    autoOpen: false,
                                    resizable: false,
                                    modal: true,
                                    buttons: {'OK':function() {
                                        $(this).dialog('destroy');
                                    }
                                    }
                                });
                                $(dialogWin).dialog('open');
                            });

                            $('#processing').hide(); //hide the "loading" div after load.

                        },
                        'oLanguage': {
                            'sInfo': 'Found <b>_TOTAL_</b> <span id="caseStatus"></span> cases',
                            'sInfoFiltered': 'from a total of <b>_MAX_</b> cases',
                            'sEmptyTable': 'No cases found.',
                            'sZeroRecords':'No cases found.'
                        },
                        'fnDrawCallback': function() {
                            $('#caseStatus').text(chooserVal);
                            //this ensures that the text of the date is visible
                            $('.hasDatepicker').css({'width': '60%'});
                            //this ensures that the range select doesn't go out of line
                            $('.complex').css({'min-width': '160px'});
                        }
                    });
                router();
            }
        }
    });

    //Reset displayed data
    function fnResetAllFilters() {
        var oSettings = oTable.fnSettings();

        //reset advanced header selects
        for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
            oSettings.aoPreSearchCols[iCol].sSearch = '';
        }

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
        $('#addOpenRow, #addCloseRow').each(function() {
            $(this).text('Add Condition');
        });
        $('#second_open_cell, #second_close_cell').css({'visibility': 'hidden'});
        $('thead tr.advanced_2').hide('slow');

        //return to default open cases filter
        oTable.fnFilter('^$', oTable.fnGetColumnIndex('Date Close'), true, false);
        chooserVal = 'open';

        //return to default sort - Last Name
        oTable.fnSort([[oTable.fnGetColumnIndex('Last Name'), 'asc']]);

        //redraw the table so that all columns line up
        oTable.fnDraw();

        //reset the default values for advanced search
        //$("thead input").each( function (i) {
        //this.value = asInitVals[$("thead input").index(this)];
        //this.className = "search_init"
        //});
    }
});

//Filtering for date fields
$.fn.dataTableExt.afnFiltering.push(
function(oSettings, aData, iDataIndex) {
    var opOperator = document.getElementById('open_range').value;
    var opOperator2 = document.getElementById('open_range_2').value;
    var clOperator = document.getElementById('close_range').value;
    var clOperator2 = document.getElementById('close_range_2').value;
    var opFieldRaw = document.getElementById('date_open').value;
    var opFieldRaw2 = document.getElementById('date_open_2').value;
    var clFieldRaw = document.getElementById('date_close').value;
    var clFieldRaw2 = document.getElementById('date_close_2').value;
    var opRowRaw = aData[6];
    var clRowRaw = aData[7];

    //date conversions

    var opField = opFieldRaw.substring(6, 10) + opFieldRaw.substring(0, 2) + opFieldRaw.substring(3, 5);
    var opField2 = opFieldRaw2.substring(6, 10) + opFieldRaw2.substring(0, 2) + opFieldRaw2.substring(3, 5);
    var clField = clFieldRaw.substring(6, 10) + clFieldRaw.substring(0, 2) + clFieldRaw.substring(3, 5);
    var clField2 = clFieldRaw2.substring(6, 10) + clFieldRaw2.substring(0, 2) + clFieldRaw2.substring(3, 5);
    var opRow = opRowRaw.substring(6, 10) + opRowRaw.substring(0, 2) + opRowRaw.substring(3, 5);
    var clRow = clRowRaw.substring(6, 10) + clRowRaw.substring(0, 2) + clRowRaw.substring(3, 5);

    //no filtering
    if (opField === '' && clField === '') {
        return true;
    }

    //filtering by date open only
    if (opField !== '' && clField === '' && opField2 === '' && clField2 === '') {
        if (opOperator === 'equals' && opRow === opField) {
            return true;
        }

        else if (opOperator === 'less' && opRow < opField) {
            return true;
        }

        else if (opOperator === 'greater' && opRow > opField) {
            return true;
        }
    }

    //filtering by date closed only
    if (opField === '' && clField !== '' && opField2 === '' && clField2 === '') {
        if (clOperator === 'equals' && clRow === clField) {
            return true;
        }

        else if (clOperator === 'less' && clRow < clField) {
            return true;
        }

        else if (clOperator === 'greater' && clRow > clField) {
            return true;
        }
    }

    //filter range between open and closed dates
    if (opField !== '' && clField !== '' && opField2 === '' && clField2 === '') {
        if (opOperator === 'equals' && clOperator === 'equals' && opRow === opField && clRow === clField) {
            return true;
        }

        else if (opOperator === 'greater' && clOperator === 'less' && opRow > opField && clRow < clField) {
            return true;
        }

        else if (opOperator === 'less' && clOperator === 'greater' && opRow < opField && clRow > clField) {
            return true;
        }
    }

    //filter between open dates
    if (opField !== '' && clField === '' && opField2 !== '' && clField2 === '') {
        if (opOperator === 'equals' && opOperator2 === 'equals' && opRow === opField && opRow === opField2) {
            return true;
        }

        else if (opOperator === 'greater' && opOperator2 === 'less' && opRow > opField && opRow < opField2) {
            return true;
        }

        else if (opOperator === 'less' && opOperator2 === 'greater' && opRow < opField && opRow > opField2) {
            return true;
        }
    }

    //filter between close dates
    if (opField === '' && clField !== '' && opField2 === '' && clField2 !== '') {
        if (clOperator === 'equals' && clOperator2 === 'equals' && clRow === clField && clRow === clField2) {
            return true;
        }

        else if (clOperator === 'greater' && clOperator2 === 'less' && clRow > clField && clRow < clField2) {
            return true;
        }

        else if (clOperator === 'less' && clOperator2 === 'greater' && clRow < clField && clRow > clField2) {
            return true;
        }
    }

    //Find open/close range within an open/close range
    if (opField !== '' && clField !== '' && opField2 !== '' && clField2 !== '') {
        if (opOperator === 'equals' && opOperator2 === 'equals' && clOperator === 'equals' &&
        opOperator2 === 'equals' && opRow === opField && opRow === opField2 && clRow === clField && clRow === clField2) {
            return true;
        }

        else if (opOperator === 'greater' && opOperator2 === 'less' && clOperator === 'greater' && opOperator2 === 'less' &&
        opRow > opField && opRow < opField2 && clRow > clField && clRow < clField2) {
            return true;
        }
    }

    //Find specific close date with an open range
    if (opField !== '' && clField !== '' && opField2 !== '' && clField2 === '') {
        if (opOperator=== 'greater' && opOperator2=== 'less' && clOperator=== 'equals' &&
        opRow > opField && opRow < opField2 && clRow === clField) {
            return true;
        }

        if (opOperator === 'greater' && opOperator2 === 'less' && clOperator === 'greater' && opRow > opField &&
        opRow < opField2 && clRow > clField) {
            return true;
        }

        if (opOperator === 'greater' && opOperator2 === 'less' && clOperator === 'less' && opRow > opField &&
        opRow < opField2 && clRow < clField) {
            return true;
        }
    }

    //Find specific open date with a closed range
    if (opField !== '' && clField !== '' && opField2 === '' && clField2 !== '') {
        if (clOperator === 'greater' && clOperator2 === 'less' && opOperator === 'equals' && clRow > clField &&
        clRow < clField2 && opRow === opField) {
            return true;
        }

        if (clOperator === 'greater' && clOperator2 === 'less' && opOperator === 'greater' && clRow > clField &&
        clRow < clField2 && opRow > opField) {
            return true;
        }

        if (clOperator === 'greater' && clOperator2 === 'less' && opOperator === 'less' && clRow > clField &&
        clRow < clField2 && opRow < opField) {
            return true;
        }
    }
    return false;
}
);

