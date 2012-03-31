<?php
//Generate html for selects using db values

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


function generate_time_selector()

{
	if (CC_TIME_UNIT == '5')
	{$minutes = array('0','5','10','15','20','25','30','35','40','45','50','55');}
	else
	{$minutes = array('0','6','12','18','24','30','36','42','48','54');}

	$selects = "<label>Hours:</label><select name='csenote_hours'>";

	for($i = 0; $i <= 8; $i++)
	{$selects .= "<option value='$i'>" . $i . "</option>";}

	$selects .= "</select>";

	$selects .= "<label>Minutes: </label><select name='csenote_minutes'>";

	foreach ($minutes as $val)

	{
		$selects .= "<option value='$val'>$val</option>";
	}

	$selects .= "</select>";

	return $selects;
}


//Generates all open and active cases the user is on for use in a html select
function generate_active_cases_select($dbh,$user)
{
	$q = $dbh->prepare("SELECT *
		FROM cm_case_assignees,cm
		WHERE  cm_case_assignees.case_id = cm.id
		AND cm_case_assignees.username = '$user'
		AND cm_case_assignees.status = 'active'
		AND cm.date_close = ''
		ORDER BY cm.last_name ASC");

	$q->execute();

	$cases = $q->fetchAll(PDO::FETCH_ASSOC);

	$options = null;

	foreach ($cases as $case) {

		if (!$case['first_name'] AND !$case['last_name'])
			{$casename = $case['organization'];}
		else
			{$casename = $case['last_name'] . ", " . $case['first_name'];}

		//Note: trim for very long case names

		$options .= "<option value='" . $case['case_id'] . "'>" . $casename . " </option>";
	}

	return $options;

}