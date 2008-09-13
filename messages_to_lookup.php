<?php
include 'db.php';

if (isset($_POST['cc1_full']))
{$look = $_POST['cc1_full'];}
else
{$look = $_POST['to_full'];}
$query = mysql_query("SELECT * FROM `cm_users` WHERE `first_name` LIKE CONVERT( _utf8 '%$look%' USING latin1 ) OR `last_name` LIKE '%$look%'");
echo "<ul>";
while($r=mysql_fetch_array($query))
{	
$fname = $r["first_name"];
$lname = $r["last_name"];
$username = $r["username"];

echo "<li id=\"$id\"><b>$fname $lname</b><span class=\"informal\">
<span class=\"username\" style=\"font-size:8pt;font-style:italic;color:black;\">$username</span>
</span></li>";
}

echo "</ul>";










?>
