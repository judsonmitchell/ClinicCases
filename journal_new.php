<?php
session_start();
include 'db.php';
include 'fckeditor/fckeditor.php';
include './classes/get_names.php';


if (isset($_GET[temp_id]))
	{
		$text = mysql_query("SELECT * FROM `cm_journals` WHERE `temp_id` = '$_GET[temp_id]' LIMIT 1");
		$z = mysql_fetch_array($text);
		setcookie("journal_temp_id",$_GET[temp_id]);
	}

		else
		{
			$rand = rand();
			setcookie("journal_temp_id",$rand);
			
		}

function lookup($username)
	{
		$get_prof = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$username'");
		$r2 = mysql_fetch_array($get_prof);
		$the_prof = $r2['assigned_prof'];
		return $the_prof;
	}

$get_prof = lookup($_SESSION[login]);

//Find out if student has multiple professors.  If so, choose prof to whom journal is submitted and set cookie; if not, just set cookie with one professor.

	$prof_q = mysql_query("SELECT `username`,`assigned_prof` FROM `cm_users` WHERE `username` = '$_SESSION[login]' LIMIT 1");
	$line = mysql_fetch_object($prof_q);
	$prof_string_f = $line->assigned_prof;
	$prof_string = substr($prof_string_f,0,-1);
	$prof_arr = explode(",",$prof_string);
	$size = count($prof_arr);
		
		if ($size > 1)
			{
				$prof_chooser = "<div style='position:absolute;top:10px;left:150px;'><select name='professor' onChange=\"setCookie('prof_choice',this.value);\"><option selected='selected'>Choose professor to receive this journal.</option>";
				foreach ($prof_arr as $p)
					{
						$fn = new get_names;$full_name=$fn->get_users_name_initial($p);
						$prof_chooser .= "<option value='$p'>$full_name</option>";
					}
				
				$prof_chooser .= "</select></div>";
			
			}
			
			else
			
			{ 
				setcookie("prof_choice",substr($get_prof,0,-1));	
			}


?>



<span id="close"><a href="#" onclick="new Ajax.Updater('journal_container','journal_list.php',{evalScripts:true,method:'get'});Effect.Shrink('window1');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small.png" border="0"></a></span>
<div style="height:30px;width:99%;background-color:rgb(255, 255, 204);padding-left:1%;padding-top:1%;margin-bottom:8px;"><h5>

<?php

	if (isset($_GET[temp_id]))
	{echo "Edit Journal";}
	else
	{echo "New Journal";}

?>



</h5>

<?php echo $prof_chooser; ?>

<span style="font-size:9pt;color:gray;">Your work is auto-saved every 30 seconds, but make sure to press save when finished so no work is lost.</span></div>

<div id="status" style="display:none;">Status</div>

<form name = "journalForm" id = "journalForm" action="journal_process.php" method="post" >
<center>

<?php
	$oFCKeditor = new FCKeditor('jour');
	$oFCKeditor->BasePath = 'fckeditor/';
	$oFCKeditor->ToolbarSet = 'ClinicCases' ;
		
		if (isset($_GET[temp_id]))
			{
				$oFCKeditor->Value = $z[text];
			}
			else
		{$oFCKeditor->Value = '';}
		
	$oFCKeditor->Width  = '99%' ;
	$oFCKeditor->Height = '89%' ;
	$oFCKeditor->Config['ajaxAutoSaveTargetUrl'] = 'plugins/ajaxAutoSave/saveAdapter/saveAdapter.php';
	$oFCKeditor->Config['ajaxAutoSaveBeforeUpdateEnabled'] = 'true';
	$oFCKeditor->Config['ajaxAutoSaveRefreshTime'] = '30';
	$oFCKeditor->Config['ajaxAutoSaveSensitivity'] = '2';
	$oFCKeditor->Create();
?>
</center>

</form>


