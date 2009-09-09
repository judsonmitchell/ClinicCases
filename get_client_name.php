<?php
function getClient($case_id)
{
$get_client = mysql_query("SELECT * FROM `cm` WHERE `id` = '$case_id' LIMIT 1");
$zclient = mysql_fetch_array($get_client);
echo "$zclient[first_name] $zclient[last_name]";

}


function getClientAsVar($case_id)
{
$get_client = mysql_query("SELECT * FROM `cm` WHERE `id` = '$case_id' LIMIT 1");
$zclient = mysql_fetch_array($get_client);
$fname = $zclient[first_name]; 
$lname = $zclient[last_name]; 
$info = array($fname,$lname);
return $info;





}







?>
