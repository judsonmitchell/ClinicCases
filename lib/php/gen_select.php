<?php

include '../db.php';
/* this is for generating selects on an edit page */

function genSelect($target,$chosen_array,$select_name){

echo "<select name=\"$select_name\" id=\"$select_name\">";

/* arrays of the possible choices in all selects */
$gender = array('M','F','U');
$race =  array('AA','H','W','O','U');
$pl_or_def = array('Plaintiff','Defendant','Other');
$per = array('Year','Month','Week');



	switch($chosen_array){
	case "gender": 
	$array = $gender;break;
	case "race":
	$array = $race;break;
	case "pl_or_def":
	$array = $pl_or_def;break;
	case "per":
	$array = $per;break;
	}

		foreach ($array as $v)
			{
			if ($v == $target)
			{echo "<option value = \"$v\" selected=\"selected\">$v</option>";}
			else {echo "<option value=\"$v\">$v</option>";}
			}
			
echo "</select>";
}


function genStateSelect($target,$select_name)
{
	$state = array('AL'=>"Alabama",  
			'AK'=>"Alaska",  
			'AZ'=>"Arizona",  
			'AR'=>"Arkansas",  
			'CA'=>"California",  
			'CO'=>"Colorado",  
			'CT'=>"Connecticut",  
			'DE'=>"Delaware",  
			'DC'=>"District Of Columbia",  
			'FL'=>"Florida",  
			'GA'=>"Georgia",  
			'HI'=>"Hawaii",  
			'ID'=>"Idaho",  
			'IL'=>"Illinois",  
			'IN'=>"Indiana",  
			'IA'=>"Iowa",  
			'KS'=>"Kansas",  
			'KY'=>"Kentucky",  
			'LA'=>"Louisiana",  
			'ME'=>"Maine",  
			'MD'=>"Maryland",  
			'MA'=>"Massachusetts",  
			'MI'=>"Michigan",  
			'MN'=>"Minnesota",  
			'MS'=>"Mississippi",  
			'MO'=>"Missouri",  
			'MT'=>"Montana",
			'NE'=>"Nebraska",
			'NV'=>"Nevada",
			'NH'=>"New Hampshire",
			'NJ'=>"New Jersey",
			'NM'=>"New Mexico",
			'NY'=>"New York",
			'NC'=>"North Carolina",
			'ND'=>"North Dakota",
			'OH'=>"Ohio",  
			'OK'=>"Oklahoma",  
			'OR'=>"Oregon",  
			'PA'=>"Pennsylvania",  
			'RI'=>"Rhode Island",  
			'SC'=>"South Carolina",  
			'SD'=>"South Dakota",
			'TN'=>"Tennessee",  
			'TX'=>"Texas",  
			'UT'=>"Utah",  
			'VT'=>"Vermont",  
			'VA'=>"Virginia",  
			'WA'=>"Washington",  
			'WV'=>"West Virginia",  
			'WI'=>"Wisconsin",  
			'WY'=>"Wyoming");
	
	echo "<select name=\"$select_name\" id=\"$select_name\">";

	foreach ($state as $key => $v)
			{
			if ($key == $target)
			{echo "<option value = \"$key\" selected=\"selected\">$v</option>";}
			else {echo "<option value=\"$key\">$v</option>";}
			}
			
echo "</select>";
	
	
	}
	
function gen_select_multiple()

	{
		$get_prof = mysql_query("SELECT * FROM `cm_users` WHERE `class` = 'prof' AND `status` = 'active' ORDER BY `last_name` asc");
		
		while ($result = mysql_fetch_array($get_prof))
			{
				$prof = $result['last_name'];
				$prof_user = $result['username'];
				echo "<option value='$prof_user'>$prof</option>";
			}	
	}

?>
