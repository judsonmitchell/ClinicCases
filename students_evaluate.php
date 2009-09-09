<?php
session_start();
if (!$_SESSION)
	{die("Error:You are not logged in");}

include 'db.php';

function br2nl($text) 
{    return  preg_replace('/<br\\s*?\/??>/i', '', $text);}

if ($_POST[update])  //if the evaluation is to be updated
{
	$txt = nl2br($_POST[eval_text]);
	$update = mysql_query("UPDATE `cm_users` SET `evals` = '$txt' WHERE `id` = '$_POST[xid]' LIMIT 1 ;");
echo<<<obs
$txt
<script>
Event.observe('eval_body','click', function(event){
	new Ajax.Updater('eval_body','students_evaluate.php',{evalScripts:true,method:'post',parameters:({xid:'$_POST[xid]'})})
	
	})
	</script>
obs;
	
	die;
	
	
	
}

if ($_POST[cancel])
{
	$get_eval = mysql_query("SELECT `evals`,`id` FROM `cm_users` WHERE `id` = '$_POST[xid]'");
	$ev = mysql_fetch_array($get_eval);
	$txt = $ev[evals];
		if (empty($ev[evals]))
		{echo "You have not evaluated this student yet.  Click anywhere in this window to begin.";}
		else
		{echo $txt;}
echo<<<this
<script>
Event.observe('eval_body','click', function(event){
	new Ajax.Updater('eval_body','students_evaluate.php',{evalScripts:true,method:'post',parameters:({xid:'$_POST[xid]'})})
	
	})
	</script>

this;
die;
	
}

if ($_SESSION['class'] !== 'prof')
{echo "You do not have permission to access this information.";die;}

$get_eval = mysql_query("SELECT `evals`,`id` FROM `cm_users` WHERE `id` = '$_POST[xid]' LIMIT 1");
$ev = mysql_fetch_array($get_eval);
$text = $ev[evals];


$strp = br2nl($text);

if (empty($ev[evals]))
	{$text = "Start typing your evaluation.  Click save when you are done";}

echo<<<ev
<form id="eval_form" name="eval_form">
<textarea id="eval_text" name="eval_text">$strp</textarea>
<input type="hidden" id="xid" name="xid" value="$_POST[xid]">
<input type="hidden" id="update" name="update" value="yes">
<input id="eval_save" type="button" value="Save">  <input id="eval_cancel" type="button" value="Cancel">
</form>
<script>

boxDimensions = $('eval_body').getDimensions();
boxw = boxDimensions.width - 40;
boxh = boxDimensions.height - 70;
$('eval_text').setStyle({width: boxw + 'px',height:boxh + 'px'});
ev;
if (!$_POST[update])
	{
		echo "$('eval_body').stopObserving('click');";
	}
	
echo<<<ev
Event.observe('eval_save', 'click', function(event) {
	new Ajax.Updater('eval_body','students_evaluate.php',{evalScripts:true,method:'post',parameters:Form.serialize('eval_form')})
	})

Event.observe('eval_cancel', 'click', function(event) {
	new Ajax.Updater('eval_body','students_evaluate.php',{evalScripts:true,method:'post',parameters:({xid:'$_POST[xid]',cancel:'true'})})
	})

</script>
ev;
?>
