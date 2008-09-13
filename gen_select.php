<?php
include 'db.php';
/* this is for generating selects on an edit page */
function genSelect($target,$chosen_array,$select_name){

echo "<select name=\"$select_name\" id=\"$select_name\"\">";
/* arrays of the possible choices in all selects */
$gender = array('M','F');
$race =  array('AA','H','W','O');
$pl_or_def = array('Plaintiff','Defendant');

switch($chosen_array){
case "gender": 
$array = $gender;break;
case "race":
$array = $race;break;
case "pl_or_def":
$array = $pl_or_def;break;
}
foreach ($array as $v)
{
if ($v == $target)
{echo "<option value = \"$v\" selected=\"selected\">$v</option>";}
else {echo "<option value=\"$v\">$v</option>";}
}
echo "</select>";
}




?>
