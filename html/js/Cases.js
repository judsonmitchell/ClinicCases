
var oTable;

$(document).ready(function(){
		
		$.idleTimeout('#idletimeout', '#idletimeout a', {
				idleAfter: 1200, //20 minutes
				pollingInterval: 60,
				keepAliveURL: 'lib/php/auth/keep_alive.php',
				serverResponseEquals: 'OK',
				onTimeout: function(){
					$(this).slideUp();
					window.location = "html/templates/Logout.php";
					},
						onIdle: function(){
						$(this).slideDown(); // show the warning bar
									},
						onCountdown: function( counter ){
						$(this).find("span").html( counter ); // update the counter
							},
						onResume: function(){
						$(this).slideUp(); // hide the warning bar
						}
				});

		oTable =	$('#table_cases').dataTable( {
					"bJQueryUI": true,
					"bProcessing": true,
					"bScrollInfinite": true,
					"bScrollCollapse": true,
					"sScrollY": "400px",
					"iDisplayLength": 50,
					"aaSorting": [[ 3, "asc" ]],
					"aoColumns": [
					{ "bSearchable": false, "bVisible":    false },
					{"bVisible": false},
					null,
					null,
					null,
					null,
					null,
					null,
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					null
					],
					"sDom": 'f<"selector">T<"clear"C>irtp',
					"oColVis": {"aiExclude": [ 0 ]},
					"oTableTools": {
								"sSwfPath": "lib/DataTables-1.7.5/extras/TableTools-2.0.0/media/swf/copy_cvs_xls_pdf.swf",
								"aButtons": [{
									"sExtends":    "collection",
									"sButtonText": "Print/Export",
									"aButtons":    [ "csv", "xls", "pdf","print" ],
									}]
							},
					"sAjaxSource": 'lib/php/data/cases_load.php'
				});
		
				$("div.selector").html('<select id="chooser"><option value="open" selected=selected>Open Cases Only</option><option value="closed">Closed Cases Only</option><option value="all">All Cases</option></select>  <a href="#" id="set_advanced">Advanced Search</a>');
	
		$('#table_cases tbody').click( function () {
			var iPos = oTable.fnGetPosition( event.target.parentNode );
			var aData = oTable.fnGetData( iPos );
			var iId = aData[0];
			alert(iId);
		})
		
		$('#chooser').change(function(){
			
			switch ($(this).val())
			{
				case 'all':
				oTable.fnFilter('',5);
				break;
				
				case 'open':
				oTable.fnFilter( '^$', 5, true, false );
				break;
				
				case 'closed':
				oTable.fnFilter( '^.+$', 5, true, false );
				break;
			}

			});
		$('#set_advanced').click(function(){
			event.preventDefault();
			$('thead tr.advanced').toggle('slow')
			})
		
	oTable.fnFilter( '^$', 5, true, false );

});

