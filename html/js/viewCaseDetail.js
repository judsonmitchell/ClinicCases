//
//Creates the Case Detail window when user clicks on table row
//


function setDetailCss()
{
	
	//once tabs are loaded, set the css for the interior blocks
					
	navWidth = $('li.ui-tabs-selected').width();
					
	panelWidth = $("#case_detail_window").width() - navWidth - 20;
					
	barHeight = $("#case_detail_window").height() * .1 ;
					
	navHeight = $("#content").height() - $("#case_detail_tab_row").height() - barHeight;
			
	barWidth = $("#case_detail_window").width() - 10;
					
	panelHeight = navHeight;
					
	$(".case_detail_nav").css({'height': navHeight,'width':navWidth})
	$(".case_detail_panel").css({'height':panelHeight, 'width':panelWidth});
	$(".case_detail_bar").css({'height':barHeight,'width':barWidth});
				
}


//Function which creates the tabs in the case_detail_tab_row div
function addDetailTabs(id)

	{

		$(function() {
			
			//number of currently opened tabs
			var numberTabs = $("#case_detail_tab_row li").length;

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
					$("#case_detail_bar").text(tabData);setDetailCss();
						})				
				});			
				
				
		//Do jqueryui css modifications
		
			$(".ui-widget-content").css({'border':'0px'})
			$(".ui-tabs").css({'padding':'0px'});
			//make tabs smaller
			$(".ui-helper-reset").css({'line-height':'0.3'});
			//make buttons smaller
			$(".ui-button-text").css({'line-height':'0.3'});

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
			
			$("#case_detail_control button:first").button({icons: {primary: "fff-icon-arrow-in"},label: "Minimize"}).next().button({icons: {primary: "fff-icon-cancel"},label:"Close"})
			
			// this creates sortable, but causes the close tab to not work - .find( ".ui-tabs-nav" ).sortable({ axis: "x" })
			
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


//Close tabs	
$( "span.ui-icon-close" ).live( "click", function() {
			
			//index of tab clicked
			var index = $( "li", $tabs ).index( $( this ).parent() );
			
			var numberTabs = $("#case_detail_tab_row li").length;

			//if there is only one tab left, close the window
			if (numberTabs == 1)
			{$("#case_detail_window").hide('fold',1000,function(){$tabs.tabs('destroy');});}
				//otherwise, remove the clicked tab
				else
				{$tabs.tabs( "remove", index)}
					
			
		});
