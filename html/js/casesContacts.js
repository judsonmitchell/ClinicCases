//
// Scripts for contacts panel on cases tab
//

function sizeContacts(contacts,panelTarget)
{
    var windowHeight = panelTarget.height();
    var minContactHeight = panelTarget.height() * 0.4;
    contacts.not('.new_contact').each(function(){
        //cache the height values for use later
        $(this).data('maxContactHeight',$(this).height());
        $(this).data('minContactHeight',minContactHeight);

        var notePercent = $(this).height() / windowHeight * 100;
        if (notePercent > 40 )
        {
            $(this).height(minContactHeight);
            $(this).css({'overflow':'hidden'});
            $(this).append('<div class="contact_more"><a href="#">More</a></div>');
        }

    });

}

$('.case_detail_nav #item6').live('click', function() {

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');

    //Get heights
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var documentsWindowHeight = thisPanelHeight - toolsHeight;

    thisPanel.load('lib/php/data/cases_contacts_load.php', {'case_id': caseId}, function() {

        //Set css
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': caseNotesWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '30%'});
        $('div.case_detail_panel_tools_right').css({'width': '70%'});

        //Adjust size of panel to accomodate combobox.  Previous size causes the panel element to go out of alignment
        var newPanelWidth = thisPanel.width() - 10;
        thisPanel.width(newPanelWidth);

        //Set buttons
        $('button.new_contact').button({icons: {primary: "fff-icon-vcard-add"},text: true}).next().button({icons: {primary: "fff-icon-printer"},text: true});

        //Round Corners
        $('div.csenote').addClass('ui-corner-all');

        //Size
        sizeContacts($(this).find('.contact'),thisPanel);

        //Apply comboxbox
        $('#contact_type').combobox();

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow'))
            {
                $(this).removeClass('csenote_shadow');
            }
            else
            {
                $(this).addClass('csenote_shadow');
            }
        });

    });

});

//Listeners

//show hidden text on clipped contact
$('div.contact_more').live('click',function(event){
    event.preventDefault();
    console.log('click');
    var contactParent = $(this).closest('.contact');
    var contactParentMaxHeight = $(this).closest('.contact').data('maxContactHeight');
    var contactParentMinHeight = $(this).closest('.contact').data('minContactHeight');

    if ($(this).find('a').html() == 'Less')
    {
        contactParent.css({'height':contactParentMinHeight});
        $(this).find('a').html('More');
    }
    else
    {
        contactParent.css({'height':contactParentMaxHeight});
        $(this).find('a').html('Less');
    }
});

//Add contact
$('.case_detail_panel_tools_right button.new_contact').live('click', function() {

    //make sure contacts are scrolled to top
    $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes').scrollTop(0);

    //display the new contact widget
    $(this).closest('.case_detail_panel_tools').siblings().find('div.new_contact').show();

    //reduce opacity on the previously entered contact
    $(this).closest('.case_detail_panel_tools').siblings().find('div.contact').not('div.csenote_new').css({'opacity': '.5'});

    //Add the phone input widget
    var phoneWidget = "<p class='contact_phone_group'><label>Phone</label><select name='phone_type' class='contact_phone_type'><option value='mobile'>Mobile</option><option value='home'>Home</option><option value='office'>Office</option><option value='fax'>Fax</option><option value='other'>Other</option></select><input type='text' name='phone' class='contact_phone_value'><a href='#' class='add_phone'>Add Another</a>";

    $(this).closest('.case_detail_panel_tools').siblings().find('span.contact_phone_widget').html(phoneWidget);

    //Add the email input widget
    var emailWidget = "<p class='contact_email_group'><label>Email</label><select name='email_type' class='contact_email_type'><option value='work'>Work</option><option value='home'>Home</option><option value='other'>Other</option></select><input type='text' name='email' class='contact_email_value'><a href='#' class='add_email'>Add Another</a>";

    $(this).closest('.case_detail_panel_tools').siblings().find('span.contact_email_widget').html(emailWidget);

    //Listeners to add new fields
    $('.add_email').live('click',function(event){
        event.preventDefault();
        $(this).closest('p').after(emailWidget);
        $(this).remove();
    });

     $('.add_phone').live('click',function(event){
        event.preventDefault();
        $(this).closest('p').after(phoneWidget);
        $(this).remove();
    });

    //User cancels adding new contact
    $(this).closest('.case_detail_panel_tools').siblings().find('button.contact_action_cancel').click(function(event) {

        event.preventDefault();
        //reset form

        //reset opacity of other case notes
        $(this).closest('.case_detail_panel_casenotes').find('.contact').css({'opacity': '1'});
        //hide the widget
        $(this).closest('.csenote_new').hide();

    });

    //User adds new contact
    $(this).closest('.case_detail_panel_tools').siblings().find('button.contact_action_submit').click(function(event){

        event.preventDefault();

        //Do validation
        var contactForm = $(this).closest('form');
        var contactVals = contactForm.serializeArray();

        //get errors, if any
        var errString = validContact(contactVals);

        //notify user or errors or submit form
        if (errString.length)
        {
            notify(errString, true);
        }
        else
        {
            //Make objects of phone/email types and phone numbers/email addresses.  Store each object in one db field, allowing user to enter unlimited email addresses and phone numbers.

            var phoneData = {};
            contactForm.find('p.contact_phone_group').each(function(){
                var phoneKey = $(this).find('select.contact_phone_type').val();
                var phoneValue = $(this).find('input.contact_phone_value').val();
                phoneData[phoneKey] = phoneValue;
            });

            phoneJson = JSON.stringify(phoneData);

            var emailData = {};
            contactForm.find('p.contact_email_group').each(function(){
                var emailKey = $(this).find('select.contact_email_type').val();
                var emailValue = $(this).find('input.contact_email_value').val();
                emailData[emailKey] = emailValue;
            });

            emailJson = JSON.stringify(emailData);

            var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

            var target = $(this).closest('.case_detail_panel_casenotes');

            $.post('lib/php/data/cases_contacts_process.php',{
                'first_name':contactForm.find('input[name = "first_name"]').val(),
                'last_name':contactForm.find('input[name = "last_name"]').val(),
                'organization':contactForm.find('input[name = "organization"]').val(),
                'contact_type':contactForm.find('select[name = "contact_type"]').val(),
                'address':contactForm.find('textarea[name = "address"]').val(),
                'city':contactForm.find('input[name = "city"]').val(),
                'state':contactForm.find('select[name = "state"]').val(),
                'zip':contactForm.find('input[name = "zip"]').val(),
                'phone':phoneJson,
                'email':emailJson,
                'url':contactForm.find('input[name = "url"]').val(),
                'notes':contactForm.find('textarea[name = "notes"]').val(),
                'action':'add',
                'case_id':caseId
                },function(data){

                    var serverResponse = $.parseJSON(data);
                    notify(serverResponse.message);
                    target.load('lib/php/data/cases_contacts_load.php div.case_detail_panel_casenotes',{'case_id': caseId});

                });
        }

    });

});

//Print displayed contacts
$('.case_detail_panel_tools_right button.contact_print').live('click',function(){
    alert('Working on it');
    //TODO printing

});

//Updates the contact name when user creates a new contact
$('#contact_first_name').live('keyup',function(){
    $(this).closest('.new_contact').find('span.first_name_live').html($(this).val());
});

$('#contact_last_name').live('keyup',function(){
    $(this).closest('.new_contact').find('span.last_name_live').html($(this).val());
});

$('#contact_type').live('change',function(){
    $(this).closest('.new_contact').find('span.contact_type_live').html($(this).val());
});

//Sets default text on contact title
$('#contact_first_name').live('focus', function(){

     $(this).closest('.new_contact').find('span.first_name_live').html('');
     $('#contact_first_name').die('focus');
});

$('#contact_organization').live('focus',function(){
    //If no name is entered, use organization name for contact title
    if ($('#contact_first_name').val() === '' && $('#contact_last_name').val() === '')
    {
            $(this).keyup(function(){
                $(this).closest('.new_contact').find('span.first_name_live').html($(this).val());
                });

            $(this).focusout().die('keyup');

    }
});

//handle search
$('input.contacts_search').live('focusin', function() {

    $(this).val('');
    $(this).css({'color': 'black'});
    $(this).next('.casenotes_search_clear').show();
});

$('input.contacts_search').live('keyup', function() {

    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();

    var search = $(this).val();

    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

    //resultTarget.unbind('scroll');

    resultTarget.load('lib/php/data/cases_contacts_load.php div.case_detail_panel_casenotes', {'case_id': caseId,'q': search}, function() {

        resultTarget.scrollTop(0);

        sizeContacts($('.contact'),resultTarget);

        if (resultTarget.hasClass('csenote_shadow'))
        {
            resultTarget.removeClass('csenote_shadow');
        }

        $('div.contact').addClass('ui-corner-all');

        resultTarget.bind('scroll.search', function() {
            if ($(this).scrollTop() > 0)
            {
                $(this).addClass('csenote_shadow');
            }
            else
            {
                $(this).removeClass('csenote_shadow');
            }
        });

    });

});

$('.casenotes_search_clear').live('click', function() {

    $(this).prev().val('Search Contacts');

    $(this).prev().css({'color': '#AAA'});

    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();

    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

    resultTarget.load('lib/php/data/cases_contacts_load.php div.case_detail_panel_casenotes', {'case_id': caseId}, function() {

        resultTarget.scrollTop(0);

        sizeContacts($('.contact'),resultTarget);

        $('div.csenote').addClass('ui-corner-all');

        resultTarget.unbind('scroll.search');

        resultTarget.bind('scroll', function() {
            addMoreNotes(resultTarget);
        });

    });

    $(this).hide();
});

//edit contact

$('a.contact_edit').live('click',function(event){

    event.preventDefault();

    //test to see if there is another contact being edited.  If so , return false
    if ($(this).closest('.case_detail_panel_casenotes').find('.contact_edit_submit').length)
    {notify('Only one contact can be edited at a time',true);return false;}

    //define contact to be edited
    var thisCseNote = $(this).closest('.contact');

    //Extract form values from that case note

    //define the dummy version of the contact used for editing
    var editContact = $(this).closest('div.contact').siblings('div.contact_new').clone();
    thisContact.after(editContact);
    editContact.show();
    thisContact.hide();

});