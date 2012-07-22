//Scripts for journals
var oTable;

$(document).ready(function() {

	var tableHeight = $('#content').height() - 100;

	var chooserVal = 'unread';

	oTable = $('#table_journals').dataTable({
		"sAjaxSource": 'lib/php/data/journals_load',
		"aoColumns":
        [
            { "sTitle" : "","bSortable" : false,"sWidth" : "40px"},
            { "sTitle" : "Id","bSearchable" : false,"bVisible" : false},
            { "sTitle" : "Name"},
            { "sTitle" : "Submitted To","bSearchable" : true,"bVisible" : false},
            { "sTitle" : "Text","bVisible" : false},
            { "sTitle" : "Date Submitted","sType": "date"},
            { "sTitle" : "Archived","bVisible" : false},
            { "sTitle" : "Read","bVisible" : false},
            { "sTitle" : "Commented","bVisible" : false},
            { "sTitle" : "Comments","bSearchable" : false,"bVisible" : false}
        ],
        "aaSorting": [[5, "desc"]],
        "bDeferRender": true,
		"bAutoWidth":false,
		"bProcessing": true,
		"bJQueryUI": true,
		"bScrollInfinite": true,
		"sScrollY":tableHeight,
		"iDisplayLength": 30,
		"iScrollLoadGap":200,
		"bSortCellsTop": true,
		"sDom": 'R<"H"fCi>rt<"F"<"journal_action">>',
		"oLanguage": {"sInfo": "Showing <b>_TOTAL_</b> <span id='journalStatus'></span> journals","sInfoFiltered": "from a total of <b>_MAX_</b>","sEmptyTable": "No journals found."},
		"fnInitComplete":function(){

			//Hide processing div
			$('#processing').hide();

			//Have ColVis and reset buttons pick up the DTTT class
            $('div.ColVis button').removeClass()
            .addClass('DTTT_button DTTT_button_collection ui-button ui-state-default');

             //Add journal action selector
            $('div.journal_action').html('<label>With displayed journals:</label><select><option value="" selected=selected>Choose Action</option><option value="print">Print</option><option value="archive">Archive</option><option value="mark_read">Mark Read</option><option value="mark_unread">Mark Unread</option></select>');

			//Add view chooser
            $('div.dataTables_filter').append('<select id="chooser"><option value="unread" selected=selected>Unread</option><option value="read">Read</option><option value="archived">Archived</option><option value="all">All</option></select>');

            //Change the journal status select
            $('#chooser').live('change', function() {

                switch ($(this).val())
                {
                    case 'unread':
                        chooserVal = "unread";
						oTable.fnFilter('^$', oTable.fnGetColumnIndex("Read"), true, false);

                        break;

                    case 'read':
                        chooserVal = "read";
                        oTable.fnFilter('yes', oTable.fnGetColumnIndex("Read"), true, false);
                        break;

                    case 'archived':
                        chooserVal = "archived";
                        oTable.fnFilter('yes', oTable.fnGetColumnIndex("Archived"), true, false);

                        break;

                     case 'all':
                        chooserVal = "all";
                        oTable.fnFilter('', oTable.fnGetColumnIndex("Archived"), true, false);
                        oTable.fnFilter('', oTable.fnGetColumnIndex("Read"), true, false);


                        break;
                }

            });

            //Apply default filter - unread
            oTable.fnFilter('^$', oTable.fnGetColumnIndex("Read"), true, false);

            //Listen for click on table row; open journal
            $('#table_journals tbody').click(function(event) {
                var iPos = oTable.fnGetPosition(event.target.parentNode);
                var aData = oTable.fnGetData(iPos);
                var iId = aData[1];
                callJournal(iId);

            });

            //resizes the table whenever parent element size changes
			$(window).bind('resize', function() {
				oTable.fnDraw(false);
				oTable.fnAdjustColumnSizing();
			});

		},
		"fnDrawCallback":function(){
			$("#journalStatus").text(chooserVal);
		}


		});
});

function callJournal(id)
{
    //Define html for journal window

    if ($('div#journal_detail_window').length < 1)
    {
        var journalDetail = "<div id='journal_detail_window'></div>";

        $("#content").append(journalDetail);
    }

    $("#journal_detail_window").load('lib/php/data/journals_detail_load.php', {'id': id}, function() {
        $(this).show('fold', 1000);

        //Mark journal as read
        // $.post('lib/php/data/journals_process.php',{'id':id,'type':'mark_read'},function(data){
        //         var serverResponse = $.parseJSON(data);
        //         if (serverResponse.error === true)
        //             {notify(serverResponse.message);}
        // });

        //Define and listen for window buttons
        $("div.journal_detail_control button").first()
        .button({icons: {primary: "fff-icon-printer"},label: "Print"})
        .click(function() {
            alert('working on it');
        })
        .next()
        .button({icons: {primary: "fff-icon-cancel"},label: "Close"})
        .click(function() {
            oTable.fnReloadAjax();
            $("#journal_detail_window").hide('fold', 1000);

        });

        //Handle textareas
        $('textarea.expand').livequery(function(){
            $(this).TextAreaExpander(40,300).css({'color':'#AAA'}).bind('focus',function(){
            $(this).val('').css({'color':'black'}).unbind('focus');
            });
        });

        $('a.comment_save').live('click', function(event){
            event.preventDefault();
            var journalId = $(this).closest('div.journal_body').attr('data-id');
            var commentText = $(this).siblings('textarea').val();
            $.post('lib/php/data/journals_process.php',{'type': 'add_comment','id':journalId,'comment_text':commentText},function(data){
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true)
                        {notify(serverResponse.message);}
                    else
                        {
                            $('div.journal_comments').load('lib/php/data/journals_detail_load.php div.journal_comments', {'id': id});
                            notify(serverResponse.message);
                        }
            });

        });



    });
}
