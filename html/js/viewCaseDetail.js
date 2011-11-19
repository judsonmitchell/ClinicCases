
//Function which creates the tabs in the case_detail_tab_row div
function addDetailTabs(id)

	{

		$(function() {
			
			
			$.getJSON("lib/php/data/case_detail_tab_case_name.php?id=" + id,function(data){
				if (data.organization.length<1)
				{tabData = data.last_name + ", " + data.first_name}
				else
				{tabData = data.organization}
				
				if (tabData.length>15)
				{tabData = tabData.substring(0,15) + "..."}
				
				$("#case_detail_tab_row").tabs("add","people/index.php",tabData);			
				});
			
			//$("#case_detail_tab_row").tabs("add","index.php","Yahoo");
			//$("#case_detail_tab_row").tabs("add","index.php","Google");
			
			$(".ui-widget-content").css({'border':'0px'})
			$(".ui-tabs").css({'padding':'0px'});
			//make tabs smaller
			$(".ui-helper-reset").css({'line-height':'0.3'});
			//make buttons smaller
			$(".ui-button-text").css({'line-height':'0.3'});

		})

	}

//Close tabs	
$( "span.ui-icon-close" ).live( "click", function() {
			var index = $( "li", $tabs ).index( $( this ).parent() );
			$tabs.tabs( "remove", index );
		});
	
//Function which creates the case detail window.
	
function callCaseWindow(id)

	{
		//create window if user has yet to call it
		if($("#case_detail_window").length<1)
		{
			var caseDetail = "<div id='case_detail_window'><div id='case_detail_tab_row'><ul></ul><div id='case_detail_control'></div></div></div>";
			
			$("#content").append(caseDetail);
			
			$("#case_detail_window").hide().show('clip',1200);
						
			$("#case_detail_control").html("<button>Minimize</button><button>Close</button>");
			$("#case_detail_control button:first").button({icons: {primary: "fff-icon-arrow-in"},
            text: true}).next().button({icons: {primary: "fff-icon-cancel"},text:true})
			
			$tabs = $("#case_detail_tab_row").tabs({tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>"});
			
			// this creates sortable, but causes the close tab to not work - .find( ".ui-tabs-nav" ).sortable({ axis: "x" })
			
			addDetailTabs(id);


		}	
		
		else
		//just slide the window in
		{
			
			$("#case_detail_window").hide().show('clip',1200);
			addDetailTabs(id);

			
		}		
		
	}
	
//Listeners

$("#case_detail_control button:first").live('click',function(){alert('minimize')});

$("#case_detail_control button + button").live('click',function(){$("#case_detail_window").hide('clip',2000);});
