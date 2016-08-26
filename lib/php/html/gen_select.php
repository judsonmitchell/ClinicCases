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
			{$casename = $case['first_name'] . " " . $case['last_name'];}

		//Note: trim for very long case names

		$options .= "<option value='" . $case['case_id_val'] . "'>" . $casename . " </option>";
	}

	return $options;

}

//Generate users on a case
function users_on_case_select($dbh,$case_id)
{
	$get_users = $dbh->prepare("SELECT * FROM cm_case_assignees WHERE case_id = '$case_id'
		AND status = 'active'");

	$get_users->execute();

	$users = $get_users->fetchAll(PDO::FETCH_ASSOC);

	$options = null;

	foreach ($users as $user) {

		$get_name = username_to_fullname($dbh,$user['username']);

		if ($user['username'] == $_SESSION['login'])
			{$options .= "<option selected=selected value = '" . $user['username']  ."'>You</option>";}
		else
			{$options .= "<option value = '" . $user['username']  ."'>" . $get_name   . "</option>";}

	}

	return $options;
}

//Generate a  select of all active users
function all_active_users($dbh)
{
	$q = $dbh->prepare("SELECT * FROM cm_users WHERE status = 'active' ORDER BY last_name ASC");

	$q->execute();

	$users = $q->fetchAll(PDO::FETCH_ASSOC);

	$options = null;

	foreach ($users as $user) {

		$options .= "<option value = '" . $user['username']  . "'>" . $user['first_name'] . " " . $user['last_name'] . "</option>";
	}

	return $options;
}

//Generate a list of all active users and all groups.  Used in messages.
function all_active_users_and_groups($dbh,$case_num,$you)
{
	$options = null;

	//If case, add ability to send to all on the case
	if ($case_num)
	{
		$q = $dbh->prepare("SELECT * FROM cm_case_assignees WHERE `case_id` = '$case_num' AND `status` = 'active'");

		$q->execute();

		$count = $q->rowCount();

		$options .= "<option value='_all_on_case_'>All Users on this Case ($count)</option>";


	}

	//Determine total number of active users
	$q = $dbh->prepare("SELECT * FROM `cm_users` WHERE `status` = 'active'");

	$q->execute();

	$count = $q->rowCount();

	$options .= "<option value='_all_users_'>All Users ($count)</option>";

	//First get all groups defined in cm_groups config
	$q = $dbh->prepare("SELECT group_name, group_title FROM cm_groups ORDER BY group_title ASC");

	$q->execute();

	$groups = $q->fetchAll(PDO::FETCH_ASSOC);

	foreach ($groups as $group) {
		$options .= "<option value='_grp_" . $group['group_name'] . "'>Group: All " . $group['group_title'] . "s</option>";
	}

	//Then get every supervisor
	$q = $dbh->prepare("SELECT cm_groups.group_name, cm_groups.supervises, cm_users.grp, cm_users.username
		FROM cm_groups, cm_users
		WHERE cm_groups.supervises =  '1'
		AND cm_users.grp = cm_groups.group_name
		AND cm_users.status =  'active'
		ORDER BY cm_users.username ASC");

	$q->execute();

	$groups = $q->fetchAll(PDO::FETCH_ASSOC);

	foreach ($groups as $group) {
		$options .= "<option value = '_spv_" . $group['username'] . "'>Group: " . username_to_fullname($dbh,$group['username']) . "'s group</option>";
	}

	//Then just get individual users
	$q = $dbh->prepare("SELECT * FROM cm_users WHERE status = 'active' ORDER BY last_name ASC");

	$q->execute();

	$users = $q->fetchAll(PDO::FETCH_ASSOC);

	foreach ($users as $user) {

		if ($you)
		{

			if ($user['username'] == $_SESSION['login'] )
				{
					$options .= "<option selected=selected value='" . $user['username'] . "'>You</option>";
				}
				else
				{
					$options .= "<option value = '" . $user['username']  . "'>" . $user['first_name'] . " " . $user['last_name'] . "</option>";
				}
		}
		else
		{

		$options .= "<option value = '" . $user['username']  . "'>" . $user['first_name'] . " " . $user['last_name'] . "</option>";
		}
	}

	return $options;

}

//Used in user_detail.php
function status_select($status)
{
	$choices = array('active' => 'Active','inactive' => 'Invactive');

	$options = null;

	foreach($choices as $key=>$value){

		if ($key == $status)
			{$selected = "selected=selected";}
		else
			{$selected = "";}

		$options .= "<option value= '$key' $selected>$value</option>";
	}

	return $options;

}

//Also used in user_detail.php
//$supervisor is a string
//$supervisor_name_data is an array
function supervisors_select($supervisors,$supervisor_name_data)
{
	$options = null;
	$sups = explode(',', $supervisors);
	foreach ($supervisor_name_data as $key => $value)
	{
		if (in_array($value, $sups))
		{
			$options .= "<option value='$value' selected=selected>$key</option>";
		}
		else
		{
			$options .= "<option value='$value'>$key</option>";
		}

	}

	return $options;
}

//also used in Users.php.  Get all groups
function group_select($dbh,$val)
{
	$q = $dbh->prepare("SELECT DISTINCT `group_name`, `group_title`  FROM `cm_groups`");

	$q->execute();

	$groups = $q->fetchAll(PDO::FETCH_ASSOC);

	$options = null;

	foreach ($groups as $group) {

		if ($group['group_name'] == $val)
			{
				$options .= '<option value = "'. $group['group_name'] . '" selected=selected>' . $group['group_title'] . '</option>';
			}
		else
			{
				$options .= '<option value = "'. $group['group_name'] . '">' . $group['group_title'] . '</option>';
			}
	}

	return $options;
}

//Generate a list of all active users and all groups.  Used in reports.
function reports_users_and_groups($dbh,$case_num)
{
	$options = null;

	if ($_SESSION['permissions']['view_users'] == '1') //essentially an Administrator
	{
		//First get all groups defined in cm_groups config
		$q = $dbh->prepare("SELECT group_name, group_title FROM cm_groups ORDER BY group_title ASC");

		$q->execute();

		$groups = $q->fetchAll(PDO::FETCH_ASSOC);

		$options .= "<optgroup label='User Groups'>";

		foreach ($groups as $group) {
			$options .= "<option value='_grp_" . $group['group_name'] . "'>All " . $group['group_title'] . "s</option>";
		}

		$options .= "</optgroup>";

		//Then get every supervisor
		$options .= "<optgroup label='Supervisor Groups'>";

		$q = $dbh->prepare("SELECT cm_groups.group_name, cm_groups.supervises, cm_users.grp, cm_users.username
			FROM cm_groups, cm_users
			WHERE cm_groups.supervises =  '1'
			AND cm_users.grp = cm_groups.group_name
			AND cm_users.status =  'active'
			ORDER BY cm_users.username ASC");

		$q->execute();

		$groups = $q->fetchAll(PDO::FETCH_ASSOC);

		foreach ($groups as $group) {
			$options .= "<option value = '_spv_" . $group['username'] . "'>" . username_to_fullname($dbh,$group['username']) . "'s group</option>";
		}

		$options .= "</optgroup>";

		//Then just get individual users
		$options .= "<optgroup label='Individual Users'>";

		$q = $dbh->prepare("SELECT * FROM cm_users WHERE status = 'active' ORDER BY last_name ASC");

		$q->execute();

		$users = $q->fetchAll(PDO::FETCH_ASSOC);

		foreach ($users as $user) {

			$options .= "<option value = '" . $user['username']  . "'>" . $user['first_name'] . " " . $user['last_name'] . "</option>";
		}

		$options .= "</optgroup>";

		//Then get all cases
		$options .= "<optgroup label='Open Cases'>";

		$q = $dbh->prepare("SELECT id,first_name,last_name,organization FROM cm WHERE date_close = '' ORDER BY date_open ASC");

		$q->execute();

		$cases = $q->fetchAll(PDO::FETCH_ASSOC);

		foreach ($cases as $c) {
			if ($c['first_name'] === '' && $c['last_name'] === '')
			{
				$options .= "<option value='_cse_" . $c['id'] . "'>" . $c['organization'] . "</option>";
			}
			else
			{
				$options .= "<option value='_cse_" . $c['id'] . "'>" .
				$c['first_name'] . " " . $c['last_name'] . "</option>";
			}
		}

		$options .= "</optgroup>";
	}

	elseif($_SESSION['permissions']['supervises'] == '1') //a supervisor
	{
		$user = $_SESSION['login'];

		//Get users this supervisor is allowed to see
		$options .= "<optgroup label='Users'>";

		//Add supervisors group and the supervisor himself
		$options .="<option value='" . $_SESSION['login'] . "'>You</option><option selected=selected value= '_spv_" . $_SESSION['login'] . "'>Your Group</option>";

		//Add each individual in this user's group
		$q = $dbh->prepare("SELECT * FROM cm_users
			WHERE (supervisors LIKE '$user,%'
			OR supervisors LIKE '%,$user,%')
			AND status ='active'");

		$q->execute();

		$users = $q->fetchAll(PDO::FETCH_ASSOC);

		foreach ($users as $u) {
			$options .= "<option value='" . $u['username'] . "'>" . $u['first_name'] .
			" " . $u['last_name'] . "</option>";
		}

		$options .= "</optgroup>";

		//Get all of this supervisor's open cases
		$options .= "<optgroup label='Open Cases'>";

		$q = $dbh->prepare("SELECT cm.first_name, cm.last_name,cm.organization,cm.id,
			cm_case_assignees.username,cm_case_assignees.case_id,
			cm_case_assignees.status FROM cm, cm_case_assignees
			WHERE cm_case_assignees.status = 'active'
			AND cm_case_assignees.case_id = cm.id
			AND cm_case_assignees.username = '$user'
			AND cm.date_close = ''
			ORDER BY cm.last_name DESC");

		$q->execute();

		$cases = $q->fetchAll(PDO::FETCH_ASSOC);

		foreach ($cases as $c) {
			if ($c['first_name'] === '' && $c['last_name'] === '')
			{
				$options .= "<option value='_cse_" . $c['id'] . "'>" . $c['organization'] . "</option>";
			}
			else
			{
				$options .= "<option value='_cse_" . $c['id'] . "'>" .
				$c['first_name'] . " " . $c['last_name'] . "</option>";
			}
		}

		$options .= "</optgroup>";

	}

	else
	{
		$options .= "<option selected=selected value='" . $_SESSION['login'] . "'>&nbsp;&nbsp&nbsp;You&nbsp;&nbsp;&nbsp;</option>";
	}

	return $options;

}

function get_journal_readers($dbh,$current_readers)
{

	$q = $dbh->prepare("SELECT group_name FROM cm_groups WHERE reads_journals = '1'");

	$q->execute();

	$groups = $q->fetchAll(PDO::FETCH_ASSOC);

	$count = count($groups);

	if ($count == 1)
	{
		$r =  $groups[0]['group_name'];
	}
	elseif ($count > 1)
	{
		$reader_groups = array();
		foreach ($groups as $g) {
			$reader_groups[] = $g['group_name'];
		}

		$r = implode("','",$reader_groups);
	}
	else
		{die("<option value=''>No users assigned to read journals</option>");}

	$users = $dbh->prepare("SELECT * FROM cm_users WHERE `grp` IN ('" . $r . "') AND `status` = 'active'ORDER BY `last_name` ASC");

	$users->execute();

	$readers = $users->fetchAll(PDO::FETCH_ASSOC);
	$options = null;

	$current_readers_array = explode(',', $current_readers);

	foreach ($readers as $reader) {

		if (in_array($reader['username'], $current_readers_array))
		{
			$options .= "<option value='" . $reader['username'] . "' selected=selected>"
		. $reader['first_name'] . " " . $reader['last_name'] . "</option>";
		}
		else
		{
		$options .= "<option value='" . $reader['username'] . "'>"
		. $reader['first_name'] . " " . $reader['last_name'] . "</option>";
		}
	}

	return $options;
}

function gen_contact_types ($dbh,$case_id)
{
	// get default contact types for this ClinicCases installation

	$get_default_types = $dbh->prepare("SELECT type from cm_contacts_types ORDER BY type ASC");

	$get_default_types->execute();

	$default_types = $get_default_types->fetchAll(PDO::FETCH_ASSOC);

	// add any user-defined contact types

	$get_db_types = $dbh->prepare("SELECT DISTINCT type from  `cm_contacts` WHERE assoc_case = :case_id");

	$data = array('case_id' => $case_id);

	$get_db_types->execute($data);

	$db_types = $get_db_types->fetchAll(PDO::FETCH_ASSOC);

	//$custom_types = array_diff_assoc($default_types, $db_types);

	$all_types = array_unique_deep(array_merge($db_types,$default_types));

	$type_options = null;

	foreach ($all_types as $type) {
		$type_options .= "<option value = '$type'>$type</option>";
	}

	return $type_options;
}

function gen_default_contact_types($dbh)
{
	// get only default contact types for this ClinicCases installation

	$get_default_types = $dbh->prepare("SELECT type from cm_contacts_types ORDER BY type ASC");

	$get_default_types->execute();

	$default_types = $get_default_types->fetchAll(PDO::FETCH_ASSOC);

	$type_options = '';

	foreach ($default_types as $type) {
		$type_options .= "<option value = '" . $type['type'] . "'>" . $type['type'] . "</option>";
	}

	return $type_options;
}

function gen_mobile_datepicker($add_time) {

    $months = array(
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December');

    $days = array(
        '01' => '1','02' => '2','03' => '3','04' => '4','05' => '5','06' => '6',
        '07' => '7','08' => '8','09' => '9','10' => '10','11' => '11','12' => '12',
        '13' => '13','14' => '14','15' => '15','16' => '16','17' => '17','18' => '18',
        '19' => '19','20' => '20','21' => '21','22' => '22','23' => '23','24' => '24',
        '25' => '25','26' => '26','27' => '27','28' => '28','29' => '29','30' => '30','31' => '31');

    $mdp = "<div class='control-group date-picker'><label>Date</label><div class='controls'><select class='small-select' name='c_month'>";
    
    foreach ($months as $key => $value) {
        $this_month = date('m');
        if ($key == $this_month){
            $mdp .= "<option selected=selected value='$key'>$value</option>";
        } else {
            $mdp .= "<option value='$key'>$value</option>";
        }
    }

    $mdp .="</select><select class='small-select' name='c_day'>";

    foreach ($days as $key => $value) {
        $today = date('d');
        if ($key == $today){
            $mdp .= "<option selected=selected value='$key'>$value</option>";
        } else {
            $mdp .= "<option value='$key'>$value</option>";
        }
    }

    $mdp .="</select><select  class='small-select' name='c_year'>";
    
    $mdp .="<option value='" . (date('Y') - 1) . "'>" .  (date('Y') - 1) . "</option>";
    $mdp .="<option selected=selected value='" . date('Y') . "'>" .  date('Y') . "</option>";
    $mdp .="<option value='" . (date('Y') + 1) . "'>" .  (date('Y') + 1) . "</option>";
    $mdp .="<option value='" . (date('Y') + 2) . "'>" .  (date('Y') + 2) . "</option>";

    $mdp .="</select></div>";

    if ($add_time){
        $hours = array(
            '01' => '1', '02' => '2','03' => '3','04' => '4','05' => '5','06' => '6',
            '07' => '7','08' => '8','09' => '9','10' => '10','11' => '11','12' => '12');

        $minutes = array(
            '00' => '00', '05' => '05', '10' => '10', '15' => '15', '20' => '20', '25' => '25',
            '30' => '30', '35' => '35', '40' => '40', '45' => '45', '50' => '50', '55' => '55' );

        $mdp .= "<div class='control-group'><label>Time</label><div class='controls'><select  class='small-select' name='c_hours'>";
        
        foreach ($hours as $key => $value) {
            $this_hour = date('g');
            if ($key == $this_hour){
                $mdp .= "<option selected=selected value='$key'>$value</option>";
            } else {
                $mdp .= "<option value='$key'>$value</option>";
            }
        }

        $mdp .="</select><select  class='small-select' name='c_minutes'>";

        foreach ($minutes as $key => $value) {
            if ($key == '00'){
                $mdp .= "<option selected=selected value='$key'>$value</option>";
            } else {
                $mdp .= "<option value='$key'>$value</option>";
            }
        }

        $mdp .="</select><select  class='small-select' name='c_ampm'>";

        $this_ampm = date('A');
        if ($this_ampm == 'AM'){
            $mdp .= "<option value='AM' selected=selected>AM</option><option value='PM'>PM</option>";
        } else {
            $mdp .= "<option value='AM'>AM</option><option value='PM' selected=selected>PM</option>";
        }

        $mdp .="</select></div></div>";
    }

    $mdp .="</div>";
    return $mdp;
}

