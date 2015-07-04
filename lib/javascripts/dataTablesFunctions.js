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

    $.fn.dataTableExt.oApi.fnGetColumnData = function(oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty) {

        // check that we have a column id
        if (typeof iColumn === 'undefined') {
            return [];
        }

        // by default we only wany unique data
        if (typeof bUnique === 'undefined')
            bUnique = true;

        // by default we do want to only look at filtered data
        if (typeof bFiltered === 'undefined')
            bFiltered = true;

        // by default we do not wany to include empty values
        if (typeof bIgnoreEmpty === 'undefined')
            bIgnoreEmpty = true;

        // list of rows which we're going to loop through
        var aiRows;

        // use only filtered rows
        if (bFiltered === true)
            aiRows = oSettings.aiDisplay;
        // use all rows
        else
            aiRows = oSettings.aiDisplayMaster; // all row numbers

        //debug
        if (oSettings.aiDisplayMaster.length < 1) {
           // alert('this array is empty');
        }

        // set up data array
        var asResultData = [];

        for (var i = 0, c = aiRows.length; i < c; i++) {
            iRow = aiRows[i];
            var aData = this.fnGetData(iRow);
            var sValue = aData[iColumn];

            // ignore empty values?
            if (bIgnoreEmpty === true && sValue.length === 0)
                continue;

            // ignore unique values?
            else if (bUnique === true && jQuery.inArray(sValue, asResultData) > -1)
                continue;

            // else push the value onto the result data array
            else
                asResultData.push(sValue);
        }

        return asResultData;
    };
}(jQuery));


function fnCreateSelect(aData) {

    var r = '<select class="fltr_select"><option value=""></option>', i, iLen = aData.length;
    for (i = 0; i < iLen; i++) {
        r += '<option value="' + aData[i] + '">' + aData[i] + '</option>';
    }
    return r + '</select>';
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
    $.fn.dataTableExt.oApi.fnGetColumnIndex = function(oSettings, sCol) {
        var cols = oSettings.aoColumns;

        //strip underscores from name attribute, if necessary
        if (sCol.indexOf('_') !== '-1') {
            sCol = sCol.replace('_', ' ');
        }
        for (var x = 0, xLen = cols.length; x < xLen; x++) {
            if (cols[x].sTitle.toLowerCase() === sCol.toLowerCase()) {
                return x;
            }
        }
        return -1;
    };
}(jQuery));

//Function to refresh DataTables via ajax source
$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw ) {
    if ( typeof sNewSource !== 'undefined' && sNewSource !== null ) {
        oSettings.sAjaxSource = sNewSource;
    }
    this.oApi._fnProcessingDisplay(oSettings, true);
    var that = this;
    var iStart = oSettings._iDisplayStart;
    var aData = [];

    this.oApi._fnServerParams( oSettings, aData );

    oSettings.fnServerData( oSettings.sAjaxSource, aData, function(json) {
        /* Clear the old information from the table */
        that.oApi._fnClearTable( oSettings );

        /* Got the data - add it to the table */
        var aData =  (oSettings.sAjaxDataProp !== '') ?
            that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;

        for ( var i=0 ; i<aData.length ; i++ ) {
            that.oApi._fnAddData( oSettings, aData[i] );
        }

        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
        that.fnDraw();

        if (typeof bStandingRedraw !== 'undefined' && bStandingRedraw === true) {
            oSettings._iDisplayStart = iStart;
            that.fnDraw( false );
        }

        that.oApi._fnProcessingDisplay( oSettings, false );

        /* Callback user function - for event handlers etc */
        if ( typeof fnCallback === 'function' && fnCallback !== null ) {
            fnCallback( oSettings );
        }
    }, oSettings );
};

/*
* Function: fnGetFilteredData()
* Purpose:  Retrieve an array with all data that survived filtering
* by mikej
*/

$.fn.dataTableExt.oApi.fnGetFilteredData = function ( oSettings ) {
        var a = [];
        for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ ) {
            a.push(oSettings.aoData[ oSettings.aiDisplay[i] ]._aData);
        }

        return a;
};

//End
