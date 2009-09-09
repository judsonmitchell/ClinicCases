<?php
session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
include 'db.php';
$type = $_GET[type];

switch ($type)
{
	case "cm_dispos":
	$type_text = "Case Dispositions";
	break;
	case "cm_courts";
	$type_text = "Courts";
	break;
	case "cm_referral":
	$type_text = "Referral";
	break;
	case "cm_case_types":
	$type_text = "Case Types";
	break;
	}

echo "<div id='wrap'>";
$get_table = mysql_query("SELECT * FROM `$type` ORDER BY `id` DESC");

	
		
$col = mysql_fetch_field($get_table,1);
$col_name = $col->name;

if (mysql_num_rows($get_table)<1)
		{
			echo "<h4>You have not defined any items in the $type_text menu.</h4><br>";
			
echo "<div style='margin-left:20px;'><table><tr><td><input type='text' name='new_item' id='new_item' size='35' value='Add an item here' onFocus=\"this.value='';\"></td><td><a href='#' onClick=\"new Ajax.Updater('wrap', 'menus_edit_process.php', {evalScripts:true,method:'post',postBody:'field=$col_name&amp;action=add&amp;table=$type&amp;value=' + document.getElementById('new_item').value});return false;\" ><img src='images/check.png' border='0'></a></td></tr></table>";
			
			die;
		}


echo "<h4>Manage $type_text menu. </h4>
<br>";

echo "<div style='margin-left:20px;'><table><tr><td><input type='text' name='new_item' id='new_item' size='35' value='Enter New Menu Item' onFocus=\"this.value='';\"></td><td><a href='#' onClick=\"new Ajax.Updater('wrap', 'menus_edit_process.php', {evalScripts:true,method:'post',postBody:'field=$col_name&amp;action=add&amp;table=$type&amp;value=' + document.getElementById('new_item').value});return false;\" ><img src='images/check.png' border='0'></a></td></tr></table>";
echo "<p>Current $type_text menu items (Click on item to edit):</p><table>";




while ($r = mysql_fetch_array($get_table))
	{
		echo "<tr id=\"row$r[id]\"><td>--</td><td><P id=\"cell$r[id]\">$r[$col_name]</P><img src=\"images/onload_tricker.gif\" border=\"0\" onLoad=\"new Ajax.InPlaceEditor('cell$r[id]', 'menus_edit_process.php',{callback: function(form, value) {return 'id=$r[id]&table=$type&field=$col_name&action=modify&value='+escape(value) }
});\"></td><td><a href='#' onClick=\"new Ajax.Updater('cell$r[id]', 'menus_edit_process.php', {evalScripts:true,method:'post',postBody:'action=delete&table=$type&id=$r[id]'});return false;\"><img alt='Click to Delete' title='Click to Delete' src='images/delete.png' border=0></a></td></tr>";
	}
echo "</table><div></div>";
?>

