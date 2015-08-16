 //
// Scripts for contacts panel on cases tab
//

/* global validContact, notify, checkConflicts, elPrint  */

function sizeContacts(contacts, panelTarget) {
    var windowHeight = panelTarget.height();
    var minContactHeight = panelTarget.height() * 0.4;
    contacts.not('.new_contact').each(function() {
        //cache the height values for use later
        $(this).data('maxContactHeight', $(this).height());
        $(this).data('minContactHeight', minContactHeight);

        var notePercent = $(this).height() / windowHeight * 100;
        if (notePercent > 40) {
            $(this).height(minContactHeight);
            $(this).css({'overflow': 'hidden'});
            $(this).append('<div class="contact_more"><a href="#">More</a></div>');
        }
    });
}

function doEditSelect(val, type) {
    var phoneOptions = ['mobile', 'home', 'office', 'fax'];
    var emailOptions = ['work', 'home', 'other'];
    var selectOptions = '';
    var i;

    if (type === 'phone') {
        for (i = 0; i < phoneOptions.length; i++) {
            if (phoneOptions[i] === val) {
                selectOptions += '<option value = "' + phoneOptions[i] + '" selected=selected>' +
                phoneOptions[i] + '</option>';
            } else {
                selectOptions += '<option value = "' + phoneOptions[i] + '">' + phoneOptions[i] + '</option>';
            }
        }

        selectOptions += '<a href="#" class="add_phone">Add Another</a>';
    } else {
        for (i = 0; i < emailOptions.length; i++) {
            if (emailOptions[i] === val) {
                selectOptions += '<option value = "' + emailOptions[i] + '" selected=selected>' +
                emailOptions[i] + '</option>';
            } else {
                selectOptions += '<option value = "' + emailOptions[i] + '">' + emailOptions[i] + '</option>';
            }
        }
    }
    return selectOptions;
}

//html widgets

var phoneWidget = '<p class="contact_phone_group"><label>Phone</label><select name="phone_type" ' +
    'class="contact_phone_type"><option value="mobile">mobile</option><option value="home">home</option>' +
    '<option value="office">office</option><option value="fax">fax</option><option value="other">other</option>' +
    '</select><input type="text" name="phone" class="contact_phone_value"><a href="#" class="add_phone">Add Another</a>';


var emailWidget = '<p class="contact_email_group"><label>Email</label><select name="email_type"' +
    'class="contact_email_type"><option value="work">work</option><option value="home">home</option>' +
    '<option value="other">other</option></select><input type="text" name="email" class="contact_email_value">' +
    '<a href="#" class="add_email">Add Another</a>';

//User clicks on Contacts in left-side navigation

$('.case_detail_nav #item6').live('click', function() {
    var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');

    //Get heights
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var contactsWindowHeight = thisPanelHeight - toolsHeight;

    thisPanel.load('lib/php/data/cases_contacts_load.php', {
        'case_id': caseId
    }, function() {
        //Set css
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': contactsWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '30%'});
        $('div.case_detail_panel_tools_right').css({'width': '70%'});

        //Set buttons
        $('button.new_contact').button({
            icons: {primary: 'fff-icon-vcard-add'},
            text: true
        }).next().button({
            icons: {primary: 'fff-icon-printer'},
            text: true
        });

        //Round Corners
        $('div.csenote').addClass('ui-corner-all');

        //Size
        sizeContacts($(this).find('.contact'), thisPanel);

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
                $(this).removeClass('csenote_shadow');
            } else {
                $(this).addClass('csenote_shadow');
            }
        });
    });
});

//Listeners

//show hidden text on clipped contact
$('div.contact_more').live('click', function(event) {
    event.preventDefault();
    var contactParent = $(this).closest('.contact');
    var contactParentMaxHeight = $(this).closest('.contact').data('maxContactHeight');
    var contactParentMinHeight = $(this).closest('.contact').data('minContactHeight');

    if ($(this).find('a').html() === 'Less') {
        contactParent.css({'height': contactParentMinHeight});
        $(this).find('a').html('More');
    } else {
        contactParent.css({'height': contactParentMaxHeight});
        $(this).find('a').html('Less');
    }
});

//Add contact
$('.case_detail_panel_tools_right button.new_contact').live('click', function() {
    //make sure contacts are scrolled to top
    $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes').scrollTop(0);

    //display the new contact widget
    var addContactWidget = $(this).closest('.case_detail_panel_tools').siblings().find('div.new_contact');
    addContactWidget.show();

    //reduce opacity on the previously entered contact
    $(this).closest('.case_detail_panel_tools')
        .siblings()
        .find('div.contact')
        .not('div.csenote_new')
        .css({'opacity': '.5'});

    //Apply comboxbox
    addContactWidget.find('select[name = "contact_type"]').combobox();
    $(this).closest('.case_detail_panel_tools').siblings().find('span.contact_phone_widget').html(phoneWidget);
    $(this).closest('.case_detail_panel_tools').siblings().find('span.contact_email_widget').html(emailWidget);

    //User cancels adding new contact
    $(this).closest('.case_detail_panel_tools').siblings().find('button.contact_action_cancel').click(function(event) {

        event.preventDefault();
        //reset form
        addContactWidget.find('form')[0].reset();
        addContactWidget.find('span.first_name_live').html('New Contact');
        addContactWidget.find('span.last_name_live').html('');
        addContactWidget.find('span.contact_type_live').html('');

        //reset opacity of other contacts
        $(this).closest('.case_detail_panel_casenotes').find('.contact').css({'opacity': '1'});
        //hide the widget
        $(this).closest('.csenote_new').hide();
        addContactWidget.find('select[name = "contact_type"]').combobox('destroy');
    });

    //User adds new contact
    $(this).closest('.case_detail_panel_tools').siblings().find('button.contact_action_submit').click(function(event) {
        event.preventDefault();
        //Do validation
        var contactForm = $(this).closest('form');
        var contactVals = contactForm.serializeArray();

        //get errors, if any
        var errString = validContact(contactForm);

        //notify user or errors or submit form
        if (errString.length) {
            notify(errString, true);
        } else {
            //Make objects of phone/email types and phone numbers/email addresses.
            //Store each object in one db field, allowing user to enter unlimited email addresses and phone numbers.

            var phoneData = {};
            contactForm.find('p.contact_phone_group').each(function () {
                var phoneKey = $(this).find('select.contact_phone_type').val();
                var phoneValue = $(this).find('input.contact_phone_value').val();
                if (phoneValue.length) {
                    phoneData[phoneKey] = phoneValue;
                }
            });

            var phoneJson = JSON.stringify(phoneData);

            var emailData = {};
            contactForm.find('p.contact_email_group').each(function () {
                var emailKey = $(this).find('select.contact_email_type').val();
                var emailValue = $(this).find('input.contact_email_value').val();
                if (emailValue.length) {
                    emailData[emailKey] = emailValue;
                }
            });

            var emailJson = JSON.stringify(emailData);
            var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
            var target = $(this).closest('.case_detail_panel_casenotes');
            addContactWidget.find('select[name = "contact_type"]').combobox('destroy');

            $.post('lib/php/data/cases_contacts_process.php', {
                'first_name': contactForm.find('input[name = "first_name"]').val(),
                'last_name': contactForm.find('input[name = "last_name"]').val(),
                'organization': contactForm.find('input[name = "organization"]').val(),
                'contact_type': contactForm.find('select[name = "contact_type"]').val(),
                'address': contactForm.find('textarea[name = "address"]').val(),
                'city': contactForm.find('input[name = "city"]').val(),
                'state': contactForm.find('select[name = "state"]').val(),
                'zip': contactForm.find('input[name = "zip"]').val(),
                'phone': phoneJson,
                'email': emailJson,
                'url': contactForm.find('input[name = "url"]').val(),
                'notes': contactForm.find('textarea[name = "notes"]').val(),
                'action': 'add',
                'case_id': caseId
            }, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true) {
                    notify(serverResponse.message, true);
                } else {
                    notify(serverResponse.message);
                    target.load('lib/php/data/cases_contacts_load.php div.case_detail_panel_casenotes', {
                        'case_id': caseId
                    });
                    //check Conflicts; see casesCaseDetail.js
                    checkConflicts(caseId);
                }
            });
        }
    });
});

//Print displayed contacts
$('.case_detail_panel_tools_right button.contact_print').live('click', function() {
    elPrint($(this).closest('div.case_detail_panel_tools')
        .siblings('div.case_detail_panel_casenotes'),'Contacts: ' + $(this).closest('.case_detail_panel')
        .siblings('.case_detail_bar')
        .find('.case_title')
        .text());
});

//Updates the displayed contact name when user creates a new contact
$('#contact_first_name').live('keyup', function() {
    $(this).closest('.new_contact').find('span.first_name_live').html(escapeHtml($(this).val()));
});

$('#contact_last_name').live('keyup', function() {
    $(this).closest('.new_contact').find('span.last_name_live').html(escapeHtml($(this).val()));
});

$('#contact_type').live('change', function() {
    $(this).closest('.new_contact').find('span.contact_type_live').html(escapeHtml($(this).val()));
});

//Sets default text on contact title
$('#contact_first_name').live('focus', function() {
    $(this).closest('.new_contact').find('span.first_name_live').html('');
    $('#contact_first_name').die('focus');
});

$('#contact_organization').live('focus', function() {
    //If no name is entered, use organization name for contact title
    if ($('#contact_first_name').val() === '' && $('#contact_last_name').val() === '') {
        $(this).keyup(function() {
            $(this).closest('.new_contact').find('span.first_name_live').html(escapeHtml($(this).val()));
        });

        $(this).focusout().die('keyup');
    }
});

//handle search
$('input.contacts_search').live('focusin', function() {
    $(this).val('');
    $(this).css({'color': 'black'});
    $(this).next('.contacts_search_clear').show();
});

$('input.contacts_search').live('keyup', function() {
    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();
    var search = $(this).val();
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

    resultTarget.load('lib/php/data/cases_contacts_load.php div.case_detail_panel_casenotes', {
        'case_id': caseId,
        'q': search
    }, function() {
        resultTarget.scrollTop(0);
        if (search.length) {
            resultTarget.highlight(search);
        }

        sizeContacts($('.contact'), resultTarget);
        if (resultTarget.hasClass('csenote_shadow')) {
            resultTarget.removeClass('csenote_shadow');
        }

        $('div.contact').addClass('ui-corner-all');
        resultTarget.bind('scroll.search', function() {
            if ($(this).scrollTop() > 0) {
                $(this).addClass('csenote_shadow');
            } else {
                $(this).removeClass('csenote_shadow');
            }
        });
    });
});

$('.contacts_search_clear').live('click', function() {
    $(this).prev().val('Search Contacts');
    $(this).prev().css({'color': '#AAA'});
    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

    resultTarget.load('lib/php/data/cases_contacts_load.php div.case_detail_panel_casenotes', {
        'case_id': caseId
    }, function() {
        resultTarget.scrollTop(0);
        sizeContacts($('.contact'), resultTarget);
        $('div.csenote').addClass('ui-corner-all');
        resultTarget.unbind('scroll.search');
        resultTarget.bind('scroll', function() {
            addMoreNotes(resultTarget);
        });
    });
    $(this).hide();
});

//edit contact

$('a.contact_edit').live('click', function(event) {
    event.preventDefault();

    //test to see if there is another contact being edited.  If so , return false
    if ($(this).closest('.case_detail_panel_casenotes').find('.contact_edit_submit').length) {
        notify('Only one contact can be edited at a time', true);
        return false;
    }

    //define contact to be edited
    var thisContact = $(this).closest('.contact');

    //Extract form values from that case note
    var firstNameVal = thisContact.find('.cnt_first_name').html();
    var lastNameVal = thisContact.find('.cnt_last_name').html();
    var typeVal = thisContact.find('.cnt_type').html();
    var orgVal = thisContact.find('.cnt_organization').html();
    var addressVal = thisContact.find('.cnt_address').html();
    var cityVal = thisContact.find('.cnt_city').html();
    var stateVal = thisContact.find('.cnt_state').html();
    var zipVal = thisContact.find('.cnt_zip').html();
    var urlVal = thisContact.find('.cnt_url').html();
    var notesValRaw = thisContact.find('.cnt_notes').html();
    var notesVal;
    if (notesValRaw !== null) {
        notesVal = notesValRaw.br2nl();
    } else {
        notesVal = '';
    }
    var contactId = thisContact.attr('data-id');

    //define the dummy version of the contact used for editing
    var editContact = $(this).closest('div.contact').siblings('div.new_contact').clone().addClass('contact_edit');
    thisContact.after(editContact);

    //get position of contact so that it will displayed correctly after edit
    var editContactPosition = thisContact.offset().top;

    //populate form with values
    if (!firstNameVal && !lastNameVal) {
        editContact.find('span.first_name_live').html(orgVal);
    } else {
        editContact.find('span.first_name_live').html(firstNameVal);
        editContact.find('span.last_name_live').html(lastNameVal);
    }
    editContact.find('span.contact_type_live').html(typeVal);
    editContact.find('input[name = "first_name"]').val(firstNameVal);
    editContact.find('input[name = "last_name"]').val(lastNameVal);
    editContact.find('input[name = "organization"]').val(orgVal);
    editContact.find('select[name = "contact_type"]').val(typeVal);
    editContact.find('textarea[name = "address"]').html(addressVal);
    editContact.find('input[name = "city"]').val(cityVal);
    editContact.find('select[name = "state"]').val(stateVal);
    editContact.find('input[name = "zip"]').val(zipVal);
    editContact.find('input[name = "url"]').val(urlVal);
    editContact.find('textarea[name = "notes"]').val(notesVal);

    //handle phones
    var phoneData = {};
    thisContact.find('p.contact_phone_group').each(function() {
        var phoneKey = $(this).find('span.contact_phone_type').text().trim();
        var phoneValue = $(this).find('span.contact_phone_value').text().trim();
        phoneData[phoneKey] = phoneValue;
    });

    var phoneForm = '';
    var phoneSelects = '';

    if ($.isEmptyObject(phoneData)) { //no phone data was previously entered
        phoneForm = '<p class="contact_phone_group"><label>Phone</label><select name="phone_type"' +
            'class="contact_phone_type"><option value="mobile">mobile</option><option value="home">home</option>' +
            '<option value="office">office</option><option value="fax">fax</option></select>' +
            '<input type="text" name="phone" class="contact_phone_value">';
    } else {
        $.each(phoneData, function(key, value) {
            var phoneOptions = doEditSelect(key, 'phone');
            phoneForm += '<p class="contact_phone_group"><label>Phone</label><select name="phone_type"' +
                'class="contact_phone_type">"' + phoneOptions + '"</select><input type="text" name="phone"' +
                'class="contact_phone_value" value="' + value + '">';
        });
    }
    editContact.find('span.contact_phone_widget').html(phoneForm);

    //Append add "another link" to last phone
    editContact.find('span.contact_phone_widget')
        .find('.contact_phone_group input')
        .last()
        .after('<a href="#" class="add_phone">Add Another</a>');

    //handle email
    var emailData = {};
    thisContact.find('p.contact_email_group').each(function() {
        var emailKey = $(this).find('span.contact_email_type').text().trim();
        var emailValue = $(this).find('span.contact_email_value').text().trim();
        emailData[emailKey] = emailValue;
    });

    var emailForm = '';
    var emailSelects = '';

    if ($.isEmptyObject(emailData)) { //no email data was previously entered
        emailForm = '<p class="contact_email_group"><label>Email</label><select name="email_type"' +
            'class="contact_email_type"><option value="work">work</option><option value="home">home</option>' +
            '<option value="other">other</option></select><input type="text" name="email" class="contact_email_value">';
    } else {
        $.each(emailData, function(key, value) {
            var emailOptions = doEditSelect(key, 'email');
            emailForm += '<p class="contact_email_group"><label>Email</label><select name="email_type" ' +
            'class="contact_email_type">' + emailOptions + '</select><input type="text" name="email" ' +
            'class="contact_email_value" value="' + value + '">';
        });
    }

    editContact.find('span.contact_email_widget').html(emailForm);

    //Append "add another" link to last email
    editContact.find('span.contact_email_widget')
        .find('.contact_email_group input')
        .last()
        .after('<a href="#" class="add_email">Add Another</a>');

    //set css
    editContact.find('.csenote_bar').css({'background-color': '#FEBBBB'});
    editContact.find('button.contact_action_submit').html('Done')
        .addClass('contact_edit_submit')
        .removeClass('contact_action_submit');
    editContact.find('button.contact_action_cancel')
        .addClass('contact_edit_cancel')
        .removeClass('contact_action_cancel');

    //Apply combobox
    editContact.find('select[name = "contact_type"]').combobox();
    editContact.show();
    thisContact.hide();

    //user cancels editing contact
    editContact.find('button.contact_edit_cancel').click(function(event) {
        event.preventDefault();
        editContact.hide().remove();
        thisContact.show();
    });

    //user submits edits
    editContact.find('button.contact_edit_submit').click(function(event) {
        event.preventDefault();

        //Do validation
        var contactForm = $(this).closest('form');
        var contactVals = contactForm.serializeArray();

        //get errors, if any
        var errString = validContact(contactForm);

        //notify user or errors or submit form
        if (errString.length) {
            notify(errString, true);
        } else {
            //Make objects of phone/email types and phone numbers/email addresses.
            //Store each object in one db field, allowing user to enter unlimited email addresses and phone numbers.

            var phoneData = {};
            contactForm.find('p.contact_phone_group').each(function() {
                var phoneKey = $(this).find('select.contact_phone_type').val();
                var phoneValue = $(this).find('input.contact_phone_value').val();
                phoneData[phoneKey] = phoneValue;
            });

            var phoneJson = JSON.stringify(phoneData);

            var emailData = {};
            contactForm.find('p.contact_email_group').each(function() {
                var emailKey = $(this).find('select.contact_email_type').val();
                var emailValue = $(this).find('input.contact_email_value').val();
                emailData[emailKey] = emailValue;
            });

            var emailJson = JSON.stringify(emailData);
            var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
            var target = $(this).closest('.case_detail_panel_casenotes');
            contactForm.find('select[name = "contact_type"]').combobox('destroy');

            $.post('lib/php/data/cases_contacts_process.php', {
                'first_name': contactForm.find('input[name = "first_name"]').val(),
                'last_name': contactForm.find('input[name = "last_name"]').val(),
                'organization': contactForm.find('input[name = "organization"]').val(),
                'contact_type': contactForm.find('select[name = "contact_type"]').val(),
                'address': contactForm.find('textarea[name = "address"]').val(),
                'city': contactForm.find('input[name = "city"]').val(),
                'state': contactForm.find('select[name = "state"]').val(),
                'zip': contactForm.find('input[name = "zip"]').val(),
                'phone': phoneJson,
                'email': emailJson,
                'url': contactForm.find('input[name = "url"]').val(),
                'notes': contactForm.find('textarea[name = "notes"]').val(),
                'action': 'edit',
                'case_id': caseId,
                'id': contactId
            }, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true) {
                    notify(serverResponse.message, true);
                } else {
                    notify(serverResponse.message);
                    target.load('lib/php/data/cases_contacts_load.php div.case_detail_panel_casenotes', {
                        'case_id': caseId
                    }, function() {
                        //Scroll to original position of edited conact
                        sizeContacts(target.find('.contact'), target);
                        var editedContact = target.find('div.contact[data-id = "' + contactId + '"]');
                        target.scrollTop(editedContact.offset().top);
                        //check conflicts; see casesCaseDetail.js
                        checkConflicts(caseId);
                    });
                }
            });
        }
    });
});

//Delete Contact
$('a.contact_delete').live('click', function(event) {
    event.preventDefault();

    var thisContact = $(this).closest('.contact');
    var thisContactId = thisContact.attr('data-id');
    var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Contact?">This contact' +
    ' will be permanently deleted.  Are you sure?</div>')
    .dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        buttons: {
            'Yes': function() {
                $.post('lib/php/data/cases_contacts_process.php', {
                    'action': 'delete',
                    'id': thisContactId
                }, function(data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify(serverResponse.message, true);
                    } else {
                        notify(serverResponse.message);
                        thisContact.remove();
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

//Listeners to add new fields
$('.add_email').live('click', function(event) {
    event.preventDefault();
    $(this).closest('p').after(emailWidget);
    $(this).remove();
});

$('.add_phone').live('click', function(event) {
    event.preventDefault();
    $(this).closest('p').after(phoneWidget);
    $(this).remove();
});
