<?php
session_start();
include 'db.php';
include 'fckeditor/fckeditor.php';

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
?>

<span id="close"><a href="#" onclick="createTargets('journal_container','journal_container');sendDataGet('journal_list.php');Effect.Shrink('window1');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small.png" border="0"></a></span>
<div style="height:30px;width:99%;background-color:rgb(255, 255, 204);padding-left:1%;padding-top:1%;margin-bottom:8px;"><h5><?php
if (isset($_GET[temp_id]))
{echo "Edit Journal";}
else
{echo "New Journal";}?></h5><span style="font-size:9pt;color:gray;">Your work is auto-saved every 30 seconds, but make sure to press save when finished so no work is lost.</span></div>

<div id="status" style="display:none;">Status</div>

<form name = "journalForm" id = "journalForm" action="journal_process.php" method="post">
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
$oFCKeditor->Create();
?>
</center>

</form>
