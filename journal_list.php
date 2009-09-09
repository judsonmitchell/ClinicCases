<?php
session_start();
if (!$_SESSION)
{die("Error:You are not logged in");}

include 'db.php';
include_once './classes/format_dates_and_times.class.php';
include 'get_name.php';

if(isset($_GET[mark_read]))
	{

		$mark = mysql_query("UPDATE `cm_journals` SET `read` = 'yes' WHERE `id` = '$_GET[id]' LIMIT 1");
	}


if(isset($_GET[xdelete]))
	{
		$del_journal = mysql_query("UPDATE `cm_journals` SET `deleted` = 'yes' WHERE `id` = '$_GET[id]' LIMIT 1");
	}

if ($_SESSION['class'] == 'student')
	{
		$get_journals = mysql_query("SELECT * FROM `cm_journals` WHERE `username` = '$_SESSION[login]' AND `deleted` != 'yes' ORDER BY `date_added` DESC");
	}


if ($_SESSION['class'] == 'prof')
	{
		if (isset($_GET[j_type]))
		{
			switch($_GET[j_type])
			{
				case "unread":
				$get_journals = mysql_query("SELECT * FROM `cm_journals` WHERE `deleted` != 'yes' AND `professor` = '$_SESSION[login]' AND `read` = '' ORDER BY `date_added` DESC");
				break;
				case "read":
				$get_journals = mysql_query("SELECT * FROM `cm_journals` WHERE `deleted` != 'yes' AND `professor` = '$_SESSION[login]' AND `read` =  'yes' ORDER BY `date_added` DESC");
				break;
				default:
				$get_journals = mysql_query("SELECT * FROM `cm_journals` WHERE `deleted` != 'yes' AND `professor` = '$_SESSION[login]' AND `username` = '$_GET[j_type]'  ORDER BY `date_added` DESC");
			}
	}
	else
	{
	$get_journals = mysql_query("SELECT * FROM `cm_journals` WHERE `deleted` != 'yes' AND `professor` = '$_SESSION[login]' AND `read` = '' ORDER BY `date_added` DESC");
}
}



while ($r = mysql_fetch_array($get_journals))
{
$snip = substr($r[text],0,80);
$snippet = strip_tags($snip);

if ($_SESSION['class'] == 'prof')
{
echo <<<ROW
<div id ="loc_$r[id]" style="width:100%;height:40px;background:url(images/grade_small.jpg) repeat-x;">
<table width="99%"><tr alt="Click to view journal" title="Click to view journal" onmouseover="document.getElementById('loc_$r[id]').style.background='url(images/grade_gray_small.jpg) repeat-x';this.style.cursor='pointer';" onmouseout="document.getElementById('loc_$r[id]').style.background='url(images/grade_small.jpg) repeat-x'" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('journal_view.php?id=$r[id]')">
<td width="20%">
ROW;
formatDate($r[date_added]);
echo "</td><td width=\"20%\">";
getName($r[username]);


ECHO <<<ROW
</td>


<td width="50%"><span style="color:gray;">$snippet....</span></td><td width="10%" align="left">
ROW;
if ($r[read])
{echo "<span style='color:red;font-size:10pt;'>Read</span>";}
if ($r[commented])
{echo "<span style='color:red;font-size:10pt;'> Commented</span>";}
ECHO <<<ROW
</td></tr></table>

</DIV>
ROW;




}

else
{
echo <<<ROW
<div id ="loc_$r[id]" style="width:100%;height:40px;background:url(images/grade_small.jpg) repeat-x;">
<table width="99%"><tr alt="Click to view journal" title="Click to view journal" onmouseover="document.getElementById('loc_$r[id]').style.background='url(images/grade_gray_small.jpg) repeat-x';this.style.cursor='pointer';" onmouseout="document.getElementById('loc_$r[id]').style.background='url(images/grade_small.jpg) repeat-x'" onclick="createTargets('window1','window1');Effect.Grow('window1');sendDataGet('journal_view.php?id=$r[id]');" >
<td width="25%">
ROW;
formatDate($r[date_added]);
ECHO <<<ROW

</td><td width="55%"><span style="color:gray;">$snippet....</span></td><td width="10%" align="left">
ROW;
if ($r[commented])
{echo "<span style='color:red;font-size:10pt;'>Comments</span>";}
ECHO <<<ROW
</td><td width="5%"><a href="#" class="nobubble" alt="Click to Edit" title="Click to Edit"  onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('journal_new.php?temp_id=$r[temp_id]');">Edit</a></td><td width="5%">

<a href="#" class="nobubble" title="Delete this journal" alt="Delete this journal" onClick="var conf = confirm('Are you sure you want to delete this journal?');if (conf == true){new Ajax.Updater('journal_container','journal_list.php',{evalScripts:true,method:'get', parameters : {xdelete:'y',id:'$r[id]'},onSuccess: function(){\$('notifications').update('Journal Deleted');$('notifications').style.display='block';new Ajax.Updater('jnum','updater.php',{method:'get',parameters: {type:'journal_student'}});Effect.Fade('notifications',{duration:4})}})};">Delete</a></td></tr></table>




</DIV>
ROW;
}
}
echo "<script>$$('a.nobubble').invoke('observe','click',function(e){Event.stop(e);})</script>";
if(mysql_num_rows($get_journals)<1)
	{if ($_SESSION['class'] == 'student')

		{echo "You have no journals.  Click on the journal icon at the upper left to begin.";}
		else
		{echo  "No journals found.";}
	}
?>

