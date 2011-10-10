<?php
session_start();

if ($_SESSION['class'] !== "admin")
{header('Location: index.php?login_error=4');}
else
{
include 'db.php';
include './classes/format_dates_and_times.class.php';
/* check if this is first visit to this page in session. If it is, log it.*/
$check_visit = mysql_query("SELECT * FROM `cm_logs` WHERE `session_id` = '$_COOKIE[PHPSESSID]'");
if (mysql_num_rows($check_visit)<1)
{
$ip = $_SERVER['REMOTE_ADDR'];
if (isset($_COOKIE[PHPSESSID]))
{$sid = $_COOKIE[PHPSESSID];}
else
{$sid = "cookie problem";}

$log_this = mysql_query("INSERT INTO `cm_logs` (`id`,`username`,`timestamp`,`ip`,`session_id`) VALUES (NULL,'$_SESSION[login]',NULL,'$ip','$sid')");

}
}

 ?>
<html>
<head>
<title>Users - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet"  href="cm_tabs.css" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script src="./javascripts/FormProtector.js" type="text/javascript"></script>
<script src="javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="javascripts/validations.js" type="text/javascript"></script>
<script src="./javascripts/print.js" type="text/javascript"></script>

<script src="./javascripts/table_stripe.js" type="text/javascript"></script>
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


new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
    frequency: 60
  });

function theSort(column,sortDir)
{
createTargets('work_space','work_space');
sendDataGetAndStripe('cm_admin_users_table.php?view=' + document.getElementById('view_chooser').value + '&sort=' + column + '&sortdir=' + sortDir);
}

function profCheck()
{
theField = document.getElementById('class');
theTarget = document.getElementById('popout');
if (theField.value == 'student')
{theTarget.style.display = 'block';}


}


Event.observe(window, 'load', function() {

$$("a.nobubble").invoke("observe", "click", function(e) {

	Event.stop(e);
})})

Event.observe(window, 'load', function() {

$$("tr").invoke("observe", "click", function(e) {

	Event.stop(e);
})})


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
    <li><a href="cm_admin_students.php"><span id="tab3">Students</span></a></li>
        <li id = "current"><a href="cm_admin_users.php"><span id="tab4">Users</span></a></li>
	   	      <li><a href="cm_board.php"><span id="tab5">Board</span></a></li>

   <li><a href="cm_admin_preferences.php"><span id="tab5">Prefs</span></a></li>


  </ul>

</div>
<?php include 'cm_menus.php';?>

</div>
<div id="content" >
<div id="choosers" style="width:95%;text-align:right;margin: 5px 5px;">
<div style="position:absolute;left:10px;top:5px;"><a href="#" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('new_user.php');document.getElementById('view_chooser').style.display = 'none';return false;" alt="Add a New User" title="Add a New User"><img src="images/new_user.png" border="0"></a></div>
<form name="user_form" id=
"user_form" style="display:inline;" onSubmit="new Ajax.Updater('work_space','cm_admin_users_table.php',{evalScripts:true,method:'get',parameters:Form.serialize('user_form')});return false;"><input type = "text" width="35" value="Search By Name" id = "searchterm" name = "searchterm" onFocus = "this.value = '';this.style.color= 'black';" style="display:inline;">
<a href="#" class="nobubble" onClick="createTargets('work_space','work_space');sendDataGetAndStripe('cm_admin_users_table.php?searchterm=' + document.getElementById('searchterm').value);document.getElementById('choose_port').style.display = 'none';return false;"><img src="./images/check.png" border="0"  class = "submit_image"></a>
</form>

<span id="choose_port">
View: <select id = "view_chooser" name = "view_chooser" onFocus = "this.style.color = 'black';" onChange = "createTargets('work_space','work_space');sendDataGetAndStripe('cm_admin_users_table.php?view=' + document.getElementById('view_chooser').value);">
<option value = "active" selected="selected">Active Users Only</option>
<option value = "inactive">Inactive Users Only</option>
<option value = "total">All Users</option>

</select>
</span>
<a alt="Print this data" title="Print this Data" href="#" onClick="printDiv('work_space');return false;"><img src="images/print.png" border="0" class="print_image"></a>
</div>
<?php
$limiter = "WHERE `status` != 'inactive'";


/* End */

$result = mysql_query("SELECT * FROM `cm_users`  $limiter ORDER BY `last_name`");


?>
<div id = "work_space" style="width:99.8%;height:90%;overflow:auto;">
<div id="the_info" style="width:100%;height:30px;display:none;"></div>
<table id = "display_cases">
<thead><tr><td colspan="9" style="background:url(images/grade_gray_small.jpg) repeat-x;color:black;"><b><?php echo mysql_num_rows($result); ?></b> active users found.</td></tr><tr><td>Face</td></td><td><a class='theader' href="#" onClick = "theSort('last_name','ASC');return false;" title="Sort by this column" alt="Sort by this column" >Last Name</td><td><a class='theader' href="#" onClick = "theSort('first_name','ASC');return false;" title="Sort by this column" alt="Sort by this column" >First Name</td><td><a class='theader' href="#" onClick = "theSort('class','ASC');return false;" title="Sort by this column" alt="Sort by this column">Group</td><td><a class='theader' href="#" onClick = "theSort('date_created','ASC');return false;" title="Sort by this column" alt="Sort by this column">Date Added</td><td><a class='theader' href="#" onClick = "theSort('status','ASC');return false;" title="Sort by this column" alt="Sort by this column">Status</a></td><td></td></tr></thead><tbody>
<?php


while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($result,$i);
        $d[$field] = $col_value;
        $i++;

    }
$kill_time = explode(' ',$d[date_created]);
$get_date_open = explode('-',$kill_time[0]);
$month = $get_date_open[1];
$day = $get_date_open[2];
$year = $get_date_open[0];
$new_date_created = "$month" . "/" . "$day" . "/" . "$year";

$thumb = explode('/',$d[picture_url]);
$thumb_target = $thumb[0] . '/tn_' . $thumb[1];

echo <<<ROWS

<tr  title="Click to View/Edit User" alt="Click to View/Edit User" onmouseover="this.style.color='red';this.style.cursor='pointer'" onmouseout="this.style.color='black';" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGetAndStripeNoStatus2('cm_users_view.php?id=$d[id]');document.getElementById('view_chooser').style.visibility = 'hidden';return false;"><td><img src="$thumb_target"></td><td>$d[last_name]</td><td>$d[first_name]</td><td>$d[class]</td><td>$new_date_created</td><td id="deac$d[id]">$d[status]</td>
<td ><a href="#" class="nobubble" onClick="createTargets('deac$d[id]','deac$d[id]');sendDataGet('user_change_status.php?id=$d[id]');return false;" title="Click this to either activate an inactive user or inactivate an active user" alt="Click this to either activate an inactive user or inactivate an active user">Change Status</a></td></tr>
ROWS;


}

?>
</tbody></table></div><script>stripe('display_cases','#fff','#e0e0e0');</script>


<div id="window1" style="display:none;">


</div>
<div id="messaging_window" style="display:none;">

</div>
</div>

</body>
</html>
