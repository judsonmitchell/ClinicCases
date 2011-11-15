
//Function which creates the tabs in the case_detail_tab_row div
function addDetailTabs(id)

	{





	}
	
//Function which creates the case detail window.
	
function callCaseWindow(id)

	{
		//create window if user has yet to call it
		if($("#case_detail_block").length<1)
		{
			var caseDetail = "<div id='case_detail_window'><div id='case_detail_tab_row'><div id='case_detail_control'></div></div><div id='case_detail_block'></div></div>";
			
			$("#content").append(caseDetail);
			
			$("#case_detail_window").hide().show('clip',1200);
			
			$("#case_detail_tab_row").css({opacity: 0.8 }).addClass("ui-corner-all");;
			
			$("#case_detail_control").html("<img id='control_down' src='html/images/downarrow.png'><img id='control_cancel' src='html/images/cancel.png'>");

		}	
		
		else
		//just slide the window in
		{
			
			$("#case_detail_window").hide().show('clip',1200);
			
		}
			
		
	}
	
//Listeners

$("#control_cancel").live('click',function(){$("#case_detail_window").hide('clip',2000);});
