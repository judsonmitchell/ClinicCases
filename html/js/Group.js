//Scripts for users page

/* global notify, fnCreateSelect, ColReorder, validUser, qq */
var oTable;
var aoColumns;

//set the intial value for the userStatus span on load
var chooserVal = 'active';

$(document).ready(function() {

    //Load dataTables
    oTable = $('#table_users').dataTable({
        'bJQueryUI': true,
        'sAjaxSource': 'lib/php/users/group_load.php',
        'bScrollInfinite': true,
        'bDeferRender': true,
        'bScrollCollapse': false,
        'iDisplayLength': 20,
        'bSortCellsTop': true,
        'aaSorting': [[3, 'asc']],
        'sScrollY': Math.round(0.85 * $('#content').height()),
        'aoColumns':
        [
            {'bSearchable': false,'bVisible': false},
            {'bSortable': false,'sWidth': '40px'},
            null,
            null,
            null,
            null,
            {'bVisible': false},
            {'bVisible': false},
            null,
            {'bVisible': false},
            null,
            {'bVisible': false},
            {'sType': 'date'}
        ],
        'sDom': '<\"H\"lfTrCi>t',
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
                "aTargets": [2,3,5,6,7,8,9,10,11,12]
            }
        ],
        'oColVis': {'aiExclude': [0],'bRestore': true,'buttonText': 'Columns','fnStateChange': function(iColumn, bVisible) {
                $('div.dataTables_scrollHeadInner thead th.addSelects:empty').each(function() {
                    this.innerHTML = fnCreateSelect(oTable.fnGetColumnData(iColumn, true, false, true));
                });
            }},
        'oTableTools': {
            'sSwfPath': 'lib/DataTables-1.8.2/extras/TableTools/media/swf/copy_cvs_xls_pdf.swf',
            'aButtons': [
                {
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
                    'sExtends': 'text',
                    'sButtonText': 'Reset',
                    'sButtonClass': 'DTTT_button_reset',
                    'sButtonClassHover': 'DTTT_button_reset_hover'
                }
            ]
        },
        'oLanguage': {
            'sInfo': 'There are <b>_TOTAL_</b> <span id="userStatus"></span> users in your group',
            'sInfoFiltered': 'from a total of <b>_MAX_</b> users',
            'sEmptyTable': 'Nobody is in your group.'
        },
        'fnInitComplete': function() {

            //When page loads, default filter is applied: active users
            oTable.fnFilter('^active', oTable.fnGetColumnIndex('Status'), true, false);

            //resizes the table whenever parent element size changes
            $(window).bind('resize', function() {
                oTable.fnDraw(false);
                oTable.fnAdjustColumnSizing();
            });

            //Have ColVis and reset buttons pick up the DTTT class
            $('div.ColVis button').removeClass().addClass('DTTT_button DTTT_button_collection ui-button ui-state-default');

            //Event for reset button
            $('#ToolTables_table_users_6').click(function() { //reset button
                fnResetAllFilters();
            });

            //Add case status seletctor
            $('div.dataTables_filter').append('<select id="chooser"><option value="active" selected=selected>' +
            'Active Users</option><option value="inactive">Inactive Users</option><option value="all">All Users' +
            '</option></select>  <a href="#" id="set_advanced">Advanced Search</a>');

            //Change the case status select
            $('#chooser').live('change', function(event) {
                switch ($(this).val()) {
                    case 'all':
                        chooserVal = 'active and inactive';
                        oTable.fnFilter('', oTable.fnGetColumnIndex('Status'));
                        break;
                    case 'active':
                        chooserVal = 'active';
                        oTable.fnFilter('^active', oTable.fnGetColumnIndex('Status'), true, false);
                        break;
                    case 'inactive':
                        chooserVal = 'inactive';
                        oTable.fnFilter('^inactive', oTable.fnGetColumnIndex('Status'), true, false);
                        break;
                }
            });

            $('div.dataTables_scrollHeadInner thead th.addSelects').each(function() {

                //Get the index of the column from its name attribute
                var columnIndex = oTable.fnGetColumnIndex($(this).attr('name'));
                this.innerHTML = fnCreateSelect(oTable.fnGetColumnData(columnIndex, true, false, true));
            });

            //hide picture column header
            $('thead .DataTables_sort_wrapper').first().css({'color': 'white'});
            $('th').first().css({'border-left': '1px solid white','border-bottom': '1px solid white'});

            //Set css for advanced date function; make room for the operator selects
            $('#set_advanced').live('click', function(event) {
                event.preventDefault();
                if ($('tr.advanced, tr.advanced_2').css('display') !== 'none') {
                    $('tr.advanced, tr.advanced_2').css({'display': 'none'});

                    //Reset scroll height
                    var defaultHeight = Math.round(0.85 * $('#content').height());
                    $('.dataTables_scrollBody').height(defaultHeight);

                    //return to default active users filter
                    oTable.fnFilter('^active', oTable.fnGetColumnIndex('Status'), true, false);
                    $('#chooser').val('active');
                    chooserVal = 'active';
                } else {
                    $('th.ui-state-default').css({'border-bottom': '0px'});
                    $('.complex').children().css({'display': 'inline','margin-bottom': '0px'});
                    $('#date_created_range').css({'margin-top': '18px'});
                    $('thead tr.advanced').toggle('fast');

                    //Change height so that footer can be seen
                    var cHeight = $('.dataTables_scrollBody').height();
                    var rHeight = $('tr.advanced').height();
                    $('.dataTables_scrollBody').height(cHeight - rHeight);

                    //Set the big filter to all users
                    oTable.fnFilter('', oTable.fnGetColumnIndex('Status'), true, false);
                    $('#chooser').val('all');
                    chooserVal = 'active and inactive';
                }
                oTable.fnDraw();
            });

            $('#addDateRow').click(function(event) {
                event.preventDefault();
                if ($('#second_open_cell').css('visibility') === 'visible') {
                    $(this).text('Add Condition');
                    $('#second_open_cell').css({'visibility': 'hidden'});
                    $('thead tr.advanced_2').hide('fast');
                } else {
                    $(this).text('AND IS');
                    $('#second_open_cell').css({'visibility': 'visible'});
                    $('thead tr.advanced_2').show('fast');

                    //Change height so that footer can be seen
                    var cHeight = $('.dataTables_scrollBody').height();
                    var rHeight = $('tr.advanced_2').height();
                    $('.dataTables_scrollBody').height(cHeight - rHeight);
                }
            });

            //Code for advanced search using inputs
            $('thead input').live('keyup', function() {
                var colName = $(this).attr('name');
                var colIndex = oTable.fnGetColumnIndex(colName);
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
            $('div.dataTables_scrollHeadInner tr.advanced th.addSelects select').live('change', function() {
                var Oparent = $(this).parent();
                var colIndex = oTable.fnGetColumnIndex(Oparent.attr('name'));
                var val = this.value;
                //regex needed to avoid, e.g., a search on "Guilty" from also returning "Not Guilty
                var regex = ('^' + val + '$');
                oTable.fnFilter(regex, colIndex, true, false, false);
            });

            //Filter function for dates
            $.fn.dataTableExt.afnFiltering.push(

            function(oSettings, aData, iDataIndex) {

                var dateOperator = document.getElementById('date_created_range').value;
                var dateOperator2 = document.getElementById('date_created_range_2').value;
                var dateFieldRaw = document.getElementById('date_created').value;
                var dateFieldRaw2 = document.getElementById('date_created_2').value;
                var dateRowRaw = aData[12];

                //date conversions
                var dateField = dateFieldRaw.substring(6, 10) + dateFieldRaw.substring(0, 2) + dateFieldRaw.substring(3, 5);
                var dateField2 = dateFieldRaw2.substring(6, 10) + dateFieldRaw2.substring(0, 2) + dateFieldRaw2.substring(3, 5);
                var dateRow = dateRowRaw.substring(6, 10) + dateRowRaw.substring(0, 2) + dateRowRaw.substring(3, 5);

                //no filtering
                if (dateField === '') {
                    return true;
                }

                //filtering by date created only
                if (dateField !== '' && dateField2 === '') {
                    if (dateOperator === 'equals' && dateRow === dateField) {
                        return true;
                    } else if (dateOperator === 'less' && dateRow < dateField) {
                        return true;
                    } else if (dateOperator === 'greater' && dateRow > dateField) {
                        return true;
                    }
                }

                //filter between date_created fields
                if (dateField !== '' && dateField2 !== '') {
                    if (dateOperator === 'equals' && dateOperator2 === 'equals' &&
                    dateRow === dateField && dateRow === dateField2) {
                        return true;
                    }

                    else if (dateOperator === 'greater' && dateOperator2 === 'less' &&
                    dateRow > dateField && dateRow < dateField2) {
                        return true;
                    }

                    else if (dateOperator === 'less' && dateOperator2 === 'greater' &&
                    dateRow < dateField && dateRow > dateField2) {
                        return true;
                    }
                }
                return false;
            }
            );

            //Add trigger for when user changes less/greater/equal
            $('#date_created_range, #date_created_range_2').live('change', function(event) {
                oTable.fnDraw();
            });

            //Listen for click on table row; open user
            $('#table_users tbody').click(function(event) {
                var iPos = oTable.fnGetPosition(event.target.parentNode);
                var aData = oTable.fnGetData(iPos);
                var iId = aData[0];
                showUserDetail(iId);
            });

            $('#processing').hide(); //hide the "loading" div after load.

            //Check for new users and notify
            $.post('lib/php/users/check_for_new_users.php', function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.new_user === true) {
                    var uText;
                    var aText;

                    if (serverResponse.number === 1) {
                        uText = 'is one new user';
                        aText = 'application';
                    } else {
                        uText = 'are ' + serverResponse.number + ' new users';
                        aText = 'applications';
                    }

                    var dialogWin = $('<div title="New Users"><p>There ' + uText +
                    'awaiting approval from you.</p> <br /><p>Would you like to review the ' + aText +
                    ' now?</p></div>')
                    .dialog({
                        autoOpen: false,
                        resizable: false,
                        modal: true,
                        buttons: {
                            'Yes': function() {
                                chooserVal = 'new';
                                $('#chooser').val('inactive');
                                oTable.fnFilter('^inactive', oTable.fnGetColumnIndex('Status'), true, false);
                                oTable.fnFilter('yes', oTable.fnGetColumnIndex('New'), true, false);
                                $(this).dialog('destroy');
                            },
                            'No': function() {
                                $(this).dialog('destroy');
                            }
                        }
                    });
                    $(dialogWin).dialog('open');
                }
            });

            //Listen for new user button
            $('button#ToolTables_table_users_7').click(function(){
                $.post('lib/php/users/new_user.php',function(data){
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify(serverResponse.message, true);
                    } else {
                        var newId = serverResponse.id;
                        userEdit(newId,true);
                    }
                });
            });
        },
        'fnDrawCallback': function() {
            $('#userStatus').text(chooserVal);

            //this ensures that the range select doesn't go out of line
            $('.complex').css({'min-width': '160px'});
        }
    });
});

//Reset displayed data
function fnResetAllFilters() {
    var oSettings = oTable.fnSettings();

    //reset advanced header selects
    for (var iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
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
    $('#addDateRow').text('Add Condition');
    // $('#second_open_cell, #second_closed_cell').css({'visibility': 'hidden'});
    $('tr.advanced, tr.advanced_2').hide('fast');

    //return to default active users filter
    oTable.fnFilter('^active', oTable.fnGetColumnIndex('Status'), true, false);
    chooserVal = 'active';

    //return to default sort - Last Name
    oTable.fnSort([[oTable.fnGetColumnIndex('Last Name'), 'asc']]);

    //Reset scroll height
    var defaultHeight = Math.round(0.85 * $('#content').height());
    $('.dataTables_scrollBody').height(defaultHeight);

    //redraw the table so that all columns line up
    oTable.fnDraw();
}

//Create user detail window
function showUserDetail(id) {
    //Define html for user window
    if ($('div#user_detail_window').length < 1) {
        var userDetail = '<div id="user_detail_window"></div>';
        $('#content').append(userDetail);
    }

    $('#user_detail_window').load('lib/php/users/user_detail_load.php', {
        'id': id,
        'view': 'display'
    }, function() {
        $(this).show('fold', 1000);

        //Listen for close window button
        $('div.user_detail_control button').button({
            icons: {primary: 'fff-icon-cancel'},
            label: 'Close'
        })
        .click(function() {
            $('#user_detail_window').hide('fold', 1000);
        });
    });
}

function userEdit(userId,newUser) {
    var view;
    if (typeof newUser === 'undefined') {
        view = 'update';
    } else {
        view = 'create';
    }

    //Define html for user window
    if ($('div#user_detail_window').length < 1) {
        var userDetail = '<div id="user_detail_window"></div>';
        $('#content').append(userDetail);
    }

    $('#user_detail_window').load('lib/php/users/user_detail_load.php', {
        'id': userId,
        'view': view
    }, function() {
        if ($(this).css('display') === 'none') {
            $(this).show('fold', 1000);
        }

        //Listen for user changes
        $(this).find('input,select').change(function(){
            $('#user_detail_window').data('dirty','true');
            $(window).bind('beforeunload', function(){
                return 'You have unsaved changes to this user. If you proceed, they will be lost.';
            });
        });

        //Click close button
        $('div.user_detail_control button').button({
            icons: {primary: 'fff-icon-cancel'},
            label: 'Close'
        })
        .click(function() {
            if ($('#user_detail_window').data('dirty') === 'true') {
                notify('<p>You have unsaved changes to this user.  Please either submit the changes or cancel.</p>',true);
                return false;
            } else {
                $('#user_detail_window').hide('fold', 1000);
            }
        });

        //Click cancel button
        $('div.user_detail_edit_actions button:eq(0)').click(function(event) {
            event.preventDefault();
            if (view === 'create') {//this is a new user
                $.post('lib/php/users/users_process.php', {
                    'action': 'delete',
                    'users': userId
                }, function(data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify('There was an error cancelling this user.',true);
                    } else {
                        notify('New user deleted');
                    }
                });
            }
            $('#user_detail_window').data('dirty','false');
            $(window).unbind('beforeunload');
            $('#user_detail_window').hide('fold', 1000);
        });

        //Click submit button
        $('div.user_detail_edit_actions button:eq(1)').click(function(event) {
            event.preventDefault();
            var formVals = $('form[name="user_edit_form"]');
            var errString = validUser(formVals);
            if (errString.length) {
                notify(errString, true);
                formVals.find('.ui-state-error').click(function() {
                    $(this).removeClass('ui-state-error');
                });
                return false;
            } else {
                var formValsArray = formVals.serializeArray();
                //Turn supervisors into a string
                var supString = '';
                $.each(formValsArray, function(i, field) {
                    if (field.name === 'supervisors') {
                        supString += field.value + ',';
                    }
                });

                var formValsOk = $('form[name="user_edit_form"] :not(select[name="supervisors"])').serializeArray();
                formValsOk.push({'name': 'supervisors','value': supString});

                $.post('lib/php/users/users_process.php', formValsOk, function(data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify(serverResponse.message, true);
                    } else {
                        notify(serverResponse.message);
                        $('span.user_data_display_area')
                        .load('lib/php/users/user_detail_load.php span.user_data_display_area', {
                            'id': userId,
                            'view': 'display'
                        });
                        oTable.fnReloadAjax();
                        $('#user_detail_window').data('dirty','false');
                        $(window).unbind('beforeunload');
                    }
                });
            }
        });

        //Add chosen to selects
        $('select.supervisor_chooser,select.status_chooser,select.group_chooser').chosen();

        //A little css hack
        $('select.supervisor_chooser').next().css({'margin-top':'10px'});
        $('select.supervisor_chooser').prev().css({'vertical-align':'top'});

        //Listen for changes to name fields, display them.
        $('.user_detail_left input[name="first_name"],.user_detail_left input[name="last_name"]').keyup(function(){
            var fname = $('.user_detail_left input[name="first_name"]').val();
            var lname = $('.user_detail_left input[name="last_name"]').val();
            $('span.name_display').html(fname + ' ' + lname);
        });

        //Add change picture functions
        var uploader = new qq.FileUploader({
            // pass the dom node (ex. $(selector)[0] for jQuery users)
            element: $('div.user_change_picture')[0],
            // path to server-side upload script
            action: 'lib/php/utilities/file_upload_user_image.php',
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
            params: {'preview': 'yes'},
            template: '<div class="qq-uploader">' +
            '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
            '<div class="qq-upload-button">Change Picture</div>' +
            '<ul class="qq-upload-list"></ul>' +
            '</div>',
            onComplete: function(id, fileName, responseJSON) {
                if (!responseJSON.error) {
                    $('div.user_picture').html('<img src="' + responseJSON.img + '">').
                    append('<div class = "user_picture_preview"><img id = "preview" src="' + responseJSON.img + '"></div>');
                }

                //hide any li with info about previously uploaded images
                if ($('ul.qq-upload-list').length > 0) {
                    $('ul.qq-upload-list li:not(:last)').hide();
                }

                //Add jcrop to crop picture
                $('div.user_picture img').Jcrop({
                    aspectRatio: 1,
                    onChange: showPreview,
                    onSelect: updateCoords
                });

                var $preview = $('#preview');

                //Show user selected area in preview panel
                function showPreview(coords) {
                    if (parseInt(coords.w) > 0) {
                        var rx = 100 / coords.w;
                        var ry = 100 / coords.h;
                        var imgWidth = $('div.user_picture img').width();
                        var imgHeight = $('div.user_picture img').height();
                        $preview.css({
                            width: Math.round(rx * imgWidth) + 'px',
                            height: Math.round(ry * imgHeight) + 'px',
                            marginLeft: '-' + Math.round(rx * coords.x) + 'px',
                            marginTop: '-' + Math.round(ry * coords.y) + 'px',
                            visibility: 'visible'
                        }).show();
                    }
                }

                //Get coordinates of user selection
                function updateCoords(c) {
                    $('div.user_picture img').data('cd', {x: c.x,y: c.y,w: c.w,h: c.h});
                }

                //if previously hidden, ensure displayed
                if ($('ul.qq-upload-list, div.crop_msg').css({'display': 'none'})) {
                    $('ul.qq-upload-list, div.crop_msg').show();
                }

                //Prompt user to crop and save
                if ($('div.crop_msg').length < 1) {
                    $('div.qq-uploader').append('<div class="crop_msg">Select the part of the image to be used' +
                    'and then <button class="image_save">Save</button> or <button class="image_cancel">' +
                    'Discard</button></div>');
                }

                //Disable form edit and cancel buttons while
                //we are holding an uploaded image
                $('div.user_detail_edit_actions').find('button').attr('disabled','disabled');
            }
        });
    });
}

//
//Listen for user data editing buttons
//

//Listen for the edit button
$('div.user_detail_actions button.user_edit').live('click',function() {
    var userId = $('.user_data_display_area').attr('data-id');
    userEdit(userId);
});

//Listen for the delete button
$('div.user_detail_actions button.user_delete').live('click',function() {
    var userId = $('.user_data_display_area').attr('data-id');
    var dialogWin = $('<div title="Are you sure?"><p>It is usually best to deactivate, rather than delete,' +
    'a user account.</p><br /><p>To deactivate, click the edit button below and then change the user status.</p>' +
    '<br /> <p>You should only delete if this user account was created by error or as a result of spam. ' +
    'Are you sure you want to delete?</p></div>')
    .dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        buttons: {
            'Yes': function() {
                $.post('lib/php/users/users_process.php', {
                    'action': 'delete',
                    'users': userId
                }, function(data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify(serverResponse.message, true);
                    } else {
                        notify(serverResponse.message);
                        oTable.fnReloadAjax();

                        //Find out if there are any new users to be looked at
                        //If not, remove the 'new' filter
                        $.post('lib/php/users/check_for_new_users.php', function(data) {
                            var serverResponse = $.parseJSON(data);
                            if (parseInt(serverResponse.number) < 1) {
                                fnResetAllFilters();
                            }
                        });
                        $('#user_detail_window').hide('fold', 1000);
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

//
//Listen for image editing buttons
//
$('button.image_cancel').live('click', function(event) {
    event.preventDefault();
    var userId = $('.user_data_display_area').attr('data-id');
    var uploadedImages = [];

    //Get all the images the user has uploaded so we keep
    //the uploads directory clean
    $('span.qq-upload-file').each(function() {
        uploadedImages.push($(this).text().toLowerCase());
    });

    //now delete them
    $.post('lib/php/utilities/file_upload_user_image.php', {'cancel':'yes','del':uploadedImages});

    //Re-show the former image
    $('div.user_picture').load('lib/php/users/user_detail_load.php div.user_picture', {
        'id': userId,
        'view': 'edit'
    });
    $('div.crop_msg').remove();
    $('ul.qq-upload-list').hide();

    //Re-enable the edit save/cancel buttons
    $('div.user_detail_edit_actions').find('button').removeAttr('disabled');
});

$('button.image_save').live('click', function(event){
    event.preventDefault();
    var userId = $('.user_data_display_area').attr('data-id');

    //generate an array of all the images user has uploaded;
    //they will have to be deleted later
    var uploadedImages = [];
    $('span.qq-upload-file').each(function() {
        uploadedImages.push($(this).text().toLowerCase());
    });

    //get user selected coordinates and image name
    var selCoord = $('div.user_picture img').data();

    //Get the last image the user uploaded.  This is the one
    //to be saved.
    var selectedImage = $('span.qq-upload-file:last').text().toLowerCase();

    if (typeof selCoord.cd === 'undefined') {
        notify('<p>Please use your mouse to select the part of the image which will be used.</p>', true);
        return false;
    } else {
        $.post('lib/php/utilities/file_upload_user_image.php', {
            'id': userId,
            'img': selectedImage,
            'del': uploadedImages,
            'x': selCoord.cd.x,
            'y': selCoord.cd.y,
            'h': selCoord.cd.h,
            'w': selCoord.cd.w
        }, function() {
            //now refresh the images displayed to user
            $('p.top_row').load('lib/php/users/user_detail_load.php p.top_row', {'id': userId,'view': 'edit'});
            $('div.user_picture').load('lib/php/users/user_detail_load.php div.user_picture', {'id': userId,'view': 'edit'});

            //hide the upload/crop controls
            $('ul.qq-upload-list, div.crop_msg').hide();

            //Re-enable the edit save/cancel buttons
            $('div.user_detail_edit_actions').find('button').removeAttr('disabled');

            //Reload thumbnails on users table
            oTable.fnReloadAjax();
        });
    }
});
