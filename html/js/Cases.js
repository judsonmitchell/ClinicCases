//init

var oTable;
var asInitVals = new Array();
var defaultHiddenColumns = Array('0','1','8','9','10','11','12');//refers to header rows in cases.php

//function to handle dates: search by date or search by date range
//function dateRange(range,date)
	//{	
		//switch(range)
		//{
			//case '=':
			//oTable.fnFilter( this.value, $("#date_open").attr('column'))
			//break;
					
			//case '>':
			
				
				 //oTable.fnDraw();	
			//break;
					
			//case '<':
				//$.fn.dataTableExt.afnFiltering.push(
				//function( oSettings, aData, iDataIndex){ 

					//if (aData[4] < date )
						//{return true;}
						//else
						//{return false;}
					//})
			
				 //oTable.fnDraw();						
			//break;
		//}
		
		
		
	//}


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

	var r='<select class="fltr_select"><option value=""></option>', i, iLen=aData.length;
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
					"sScrollX": "100%",
					"bScrollCollapse": true,
					"iDisplayLength": 50,
					"aaSorting": [[ 3, "asc" ]],
					"aoColumns": [
					{ "bSearchable": false, "bVisible":    false },
					{"bVisible": false},
					null,
					null,
					{"sWidth":"15%"},
					{"sWidth":"15%"},
					null,
					null,
					null,
					null,
					null,
					null,
					null,
					null
					],
					"sDom": 'f<"selector">T<"clear"C>i<"reset">rtp',
					"oColVis": {"aiExclude": [ 0 ],"bRestore":true},
					"oTableTools": {
								"sSwfPath": "lib/DataTables-1.7.5/extras/TableTools-2.0.0/media/swf/copy_cvs_xls_pdf.swf",
								"aButtons": [
									{
									"sExtends":    "collection",
									"sButtonText": "Print/Export",
									"aButtons":    [ 
											{	"sExtends":"csv",
												"mColumns":"visible"
											},
											
											{	"sExtends":"xls",
												"mColumns":"visible"
											},
											
											{	"sExtends":"pdf",
												"mColumns":"visible"
											},
											
											{	"sExtends":"print",
												"mColumuns":"visible",
												"sInfo":"Use your browser's print function to print.  Press Esc when done."	
												
											}
										]
									 }
									]
							},
					"sAjaxSource": 'lib/php/data/cases_load.php',
					"fnInitComplete": function() {
						$("div.dataTables_scrollHeadInner thead th.addSelects").each(function(){
							
							this.innerHTML = fnCreateSelect( oTable.fnGetColumnData($(this).attr('column'),true,false,true));		

							})
							
							//Important: After the selects have been rendered, set visibilities.  This allows the hidden selects to get the proper values.  See http://datatables.net/forums/comments.php?DiscussionID=3318
							
							for (var c in defaultHiddenColumns)
							{oTable.fnSetColumnVis(defaultHiddenColumns[c],false);}
							
						}
					
				});
				
		
		$("div.selector").html('<select id="chooser"><option value="open" selected=selected>Open Cases Only</option><option value="closed">Closed Cases Only</option><option value="all">All Cases</option></select>  <a href="#" id="set_advanced">Advanced Search</a>');
	
		$('#table_cases tbody').click( function () {
			var iPos = oTable.fnGetPosition( event.target.parentNode );
			var aData = oTable.fnGetData( iPos );
			var iId = aData[0];
			alert(iId);
		})
		
		//Change the case status select
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
		
		//Set css for advanced date function; make room for the operator selects 	
		$('#set_advanced').click(function(){
			event.preventDefault();			
			$(".complex").children().css({'display' : 'inline','margin-bottom' : '0px'});	
			$("#date_open , #date_close").css('width','65%');
			$('thead tr.advanced').toggle('slow')
			oTable.fnDraw();
			})
			
		$('#addOpenRow , #addCloseRow').click(function(){
			event.preventDefault();
			$(this).text('AND IS');
			$(".complex").children().css({'display' : 'inline','margin' : '0px'});	
			$("#date_open_2 , #date_close_2").css({'width':'60%'});
			$('thead tr.advanced_2').toggle('slow')
			

		})
		
		//Code for advanced search using inputs
		$("thead input").keyup(function () {
			
			parent = $(this).parent();
			colIndex = parent.attr('column');
			oTable.fnFilter( this.value, $(this).attr('column') );
			
			
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
	
	//resizes the table whenever parent element size changes
	$(window).bind('resize', function () {
		oTable.fnAdjustColumnSizing();
	} );
	
	//Enable search via selects in advanced search
	$("div.dataTables_scrollHeadInner tr.advanced th.addSelects select").live('change',function(){
		parent = $(this).parent();
		colIndex = parent.attr('column');
		oTable.fnFilter(this.value,colIndex)
		})
	
	//Add datepickers	
	$(function() {
		$( "#date_open , #date_close, #date_open_2, #date_close_2" ).datepicker({	
			changeMonth: true,
			changeYear: true,		
			onSelect:function(){
					oTable.fnDraw();
				}
		})
	});
	
	

	//Reset displayed data
	function fnResetAllFilters() {
		var oSettings = oTable.fnSettings();
		
		//reset advanced header selects
		for(iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
			oSettings.aoPreSearchCols[ iCol ].sSearch = '';
		}
		
		//reset the main filter
		oTable.fnFilter('');
		
		//reset the user display for inputs and selects
		$("input").each(function(){this.value=''});
		$("select").each(function(){this.selectedIndex='0'});
		
		//return to default open cases filter
		oTable.fnFilter( '^$', 5, true, false );
		
		//return to default sort - Last Name
		oTable.fnSort([[3,'asc']]);
		
		//redraw the table so that all columns line up
		oTable.fnDraw();
		
		//reset the default values for advanced search
		//$("thead input").each( function (i) {
			//this.value = asInitVals[$("thead input").index(this)];
			//this.className = "search_init"
			//});
		
	}
	
	//Add the reset button
	$(".reset").html("<button>Reset</button>");
	$(".reset").click(function(){fnResetAllFilters()});
	
	



});

$.fn.dataTableExt.afnFiltering.push(
	
	function( oSettings, aData, iDataIndex){ 
		
		var opOperator = document.getElementById('open_range').value;
		var opOperator2 = document.getElementById('open_range_2').value;
		var clOperator = document.getElementById('close_range').value;
		var clOperator2 = document.getElementById('close_range_2').value;
		var opFieldRaw = document.getElementById('date_open').value;
		var opFieldRaw2 = document.getElementById('date_open_2').value;
		var clFieldRaw = document.getElementById('date_close').value;
		var clFieldRaw2 = document.getElementById('date_close_2').value;		
		var opRowRaw = aData[4];
		var clRowRaw = aData[5];
		
		//date conversions
		
		var opField = opFieldRaw.substring(6,10) + opFieldRaw.substring(0,2)  + opFieldRaw.substring(3,5);
		var opField2 = opFieldRaw2.substring(6,10) + opFieldRaw2.substring(0,2)  + opFieldRaw2.substring(3,5);
		var clField = clFieldRaw.substring(6,10) + clFieldRaw.substring(0,2)  + clFieldRaw.substring(3,5);
		var clField2 = clFieldRaw2.substring(6,10) + clFieldRaw2.substring(0,2)  + clFieldRaw2.substring(3,5);
		var opRow = opRowRaw.substring(6,10) + opRowRaw.substring(0,2)  + opRowRaw.substring(3,5);
		var clRow = clRowRaw.substring(6,10) + clRowRaw.substring(0,2)  + clRowRaw.substring(3,5);
		
			//Basic open field sorting
			if ( opField == '' && clField == '' )
				{
					return true;
				}
			
			else if (opField2 == '' && opOperator == 'equals' && opRow == opField )
				{
					return true;	
				}
				
			else if (opField2 == '' && opOperator == 'greater' && opRow > opField)
				{
					return true;
				}
				
			else if (opField2 == '' && opOperator == 'less' && opRow < opField)
				{
					return true;
				}
			
			//Basic closed field sorting	 
			else if (clField2 == '' && clField !== '' && clOperator == 'equals' && clRow == clField)
				{
					return true;
				}
				
			else if (clField2 == '' && clField !== '' && clOperator == 'greater' && clRow > clField)
				{
					return true;
				}
				
			else if (clField2 == '' && clField !== '' && clOperator == 'less' && clRow < clField)
				{
					return true;
				}
			//Complex (two conditions) open field sorting
			else if (opField2 != '' && opOperator == 'equals' && opRow == opField && opOperator2 == 'equals' && opRow == opField2)
				{
					return true;
				}
			
			else if (opField2 != '' && opOperator == 'less' && opRow < opField && opOperator2 == 'greater' && opRow > opField2)
				{
					return true;
				}
				
			else if (opField2 !== '' && opOperator == 'greater' && opRow > opField && opOperator2 == 'less' && opRow < opField2)
				{
					return true;
				}
			//Complex (two conditions closed field sorting
			else if (clField != '' && clOperator == 'equals' && clRow == clField && clOperator2 == 'equals' && clRow == clField2)
				{
					return true;
				}
				
			else if (clField != '' && clOperator == 'greater' && clRow > clField && clOperator2 == 'less' && clRow < clField2)
				{
					return true;
				}
				
			else if (clField != '' && clOperator == 'less' && clRow < clField && clOperator2 == 'greater' && clRow > clField2)
				{
					return true;
				}
				return false;
		}
	)

