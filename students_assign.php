<?php
session_start();
include 'db.php';

$check = mysql_query("SELECT * FROM `cm_cases_students` WHERE `case_id` = '$_GET[case_id]' AND `username` = '$_GET[username]'");
$r = mysql_fetch_array($check);
if (mysql_num_rows($check)>0)
{
	 $q=mysql_query("UPDATE `cm_cases_students` SET `status` = 'active' WHERE `id` = '$r[id]' LIMIT 1");


ECHO <<<NOTIFY
<SPAN style="color:red;font-weight:bold;">Student has been assigned to this case again.</span>
NOTIFY;
die;
}


$assign = mysql_query("INSERT INTO `cm_cases_students` (`id`,`username`,`first_name`,`last_name`,`case_id`,`status`) VALUES (NULL,'$_GET[username]','$_GET[first_name]','$_GET[last_name]','$_GET[case_id]','active')");

$get_case_name = mysql_query("SELECT * FROM `cm` WHERE `id` = '$_GET[case_id]' LIMIT 1");
while ($line = mysql_fetch_array($get_case_name, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_case_name,$i);
        $d[$field] = $col_value;
        $i++;

    }
$subject = "Case Assignment: $d[first_name] $d[last_name]";
$body = "Your professor has assigned you to the $d[first_name] $d[last_name] case.";
$rand = rand();
$notify = mysql_query("INSERT INTO `cm_messages` ( `id` ,`thread_id` ,`to` ,`from` ,`subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive` ,`temp_id` ) VALUES (NULL,'','$_GET[username]','system','$subject','$body','$_GET[case_id]',CURRENT_TIMESTAMP,'','','$rand')");

$upd = mysql_query("UPDATE `cm_messages` SET `thread_id` = cm_messages.id WHERE `temp_id` = '$rand' LIMIT 1 ");

$del_upd = mysql_query("UPDATE `cm_messages` SET `temp_id` = '' WHERE `temp_id` = '$rand' LIMIT 1 ");

$headers = 'From: ' . $CC_default_email . "\r\n" .
   'Reply-To: ' . $CC_default_email . "\r\n" .
   'X-Mailer: PHP/' . phpversion();

$get_email = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$_GET[username]' LIMIT 1");
$x = mysql_fetch_array($get_email);
mail($x[email],$subject,$body,$headers);

ECHO <<<NOTIFY
<SPAN style="color:red;font-weight:bold;">$d[first_name] $d[last_name] case assigned to $_GET[first_name] $_GET[last_name]</span>
NOTIFY;


}


?>
