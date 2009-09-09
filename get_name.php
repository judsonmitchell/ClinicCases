<?php
include 'db.php';
function getName($username)
{
$get_full_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$username' LIMIT 1");
$r = mysql_fetch_array($get_full_name);
$fname = $r['first_name'];
$lname = $r['last_name'];
echo "$fname $lname";




}

function getNameAsVar($username)
{
$get_full_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$username' LIMIT 1");
$r = mysql_fetch_array($get_full_name);
$fname = $r['first_name'];
$lname = $r['last_name'];
$info = array($fname,$lname);
return $info;





}

function getNameAsVar2($username)
{
$get_full_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$username' LIMIT 1");
$r = mysql_fetch_array($get_full_name);
$fname2 = $r['first_name'];
$lname2 = $r['last_name'];
$info = array($fname2,$lname2);
return $info;





}

function getLastName($username)
{
$get_full_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$username' LIMIT 1");
$r = mysql_fetch_array($get_full_name);
$info = $r['lname'];
return $info;
	
	
}
?>
