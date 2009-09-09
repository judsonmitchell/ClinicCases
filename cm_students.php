<?php 
session_start();
include 'db.php';
if (!$_SESSION)
{header('Location: index.php?login_error=3');}

 ?>

<html>
<head>
<title>Your Students - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet"  href="cm_tabs.css" type="text/css">
<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/print.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script>
function goLookup()
{
new Ajax.Autocompleter("to_full", "autocomplete", "messages_to_lookup.php", {afterUpdateElement: updateFields});
          
function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("to").value = oResult.childNodes.item(1).innerHTML;
  }
}

function goLookup2()
{
new Ajax.Autocompleter("cc1_full", "autocomplete2", "messages_to_lookup.php", {afterUpdateElement: updateFields});
          
function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("cc1").value = oResult.childNodes.item(1).innerHTML;
  }

}

Position.includeScrollOffsets = true;
</script>
<script>

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php?sid=<?php echo $_COOKIE[PHPSESSID]; ?>', {
    method: 'get',
    frequency: 300
  });



</script>

</head>
<body>
<?php include 'cm_menus.php';?>
<div id = "nav_container">
<div id="header">

  <ul>
    <li ><a href="cm_home.php"><span id="tab1">At A Glance</span></a></li>
      <?php 
if ($_SESSION['pref_case'] == 'on')
{ 
echo "<li><a href=\"cm_cases.php\"><span id=\"tab2\">Cases</span></a></li>";
}

if ($_SESSION['pref_journal'] == 'on')
{ 
echo "<li><a href=\"cm_journals.php\"><span id=\"tab2\">Journals</span></a></li>";
}
?>
    <li id = "current"><a href="cm_students.php"><span id="tab3">Students</span></a></li>
    <li><a href="cm_utilities.php"><span id="tab5">Utilities</span></a></li>

    <li><a href="cm_preferences.php"><span id="tab6">Preferences</span></a></li>
  </ul>
</div>
</div>
<div id="content" >
<div id="notifier" style="width:100%; height:4%;display:none;"></div>
<div id ="students_top">
<?php

$get_students = mysql_query("SELECT * FROM `cm_users` WHERE `assigned_prof` = '$_SESSION[login]' AND `status` = 'active' ORDER BY `last_name` ASC");
while ($line = mysql_fetch_array($get_students, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_students,$i);
        $d[$field] = $col_value;
        $i++;

    }
echo "<div id=\"stud_$d[id]\" class='students'><a title=\"Click for Student's Details\" alt=\"Click for Student's Details\" href=\"#\" onClick=\"createTargets('window1','window1');sendDataGet('student_detail.php?id=$d[id]');Effect.Grow('window1');return false;\"><img src='$d[picture_url]' border=0 onLoad=\"Droppables.add('stud_$d[id]', {onDrop:function(element,dropon){createTargets('notifier','notifier');sendDataGet('students_assign.php?username=$d[username]&first_name=$d[first_name]&last_name=$d[last_name]&case_id=' + element.id);element.style.backgroundColor='#eaeaea';}});
\"></a><br>$d[first_name] $d[last_name]</div>";    
}
if (mysql_num_rows($get_students) < 1)
	{echo "No students have been assigned to you yet.";}
?>
</div>
<div id="students_cases">
<p><i>Drag case file to student to assign.</P>
<?php

$get_limit = mysql_query("SELECT * FROM `cm` WHERE `professor` = '$_SESSION[login]' AND `date_close` = '' OR `professor2` = '$_SESSION[login]' AND `date_close` = ''");

$dingo = mysql_num_rows($get_limit);
$limit = round($dingo / 2);

echo "<table width=\"100%\"><tr><td>";
$get_cases = mysql_query("SELECT * FROM `cm` WHERE `professor` = '$_SESSION[login]' AND `date_close` = '' OR `professor2` = '$_SESSION[login]' AND `date_close` = '' ORDER BY `last_name` ASC LIMIT 0, $limit");
while ($line = mysql_fetch_array($get_cases, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_cases,$i);
        $d[$field] = $col_value;
        $i++;

    }
echo <<<CASELIST
<div id="$d[id]"><img src="images/folder_very_small.png" border="0" onLoad="new Draggable('$d[id]',{snap:false,revert:true});this.style.cursor='move';">$d[first_name] $d[last_name] $d[case_type]</div>
CASELIST;
}
echo "</td><td>";
$increment_limit = $limit +1;
$get_cases2 = mysql_query("SELECT * FROM `cm` WHERE `professor` = '$_SESSION[login]' AND `date_close` = '' OR `professor2` = '$_SESSION[login]' AND `date_close` = '' ORDER BY `last_name` ASC LIMIT $limit, $dingo");
while ($line = mysql_fetch_array($get_cases2, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_cases2,$i);
        $d[$field] = $col_value;
        $i++;

    }
echo <<<CASELIST
<div id="$d[id]"><img src="images/folder_very_small.png" border="0" onLoad="new Draggable('$d[id]',{snap:false,revert:true});this.style.cursor='move';">$d[first_name] $d[last_name] $d[case_type]</div>
CASELIST;

}
if (mysql_num_rows($get_cases2) < 1)
	{echo "<center>You have no cases on this system.</center>";}
echo "</td></tr></table>";
?>


</div>

<div id="window1" style="display:none;background-color:white;">



</div>
<div id="messaging_window" style="display:none;">

</div>

</div>
<div id = "bug" style="display:none;">
</div>

</script>
</body>
</html>
