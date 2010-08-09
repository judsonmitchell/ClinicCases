<?php
session_start();
if (!$_SESSION)
{echo "There is a login problem.  Please login again.";die;}
include 'db.php';
include './classes/format_case_number.php';
include './classes/format_dates_and_times.class.php';
include './classes/get_names.php';



$id = $_GET['id'];
$result = mysql_query("SELECT * FROM `cm` WHERE `id` = '$id' LIMIT 1");
$rand = rand();
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($result,$i);
        $d[$field] = $col_value;
        $i++;

    }

}
ECHO <<<DISPLAY

<div id = "case_single_side">



<span id="print_name">
<h3>$d[first_name]
DISPLAY;
if (!empty($d[address2]))
echo" $d[m_initial]";
ECHO <<<DISPLAY
 $d[last_name]</h3>
Case No: 
DISPLAY;
formatCaseNo($d[id]);
ECHO <<<DISPLAY
</span>
<p style="height:20%;">$d[address1]
DISPLAY;
if (!empty($d[address2]))
echo "<br>$d[address2]";

echo "<br>$d[city] $d[state] $d[zip]";

if (!empty($d[phone1]))
echo "<br>$d[phone1]";

if (!empty($d[phone2]))
echo "<br>$d[phone2]";

if (!empty($d[email]))
echo "<br><a href='mailto:$d[email]' target='_blank'>$d[email]</a><br><br>";
ECHO <<<DISPLAY
<br>

<div id="contacts" style="height:71.5%;">
<h4>Students</h4>

DISPLAY;
$assigned_students = mysql_query("SELECT * FROM `cm_cases_students` WHERE `case_id` = '$id' and `status` = 'active'");
while ($stu = mysql_fetch_array($assigned_students))
{
$first_name = $stu['first_name'];
$last_name= $stu['last_name'];
echo "$first_name $last_name<br>";

}
if (mysql_num_rows($assigned_students)<1)
{echo "<span style='color:gray;font-style:italic'>No students assigned to this case.</span><br>";}
ECHO <<<DISPLAY

<br>
<h4>Professor(s)</h4>

DISPLAY;

//format prof names
$plist = explode(",",substr($d[professor],0,-1));
					foreach ($plist as $v)
					{
						$p = new get_names;$px = $p->get_users_name_initial($v); 
						$prof_str .= $px . "<BR> ";
					}	
					


ECHO <<<DISPLAY
$prof_str<br>
<h4>Next Event</h4>
DISPLAY;
$get_event = mysql_query("SELECT * FROM `cm_events` WHERE `case_id` = '$id' and `status` = 'Pending' ORDER BY `date_due` ASC LIMIT 1");
$x = mysql_fetch_array($get_event);
$date = sqlToDateAsVar($x[date_due]);
if (strlen($x[task]) > 100)
{
$result_x = substr($x[task],0,100) . "...";}
else
{$result_x = $x[task];}

if (mysql_num_rows($get_event)<1)
{echo "<span id = \"event_notifier\" style='color:gray;font-style:italic'>No events scheduled.</span>";}
else
{echo "<span id = \"event_notifier\" style='color:gray;font-style:italic'>$date - $result_x</span>";}
ECHO <<<DISPLAY
<br>
<br>
<h4>Latest Activity</h4>
DISPLAY;
$get_activity = mysql_query("SELECT * FROM `cm_case_notes` WHERE `case_id` = '$id' ORDER BY `date` DESC LIMIT 1");
$y = mysql_fetch_array($get_activity);
if (strlen($y[description]) > 130)
{
$result = substr($y[description],0,130) . "...";}
else
{$result = $y[description];}
if (mysql_num_rows($get_activity)<1)
{echo "<span id=\"activity_notifier\" style='color:gray;font-style:italic'>No activity on this case.</span>";}
else
{echo "<span id=\"activity_notifier\" style='color:gray;font-style:italic'>$result.</span>";}

ECHO <<<DISPLAY
<br>
<br>
<h4>Approx. Time</h4>
DISPLAY;

$get_hours = mysql_query("SELECT `case_id`, SUM(time) FROM `cm_case_notes` WHERE `case_id` = '$id' GROUP BY `case_id`");
$z = mysql_fetch_array($get_hours);
$time = $z['SUM(time)'];
$format_time = round($time / 3600);
echo "<span id=\"time_notifier\"> $format_time hours</span>";

ECHO <<<DISPLAY
</div>
</div>

<div id = "single_menu">
<a class="singlenav"  style="text-decoration:none;color:#d2d2d2;font-weight:bold;" id="one_href" href="#" onClick="createTargets('case_activity','case_activity');sendDataGetAndStripe2('cm_case_activity.php?id=$id');changeTab('one_href');return false;">Case Activity</a> | 


<a class="singlenav"  id="two_href" href="#" onClick="new Ajax.Updater('case_activity','cm_docs.php?id=$id',{evalScripts:true,method:'get'});changeTab('two_href');return false;">Documents</a> | <a class="singlenav"  id="three_href"  href="#" onClick="createTargets('case_activity','case_activity');sendDataGet('cm_cases_events.php?id=$id');changeTab('three_href');return false;">Events</a>  | <a class="singlenav"  id="four_href"  href="#" onClick="new Ajax.Updater('case_activity', 'message_roll.php', {evalScripts:true,method:'post',postBody:'case_id=$id&amp;draw_menu=y&amp;re_interior=y'});changeTab('four_href');return false;">Messages</a> | <a  class="singlenav" id="five_href" href="#" onClick="createTargets('case_activity','case_activity');sendDataGet('cm_case_contacts.php?id=$id&ieyousuck=' + Math.random()*5);changeTab('five_href');return false;">Case Contacts</a> | <a class="singlenav"  id="six_href"  href="#" onClick="createTargets('case_activity','case_activity');sendDataGet('client_data.php?id=$id&interior=y');changeTab('six_href');return false;">Client Data</a>
DISPLAY;

if ($_SESSION['class'] != 'student')
{ 

echo <<<CLSTT

 | <a  class="singlenav" id="seven_href" href="#" onClick="createTargets('case_activity','case_activity');sendDataGet('case_close.php?id=$id');changeTab('seven_href');return false;">Close</a>
CLSTT;
}
else
{
ECHO "<span id=\"seven_href\" style=\"display:hidden\"></span>";

}
ECHO <<<DISPLAY

</div>
<div id = "case_single_main" >

<div id = "case_activity">




DISPLAY;
include 'cm_case_activity.php';
ECHO <<<DISPLAY

</div>

</div>
<div id="close"><a alt="Print this data" title="Print this Data" href="#" onClick="printDivComplex('case_activity');return false;"><img src="images/print_small.png" border="0" style="margin-right:30px;"></a><a href="#" onclick="Effect.Shrink('window1');document.getElementById('view_chooser').style.display = 'inline';return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small.png" border="0"></a></div>
DISPLAY;

?>
