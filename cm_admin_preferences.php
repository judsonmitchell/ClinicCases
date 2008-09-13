<?php 
session_start();
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
    <li><a href="cm_admin_home.php"><span id="tab1">At a Glance</span></a></li>
   
    <li><a href="cm_admin_cases.php"><span id="tab2">Cases</span></a></li>
    <li><a href="cm_admin_students.php"><span id="tab3">Students</span></a></li>
        <li><a href="cm_admin_users.php"><span id="tab4">Users</span></a></li>
   <li id = "current"><a href="cm_admin_preferences.php"><span id="tab5">Preferences</span></a></li>


  </ul>

</div>

</div>
<div id="content">
<div class="box" style="margin-right:60%;">
<div class="box_menu"><h5 style="float:left;color:white;">Change Your Password</h5>   
</div>
<div id="pword">
<?php

include 'password_change.php';
?>
</div>
</div>

</div>

</body>
</html>
