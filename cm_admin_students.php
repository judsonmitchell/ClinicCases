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
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<script src="tabs_scripts.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/FormProtector.js" type="text/javascript"></script>
<script src="./javascripts/accordion.js" type="text/javascript"></script>
<script>
function goLookup()
{
new Ajax.Autocompleter("to_full", "autocomplete", "messages_to_lookup.php", {tokens: ',',afterUpdateElement: updateFields});

function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("to").value = $("to").value + oResult.childNodes.item(1).innerHTML + ',';
  }
}

function goLookup2()
{
new Ajax.Autocompleter("cc1_full", "autocomplete2", "messages_to_lookup.php", {tokens: ',',afterUpdateElement: updateFields});

function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("cc1").value = $("cc1").value + oResult.childNodes.item(1).innerHTML + ',';
  }

}

Position.includeScrollOffsets = true;

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
    frequency: 300
  });


function positionWindow()
	{
		
		var oset = $('window1').viewportOffset();
		var num = Math.abs(oset[1]);
		$('window1').setStyle({marginTop:num});
		
	}

</script>
</head>

<body>

<div id="notifications"></div>
<div id = "bug" style="display:none;">
</div>
<div id = "nav_container">
<div id="header">

  <ul>
      <li><a href="cm_admin_home.php"><span id="tab1">At a Glance</span></a></li>

    <li><a href="cm_admin_cases.php"><span id="tab2">Cases</span></a></li>
    <li id = "current"><a href="cm_admin_students.php"><span id="tab3">Students</span></a></li>
        <li><a href="cm_admin_users.php"><span id="tab4">Users</span></a></li>
	   	      <li><a href="cm_board.php"><span id="tab5">Board</span></a></li>

   <li><a href="cm_admin_preferences.php"><span id="tab5">Prefs</span></a></li>


  </ul>
</div>
<?php include 'cm_menus.php';?>

</div>
<div id="content" style="overflow:auto;">

<div id="notifier" style="width:100%; height:4%;display:none;"></div>
<?php

$get_students = mysql_query("SELECT * FROM `cm_users` WHERE  `class` = 'student' AND `status` = 'active'  ORDER BY `last_name` ASC");
while ($line = mysql_fetch_array($get_students, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_students,$i);
        $d[$field] = $col_value;
        $i++;

    }
echo "<div class='students'><a title=\"Click for Student's Details\" alt=\"Click for Student's Details\" href=\"#\" onClick=\"new Ajax.Updater('window1','student_detail.php',{evalScripts:true,method:'get',parameters:{id:'$d[id]'}});Effect.Grow('window1');positionWindow();return false;\"><img src='$d[picture_url]' border=0></a><br>$d[first_name] $d[last_name]</div>";
}

?>


<div id="window1" style="display:none;background-color:rgb(255, 255, 204);">



</div>
<div id="messaging_window" style="display:none;">

</div>
<script>
new Draggable('messaging_window');
</script>
</div>

</body>
</html>
