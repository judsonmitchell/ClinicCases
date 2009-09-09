<?php
session_start();
include 'db.php';
include './classes/format_dates_and_times.class.php';

$event_id = $_GET['event_id'];
$id = $_GET['case_id'];

function who($the_id)
{

$get_responsibles = mysql_query("SELECT * FROM `cm_events_responsibles` WHERE `event_id` = '$the_id'");
while ($line2 = mysql_fetch_array($get_responsibles, MYSQL_ASSOC)) {
    $i2=0;
    foreach ($line2 as $col_value2) {
        $field2=mysql_field_name($get_responsibles,$i2);
        $r[$field2] = $col_value2;
        $i2++;

    }
  $get_full_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$r[username]'");
  while ($x = mysql_fetch_array($get_full_name))
  {echo "$x[first_name] $x[last_name]<br>";}

}

}



$del_event = mysql_query("DELETE FROM `cm_events` WHERE `id` = '$event_id' LIMIT 1");
$del_resp = mysql_query("DELETE FROM `cm_events_responsibles` WHERE `event_id` = '$event_id'");
header('Location: cm_cases_events.php?alerter=delete&id=' . $_GET[case_id]);

?>
