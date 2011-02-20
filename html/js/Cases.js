//init

var oTable;
var asInitVals = new Array();
var selectCols = Array('Case Type','Gender','Race','Disposition');

//function to create selects for advanced search

(function($) {
/*
 * Function: fnGetColumnData
 * Purpose:  Return an array of table values from a particular column.
 * Returns:  array string: 1d data array 
 * Inputs:   object:oSettings - dataTable settings object. This is always the last argument past to the function
 *           int:iColumn - the id of the column to extract the data from
 *           bool:bUnique - optional - if set to false duplicated values are not filtered out
 *           bool:bFiltered - optional - if set to false all the table data is used (not only the filtered)
 *           bool:bIgnoreEmpty - optional - if set to false empty values are not filtered from the result array
 * Author:   Benedikt Forchhammer <b.forchhammer /AT\ mind2.de>
 */

$.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {

	// check that we have a column id
	if ( typeof iColumn == "undefined" ) {return new Array();}

	// by default we only wany unique data
	if ( typeof bUnique == "undefined" ) bUnique = true;
	
	// by default we do want to only look at filtered data
	if ( typeof bFiltered == "undefined" ) bFiltered = true;
	
	// by default we do not wany to include empty values
	if ( typeof bIgnoreEmpty == "undefined" ) bIgnoreEmpty = true;
	
	// list of rows which we're going to loop through
	var aiRows;
	
	// use only filtered rows
	if (bFiltered == true) aiRows = oSettings.aiDisplay; 
	// use all rows
	else aiRows = oSettings.aiDisplayMaster; // all row numbers
	
	//debug
	//if (oSettings.aiDisplayMaster.length < 1)
	//{alert('this array is empty');}
	
	// set up data array	
	var asResultData = new Array();
	
	for (var i=0,c=aiRows.length; i<c; i++) {
		iRow = aiRows[i];
		var aData = this.fnGetData(iRow);
		var sValue = aData[iColumn];
		
		// ignore empty values?
		if (bIgnoreEmpty == true && sValue.length == 0) continue;

		// ignore unique values?
		else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) continue;
		
		// else push the value onto the result data array
		else asResultData.push(sValue);
	}
	
	return asResultData;
}}(jQuery));


function fnCreateSelect( aData )
{

	var r='<select><option value=""></option>', i, iLen=aData.length;
	for ( i=0 ; i<iLen ; i++ )
	{
		r += '<option value="'+aData[i]+'">'+aData[i]+'</option>';
	}
	return r+'</select>';
}


//End function to create selects for advanced search

//Function to get the index of a column by column name
(function($) {

/*
 * Function: fnGetColumnIndex
 * Purpose:  Return an integer matching the column index of passed in string representing sTitle
 * Returns:  int:x - column index, or -1 if not found
 * Inputs:   object:oSettings - automatically added by DataTables
 *           string:sCol - required - string matching the sTitle value of a table column
 */
$.fn.dataTableExt.oApi.fnGetColumnIndex = function ( oSettings, sCol ) 
{
	var cols = oSettings.aoColumns;
	for ( var x=0, xLen=cols.length ; x<xLen ; x++ )
	{
		if ( cols[x].sTitle.toLowerCase() == sCol.toLowerCase() )
		{
			return x;
		};
	}
	return -1;
}
}(jQuery))
//End

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
					"oColVis": {"aiExclude": [ 0 ],"bRestore":true},
					"oTableTools": {
								"sSwfPath": "lib/DataTables-1.7.5/extras/TableTools-2.0.0/media/swf/copy_cvs_xls_pdf.swf",
								"aButtons": [{
									"sExtends":    "collection",
									"sButtonText": "Print/Export",
									"aButtons":    [ "csv", "xls", "pdf","print" ],
									}]
							},
					"sAjaxSource": 'lib/php/data/cases_load.php',
					"fnInitComplete": function() {
						i=-1;
						$("thead tr.advanced th.addSelects").each(function(){
							//colTitle = this.text();//wont work; need the previous sibling
							i++
							realIndex = oTable.fnGetColumnIndex(selectCols[i])
							this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(realIndex) );

							})

						}	
					
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
			
//Code for advanced search using inputs
		$("thead input").keyup(function () {
			//oTable.fnFilter( this.value, $(this).attr('column') );
			oTable.fnFilter( this.value, oTable.oApi._fnVisibleToColumnIndex( oTable.fnSettings(),$("thead input").index(this) ) );
			
			});
			
		$("thead input").each( function (i) {
		asInitVals[i] = this.value;
			} );
	
		$("thead input").live("focus",function () {
			if ( this.className == "search_init" )
				{
				this.className = "";
				this.value = "";
				//alert($(this).attr('column'));
				}
			} );
	
		$("thead input").live("blur",function (i) {
			if ( this.value == "" )
			{
				this.className = "search_init";
				this.value = asInitVals[$("thead input").index(this)];
			}
			} );
	


		

	//When page loads, default filter is applied: open cases	
	oTable.fnFilter( '^$', 5, true, false );
	
	//Provides filter for the selects
	$("thead th.addSelects").each( function ( i ) {
		$('select', this).change( function () {
		oTable.fnFilter( $(this).val(), i );
			})
		})
		
	
			

});

