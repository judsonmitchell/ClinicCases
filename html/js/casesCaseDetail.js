 //
//Creates the Case Detail window when user clicks on table row.
//


function setDetailCss() 
{

    //once tabs are loaded, set the css for the interior blocks
    
    windowWidth = Math.ceil($('#case_detail_window').width());
    
    windowHeight = Math.ceil($('#case_detail_window').height());
    
    panelWidthFix = $('#case_detail_window').width() * .004;
    
    navWidth = Math.floor(windowWidth * .15);
    
    panelWidth = windowWidth - navWidth - panelWidthFix;
    
    var bh = Math.floor($(windowHeight * .07));

    //this for small screens
    if (bh > 52) 
    {
        barHeight = bh
    } 
    
    else 
    
    {
        barHeight = 52
    }
    
    navHeight = windowHeight - $("#case_detail_tab_row").height() - barHeight;
    
    barWidth = windowWidth - 3
    
    panelHeight = navHeight
    
    caseTitleHeight = barHeight - 10;
    
    $(".case_detail_nav").css({'height': navHeight,'width': navWidth})
    $(".case_detail_panel").css({'height': panelHeight,'width': panelWidth});
    $(".case_detail_bar").css({'height': barHeight,'width': barWidth});
    $(".case_title").css({'height': caseTitleHeight});


}

//Function which creates the tabs in the case_detail_tab_row div
function addDetailTabs(id) 

{
    
    $(function() {

        //number of currently opened tabs
        var numberTabs = $("ul.ui-tabs-nav > li").length;

        //set maximum number of tabs for layout reasons and page weight
        if (numberTabs == 5) 
        {
            $('#error').text('Sorry, but ClinicCases can only open a maximum of five cases at a time.').dialog({modal: true,title: 'Error'});
            return false;
        }
        
        $.getJSON("lib/php/data/cases_detail_tab_case_name.php?id=" + id, function(data) {
            if (data.organization.length < 1) 
            {
                tabData = data.last_name + ", " + data.first_name
            } 
            else 
            {
                tabData = data.organization
            }
            
            if (tabData.length > 12) 
            {
                tabData = tabData.substring(0, 12) + "..."
            }
            
            $tabs = $("#case_detail_tab_row").tabs({tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>"});
            
            $tabs.tabs("add", "lib/php/data/cases_detail_load.php?id=" + id, tabData);

            //make sure the just selected tab gets the focus and load its data
            $tabs.tabs({
                add: function(event, ui) {
                    $tabs.tabs('select', '#' + ui.panel.id);
                },
                //cache keeps tabs from reloading each time clicked, so user won't lose their place.
                cache: true,
                load: function(event, ui) {
                    
                    setDetailCss();
                    
                    $("#case_detail_bar").text(tabData);
                    
                    if ($('div.assigned_people  button').length > 0) 
                    {
                        $("div.assigned_people  button").button({icons: {primary: "fff-icon-user-add"},text: true})
                    }
                    
                    scroller = $('.assigned_people').jScrollPane();

                    //define the id of the clicked tab
                    panelTarget = '#' + ui.panel.id;
                    
                    
                    loadCaseNotes(panelTarget, id);
                
                }
            
            })

            //Do jqueryui css modifications
            $("ul.ui-tabs-nav").removeClass('ui-corner-all').addClass('ui-corner-top');
            $("#case_detail_tab_row").removeClass('ui-corner-all').addClass('ui-corner-top');
        
        })
    
    }
    )
}


//Function which creates the case detail window.

function callCaseWindow(id) 

{
    //create window if user has yet to call it
    if ($("#case_detail_window").length < 1) 
    {
        var caseDetail = "<div id='case_detail_window'><div id='case_detail_tab_row'><ul></ul><div id='case_detail_control'></div></div></div>";
        
        $("#content").append(caseDetail);
        
        $("#case_detail_window").hide().show('fold', 600, function() {
            setDetailCss();
            addDetailTabs(id);
        });
        
        $("#case_detail_control").html("<button></button><button></button>");
        
        $("#case_detail_control button:first").button({icons: {primary: "fff-icon-arrow-in"},label: "Minimize"}).next().button({icons: {primary: "fff-icon-cancel"},label: "Close"});
    
    
    } 
    
    else 
    //just slide the window in
    {
        if ($("#case_detail_control button:first").text() == 'Maximize')  //window is in minimized state
        {
            toggleTabs();
            addDetailTabs(id);
        } 
        else 
        {
            $("#case_detail_window").hide().show('fold', 600, function() {
                setDetailCss();
                addDetailTabs(id);
            });
        }
    
    }
}


//Toggle the div #case_detail_window div
function toggleTabs() 

{
    
    var minimized = adjustedHeight + 8;
    
    if ($("#case_detail_control button:first").text() == 'Minimize') 
    
    {
        $("#case_detail_window").animate({'top': minimized}, function() {
        
        });
        $("#case_detail_control button:first").button({icons: {primary: "fff-icon-arrow-out"},label: "Maximize"});
        $("#case_detail_control button:first .ui-button-text").css({'line-height': '0.3'});
    } 
    
    else 
    
    {
        //Recalculate top
        var paddingTop = adjustedHeight * .021;
        $("#case_detail_window").animate({'top': paddingTop}, function() {
        });
        $("#case_detail_control button:first").button({icons: {primary: "fff-icon-arrow-in"},label: "Minimize"});
        $("#case_detail_control button:first .ui-button-text").css({'line-height': '0.3'});
    
    }


}


//Listeners

$("#case_detail_control button:first").live('click', function() {
    toggleTabs();
});

$("#case_detail_control button + button").live('click', function() {
    $("#case_detail_window").hide('fold', 1000, function() {
        $tabs.tabs('destroy');
    });
});

$("ul.case_detail_nav_list > li").live("click", function() {
    $(this).siblings().removeClass('selected');
    $(this).addClass('selected');

})



//Open the user detail window when user image is clicked.
$("div.assigned_people img:not(.user_add_button)").live("click", function() {
    $("div.assigned_people img").css({'border': '3px solid #FFFFCC'});
    clickedImage = $(this);
    if ($(this).parents('li').hasClass('inactive')) 
    {
        $(this).css({'border': '3px solid gray'});
    } 
    else 
    {
        $(this).css({'border': '3px solid green'});
    }

    //get case number and user id
    pos1 = $(this).attr('id').indexOf("_");
    pos2 = $(this).attr('id').lastIndexOf("_");
    var getCaseId = $(this).attr('id').substring(pos1 + 1, pos2);
    var getUserId = $(this).attr('id').substring(pos2 + 1);
    
    $('div.user_display').load('lib/php/users/cases_detail_user_activity_load.php', {'case_id': getCaseId,'username': getUserId,}, function() 
    {

        //if user has permission to remove users, show remove button		
        if ($('div.user_display_detail button').length > 0) 
        {
            if ($('div.user_display_detail button').parent().hasClass('inactive_user')) 
            {
                $('button.user-action-button').button({icons: {primary: "fff-icon-user-delete"},text: true,label: "Reassign"})
            } 
            else 
            {
                $('button.user-action-button').button({icons: {primary: "fff-icon-user-delete"},text: true,label: "Unassign"})
            }
        }

        //hide the display and reset the clicked image border
        $(this).toggle(0, function() {
            if ($(this).css('display') == 'none') 
            {
                clickedImage.css({'border': '3px solid #FFFFCC'})
            }
        });
    
    })
})

//Toggle to show all users, not just currently assigned

$("div.assigned_people li.slide").live('click', function() {
    var inactiveUsers = $('div.assigned_people li.inactive');
    var inactiveUsersDisplay = inactiveUsers.css('display');
    if (inactiveUsersDisplay == 'none') 
    {
        inactiveUsers.css({'display': 'inline'});
        inactiveUsers.find('img').css({'opacity': '.4'});
        $(this).children().text('Assigned (History):');
        $(this).removeClass('closed').addClass('open');
        $('.assigned_people').jScrollPane();
    
    } 
    else 
    {
        inactiveUsers.css({'display': 'none'});
        $(this).children().text('Assigned:');
        $(this).removeClass('open').addClass('closed');
        $('.assigned_people').jScrollPane();
    
    
    }

});


//Call Add User Widget

$('div.assigned_people img.user_add_button').live('click', function() {
    $('div.assigned_people img').css({'border': '3px solid #FFFFCC'});
    userAddImage = $(this);
    $(this).css({'border': '3px solid green'});

    //Get case id from the add button clicked.
    var pos = $(this).attr('id').lastIndexOf("_");
    var cseId = $(this).attr('id').substring(pos + 1);
    
    if ($(this).css('display') == 'none') 
    {
        userAddImage.css({'border': '3px solid #FFFFCC'});
    }
    
    $('div.user_display').load('lib/php/users/cases_detail_user_chooser_load.php', {'case_id': cseId}, function() {
        $('button.user-action-adduser-button').button({icons: {primary: "fff-icon-user-add"},text: true});
        
        $('.chzn-select').chosen();
    
    })
    
    $('div.user_display').toggle();

})

//Add Users to Case
$('div.user_widget button.user-action-adduser-button').live('click', function() {

    //finds the value of the select
    var usersArray = $(this).parent().find('select').val();
    
    var usersCaseId = $('#user_chooser_case_id').val();
    $.ajax({url: 'lib/php/users/add_user_to_case.php',data: ({'users_add': usersArray,'case_id': usersCaseId}),success: function(data) 
        {
            $('.assigned_people ul').load('lib/php/users/cases_detail_assigned_people_refresh_load.php', {'id': usersCaseId});
            $('div.user_widget').hide();
            notify(data);
        }
    })
})

//Unassign or re-assign user from case
$('div.user_widget button.user-action-button').live('click', function() {
    //create user remove dialog
    var dialogWin = $(this).siblings('.dialog-user-remove');
    //gets the values from the form
    var formObj = $(this).siblings('form');
    var assignId = formObj.children('.RemoveId').val();
    var imgId = formObj.children('.RemoveImgId').val();
    var caseId = imgId.split('_');
        
    $(dialogWin).dialog({
        resizable: false,
        modal: true,
        buttons: {
            "Yes": function() {
                $.ajax({url: 'lib/php/users/remove_user_from_case.php',data: ({remove_id: assignId}),success: function(data) {
                        $("div.user_widget").hide();
                        notify(data);
                        $('.assigned_people ul').load('lib/php/users/cases_detail_assigned_people_refresh_load.php', {'id': caseId[1]});
                    }})
                       
                $(this).dialog("close");
            },
            "No": function() {
                $(this).dialog("close");
            }
        }
    });
})

//Close tabs	
$("span.ui-icon-close").live("click", function() {

    //index of tab clicked
    var index = $("li", $tabs).index($(this).parent());
    
    var numberTabs = $("ul.ui-tabs-nav > li").length;

    //if there is only one tab left, close the window
    if (numberTabs == 1) 
    {
        $("#case_detail_window").hide('fold', 1000, function() {
            $tabs.tabs('destroy');
        });
    } 
    //otherwise, remove the clicked tab
    else 
    {
        $tabs.tabs("remove", index);
    }

});

//Adjust case detail div sizes on window resize		
$(window).resize(function() {
    
    setDetailCss();

});





