<?php
include 'db.php';
include 'get_client_name.php';
include 'get_name.php';


$date = date('Y-m-d H:i:s');
$lastweek = strtotime("-1 week");
$lastweek2 = date('Y-m-d H:i:s',$lastweek);
$nextweek = strtotime("+1 week");
$nextweek2 = date('Y-m-d H:i:s',$nextweek);
$headers = 'From: <no-reply@ClinicCases.com>' . "\r\n" .
   'Reply-To: <no-reply@ClinicCases.com>' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();
   
$subject = "ClinicCases.com: Your Monday Morning Report";
$get_profs = mysql_query("SELECT * FROM `cm_users` WHERE `class` = 'prof' AND `status` = 'active'");


while ($r = mysql_fetch_array($get_profs))
{

$body = "Here is your Monday Morning Report of student activity and upcoming events.\r\nYour Students' Activity for the last 7 days, rounded to the nearest hour:\n\n";
  $get_students = mysql_query("SELECT * FROM `cm_users` WHERE `assigned_prof` = '$r[username]' ORDER BY `last_name`");
  
    while ($x = mysql_fetch_array($get_students))
    {
     $get_time = mysql_query("SELECT `username`, `date`, SUM(time) FROM `cm_case_notes` WHERE `date` BETWEEN '$lastweek2' AND '$date' AND `username` = '$x[username]' GROUP BY `username`");
     while ($y = mysql_fetch_array($get_time))
     {
     $time = $y['SUM(time)'];
     if ($time < 3600)
     {$format_time = round($time / 60); $unit = "minutes";}
     else
     {$format_time = round($time / 3600);$unit = hours;}
     $body .="$x[first_name] $x[last_name]: $format_time $unit. \n";
      
     }
 
  
if (mysql_num_rows($get_time)<1)
{$body .= "$x[first_name] $x[last_name]: No time entered.\n";}
}
     


$body .= "\nThis week's events: \n\n";
$get_events = mysql_query("SELECT * FROM `cm_events` WHERE `prof` = '$r[username]' and `status` = 'pending' AND `date_due` BETWEEN '$date' AND '$nextweek2' ORDER BY `date_due` ASC");
while ($p = mysql_fetch_array($get_events))
{
list($fname, $lname) = getClientAsVar($p[case_id]);

$body .= "Case: $fname $lname\nDue: $p[date_due]\nWhat: $p[task]\nResponsible:";
$get_responsibles = mysql_query("SELECT * FROM `cm_events_responsibles`,`cm_events` WHERE cm_events.id = '$p[id]' AND cm_events_responsibles.event_id = cm_events.id AND cm_events.date_due BETWEEN '$date' AND '$nextweek2'");
while ($o = mysql_fetch_array($get_responsibles))
{
list($fname2,$lname2) = getNameAsVar2($o[username]);
$body .=" $fname2 $lname2 ";
}
$body .=" \n\n";
}
if (mysql_num_rows($get_events)<1)
{$body .="No events set for this week. \r";}

$body .="\nHave a great week!";

mail($r[email],$subject,$body,$headers);
}






?>
