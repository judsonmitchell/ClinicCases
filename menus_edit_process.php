<?php
session_start();
if (!$_SESSION)
{die("Error:You are not logged in");}
include 'db.php';

/* Post variables from menus_edit_table.php */
$table = $_POST[table];
$field = $_POST[field];
$data = $_POST[value];
$action = $_POST[action];
$id = $_POST[id];

switch ($action)
	{
		
		case "modify":
		$q = mysql_query("UPDATE `$table` SET `$field` = '$data' WHERE `id` = '$id' LIMIT 1 ");
		echo $data;
		break;
		
		case "delete";
		$q = mysql_query("DELETE FROM `$table` WHERE `id` = '$id' LIMIT 1");
		echo"<span style='color:red;font-weight:bold;'>Deleted</span><script>Effect.Fade('row$id');</script>";
		break;
		
		case "add":
		$q = mysql_query("INSERT INTO `$table` (`id`,`$field`) VALUES (NULL,'$data')");
		$prev_id = mysql_insert_id();
	//	echo "<tr id='row$prev_id'><td><P id=\"cell$prev_id\">$data</P><img src=\"images/onload_tricker.gif\" border=\"0\" onLoad=\"new Ajax.InPlaceEditor('cell$prev_id','menus_edit_process.php',{callback: function(form, value) {return 'id=$prev_id&table=$type&field=$field&action=modify&value='+escape(value) }});\"></td><td><a href='#' onClick=\"new Ajax.Updater('cell$prev_id', 'menus_edit_process.php', {evalScripts:true,method:'post',postBody:'action=delete&table=$table&id=$prev_id'});\"><img alt='Click to Delete' title='Click to Delete' src='images/delete.png' border=0></a></td></tr>";
		header('Location: menus_edit_table.php?type=' . $table);
		break;
	}
		





?>
