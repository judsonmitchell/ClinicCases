<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}
include 'db.php';

$id = $_POST[id];

$q = mysql_query("SELECT `id`,`attachment` from `cm_board` WHERE `id` = '$id' LIMIT 1");

$r = mysql_fetch_array($q);

	if ($r[attachment] !== '')
	{
echo "<ul>";
$strp_first_pipe = substr($r[attachment],1);
$list = explode("|",$strp_first_pipe);

	foreach ($list as $item)
		{
			
			echo "<li><a id=\"$r[id]\" class=\"del\" href=\"#\" alt=\"Remove Attachment\" title=\"Remove Attachment\" onClick=\"new Ajax.Request('board_delete.php',{method:'post',parameters:{id:'$r[id]',doc_name:'$item',del_type:'doc'},onSuccess:function(){
				new Ajax.Updater('attach_list','board_attchmnt_list.php',{evalScripts:true,method:'post',parameters:{id:'$r[id]'}});
				$('notifications').style.display = 'inline';
				$('notifications').update('Attachment Removed');
				Effect.Fade($('notifications'),{duration:3.0})
				}
				});return false;\"><img  src='images/delete.png' border='0'>  </a><a href='docs/$item' target='new'>$item</a> </li>";
			
		}
		
echo "</ul>";
}
 else
 {echo  "<span style='color:gray;font-size:10px'>None</span>";}
