<?php

//function for when a single thumbnail is needed.
function get_thumb($user)
{
	$pn = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$user' LIMIT 1");
	$pn_l = mysql_fetch_object($pn);
	$thumb = explode('/',$pn_l->picture_url);
	$thumb_target = $thumb[0] . '/tn_' . $thumb[1];
	return $thumb_target;	
}

//function for when a group of thumbnails are needed for the facebar
function get_faces($id,$output)
	{
	
		$f = mysql_query("SELECT * FROM `cm_cases_students` WHERE `case_id` = '$id' AND `status` = 'active' ORDER BY `id` ASC");	
		//start the box
		
			while ($l = mysql_fetch_array($f))
			{
				//get pictures and names
				$pn = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$l[username]' LIMIT 1");
				$pn_l = mysql_fetch_object($pn);
				$unique = $pn_l->id . "_" . $id;
				
				$thumb = explode('/',$pn_l->picture_url);
				$thumb_target = $thumb[0] . '/tn_' . $thumb[1];
				
				$date_assigned = formatDate2($l[date_assigned]);
				
				$faces .= "<a class=\"tooltip\" onmouseover=\"$('s_$unique').setStyle({display:'inline',position:'absolute', background:'#ffffff',border:'1px solid #555' ,color:'#6c6c6c'});\" onmouseout=\"$('s_$unique').hide();\" onClick=\"var check = confirm('Are sure you want to remove $pn_l->first_name $pn_l->last_name from this case?');
				
				if (check==true){new Ajax.Updater('notifications','./student_remove_from_case.php',{method:'get',parameters:{username:'$l[username]',case_id:'$l[case_id]'},onComplete:function(){new Ajax.Updater($('facebar_$id'),'./classes/get_faces.php',{evalScripts:true,method:'get',parameters:{id:'$id'}})
				}});}else{return false;}\">
			
				<div id=\"$unique\" style=\"width:33px;height:32px;float:left;margin:5px;\">
					<span class=\"s_tooltip\" id=\"s_$unique\">$pn_l->first_name $pn_l->last_name<br><p>Assigned: $date_assigned</p> <p style=\" color:red;\">[Remove]</p></span>
				<img src='$thumb_target' border='0'></div></a>";
			}
			
			$faces .="<script>  $$('span.s_tooltip').invoke('hide');</script>";
			
		if (mysql_num_rows($f)<1)
		{$faces = "<span style='font-style:italic;color:gray;'>No current students assigned to this case.</span>";}
		
		if ($output == 'text')
			{echo $faces;}
				else
			{return $faces;}
	}
	

//create a facebar by calling this script with a _get value	
if (isset($_GET['id']))
	{include_once '../db.php';include_once 'format_dates_and_times.class.php';
$id = $_GET['id'];$output= "text";get_faces($id,$output);}
?>
