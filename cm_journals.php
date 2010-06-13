<?php
session_start();
include 'db.php';
include './classes/format_dates_and_times.class.php';


if (!$_SESSION)
{header('Location: index.php?login_error=3');}
 ?>
<html>
<head>
<title>Journals - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet"  href="cm_tabs.css" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/print.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="./javascripts/table_stripe.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script type="text/javascript">


function setCookie( name, value, expires, path, domain, secure ) {
	var today = new Date();
	today.setTime( today.getTime() );
	if ( expires ) {
		expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name+'='+escape( value ) +
		( ( expires ) ? ';expires='+expires_date.toGMTString() : '' ) + //expires.toGMTString()
		( ( path ) ? ';path=' + path : '' ) +
		( ( domain ) ? ';domain=' + domain : '' ) +
		( ( secure ) ? ';secure' : '' );
}
</script>





<script>

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
    frequency: 300
  });


</script>
</head>
<body >
<div id="notifications"></div>
<div id = "bug" style="display:none;">
</div>

<div id = "nav_container">
<div id="header">

  <ul>
    <li><a href="cm_home.php"><span id="tab1">At A Glance</span></a></li>
         <?php
if ($_SESSION['pref_case'] == 'on')
{
echo "<li><a href=\"cm_cases.php\"><span id=\"tab2\">Cases</span></a></li>";
}

if ($_SESSION['pref_journal'] == 'on')
{
echo "<li id=\"current\"><a href=\"cm_journals.php\"><span id=\"tab2\">Journals</span></a></li>";
}
if ($_SESSION['class'] == 'prof')
{echo "<li><a href=\"cm_students.php\"><span id=\"tab3\">Students</span></a></li>";}
?>
   <li><a href="cm_board.php"><span id="tab4">Board</span></a></li>

  <li><a href="cm_utilities.php"><span id="tab5">Utilities</span></a></li>

    <li><a href="cm_preferences.php"><span id="tab6">Prefs</span></a></li>

  </ul>

</div>
<?php include 'cm_menus.php';?>
</div>
<div id="content" style="background-color:rgb(255,255,204);">
<div id="choosers" style="width:95%;height:35px;text-align:left;margin: .25% 2.5% .25% 2.5%;">
 <table width="700px"><tr>

<?php
if ($_SESSION['class'] == 'student')
{
echo <<<NEWJ
<td width="35px"><a href="#" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('journal_new.php');return false;" alt="New Journal Entry" title="New Journal Entry"><img src="images/new_journal_bg.png" border="0"></a></td>
NEWJ;
}
?>
<td>
<span class="name">Journals</span></td><td>
<?php

if ($_SESSION['class'] == 'student')
{
$q = mysql_query("SELECT *  FROM `cm_journals` WHERE `username` = '$_SESSION[login]' and `deleted` != 'yes' ORDER BY `date_added` DESC LIMIT 1");
$x = mysql_fetch_array($q);

$n = mysql_query("SELECT *  FROM `cm_journals` WHERE `username` = '$_SESSION[login]' and `deleted` != 'yes'");
$no = mysql_fetch_array($n);

echo "Your Last Journal Submitted:<strong> ";
if (mysql_num_rows($n) < 1)
	{echo "Never";}
else {
$date = formatDateAsVarHuman($x[date_added]);
echo $date[0];
}

echo "</strong></td>";

if (mysql_num_rows($n) < 1)
	{$nm = "0";}
else
{
$nm = mysql_num_rows($n);
}
echo "<td><span id=\"jnum\" style=\"color:red;font-weight:bold;\">$nm</span> total journals</td>";

echo "</tr></table>";
}

else

{
$get_num = mysql_query("SELECT * FROM `cm_journals` WHERE `deleted` != 'yes'  AND `read` != 'yes' AND `professor` = '$_SESSION[login]' ");
$jnum = mysql_num_rows($get_num);
echo "<td><span id=\"jnum\" style=\"color:red;\">$jnum</span> unread journals</td><td>";
echo <<<SELECT
<select name="j_type" id="j_type" onChange="new Ajax.Updater('journal_container','journal_list.php',{method:'get',parameters:{j_type :  this.value}});">

<option value = "unread" selected="selected">Unread Journals</option>
<option value = "read">Read Journals</options>
<optgroup label="--By Student--">
SELECT;
$by_student = mysql_query("SELECT * FROM `cm_users` WHERE `class`= 'student' and `assigned_prof` LIKE '%$_SESSION[login]%' and `status` = 'active' ORDER BY `last_name` ASC");
	while ($b = mysql_fetch_array($by_student))
	{
		echo "<option value=\"$b[username]\">$b[first_name] $b[last_name]</option>";

		}


echo"</optgroup></select></td></tr></table>";
}

?>



</div>
<div id = "journal_container" style="background-color:white;">

<?php
include 'journal_list.php';
?>

</div>
<div id="window1" style="display:none;">




</div>
</div>

</body>
</html>
