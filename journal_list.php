<?php
include 'db.php';
include_once './classes/format_dates_and_times.class.php';
include 'get_name.php';
if ($_SESSION['class'] == 'student')
{
$get_journals = mysql_query("SELECT * FROM `cm_journals` WHERE `username` = '$_SESSION[login]' AND `deleted` != 'yes' ORDER BY `date_added` DESC");
}
if ($_SESSION['class'] == 'prof')
{
$get_journals = mysql_query("SELECT * FROM `cm_journals` WHERE `deleted` != 'yes' AND `professor` = '$_SESSION[login]' ORDER BY `date_added` DESC");
}
if (isset($_GET[notify]))
{
if ($_GET[notify] == '1')
{
echo <<<NOTIFIER
<div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');">Journal Deleted</div>
NOTIFIER;



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
<table width="99%"><tr alt="Double-click to view journal" title="Double-click to view journal" onmouseover="document.getElementById('loc_$r[id]').style.background='url(images/grade_gray_small.jpg) repeat-x';this.style.cursor='pointer';" onmouseout="document.getElementById('loc_$r[id]').style.background='url(images/grade_small.jpg) repeat-x'" ondblclick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('journal_view.php?id=$r[id]')">
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
<table width="99%"><tr alt="Double-click to view journal" title="Double-click to view journal" onmouseover="document.getElementById('loc_$r[id]').style.background='url(images/grade_gray_small.jpg) repeat-x';this.style.cursor='pointer';" onmouseout="document.getElementById('loc_$r[id]').style.background='url(images/grade_small.jpg) repeat-x'" ondblclick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('journal_view.php?id=$r[id]')">
<td width="25%">
ROW;
formatDate($r[date_added]);
ECHO <<<ROW

</td><td width="55%"><span style="color:gray;">$snippet....</span></td><td width="10%" align="left"> 
ROW;
if ($r[commented])
{echo "<span style='color:red;font-size:10pt;'>Comments</span>";}
ECHO <<<ROW
</td><td width="5%"><a alt="Click to Edit" title="Click to Edit" href="#" onClick="createTargets('window1','window1');sendDataGet('journal_new.php?temp_id=$r[temp_id]');Effect.Grow('window1');return false;">Edit</a></td><td width="5%"><a title="Delete this journal" alt="Delete this journal" href="#" onClick="var conf = confirm('Are you sure you want to delete this journal?');if (conf ==true){createTargets('journal_container','journal_container');sendDataGet('journal_delete.php?id=$r[id]');return false;}else {return false;}">Delete</a></tr></table>

</DIV>
ROW;
}
}
if(mysql_num_rows($get_journals)<1)
	{if ($_SESSION['class'] == 'student')
		
		{echo "You have no journals.  Click on the journal icon at the upper left to begin.";}
		else
		{echo  "Your students have not yet submitted any journals.";}
	}
?>

