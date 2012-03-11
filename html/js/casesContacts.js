//
// Scripts for contacts panel on cases tab
//

function sizeContacts(contacts,panelTarget)
{
    var windowHeight = panelTarget.height();
    var minContactHeight = panelTarget.height() * 0.3;
    contacts.not('.new_contact').each(function(){
        //cache the height values for use later
        $(this).data('maxContactHeight',$(this).height());
        $(this).data('minContactHeight',minContactHeight);

        var notePercent = $(this).height() / windowHeight * 100;
        if (notePercent > 30 )
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
    var phoneWidget = "<p><label>Phone</label><select name='phone_type' class='phone_type'><option value='mobile'>Mobile</option><option value='home'>Home</option><option value='office'>Office</option><option value='fax'>Fax</option><option value='other'>Other</option></select><input type='text' name='email'><a href='#' class='add_phone'>Add Another</a>";

    $(this).closest('.case_detail_panel_tools').siblings().find('span.contact_phone_widget').html(phoneWidget);

    //Add the email input widget
    var emailWidget = "<p><label>Email</label><select name='email_type'><option value='work'>Work</option><option value='home'>Home</option><option value='other'>Other</option></select><input type='text' name='email'><a href='#' class='add_email'>Add Another</a>";

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
           console.log('they are empty');
            $(this).keyup(function(){
                $(this).closest('.new_contact').find('span.first_name_live').html($(this).val());
                });

            $(this).focusout().die('keyup');

    }
});