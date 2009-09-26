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
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script>

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
    frequency: 300
  });


</script>
</head>
<body>
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
echo "<li><a href=\"cm_journals.php\"><span id=\"tab3\">Journals</span></a></li>";
}

if ($_SESSION['class'] == 'prof')
{echo "<li><a href=\"cm_students.php\"><span id=\"tab4\">Students</span></a></li>";}
?>
   <li><a href="cm_board.php"><span id="tab5">Board</span></a></li>

   <li><a href="cm_utilities.php"><span id="tab6">Utilities</span></a></li>

    <li id = "current"><a href="cm_preferences.php"><span id="tab7">Prefs</span></a></li>

  </ul>
</div>
<?php include 'cm_menus.php';?>
</div>
<div id="content">
<div class = "box_menu">
<p style="margin-left:15px;">
<a href="#" onClick = "Effect.Grow('window1');createTargets('window1','window1');sendDataGet('password_change.php');return false;">Change Your Password</a> |

<?php
if ($_SESSION['class'] == 'prof')
{
echo <<<STUPREF
<a href="#" onClick = "Effect.Grow('window1');createTargets('window1','window1');sendDataGet('pref_config_change.php');return false;">Class Configuration</a>
STUPREF;

}
?>

| <a href="#" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('private_key.php');return false;">Private Key</a>

</p></div>

<div id="window1" style="display:none;">
</div>

</div>
</div>

</body>
</html>
