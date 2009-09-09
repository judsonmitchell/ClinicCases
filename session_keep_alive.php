<?php
session_start();
include 'db.php';
$sid = $_POST['sid'];
$unixtime = time();

if (!$oConn = @mysql_connect("$CC_dbhost", "$CC_dbusername", "$CC_dbpasswd"))
{
echo "<blink><a title=\"Server is down. Please click for message.\" alt=\"Server is down. Please click for message.\" href=\"#\" style=\"color:red;text-decoration:underline;\" onClick=\"alert('The server is down.  Any data you enter while the server is down will not be saved.  Please either wait for the connection to be re-established or try logging in again.');return false;\">Offline</a></blink>";

}
else
{
$connection = mysql_pconnect("$CC_dbhost","$CC_dbusername","$CC_dbpasswd");
$db = mysql_select_db("$CC_database_name", $connection);
$ping = mysql_query("UPDATE `cm_logs` SET `last_ping` = '$unixtime' WHERE `session_id` = '$sid' LIMIT 1");
echo "<span style=\"color:blue;\">Online</span>";
}



?>
