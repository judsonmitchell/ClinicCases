//Scripts for users page
var oTable;
var aoColumns;

$(document).ready(function() {

    //Load dataTables
    $('#table_users').dataTable(
		{
		"bJQueryUI": true,
		"sAjaxSource": 'lib/php/users/users_load.php',
		"bScrollInfinite": true,
		"bDeferRender": true,
		"bScrollCollapse": true,
		"iDisplayLength": 20,
        "sScrollY": Math.round(0.8 * $('#content').height()),
        "aoColumns":
        [
        { "bSearchable": false, "bVisible": false },
        { "bSortable": false},
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        { "bSearchable": false, "bVisible": false },
        null
        ],
        "sDom":'<"H"lfTri>t',
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
        "oLanguage": {"sInfo": "Found <b>_TOTAL_</b> users","sInfoFiltered": "from a total of <b>_MAX_</b> users"},
		"fnInitComplete": function(){
			$('#processing').hide(); //hide the "loading" div after load.
			//resizes the table whenever parent element size changes
            $(window).bind('resize', function() {
                oTable.fnAdjustColumnSizing();
            });
			}
		});

 });