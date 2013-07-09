//Get url parameters
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$(document).ready(function() {
    //Select correct subtab based on url
    var tab = getParameterByName('tabsection');

    if (tab.length) {
        $('#myTab a[href="#' + tab + '"]').tab('show');
    } else {
        $('#myTab a.default-tab').tab('show');
    }

    //Adds tabsection to url for tab-panes which will have
    //multiple levels; preserves navigation by back button
    $('#myTab a.multi-level').click(function () {
        var current = document.location.search;
        var addTab = $(this).attr('href').substring(1);
        document.location.search = current + '&tabsection=' + addTab;
    });

    //Handle document downloads
    $('a.doc-item').click(function () {
        var itemId = $(this).attr('data-id');
        var itemExt = $(this).attr('data-ext');
        if (itemExt === 'url') {
            $.post('lib/php/data/cases_documents_process.php',
            {'item_id': itemId, 'action': 'open', 'doc_type': 'document'},
            function (data) {
                var serverResponse = $.parseJSON(data);
                window.open(serverResponse.target_url, '_blank');
            });
        } else if (itemExt === 'ccd') {
            $.post('lib/php/data/cases_documents_process.php',
            {'item_id': itemId, 'action': 'open', 'doc_type': 'document'},
            function (data) {
                var serverResponse = $.parseJSON(data);
                var ccdItem = '<a class="ccd-clear" href="#">Back</a><h2>' + unescape(serverResponse.ccd_title) + '</h2>' + serverResponse.ccd_content;
                var hideList = $('.doc-list').detach();
                $('#caseDocs').append(ccdItem);
                console.log(serverResponse);
                //Close a ccd document after viewing
                $('.tab-content').on('click', 'a.ccd-clear', function () {
                    $('#caseDocs').html('').append(hideList);
                });
            });
        } else {
            $.download('lib/php/data/cases_documents_process.php', {'item_id': itemId, 'action': 'open', 'doc_type': 'document'});
        }
    });

});
