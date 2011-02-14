


$(document).ready(function(){
		

		$('#table_cases').dataTable( {
			"bProcessing": true,
			"bScrollInfinite": true,
			"bScrollCollapse": true,
			"sScrollY": "400px",
			"iDisplayLength": 50,
			"aaSorting": [[ 2, "asc" ]],
			"aoColumns": [
			{ "bSearchable": false, "bVisible":    false },
			null,
			null,
			null,
			null,
			null,
			null,
			null
			],
			"sDom": 'RTfilrtp',
			"oTableTools": {
						"sSwfPath": "lib/DataTables-1.7.5/extras/TableTools-2.0.0/media/swf/copy_cvs_xls_pdf.swf"
					},
			"sAjaxSource": 'lib/php/data/cases_load.php'
		} );
		
			
	})

