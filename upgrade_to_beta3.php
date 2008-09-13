<?php

include 'db.php';


$q2 = mysql_query("SELECT * from cm_users");
while ($r = mysql_fetch_array($q2))
	{
		$id = $r[id];
$replace = md5($r[password]);
$update = mysql_query("UPDATE `cm_users` SET `password` = '$replace' WHERE `id` = '$id'");
$no = mysql_num_rows($q2);
	}
echo "$no passwords succesfully converted to md5";



?>
