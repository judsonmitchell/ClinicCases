<?php 
session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
include 'db.php';
?>
<html>
<head>
<title>Preferences - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet"  href="cm_tabs.css" type="text/css">
<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
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
echo "<li><a href=\"cm_journals.php\"><span id=\"tab2\">Journals</span></a></li>";
}

if ($_SESSION['class'] == 'prof')
{echo "<li><a href=\"cm_students.php\"><span id=\"tab3\">Students</span></a></li>";}
?>
   <li><a href="cm_utilities.php"><span id="tab5">Utilities</span></a></li>

    <li id = "current"><a href="cm_preferences.php"><span id="tab6">Preferences</span></a></li>

  </ul>
</div>

</div>
<div id="content">
<div class="box" >
<div class="box_menu"><h5 style="float:left;color:white;">Change Your Password</h5>   
</div>
<div id="pword">
<?php

include 'password_change.php';
?>
</div>
</div>
<?php
if ($_SESSION['class'] == 'prof')
{
echo <<<STUPREF
<DIV CLASS="box">
<div class="box_menu">Class Configuration</div>
STUPREF;

include 'pref_config_change.php';

ECHO <<<STUPREF

</div>
</div>
STUPREF;

}









?>
</div>

</body>
</html>
