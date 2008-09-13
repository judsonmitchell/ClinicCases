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
<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/print.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="./javascripts/table_stripe.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script>

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php?sid=<?php echo $_COOKIE[PHPSESSID]; ?>', {
    method: 'get',
    frequency: 300
  });



</script>
</head>
<body>
<div id = "bug" style="display:none;">
</div>
<?php include 'cm_menus.php';?>
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
  <li><a href="cm_utilities.php"><span id="tab5">Utilities</span></a></li>

    <li><a href="cm_preferences.php"><span id="tab6">Preferences</span></a></li>

  </ul>

</div>

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
$q = mysql_query("SELECT *  FROM `cm_journals` WHERE `username` = '$_SESSION[login]' ORDER BY `date_added` DESC LIMIT 1");	
$x = mysql_fetch_array($q);

$n = mysql_query("SELECT *  FROM `cm_journals` WHERE `username` = '$_SESSION[login]'");	
$no = mysql_fetch_array($n);

echo "Your Last Journal Submitted:<strong>";
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
echo "<td><span style=\"color:red;font-weight:bold;\">$nm</span> total journals</td>";

echo "</tr></table>";
}

else

{
$get_num = mysql_query("SELECT * FROM `cm_journals` WHERE `deleted` != 'yes'  AND `read` != 'yes' AND `professor` = '$_SESSION[login]' ");
$jnum = mysql_num_rows($get_num);
echo "<td><span style=\"color:red;\">$jnum</span> new journals</td></tr></table>";
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
