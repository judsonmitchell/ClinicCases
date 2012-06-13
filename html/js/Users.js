//Scripts for users page
var oTable;
var aoColumns;

$(document).ready(function() {

    //set the intial value for the userStatus span on load
    var chooserVal = "active";

    //Load dataTables
    oTable = $('#table_users').dataTable(
		{
		"bJQueryUI": true,
		"sAjaxSource": 'lib/php/users/users_load.php',
		"bScrollInfinite": true,
		"bDeferRender": true,
		"bScrollCollapse": true,
		"iDisplayLength": 20,
		"bSortCellsTop": true,
        "sScrollY": Math.round(0.8 * $('#content').height()),
        "aoColumns":
        [
        { "bSearchable": false, "bVisible": false },
        { "bSortable": false, "sWidth" : "40px"},
        null,
        null,
        {"bVisible": false},
        {"bVisible": false},
        {"bVisible": false},
        {"bVisible": false},
        null,
        null,
        null,
        null,
        { "bSearchable": false, "bVisible": false },
        {"sType": "date"}
        ],
        "sDom":'<"H"lfTrCi>t<"F">',
        "oColVis": {"aiExclude": [0],"bRestore": true,"buttonText": "Columns","fnStateChange": function(iColumn, bVisible) {
                            $("div.dataTables_scrollHeadInner thead th.addSelects:empty").each(function() {
                                this.innerHTML = fnCreateSelect(oTable.fnGetColumnData(iColumn, true, false, true));
                            });
                        }},
        "oTableTools":
        {
            "sSwfPath": "lib/DataTables-1.8.2/extras/TableTools/media/swf/copy_cvs_xls_pdf.swf",
            "aButtons": [
             {
                    "sExtends": "collection",
                    "sButtonText": "Print/Export",
                    "aButtons": [
                        {"sExtends": "copy",
                            "mColumns": "visible"
                        },

                        {"sExtends": "csv",
                            "mColumns": "visible"
                        },

                        {"sExtends": "xls",
                            "mColumns": "visible"
                        },

                        {"sExtends": "pdf",
                            "mColumns": "visible"
                        },

                        {"sExtends": "print",
                            "mColumns": "visible"
                        }
                    ]
                },
            {"sExtends":"text","sButtonText":"Reset","sButtonClass":"DTTT_button_reset","sButtonClassHover":"DTTT_button_reset_hover"},
            {"sExtends":"text","sButtonText":"New User","sButtonClass":"DTTT_button_new_case","sButtonClassHover":"DTTT_button_new_case_hover"}



            ]
        },
        "oLanguage": {"sInfo": "Found <b>_TOTAL_</b> <span id='userStatus'></span> users","sInfoFiltered": "from a total of <b>_MAX_</b> users"},
		"fnInitComplete": function(){

            //When page loads, default filter is applied: active users
            oTable.fnFilter('^active', oTable.fnGetColumnIndex("Status"), true, false);

            //resizes the table whenever parent element size changes
            $(window).bind('resize', function() {
                oTable.fnAdjustColumnSizing();
            });

            //Have ColVis and reset buttons pick up the DTTT class
            $('div.ColVis button').removeClass().addClass('DTTT_button DTTT_button_collection ui-button ui-state-default');

            //Event for reset button
            $("#ToolTables_table_users_6").click(function() { //reset button
                fnResetAllFilters();
            });

            //Add case status seletctor
            $('div.dataTables_filter').append('<select id="chooser"><option value="active" selected=selected>Active Users</option><option value="inactive">Inactive Users</option><option value="all">All Users</option></select>  <a href="#" id="set_advanced">Advanced Search</a>');

            //Change the case status select
            $('#chooser').live('change', function(event) {

                switch ($(this).val())
                {
                    case 'all':
                        chooserVal = "active and inactive";
                        oTable.fnFilter('', oTable.fnGetColumnIndex("Status"));

                        break;

                    case 'active':
                        chooserVal = "active";
                        oTable.fnFilter('^active', oTable.fnGetColumnIndex("Status"), true, false);
                        break;

                    case 'inactive':
                        chooserVal = "inactive";
                        oTable.fnFilter('^inactive', oTable.fnGetColumnIndex("Status"), true, false);

                        break;
                }

            });


            $("div.dataTables_scrollHeadInner thead th.addSelects").each(function() {

                //Get the index of the column from its name attribute
                columnIndex = oTable.fnGetColumnIndex($(this).attr('name'));

                this.innerHTML = fnCreateSelect(oTable.fnGetColumnData(columnIndex, true, false, true));
            });

            //hide picture column header
            $('thead .DataTables_sort_wrapper').first().css({'color':'white'});
            $('th').first().css({'border-left':'1px solid white','border-bottom':'1px solid white'});

            //Set css for advanced date function; make room for the operator selects
            $('#set_advanced').live('click', function(event) {
                event.preventDefault();
                if ($("tr.advanced, tr.advanced_2").css("display") !== "none")
                {
                    $("tr.advanced, tr.advanced_2").css({'display': 'none'});
                }

                else {
                    $("th.ui-state-default").css({'border-bottom': '0px'});
                    $(".complex").children().css({'display': 'inline','margin-bottom': '0px'});
                    $("#date_created_range").css({'margin-top': '18px'});
                    $("thead tr.advanced").toggle('slow');
                    //$("#second_open_cell, #second_close_cell").css({'visibility': 'hidden'});

                    //Set the big filter to all cases

                    // oTable.fnFilter('', oTable.fnGetColumnIndex("Date Close"), true, false);
                    // $('#chooser').val('all');
                    // chooserVal = "open and closed";
                }

                oTable.fnDraw();

            });

            //Code for advanced search using inputs
            $("thead input").live('keyup', function() {

                //Oparent = $(this).parent();
                colName = $(this).attr('name');
                colIndex = oTable.fnGetColumnIndex(colName);
                oTable.fnFilter(this.value, colIndex);
            });

            //Add datepickers
            $(function() {
                $('input[name="date_created"]').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    onSelect: function() {
                        $(this).css({'color': 'black'});
                        oTable.fnDraw();
                    }
                });
            });


            //Enable search via selects in advanced search
            $("div.dataTables_scrollHeadInner tr.advanced th.addSelects select").live('change', function() {
                Oparent = $(this).parent();
                colIndex = oTable.fnGetColumnIndex(Oparent.attr('name'));
                val = this.value;
                //regex needed to avoid, e.g., a search on "Guilty" from also returning "Not Guilty
                regex = ("^" + val + "$");
                oTable.fnFilter(regex, colIndex, true, false, false);
            });

            //Filter function for dates
            $.fn.dataTableExt.afnFiltering.push(

            function(oSettings, aData, iDataIndex) {

                var dateOperator = document.getElementById('date_created_range').value;
                var dateOperator2 = document.getElementById('date_created_range_2').value;
                var dateFieldRaw = document.getElementById('date_created').value;
                var dateFieldRaw2 = document.getElementById('date_created_2').value;
                var dateRowRaw = aData[13];

                //date conversions
                var dateField = dateFieldRaw.substring(6, 10) + dateFieldRaw.substring(0, 2) + dateFieldRaw.substring(3, 5);
                var dateField2 = dateFieldRaw2.substring(6, 10) + dateFieldRaw2.substring(0, 2) + dateFieldRaw2.substring(3, 5);
                var dateRow = dateRowRaw.substring(6, 10) + dateRowRaw.substring(0, 2) + dateRowRaw.substring(3, 5);

                //no filtering
                if (dateField === '')
                {
                    return true;
                }

                //filtering by date created only
                if (dateField !== ''&& dateField2 === '')
                {
                    if (dateOperator == 'equals' && dateRow == dateField)
                    {
                        return true;
                    }

                    else if (dateOperator == 'less' && dateRow < dateField)
                    {
                        return true;
                    }

                    else if (dateOperator == 'greater' && dateRow > dateField)
                    {
                        return true;
                    }
                }


                //filter between dates created fields
                if (dateField !== '' && dateField2 !== '')

                {
                    if (dateOperator == 'equals' && dateOperator2 == 'equals' && dateRow == dateField && dateRow == dateField2)
                    {
                        return true;
                    }

                    else if (dateOperator == 'greater' && dateOperator2 == 'less' && dateRow > dateField && dateRow < dateField2)
                    {
                        return true;
                    }

                    else if (dateOperator == 'less' && dateOperator2 == 'greater' && dateRow < dateField && dateRow > dateField2)
                    {
                        return true;
                    }

                }

                return false;

            }
            );

            $('#processing').hide(); //hide the "loading" div after load.

			},
          "fnDrawCallback": function() {

            $("#userStatus").text(chooserVal);
            //this ensures that the text of the date is visible
            $(".hasDatepicker").css({'width': '60%'});
            //this ensures that the range select doesn't go out of line
            $(".complex").css({'min-width': '160px'});
            }
		});
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
    $("input").each(function() {
        this.value = '';
    });
    $("select").each(function() {
        this.selectedIndex = '0';
    });
    $('#addOpenRow').text('Add Condition');
    // $("#second_open_cell, #second_closed_cell").css({'visibility': 'hidden'});
    $('thead tr.advanced_2').hide('slow');

    //return to default open cases filter
    oTable.fnFilter('active', oTable.fnGetColumnIndex("Status"), true, false);
    chooserVal = "open";

    //return to default sort - Last Name
    oTable.fnSort([[oTable.fnGetColumnIndex("Last Name"), 'asc']]);

    //redraw the table so that all columns line up
    oTable.fnDraw();

//reset the default values for advanced search
//$("thead input").each( function (i) {
//this.value = asInitVals[$("thead input").index(this)];
//this.className = "search_init"
//});

}
