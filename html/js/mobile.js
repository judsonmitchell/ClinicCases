//Get url parameters
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$(document).ready(function () {
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

    //Display cases based on open/closed status
    $('select[name="case-status"]').change(function () {
        $('li.table-case-item').removeClass('search-result-hit search-result-miss');
        $('.table-case-item').toggle();
    });

    //Search Cases
    $('input.case-search').keyup(function () {
        var searchVal = $(this).val().toLowerCase();;
        var caseStatus = $('select[name="case-status"]').val();
        var targetClass;
        if (caseStatus === 'open') {
            targetClass = 'table-case-open';
        } else {
            targetClass = 'table-case-closed';
        }
        $('li.table-case-item').removeClass('search-result-hit search-result-miss');
        $('li.' + targetClass).each(function () {
            if ($(this).find('a').text().toLowerCase().indexOf(searchVal) !== -1) {
                $(this).addClass('search-result-hit');
            } else {
                $(this).addClass('search-result-miss');
            }
        });

    });

    //Hide system case id
    $('#caseData dd:eq(0)').hide();
    $('#caseData dt:eq(0)').hide();

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
                var ccdItem = '<a class="ccd-clear" href="#">Back</a><h2>' +
                unescape(serverResponse.ccd_title) + '</h2>' + serverResponse.ccd_content;
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

    //Add chosen to selects
    //Must initialize with size on hidden div: see https://github.com/harvesthq/chosen/issues/1297
    $('#ev_users').chosen({ width: '16em' });

    //Submit Quick Adds
    //Case notes
    $.validator.addMethod('timeReq', function (value) {
        return !(value === '0' && $('select[name="csenote_hours"]').val() === '0');
    }, 'You must enter some time.');

    $('form[name="quick_cn"]').validate({
        errorClass: 'text-error',
        errorElement: 'span',
        rules: {
            csenote_minutes: {timeReq: true}
        }
    });

    $('form[name="quick_cn"]').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var dateVal = $('select[name="c_month"]').val() + '/' + $('select[name="c_day"]').val() + '/' + $('select[name="c_year"]').val();
        $('input[name="csenote_date"]').val(dateVal);

        $.post('lib/php/data/cases_casenotes_process.php', form.serialize(), function (data) {
            var serverResponse = $.parseJSON(data);
            if (serverResponse.error === true) {
                $('p.error').html(serverResponse.message);
            } else {
                var successMsg = '<p class="text-success">' + serverResponse.message +
                '</p><p><a class="btn show-form" href="#">Add Another?</a></p>';
                form[0].reset();
                var hideForm = $('form[name="quick_cn"]').detach();
                $('#qaCaseNote').append(successMsg);
                $('a.show-form').click(function (event) {
                    event.preventDefault();
                    $('#qaCaseNote').html('').append(hideForm);
                });
            }
        });

    });

    //Case events
    $('form[name="quick_event"]').validate({
        errorClass: 'text-error',
        errorElement: 'span'
    });

    //Convenience method for advancing end date
    $('form[name="quick_event"] div.date-picker:eq(0) select').change(function () {
        var el = $(this).attr('name');
        $(this).closest('.date-picker').siblings('.date-picker').find('select[name=' + el + ']').val($(this).val());
    });

    $('form[name="quick_event"]').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var startVal = $('select[name="c_month"]').eq(0).val() + '/' + $('select[name="c_day"]').eq(0).val() +
        '/' + $('select[name="c_year"]').eq(0).val() + ' ' +  $('select[name="c_hours"]').eq(0).val() +
        ':' + $('select[name="c_minutes"]').eq(0).val() +
        ' ' + $('select[name="c_ampm"]').eq(0).val();
        $('input[name="start"]').val(startVal);

        var endVal = $('select[name="c_month"]').eq(1).val() + '/' + $('select[name="c_day"]').eq(1).val() +
        '/' + $('select[name="c_year"]').eq(1).val() + ' ' +  $('select[name="c_hours"]').eq(1).val() +
        ':' + $('select[name="c_minutes"]').eq(1).val() +
        ' ' + $('select[name="c_ampm"]').eq(1).val();
        $('input[name="end"]').val(endVal);

        //serialize form values
        var evVals = form.not('select[name="responsibles"]').serializeArray();
        var resps = form.find('select[name="responsibles"]').val();
        var resps_obj = $.extend({}, resps);
        evVals.unshift(resps_obj); //put this object at the beginning
        var allDayVal = null;
        if (form.find('input[name = "all_day"]').is(':checked')) {
            allDayVal = 'on';
        } else {
            allDayVal = 'off';
        }

        $.post('lib/php/data/cases_events_process.php', {
            'task': form.find('input[name = "task"]').val(),
            'where': form.find('input[name = "where"]').val(),
            'start': form.find('input[name = "start"]').val(),
            'end': form.find('input[name = "end"]').val(),
            'all_day': allDayVal,
            'notes': form.find('textarea[name = "notes"]').val(),
            'responsibles': resps,
            'action': 'add',
            'case_id': form.find('select[name = "case_id"]').val()
        }, function (data) {
            var serverResponse = $.parseJSON(data);
            if (serverResponse.error === true) {
                $('p.error').html(serverResponse.message);
            } else {
                var successMsg = '<p class="text-success">' + serverResponse.message +
                '</p><p><a class="btn show-form" href="#">Add Another?</a></p>';
                form[0].reset();
                $('#ev_users').trigger('liszt:updated')
                var hideForm = $('form[name="quick_event"]').detach();
                $('#qaEvent').append(successMsg);
                $('a.show-form').click(function (event) {
                    event.preventDefault();
                    $('#qaEvent').html('').append(hideForm);
                });
            }
        });

    });

    //Case contacts
    $('form[name="quick_contact"]').validate({
        errorClass: 'text-error',
        errorElement: 'span'
    });

    $('form[name="quick_contact"]').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var phoneData = {};
        phoneData[$('#qaContact select[name="phone_type"]').val()] = $('#qaContact input[name="phone"]').val();
        var phone = JSON.stringify(phoneData);
        var emailData = {};
        emailData[$('#qaContact select[name="email_type"]').val()] = $('#qaContact input[name="email"]').val();
        var email = JSON.stringify(emailData);
        console.log(phone + ' and ' + email);
        $.post('lib/php/data/cases_contacts_process.php', {
                'first_name': form.find('input[name = "first_name"]').val(),
                'last_name': form.find('input[name = "last_name"]').val(),
                'organization': form.find('input[name = "organization"]').val(),
                'contact_type': form.find('select[name = "contact_type"]').val(),
                'address': form.find('textarea[name = "address"]').val(),
                'city': form.find('input[name = "city"]').val(),
                'state': form.find('select[name = "state"]').val(),
                'zip': form.find('input[name = "zip"]').val(),
                'phone': phone,
                'email': email,
                'url': form.find('input[name = "url"]').val(),
                'notes': form.find('textarea[name = "notes"]').val(),
                'action': 'add',
                'case_id': form.find('select[name = "case_id"]').val()
            }, function (data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        $('p.error').html(serverResponse.message);
                    } else {
                        var successMsg = '<p class="text-success">' + serverResponse.message +
                        '</p><p><a class="btn show-form" href="#">Add Another?</a></p>';
                        form[0].reset();
                        var hideForm = $('form[name="quick_contact"]').detach();
                        $('#qaContact').append(successMsg);
                        $('a.show-form').click(function (event) {
                            event.preventDefault();
                            $('#qaContact').html('').append(hideForm);
                        });
                    }
                });
    });
    
    //Case sections
    $('.li-expand > a').click(function (event) {
        event.preventDefault();
        $(this).parent().find('ul').toggle();
    });
});
