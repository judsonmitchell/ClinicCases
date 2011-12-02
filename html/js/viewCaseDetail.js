//
//Creates the Case Detail window when user clicks on table row
//

function removeUser(pictureId,AssignId)
	{
		$(pictureId).destroy();
		$.ajax()
	
	}
function setDetailCss()
{
	
	//once tabs are loaded, set the css for the interior blocks
					
	navWidth = $('li.ui-tabs-selected').width();
					
	panelWidth = $("#case_detail_window").width() - navWidth - 3;
					
	barHeight = $("#case_detail_window").height() * .1 ;
					
	navHeight = $("#content").height() - $("#case_detail_tab_row").height() - barHeight;
			
	barWidth = $("#case_detail_window").width() - 2
					
	panelHeight = navHeight -2;
					
	$(".case_detail_nav").css({'height': navHeight,'width':navWidth})
	$(".case_detail_panel").css({'height':panelHeight, 'width':panelWidth});
	$(".case_detail_bar").css({'height':barHeight,'width':barWidth});
				
}


//Function which creates the tabs in the case_detail_tab_row div
function addDetailTabs(id)

	{

		$(function() {
			
			//number of currently opened tabs
			var numberTabs = $("ul.ui-tabs-nav > li").length;

			//set maximum number of tabs for layout reasons and page weight
			if (numberTabs == 5)
			{$('#error').text('Sorry, but ClinicCases can only open a maximum of five cases at a time.').dialog({modal:true,title:'Error'});return false;}
			
			$.getJSON("lib/php/data/case_detail_tab_case_name.php?id=" + id,function(data){
				if (data.organization.length<1)
				{tabData = data.last_name + ", " + data.first_name}
				else
				{tabData = data.organization}
				
				if (tabData.length>15)
				{tabData = tabData.substring(0,15) + "..."}
				
				$("#case_detail_tab_row").tabs("add","html/templates/interior/case_detail.php?id=" + id,tabData);
				
				//make sure the just selected tab gets the focus
				$("#case_detail_tab_row").tabs({ add: function(event, ui) {
				$tabs.tabs('select', '#' + ui.panel.id);}
				})
				
				$("#case_detail_window").bind('tabsload',function(event, ui){
					
					$("#case_detail_bar").text(tabData);
					
					setDetailCss();
					
					$("ul.case_detail_nav_list > li").mouseenter(function(){$(this).addClass('hover');}).mouseleave(function(){$(this).removeClass('hover')} );
															
					if ($('div.assigned_people  button').length > 0)
						{$("div.assigned_people  button").button({icons: {primary: "fff-icon-add"},text: false})}
						
					if ($('div.user_display_detail button').length > 0)
						{$('div.user_display_detail button').button({icons: {primary:"fff-icon-user-delete"},text:"Remove"})}
						
				});
			

				
				//This to allow tab re-ordering.  Won't work because the tab index doesn't get update 		
				//.find( ".ui-tabs-nav" ).sortable({ axis: "x" })
						
				//This would work if jqueryui provided a method to reindex the tabs on update, but it doens't.
				//$( "#case_detail_window" ).bind( "sortupdate", function(event, ui) {
				//	$(".ui_tabs_nav").sortable("refresh");
				//	})				
				});			
				
				
		//Do jqueryui css modifications
			$("ul.ui-tabs-nav").removeClass('ui-corner-all').addClass('ui-corner-top');	
			$("#case_detail_tab_row").removeClass('ui-corner-all').addClass('ui-corner-top');

		})

	}

	
//Function which creates the case detail window.
	
function callCaseWindow(id)

	{
		//create window if user has yet to call it
		if($("#case_detail_window").length<1)
		{
			var caseDetail = "<div id='case_detail_window'><div id='case_detail_tab_row'><ul></ul><div id='case_detail_control'></div></div></div>";
			
			$("#content").append(caseDetail);
			
			$("#case_detail_window").hide().show('fold',1000,function(){setDetailCss()});
						
			$("#case_detail_control").html("<button></button><button></button>");
			
			$("#case_detail_control button:first").button({icons: {primary: "fff-icon-arrow-in"},label: "Minimize"}).next().button({icons: {primary: "fff-icon-cancel"},label:"Close"});
			
		}	
		
		else
		//just slide the window in
		{
			if ($("#case_detail_control button:first").text() == 'Maximize')
			{toggleTabs()}
			else
			{$("#case_detail_window").hide().show('fold',1200,function(){setDetailCss();});}

		}		
		
		$tabs = $("#case_detail_tab_row").tabs({tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>"});
		
		addDetailTabs(id);

		
	}

//Toggle the div #case_detail_window div
function toggleTabs()

	{
		
		var minimized = adjustedHeight + 8; 
		
		if ($("#case_detail_control button:first").text() == 'Minimize')
		
		{
			$("#case_detail_window").animate({'top':minimized});
			$("#case_detail_control button:first").button({icons: {primary: "fff-icon-arrow-out"},label:"Maximize"});
			$("#case_detail_control button:first .ui-button-text").css({'line-height':'0.3'});
		}
		
		else
		
		{
			//Recalculate top
			var paddingTop = adjustedHeight * .021;
			$("#case_detail_window").animate({'top': paddingTop});
			$("#case_detail_control button:first").button({icons: {primary: "fff-icon-arrow-in"},label:"Minimize"});
			$("#case_detail_control button:first .ui-button-text").css({'line-height':'0.3'});
			
		}
				
		
	}
	
	
//Listeners

$("#case_detail_control button:first").live('click',function(){toggleTabs();});

$("#case_detail_control button + button").live('click',function(){
	$("#case_detail_window").hide('fold',1000,function(){$tabs.tabs('destroy');});	
	});

$("ul.case_detail_nav_list > li").live("click",function(){$("ul.case_detail_nav_list > li.selected").removeClass('selected');$(this).addClass('selected');})

$("div.assigned_people img").live("click",function(){
	$("div.assigned_people img").css({'border':'0px'});
	$(this).css({'border':'3px solid grey'});
	$('div.user_widget').show();
	$('div.user_display_detail').hide();
	
	pos1 = $(this).attr('id').indexOf("_");
	pos2 = $(this).attr('id').lastIndexOf("_");

	var getCaseId = $(this).attr('id').substring(pos1 + 1,pos2);
	var getUserId = $(this).attr('id').substring(pos2 +1);
	var selectedUserBox = "#user_box_" + getCaseId + "_"  + getUserId;
	$(selectedUserBox).css({'display':'block'});
	})

	$("div.user_display_closer").live('click',function(){
		$('div.assigned_people img').each(function(){$(this).css({'border':'0px'})});
		$('.user_widget').css({'display':'none'})
		})
//Close tabs	
$( "span.ui-icon-close" ).live( "click", function() {
			
			//index of tab clicked
			var index = $( "li", $tabs ).index( $( this ).parent() );
			
			//var index = $(this).live().parent().index();
			
			var numberTabs = $("ul.ui-tabs-nav > li").length;

			//if there is only one tab left, close the window
			if (numberTabs == 1)
			{$("#case_detail_window").hide('fold',1000,function(){$tabs.tabs('destroy');});}
				//otherwise, remove the clicked tab
				else
				{$tabs.tabs( "remove", index);}
					
			
		});
