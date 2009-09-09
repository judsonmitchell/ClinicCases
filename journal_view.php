<?php
session_start();
include 'db.php';
include_once './classes/format_dates_and_times.class.php';
include 'get_name.php';
function photo($username)
{

$get_id = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$username' LIMIT 1");
$ph = mysql_fetch_array($get_id);

echo "<div id=\"student_photo\" style='float:left;margin-right:5px;'><img src=\"$ph[picture_url]\" width=\"64\" height=\"64\" border=\"0\" style=\"display:inline;\"></div>";

}

$get_journal = mysql_query("SELECT * FROM `cm_journals` WHERE `id` = '$_GET[id]' LIMIT 1");
$r = mysql_fetch_array($get_journal);
?>
<span id="close"><a alt="Print this journal" title="Print this journal" href="#" onClick="printDiv('window1');return false;"><img src="images/print_small.png" border="0" style="margin-right:30px;"></a><a href="#" onclick="<?php if($_SESSION['class'] == 'prof'){echo "sendDataGet('journal_mark_read.php?id=$_GET[id]');";}?>createTargets('journal_container','journal_container');sendDataGet('journal_list.php');Effect.Shrink('window1');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small.png" border="0"></a></span>

<div style="height:30px;width:90%;background-color:rgb(255, 255, 204);padding-left:2.5%;padding-top:1%;margin-bottom:8px;"><?php if($_SESSION['class'] == 'prof'){photo($r[username]);}   ?><h5>Journal submitted <?php formatDate($r[date_added]); if($_SESSION['class'] == 'prof'){echo " by ";getName($r[username]);}   ?> 
<?php if($_SESSION['class'] == 'student')
{echo "<a href=\"#\" onClick=\"createTargets('window1','window1');sendDataGet('journal_new.php?temp_id=$r[temp_id]');return false;\">Edit</a>";}?></h5></div>
<?php
if ($_SESSION['class'] == 'prof')
{
echo <<<SIZER

<div id="journal_substance">
SIZER;
}
else
{

echo <<<SIZER


<div id="journal_substance" style="width:95%;height:88%;margin:1% 2.5% 1% 2.5%;background-color:white;border:1px solid black;text-align:left;overflow:auto;">
SIZER;

}
?>
<?php 
$comment_no_slash = stripslashes($r[comments]);
echo "$r[text]<br><p style=\"color:red\">$comment_no_slash</p>"; ?></div>

<?php
if ($_SESSION['class'] == 'prof')
{
ECHO <<<CMT

<DIV id="commenter" style="width:95%;height:17%;margin-top:1%;margin-left:2.5%;overflow:auto;display:none;"><form id="comment">
<textarea name="comment_text" id="comment_text" rows="2" cols="110" ></textarea>
<input type="hidden" name="id" value="$r[id]";>
<input type="button" value="Comment" onClick="createTargets('commenter','commenter');sendDataPost('journal_comment_add.php','comment');return false;">
</form>
</div>
<div id = "doer" style="text-align:center;"><a href="#" onClick="this.style.display='none';Effect.Appear('commenter');return false;">Add Comment</a></div>
CMT;
}

?>




