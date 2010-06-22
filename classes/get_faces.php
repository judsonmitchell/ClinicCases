<?php


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
				
				$jscript = "'<img src=\'images/cross.png\' border=\'0\'>'";
				$faces .= "<a onmouseover=\"$('$unique').setStyle({width:'50px'});\"  onmouseout=\"$('$unique').setStyle({width:'30px'});\" onClick=\"var check = confirm('Are sure you want to remove $pn_l->first_name $pn_l->last_name from this case?');\" title='$pn_l->first_name $pn_l->last_name' alt='$pn_l->first_name $pn_l->last_name'><div id=\"$unique\" style=\"width:33px;height:32px;float:left;margin:5px;background:url('images/tn_del.png') no-repeat;\"><img src='$thumb_target' border='0'></div></a>";
			}
			
		if (mysql_num_rows($f)<1)
		{$faces = "<span style='font-style:italic;color:gray;'>No current students assigned to this case.</span>";}
		
		if ($output == 'text')
			{echo $faces;}
				else
			{return $faces;}
	}
	
	
if (isset($_GET['id']))
	{include_once '../db.php';$id = $_GET['id'];$output= "text";get_faces($id,$output);}
?>
