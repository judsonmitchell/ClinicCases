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

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
    frequency: 300
  });


</script>
<script type="text/javascript">
function chooseTable(name)
{
	createTargets('substance','substance');
	sendDataGet('menus_edit_table.php?type=' + name);
}




</script>
</head>
<body>
<div id = "bug" style="display:none;">
</div>

<div id = "nav_container">
<div id="header">

  <ul>
    <li><a href="cm_admin_home.php"><span id="tab1">At a Glance</span></a></li>

    <li><a href="cm_admin_cases.php"><span id="tab2">Cases</span></a></li>
    <li><a href="cm_admin_students.php"><span id="tab3">Students</span></a></li>
        <li><a href="cm_admin_users.php"><span id="tab4">Users</span></a></li>
	   	      <li><a href="cm_board.php"><span id="tab5">Board</span></a></li>

   <li id = "current"><a href="cm_admin_preferences.php"><span id="tab5">Prefs</span></a></li>


  </ul>

</div>
<?php include 'cm_menus.php';?>
</div>
<div id="content">
<div class = "box_menu">
<p style="margin-left:15px;"><a href="#" onClick = "Effect.Grow('window1');createTargets('window1','window1');sendDataGet('password_change.php');return false;">Change Your Password</a> | <a href="#" onClick = "Effect.Grow('window1');createTargets('window1','window1');sendDataGet('menus_edit.php');return false;"> Edit Menus  </a> |  <a href="#" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('private_key.php');return false;">Private Key</a></p></div>

<div id="window1" style="display:none;">

</div>


</div>


</body>
</html>
