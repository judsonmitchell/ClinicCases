<?php 
session_start();
include 'db.php';
if (!$_SESSION)
{header('Location: index.php');}

 ?>

<html>
<head>
<title>Students - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet"  href="cm_tabs.css" type="text/css">
<script src="tabs_scripts.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script src="javascripts/ajax_scripts.js" type="text/javascript"></script>
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
    <li id = "current"><a href="cm_admin_students.php"><span id="tab3">Students</span></a></li>
        <li><a href="cm_admin_users.php"><span id="tab4">Users</span></a></li>
   <li><a href="cm_admin_preferences.php"><span id="tab5">Preferences</span></a></li>


  </ul>
</div>
</div>
<div id="content" style="overflow:auto;">
<?php

$get_students = mysql_query("SELECT * FROM `cm_users` WHERE  `status` = 'active'AND `class` = 'student' AND `status` = 'active'  ORDER BY `last_name` ASC");
while ($line = mysql_fetch_array($get_students, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_students,$i);
        $d[$field] = $col_value;
        $i++;

    }
echo "<div class='students'><a title=\"Click for Student's Details\" alt=\"Click for Student's Details\" href=\"#\" onClick=\"createTargets('window1','window1');sendDataGet('student_detail.php?id=$d[id]');Effect.Grow('window1');return false;\"><img src='$d[picture_url]' border=0></a><br>$d[first_name] $d[last_name]</div>";    
}

?>


<div id="window1" style="display:none;background-color:white;">



</div>
<div id="messaging_window" style="display:none;">

</div>
</div>

</body>
</html>
