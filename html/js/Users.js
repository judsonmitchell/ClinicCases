//Scripts for users page
var oTable;
var aoColumns;

//set the intial value for the userStatus span on load
var chooserVal = "active";

$(document).ready(function() {

    //Load dataTables
    oTable = $('#table_users').dataTable(
		{
		"bJQueryUI": true,
		"sAjaxSource": 'lib/php/users/users_load.php',
		"bScrollInfinite": true,
		"bDeferRender": true,
		"bScrollCollapse": false,
		"iDisplayLength": 20,
		"bSortCellsTop": true,
        "aaSorting": [[3, "asc"]],
        "sScrollY": Math.round(0.85 * $('#content').height()),
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
        "sDom":'<"H"lfTrCi>t<"F"<"user_action">>',
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

            //Add user action selector
            $('div.user_action').html('<label>With displayed users:</label><select><option value="" selected=selected>Choose Action</option><option value="activate">Make Active</option><option value="deactivate">Make Inactive</option></select>');

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

                    //Reset scroll height
                    var defaultHeight = Math.round(0.85 * $('#content').height());
                    $(".dataTables_scrollBody").height(defaultHeight);

                    //return to default active users filter
                    oTable.fnFilter('^active', oTable.fnGetColumnIndex("Status"), true, false);
                    $('#chooser').val('active');
                    chooserVal = "active";
                }

                else {
                    $("th.ui-state-default").css({'border-bottom': '0px'});
                    $(".complex").children().css({'display': 'inline','margin-bottom': '0px'});
                    $("#date_created_range").css({'margin-top': '18px'});
                    $("thead tr.advanced").toggle('fast');
                    //$("#second_open_cell, #second_close_cell").css({'visibility': 'hidden'});

                    //Change height so that footer can be seen
                    var cHeight = $('.dataTables_scrollBody').height();
                    var rHeight = $('tr.advanced').height();
                    $(".dataTables_scrollBody").height(cHeight - rHeight);

                    //Set the big filter to all users
                    oTable.fnFilter('', oTable.fnGetColumnIndex("Status"), true, false);
                    $('#chooser').val('all');
                    chooserVal = "active and inactive";
                }

                oTable.fnDraw();

            });

            $('#addDateRow').click(function(event) {
                event.preventDefault();
                if ($("#second_open_cell").css('visibility') == 'visible')
                {
                    $(this).text('Add Condition');
                    $("#second_open_cell").css({'visibility': 'hidden'});
                    $('thead tr.advanced_2').hide('fast');

                }
                else
                {
                    $(this).text('AND IS');
                    $("#second_open_cell").css({'visibility': 'visible'});
                    $('thead tr.advanced_2').show('fast');

                    //Change height so that footer can be seen
                    var cHeight = $('.dataTables_scrollBody').height();
                    var rHeight = $('tr.advanced_2').height();
                    $(".dataTables_scrollBody").height(cHeight - rHeight);

                }
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
                $('input[name="date_created"], input[name="date_created_2"]').datepicker({
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

            //Get action from user_action
            $('div.user_action select').live('change',function(){
                var filteredData = oTable.fnGetFilteredData();
                var affectedUsers = [];
                var action = $(this).val();

                //Loop through filtered data to get user ids
                $.each(filteredData,function(){
                    affectedUsers.push($(this)[0]);
                });

                var dialogWin = $('<div title="Are you sure?">This will ' + $(this).val() + ' ' + filteredData.length + ' users.  Are you sure you want to do that?</div>').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $.post('lib/php/users/users_process.php', {'action': action,'users':affectedUsers}, function(data) {
                                var serverResponse = $.parseJSON(data);
                                if (serverResponse.error === true)
                                {
                                    notify(serverResponse.message, true);
                                }
                                else
                                {
                                    notify(serverResponse.message);
                                    oTable.fnReloadAjax();
                                }
                            });

                            $(this).dialog("destroy");
                        },
                        "No": function() {
                            $(this).dialog("destroy");
                        }
                    }
                });

                $(dialogWin).dialog('open');

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

            //Add trigger for when user changes less/greater/equal

            $("#date_created_range, #date_created_range_2").live('change', function(event) {
                oTable.fnDraw();
            });

            //Listen for click on table row; open case
            $('#table_users tbody').click(function(event) {
                var iPos = oTable.fnGetPosition(event.target.parentNode);
                var aData = oTable.fnGetData(iPos);
                var iId = aData[0];
                showUserDetail(iId);
            });

            $('#processing').hide(); //hide the "loading" div after load.

			},
          "fnDrawCallback": function() {

            $("#userStatus").text(chooserVal);

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
    $('#addDateRow').text('Add Condition');
    // $("#second_open_cell, #second_closed_cell").css({'visibility': 'hidden'});
    $('tr.advanced, tr.advanced_2').hide('fast');

    //return to default active users filter
    oTable.fnFilter('^active', oTable.fnGetColumnIndex("Status"), true, false);
    chooserVal = "active";

    //return to default sort - Last Name
    oTable.fnSort([[oTable.fnGetColumnIndex("Last Name"), 'asc']]);

    //Reset scroll height
    var defaultHeight = Math.round(0.85 * $('#content').height());
    $(".dataTables_scrollBody").height(defaultHeight);

    //redraw the table so that all columns line up
    oTable.fnDraw();

//reset the default values for advanced search
//$("thead input").each( function (i) {
//this.value = asInitVals[$("thead input").index(this)];
//this.className = "search_init"
//});

}

//Create user detail window
function showUserDetail(id)
{
    //Define html for user window

    if ($('div#user_detail_window').length < 1)
    {
        var userDetail = "<div id='user_detail_window'></div>";

        $("#content").append(userDetail);
    }

    $("#user_detail_window").load('lib/php/users/user_detail_load.php',{'id':id,'view':'display'},function(){
        $(this).show('fold', 1000);

        $("div.user_detail_control button").button({icons: {primary: "fff-icon-cancel"},label: "Close"}).
        click(function(){

            $("#user_detail_window").hide('fold', 1000);

        });

        $(this).find('div.user_detail_actions button').click(function(){
            $('#user_detail_window').load('lib/php/users/user_detail_load.php',{'id':id,'view':'edit'},function(){
                 $("div.user_detail_control button").button({icons: {primary: "fff-icon-cancel"},label: "Close"}).
                    click(function(){
                        $("#user_detail_window").hide('fold', 1000);
                });
            });
        });
    });


}
