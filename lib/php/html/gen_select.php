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

	$selects = "<label for 'cn_h'>Hours:</label><select name='csenote_hours' id='cn_h'>";

	for($i = 0; $i <= 8; $i++)
	{$selects .= "<option value='$i'>" . $i . "</option>";}

	$selects .= "</select>";

	$selects .= "<label for 'cn_m'>Minutes: </label><select name='csenote_minutes' id='cn_m'>";

	foreach ($minutes as $val)

	{
		$selects .= "<option value='$val'>$val</option>";
	}

	$selects .= "</select>";

	return $selects;
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

//Generates all open and active cases the user is on for use in a html select
function generate_active_cases_select($dbh,$user)
{

	if ($_SESSION['permissions']['view_all_cases'] == '1')
		{$sql = "SELECT *,cm.id as case_id_val FROM cm WHERE date_close = '' ORDER BY last_name ASC";}
	else
		{$sql = "SELECT *,cm.id as case_id_val
		FROM cm_case_assignees,cm
		WHERE  cm_case_assignees.case_id = cm.id
		AND cm_case_assignees.username = '$user'
		AND cm_case_assignees.status = 'active'
		AND cm.date_close = ''
		ORDER BY cm.last_name ASC";}

	$q = $dbh->prepare($sql);

	$q->execute();

	$cases = $q->fetchAll(PDO::FETCH_ASSOC);

	$options = null;

	foreach ($cases as $case) {

		if (!$case['first_name'] AND !$case['last_name'])
			{$casename = $case['organization'];}
		else
			{$casename = $case['last_name'] . ", " . $case['first_name'];}

		//Note: trim for very long case names

		$options .= "<option value='" . $case['case_id_val'] . "'>" . $casename . " </option>";
	}

	return $options;

}

//Generate users on a case
function users_on_case_select($dbh,$case_id)
{
	$get_users = $dbh->prepare("SELECT * FROM cm_case_assignees WHERE case_id = '$case_id'");

	$get_users->execute();

	$users = $get_users->fetchAll(PDO::FETCH_ASSOC);

	$options = null;

	foreach ($users as $user) {

		$get_name = username_to_fullname($dbh,$user['username']);

		$options .= "<option value = '" . $user['username']  ."'>" . $get_name   . "</option>";

	}

	return $options;
}