<?php
require('../db.php');
require('../lib/php/utilities/names.php');
require('../lib/php/utilities/convert_times.php');
require('../lib/php/users/update_case_with_users.php');

ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);
ini_set('memory_limit', '-1');

echo "Beginning upgrade process</br>";

//This fixes incorrect date entries from cc6.  Date field on case notes had 00:00:00 in the
// time part which led to incorrect sorts when displaying case notes.  This was fixed in r663
// of add_time.php, but was not corrected in casenote_edit.php. There are therefore, a lot of
//incorrect entries in some dbs.
echo "Correcting date entries on case notes...<br>";

$query = $dbh->prepare("SELECT id, date, datestamp from cm_case_notes ORDER BY datestamp desc");
$query->execute();
$notes = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($notes as $note) {
	$id = $note['id'];
	$date_parts = explode(' ',$note['date']);
	$datestamp_parts = explode(' ',$note['datestamp']);

	if ($date_parts[1] = '00:00:00')
	{
		$new_date = $date_parts[0] . " " . $datestamp_parts[1];
		$update = $dbh->prepare("UPDATE cm_case_notes set date = :new_date WHERE id = :id LIMIT 1 ");
		$data = array(':new_date'=>$new_date,':id'=>$id);
		$update->execute($data);
	}

}

$error = $query->errorInfo();

if ($error[1])
	{echo $error[1];}
else
	{echo "Case note date entries corrected.<br>";}

echo "Updating db fields<br />";

$query = $dbh->prepare("ALTER TABLE  `cm_users` CHANGE  `class`  `grp` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';
	ALTER TABLE  `cm_users` CHANGE  `assigned_prof`  `supervisors` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';
	ALTER TABLE  `cm_logs` CHANGE  `last_ping`  `type` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';
	ALTER TABLE  `cm_logs` ADD  `last_msg_check` DATETIME NOT NULL;
	ALTER TABLE  `cm` ADD FULLTEXT (`professor`);
	ALTER TABLE  `cm` ADD  `organization` VARCHAR( 250 ) NOT NULL AFTER  `last_name`;
	ALTER TABLE  `cm` CHANGE  `clinic_id`  `clinic_id` VARCHAR( 255 ) NOT NULL;
	ALTER TABLE  `cm` ADD  `clinic_type` VARCHAR( 200 ) NOT NULL AFTER  `case_type`;
	ALTER TABLE  `cm` CHANGE  `income`  `income` INT( 50 ) NULL
");

$query->execute();

echo "Done updating db fields<br />";

echo "Adding groups table to db</br>";

$super_tabs = serialize(array("Home","Cases","Group","Users","Journals","Board","Utilities","Messages"));
$admin_tabs = serialize(array("Home","Cases","Users","Board","Utilities","Messages"));
$student_tabs = serialize(array("Home","Cases","Journals","Board","Utilities","Messages"));
$prof_tabs = serialize(array("Home","Cases","Group","Journals","Board","Utilities","Messages"));

$query = $dbh->prepare("CREATE TABLE IF NOT EXISTS `cm_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `group_title` varchar(100) NOT NULL,
  `group_description` text NOT NULL,
  `allowed_tabs` varchar(500) NOT NULL COMMENT 'An object which controls which tabs the user is allowed to see.',
  `add_cases` int(2) NOT NULL,
  `delete_cases` int(2) NOT NULL,
  `edit_cases` int(2) NOT NULL,
  `close_cases` int(2) NOT NULL,
  `view_all_cases` int(2) NOT NULL COMMENT 'User can view all cases or only cases to which they are assigned',
  `assign_cases` int(2) NOT NULL COMMENT 'Can the user assign cases to users?',
  `view_users` int(2) NOT NULL,
  `add_users` int(2) NOT NULL,
  `delete_users` int(2) NOT NULL,
  `edit_users` int(2) NOT NULL,
  `activate_users` int(2) NOT NULL,
  `add_case_notes` int(2) NOT NULL,
  `edit_case_notes` int(2) NOT NULL,
  `delete_case_notes` int(2) NOT NULL,
  `documents_upload` int(2) NOT NULL,
  `documents_modify` int(2) NOT NULL,
  `add_events` int(2) NOT NULL,
  `edit_events` int(2) NOT NULL,
  `delete_events` int(2) NOT NULL,
  `add_contacts` int(2) NOT NULL,
  `edit_contacts` int(2) NOT NULL,
  `delete_contacts` int(2) NOT NULL,
  `post_in_board` int(2) NOT NULL,
  `view_board` int(2) NOT NULL,
  `edit_posts` int(2) NOT NULL,
  `reads_journals` int(2) NOT NULL,
  `writes_journals` int(2) NOT NULL,
  `change_permissions` int(2) NOT NULL,
  `can_configure` int(2) NOT NULL,
  `supervises` int(2) NOT NULL COMMENT 'The user has other users under him who he supervises, e.g, students, associates',
  `is_supervised` int(2) NOT NULL COMMENT 'This user works on cases,but is supervised by another user',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Allows admin to create user groups and set definitions' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cm_groups`
--

INSERT INTO `cm_groups` (`id`, `group_name`, `group_title`, `group_description`, `allowed_tabs`, `add_cases`, `delete_cases`, `edit_cases`, `close_cases`, `view_all_cases`, `assign_cases`, `view_users`, `add_users`, `delete_users`, `edit_users`, `activate_users`, `add_case_notes`, `edit_case_notes`, `delete_case_notes`, `documents_upload`, `documents_modify`, `add_events`, `edit_events`, `delete_events`, `add_contacts`, `edit_contacts`, `delete_contacts`, `post_in_board`, `view_board`, `edit_posts`, `reads_journals`, `writes_journals`, `change_permissions`, `can_configure`, `supervises`, `is_supervised`) VALUES
(1, 'super', 'Super User', 'The super user can access all ClinicCases functions and add, edit, and delete all data.  Most importantly, only the super user can change permissions for all users.\r\nSuper User access should be restricted to a limited number of users.', '$super_tabs', 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 0, 0),
(2, 'admin', 'Adminstrator', 'The administrator can access all ClinicCases functions and view,edit, and delete all data.  By default, the administrator is the only user who can add new files or authorize new users.\r\n\r\nThe administrator cannot change group permissions.', '$admin_tabs', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0),
(3, 'student', 'Student', 'Students can only access the cases to which they have been assigned by a professor.', '$student_tabs', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 1),
(4, 'prof', 'Professor', 'Professors supervise students.  By default, they can assign students to cases and view, edit, and delete all data in cases to which they are assigned.', '$prof_tabs', 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 1, 0);

");

$query->execute();

echo "Finished<br />";

echo "Updating cm_students";

//script to move professor field to cm_cases_students

$query = $dbh->prepare("SELECT * FROM cm");

$query->execute();

$fields = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($fields as $field) {
		$assig = explode(',',$field['professor']);

		foreach ($assig as $p)
		{
			if (!empty($p))
			{
				$date_add = $field['date_open'] . " 00:00:00";
				$insert = $dbh->prepare("INSERT INTO cm_cases_students (id,username,case_id,status,date_assigned) VALUES (NULL,'$p','$field[id]','active','$date_add')");
				$insert->execute();


			}

		}

}

echo "Done updating cm_students<br />";

echo "Updating more fields<br />";

$query = $dbh->prepare("ALTER TABLE `cm_cases_students` DROP `first_name`,DROP `last_name`;RENAME TABLE cm_cases_students TO cm_case_assignees;
");

$query->execute();

echo "Done<br />";

echo "Adding columns table<br />";

$gender_choices =  serialize(array("M" => "Male","F" => "Female"));
$race_choices = serialize(array("AA" => "African-American", "W" => "White", "H" => "Hispanic", "A" => "Asian", "O" => "Other"));
$phone_choices = serialize(array("home" => "Home","work"=>"Work","mobile"=>"Mobile","fax"=>"Fax","other"=>"Other"));
$email_choices = serialize(array("Home" => "Home", "Work" => "Work", "Other" => "Other"));
$state_choices = serialize(array('AL'=>"Alabama",
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
			'WY'=>"Wyoming"));

$per_choices = serialize(array("day" => "Day", "week" => "Week", "month" => "Month","year" => "Year"));
$pd_choices = serialize(array("plaintiff" => "Plainiff", "defendant" => "Defendant", "other" => "Other"));

$query = $dbh->prepare("CREATE TABLE IF NOT EXISTS `cm_columns` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `db_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `include_in_case_table` varchar(10) NOT NULL COMMENT 'Should this column be included into the data sent to the main case table?',
  `input_type` varchar(10) NOT NULL,
  `select_options` text NOT NULL,
  `display_by_default` varchar(10) NOT NULL COMMENT 'Should this column be displayed to the case table user by default?',
  `required` int(11) NOT NULL DEFAULT '0' COMMENT 'ClinicCases cannot function without this field',
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Defines the columns to be used in ClinicCases cases table' AUTO_INCREMENT=41 ;

--
-- Dumping data for table `cm_columns`
--

INSERT INTO `cm_columns` (`id`, `db_name`, `display_name`, `include_in_case_table`, `input_type`, `select_options`, `display_by_default`, `required`, `display_order`) VALUES
(2, 'id', 'Id', 'true', 'text', '', 'false', 1, 2),
(3, 'clinic_id', 'Case Number', 'true', 'text', '', 'false', 1, 3),
(4, 'first_name', 'First Name', 'true', 'text', '', 'true', 1, 4),
(5, 'middle_name', 'Middle Name', 'true', 'text', '', 'false', 1, 5),
(6, 'last_name', 'Last Name', 'true', 'text', '', 'true', 1, 6),
(7, 'organization', 'Organization', 'true', 'text', '', 'false', 1, 7),
(8, 'date_open', 'Date Open', 'true', 'date', '', 'true', 1, 8),
(9, 'date_close', 'Date Close', 'true', 'date', '', 'true', 1, 41),
(10, 'case_type', 'Case Type', 'true', 'select', '', 'true', 1, 10),
(41, 'adverse_parties', 'Adverse Party', 'true', 'multi-text', '', 'false', 1, 33),
(11, 'clinic_type', 'Clinic Type', 'true', 'select', '', 'false', 0, 11),
(12, 'address1', 'Address 1', 'false', 'text', '', 'false', 0, 12),
(13, 'address2', 'Address 2', 'false', 'text', '', 'false', 0, 13),
(14, 'city', 'City', 'false', 'text', '', 'false', 0, 14),
(15, 'state', 'State', 'false', 'select', '$state_choices', 'false', 0, 15),
(16, 'zip', 'Zip', 'false', 'text', '', 'false', 0, 16),
(17, 'phone', 'Phone', 'true', 'dual', '$phone_choices', 'false', 1, 17),
(19, 'email', 'Email', 'true', 'dual', '$email_choices', 'false', 1, 19),
(20, 'ssn', 'SSN', 'true', 'text', '', 'false', 0, 20),
(21, 'dob', 'DOB', 'true', 'text', '', 'false', 0, 21),
(22, 'age', 'Age', 'true', 'text', '', 'false', 0, 22),
(23, 'gender', 'Gender', 'true', 'select', '$gender_choices', 'false', 0, 23),
(24, 'race', 'Race', 'true', 'select', '$race_choices', 'false', 0, 24),
(25, 'income', 'Income', 'false', 'text', '', 'false', 0, 25),
(26, 'per', 'Per', 'false', 'select', '$per_choices', 'false', 0, 26),
(27, 'judge', 'Judge', 'false', 'text', '', 'false', 0, 27),
(28, 'pl_or_def', 'Plaintiff/Defendant', 'false', 'select', '$pd_choices', 'false', 0, 28),
(29, 'court', 'Court', 'true', 'select', '', 'false', 0, 29),
(30, 'section', 'Section', 'false', 'text', '', 'false', 0, 30),
(31, 'ct_case_no', 'Court Case Number', 'false', 'text', '', 'false', 0, 31),
(32, 'case_name', 'Case Name', 'false', 'text', '', 'false', 0, 32),
(33, 'notes', 'Notes', 'false', 'textarea', '', 'false', 0, 33),
(36, 'dispo', 'Disposition', 'true', 'select', '', 'true', 0, 42),
(38, 'close_notes', 'Closing Notes', 'false', 'textarea', '', 'false', 0, 44),
(39, 'referral', 'Referred By', 'true', 'text', '', 'false', 0, 39),
(40, 'opened_by', 'Opened By', 'true', 'text', '', 'true', 1, 40);

");


$query->execute();

//
//Documents db has to be updated
//

echo "Updating documents db...<br />";

$query = $dbh->prepare("ALTER TABLE  `cm_documents` ADD  `containing_folder` VARCHAR( 100 ) NOT NULL AFTER  `folder`;
	ALTER TABLE  `cm_documents` ADD  `extension` VARCHAR( 10 ) NOT NULL AFTER  `url`;
	ALTER TABLE  `cm_documents` CHANGE  `url`  `local_file_name` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';
	ALTER TABLE  `cm_documents` ADD  `text` TEXT NOT NULL AFTER  `containing_folder`;
	ALTER TABLE  `cm_documents` ADD  `write_permission` VARCHAR( 500 ) NOT NULL AFTER  `text`
");

$query->execute();


//get the document extension and put it in extension column

$query = $dbh->prepare("SELECT * from cm_documents");
$query->execute();
$documents = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($documents as $document) {

if (stristr($document['local_file_name'], 'http://') || stristr($document['local_file_name'], 'https://') || stristr($document['local_file_name'], 'ftp://'))
		{$ext = 'url';}
		else
		{$ext = strtolower(substr(strrchr($document['local_file_name'], "."), 1));}

		$id = $document['id'];

		if ($ext != '')
		{
		$update = $dbh->prepare("UPDATE cm_documents SET extension = :ext WHERE id = :id");
		$data = array(':ext'=>$ext,':id'=>$id);
		$update->execute($data);
		$error = $update->errorInfo();

		}
}


if ($error[1])
	{echo $error[1];}
else
	{echo "Documents have been updated.<br>";}


//Update contacts db
echo "Updating contacts db...<br />";

	//Update fields
$query = $dbh->prepare("ALTER TABLE  `cm_contacts` ADD  `organization` VARCHAR( 200 ) NOT NULL AFTER  `last_name`;ALTER TABLE  `cm_contacts` ADD  `url` TEXT NOT NULL AFTER  `email`;ALTER TABLE  `cm_contacts` CHANGE  `address`  `address` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';ALTER TABLE  `cm_contacts` CHANGE  `phone1`  `phone1` TEXT NOT NULL DEFAULT  '', CHANGE  `email`  `email` TEXT NOT NULL DEFAULT  '';");

$query->execute();

	//Change phone and email fields
$query = $dbh->prepare('SELECT id,phone1, phone2, fax FROM cm_contacts ORDER BY id asc');

$query->execute();

$phones = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($phones as $phone) {

	//Make a guess at what kind of phone this is

	if (stristr($phone['phone1'], 'cell')  || stristr($phone['phone1'], 'mobile')|| stristr($phone['phone1'], 'c'))
		{
			$phone1_type = 'mobile';
		}
		elseif (stristr($phone['phone1'], 'home')  || stristr($phone['phone1'], 'h'))
		{
			$phone1_type = 'home';
		}
		elseif (stristr($phone['phone1'], 'work')  || stristr($phone['phone1'], 'office') || stristr($phone['phone1'], 'w') || stristr($phone['phone1'], 'o'))
		{
			$phone1_type = 'office';
		}
		else
			{$phone1_type = 'other';}

	if (stristr($phone['phone2'], 'cell')  || stristr($phone['phone2'], 'mobile') || stristr($phone['phone2'], 'c'))
		{
			$phone2_type = 'mobile';
		}
		elseif (stristr($phone['phone2'], 'home')|| stristr($phone['phone2'], 'h'))
		{
			$phone2_type = 'home';
		}
		elseif (stristr($phone['phone2'], 'work')  || stristr($phone['phone2'], 'office')|| stristr($phone['phone2'], 'w') || stristr($phone['phone2'], 'o'))
		{
			$phone2_type = 'office';
		}
		else
			{$phone2_type = 'other ';}


	$new_phone = array($phone1_type => $phone['phone1'], $phone2_type => $phone['phone2'], 'fax' => $phone['fax']);

	$new_phone_filtered = array_filter($new_phone);//take out empty phone fields

	$json = json_encode($new_phone_filtered);

	if ($json != '[]')//empty set
	{

	$update = $dbh->prepare("UPDATE cm_contacts SET phone1 = :phone, phone2 = '', fax = '' WHERE id = :id");

	$data = array('phone'=>$json,'id'=>$phone['id']);

	$update->execute($data);

	}

}

//update email field
$query = $dbh->prepare('SELECT id,email FROM cm_contacts');

$query->execute();

$emails = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($emails as $email) {

	if ($email['email'])
	{

		$new_email = array('other' => $email['email']);

		$json = json_encode($new_email);

		//echo $json . "<br />";

		$update = $dbh->prepare("UPDATE cm_contacts SET email = :email WHERE id = :id");

		$data = array('email' => $json, 'id' => $email['id']);

		$update->execute($data);
	}

}

$query = $dbh->prepare("ALTER TABLE `cm_contacts` DROP `phone2`, DROP `fax`;");

$query->execute();

$query = $dbh->prepare("ALTER TABLE  `cm_contacts` CHANGE  `phone1`  `phone` TEXT NOT NULL DEFAULT  '';DROP TABLE `cm_contacts_types`");

$query->execute();

echo "Finished updating contacts.<br />";

echo "Updating events db<br />";

$query = $dbh->prepare("ALTER TABLE `cm_events` DROP `temp_id`;ALTER TABLE  `cm_events` CHANGE  `date_due`  `start` DATETIME NOT NULL;ALTER TABLE  `cm_events` ADD  `notes` TEXT NOT NULL AFTER  `status`
");

$query->execute();

$query = $dbh->prepare("ALTER TABLE  `cm_events` ADD  `end` DATETIME NULL AFTER  `start` ,
ADD  `all_day` BOOLEAN NOT NULL AFTER  `end`;ALTER TABLE  `cm_events` ADD  `location` TEXT NOT NULL AFTER  `notes`;
ALTER TABLE  `cm_events` ADD  `start_text` VARCHAR( 200 ) NOT NULL AFTER  `start` ,
ADD  `end_text` VARCHAR( 200 ) NOT NULL AFTER  `start_text`;ALTER TABLE  `cm_events` ADD  `time_added` DATETIME NOT NULL ;

ALTER TABLE  `cm_events_responsibles` ADD  `time_added` DATETIME NOT NULL;

ALTER TABLE  `cm_events_responsibles` DROP  `first_name` ,
DROP  `last_name` ;

");

$query->execute();

echo "Done updating events db<br />";

echo "Converting old events<br />";

$query = $dbh->prepare("UPDATE cm_events SET all_day = '1'");

$query->execute();

//Add text start date, makes old dates searchable by keyword

$query = $dbh->prepare("SELECT id, start FROM cm_events");

$query->execute();

$events = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($events as $event) {

	$date_text = extract_date_time($event['start']);

	$update = $dbh->prepare("UPDATE cm_events SET start_text = :date_text WHERE id = :id");

	$data = array('id' =>  $event['id'], 'date_text' => $date_text );

	$update->execute($data);
}

echo "Done converting old events<br />";

//Make changes to messages
echo "Updating messages database. (This may take a while).<br />";

//make certain fields in messages text searchable
$q = $dbh->prepare("ALTER TABLE  `cm_messages` ADD  `to_text` TEXT NOT NULL ,
ADD  `cc_text` TEXT NOT NULL ,
ADD  `assoc_case_text` TEXT NOT NULL ,
ADD  `time_sent_text` TEXT NOT NULL");

$q->execute();

//add text data
$q = $dbh->prepare("SELECT * FROM cm_messages");

$q->execute();

$msgs = $q->fetchAll(PDO::FETCH_ASSOC);

foreach ($msgs as $msg) {
	$id = $msg['id'];

	$date_text = extract_date_time($msg['time_sent']);

	$tos = explode(',', $msg['to']);
	$to_string = null;
	foreach ($tos as $to) {
		$to_string .= username_to_fullname($dbh,$to) . " ";
	}

	if (!empty($msg['ccs']))
	{
		$ccs = explode(',', $msg['ccs']);
		$cc_string = null;
		foreach ($ccs as $cc) {
			$cc_string .= username_to_fullname($dbh,$cc) . " ";
		}
	}
	else
		{$cc_string = null;}

	if (!empty($msg['assoc_case']))
	{
		$assoc_case = case_id_to_casename($dbh,$msg['assoc_case']);
	}
	else
		{$assoc_case = null;}

	$update = $dbh->prepare("UPDATE cm_messages SET `time_sent_text` = '$date_text', `to_text` = '$to_string',`cc_text` = '$cc_string',`assoc_case_text` = '$assoc_case' WHERE `id` = '$id'");
	$update->execute();
}

echo "Messages db upgraded <br />";

echo "Upgrading password fields <br />";
$q = $dbh->prepare("ALTER TABLE `cm_users`  ADD `force_new_password` INT(2) NOT NULL DEFAULT '0';ALTER TABLE  `cm_users` CHANGE  `password`  `password` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';
");

$q->execute();

$q = $dbh->prepare("UPDATE cm_users SET `force_new_password` = '1'");

$q->execute();

echo "Password upgrade successful.  Users will be asked to provide new password. <br />";

echo "Updating case type database...</br >";

$q = $dbh->prepare("ALTER TABLE  `cm_case_types` ADD  `case_type_code` VARCHAR( 200 ) NOT NULL;UPDATE cm_case_types SET case_type_code = case_type;");

$q->execute();

echo "Done updating case type databse.<br />";

echo "Adding clinic type table and case type code field.<br />";
$q = $dbh->prepare("CREATE TABLE IF NOT EXISTS `cm_clinic_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_name` text NOT NULL,
  `clinic_code` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

$q->execute();

echo "Done adding clinic type table and case type code field. <br />";

echo "Cleaning up db tables.<br />";


//now do types

$q = $dbh->prepare("SELECT * FROM cm_case_types");

$q->execute();

$types = $q->fetchAll(PDO::FETCH_ASSOC);

foreach ($types as $type) {

	$array[$type['type']] = $type['type'];

}

$type_string = serialize($array);

$update = $dbh->prepare("UPDATE cm_columns SET select_options = '$type_string' WHERE db_name = 'case_type'");

$update->execute();

$del = $dbh->prepare("DROP TABLE `cm_bugs`;DROP TABLE `cm_drafts`;ALTER TABLE  `cm` DROP  `type1` ,
DROP  `type2` ;ALTER TABLE  `cm` DROP  `close_code` ;");

$del->execute();

$q = $dbh->prepare("ALTER TABLE `cm` CHANGE `m_initial` `middle_name` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''");

$q->execute();

//Add a couple of fields to cm table
$q = $dbh->prepare("ALTER TABLE `cm`  ADD `time_opened` DATETIME NOT NULL,  ADD `closed_by` VARCHAR(50) NOT NULL,  ADD `time_closed` DATETIME NOT NULL");

$q->execute();

//
//Change phone and email fields
//

//First change type/length of phone1 column

$q = $dbh->prepare("ALTER TABLE  `cm` CHANGE  `phone1`  `phone1` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  ''
");

$q->execute();

//Get all current phone values
$query = $dbh->prepare('SELECT phone1, phone2,id FROM cm ORDER BY id asc');

$query->execute();

$phones = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($phones as $phone) {

	$phone1_s = null;
	$phone1_type = null;
	$phone2_s = null;
	$phone2_type = null;
	//Make a guess at what kind of phone this is
	if (!empty($phone['phone1']))
	{
	if (stristr($phone['phone1'], 'cell')  || stristr($phone['phone1'], 'mobile')|| stristr($phone['phone1'], 'c'))
		{
			$phone1_type = 'mobile';
		}
		elseif (stristr($phone['phone1'], 'home')  || stristr($phone['phone1'], 'h'))
		{
			$phone1_type = 'home';
		}
		elseif (stristr($phone['phone1'], 'work')  || stristr($phone['phone1'], 'office') || stristr($phone['phone1'], 'w') || stristr($phone['phone1'], 'o'))
		{
			$phone1_type = 'office';
		}
		else
			{trim($phone1_type = 'other');}

		//Strip any text from column (users used to write 'mobile', etc along with phone number)
		$phone1_s = preg_replace("/[^0-9-]/i", "", $phone['phone1']);

	}
	if (!empty($phone['phone2']))
	{
	if (stristr($phone['phone2'], 'cell')  || stristr($phone['phone2'], 'mobile') || stristr($phone['phone2'], 'c'))
		{
			$phone2_type = 'mobile';
		}
		elseif (stristr($phone['phone2'], 'home')|| stristr($phone['phone2'], 'h'))
		{
			$phone2_type = 'home';
		}
		elseif (stristr($phone['phone2'], 'work')  || stristr($phone['phone2'], 'office')|| stristr($phone['phone2'], 'w') || stristr($phone['phone2'], 'o'))
		{
			$phone2_type = 'office';
		}
		else
			{trim($phone2_type = 'other');}

		$phone2_s = preg_replace("/[^0-9-]/i", "", $phone['phone2']);

	}

	$new_phone = array($phone1_s => $phone1_type, $phone2_s => $phone2_type);

	$new_phone_filtered = array_filter($new_phone);//take out empty phone fields


	if (!empty($new_phone_filtered) AND is_array($new_phone_filtered))
	{

		$ser = serialize($new_phone_filtered);

		$update = $dbh->prepare("UPDATE cm SET phone1 = :phone, phone2 = '' WHERE id = :id");

		$data = array('phone'=>$ser,'id'=>$phone['id']);

		$update->execute($data);

	}
	else
	{
		//This is for the rare situation where somebody put all text in the phone fields
		//Just clear that out.  If not a proper array, it will throw php notices and crash
		//javascript.
		$update = $dbh->prepare("UPDATE cm SET phone1 = '', phone2 = '' WHERE id = :id");

		$data = array('id'=>$phone['id']);

		$update->execute($data);
	}

}

//delete phone2; unnecessary

$query = $dbh->prepare("ALTER TABLE `cm` DROP `phone2`;ALTER TABLE  `cm` CHANGE  `phone1`  `phone` TEXT NOT NULL DEFAULT  ''");

$query->execute();


//update email field
$q = $dbh->prepare("ALTER TABLE `cm` CHANGE `email` `email` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''");

$q->execute();

$query = $dbh->prepare('SELECT id,email FROM cm');

$query->execute();

$emails = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($emails as $email) {

	if ($email['email'])
	{

		$new_email = array($email['email'] => 'other');

		$ser = serialize($new_email);

		$update = $dbh->prepare("UPDATE cm SET email = :email WHERE id = :id");

		$data = array('email' => $ser, 'id' => $email['id']);

		$update->execute($data);
	}

}

//Put in adverse parties place holder in cases table.
$q = $dbh->prepare("ALTER TABLE  `cm` ADD  `adverse_parties` TEXT NOT NULL AFTER  `case_name`
");

$q->execute();

//Get rid of all caps in adverse parties db.
$q = $dbh->prepare("SELECT id,name FROM cm_adverse_parties");

$q->execute();

$n = $q->fetchAll(PDO::FETCH_ASSOC);

foreach ($n as $value) {
	$id = $value['id'];
	$uc = ucwords(strtolower($value['name']));
	$update = $dbh->prepare("UPDATE cm_adverse_parties SET name = '$uc' WHERE id = '$id'");
	$update->execute();
}


//Upgrade courts
$q = $dbh->prepare("SELECT * FROM cm_courts");

$q->execute();

$courts = $q->fetchAll(PDO::FETCH_ASSOC);

$arr = array();

foreach ($courts as $c) {
	$arr[] =$c['court'];
}

if (count($arr) > 0)
{
	$s = serialize($arr);

	$update = $dbh->prepare("UPDATE cm_columns SET select_options = '$s' WHERE db_name = 'court'");

	$update->execute();
}

//Upgrade dispos
$q = $dbh->prepare("SELECT * FROM cm_dispos");

$q->execute();

$dispos = $q->fetchAll(PDO::FETCH_ASSOC);

$arr = array();

foreach ($dispos as $c) {
	$arr[] =$c['dispo'];
}

if (count($arr) > 0)
{
	$s = serialize($arr);

	$update = $dbh->prepare("UPDATE cm_columns SET select_options = '$s' WHERE db_name = 'dispo'");

	$update->execute();
}

//Upgrade referrals
$q = $dbh->prepare("SELECT * FROM cm_referral");

$q->execute();

$courts = $q->fetchAll(PDO::FETCH_ASSOC);

$arr = array();

foreach ($courts as $c) {
	$arr[] =$c['referral'];
}

if (count($arr) > 0)
{
	$s = serialize($arr);

	$update = $dbh->prepare("UPDATE cm_columns SET select_options = '$s' WHERE db_name = 'referral'");

	$update->execute();
}

echo "Updating Journals</br>";
$q = $dbh->prepare("ALTER TABLE `cm_journals` DROP `temp_id`;
  ALTER TABLE `cm_journals` CHANGE `professor` `reader` VARCHAR(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '';
  ALTER TABLE  `cm_journals` CHANGE  `reader`  `reader` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';
  ALTER TABLE  `cm_journals` CHANGE  `deleted`  `archived` VARCHAR( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';
  ALTER TABLE  `cm_journals` CHANGE  `archived`  `archived` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '',CHANGE  `read`  `read` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  ''");

$q->execute();

$q = $dbh->prepare("SELECT id,reader FROM cm_journals");

$q->execute();

$r = $q->fetchAll(PDO::FETCH_ASSOC);

$update = $dbh->prepare("UPDATE cm_journals SET reader = :val WHERE id = :id");

foreach ($r as $value) {
	$new_reader = $value['reader'] . ',';

	$data = array('val'=>$new_reader,'id' => $value['id']);

	$update->execute($data);
}

//convert journal comments to arrays

$q = $dbh->prepare("SELECT id, reader, comments FROM cm_journals WHERE comments != ''");

$q->execute();

$comments = $q->fetchAll(PDO::FETCH_ASSOC);

if ($q->rowCount() > 0)
{
	$update = $dbh->prepare("UPDATE cm_journals SET comments = :comments WHERE id = :id");

	foreach ($comments as $v) {

		$c = array();

		$time =  date('Y-m-d H:i:s');

		$clean = stripslashes($v['comments']);

		$c[] = array('id' => $v['id'],'by' =>  $v['reader'],'text' => $clean,'time' => $time);

		$s = serialize($c);

		$data = array('comments' => $s,'id' => $v['id']);

		$update->execute($data);
	}
}

//convert journal read and archive fields
$q = $dbh->prepare("SELECT `id`, `reader`, `archived`, `read` FROM cm_journals");

$q->execute();

$journals = $q->fetchAll(PDO::FETCH_ASSOC);

if ($q->rowCount() > 0)
{
	$update = $dbh->prepare("UPDATE cm_journals SET `read` = :read, `archived` = :archived WHERE id = :id");

	foreach ($journals as $key => $v) {

		if ($v['read'])
		{
			$read = $v['reader'];
		}
		else
		{
			$read = '';
		}

		if ($v['archived'])
		{
			$archived = $v['reader'];
		}
		else
		{
			$archived ='';
		}

		$data = array('id' => $v['id'],'read' => $read,'archived' => $archived);

		$update->execute($data);

	}
}


echo "Finished updating journals";

//From beta 1.3: add assigned users to cm
echo "Adding users to cases table<br />";
$q = $dbh->prepare("ALTER TABLE  `cm` ADD  `assigned_users` TEXT NOT NULL AFTER  `referral` ;INSERT INTO `cm_columns` (
`id` ,
`db_name` ,
`display_name` ,
`include_in_case_table` ,
`input_type` ,
`select_options` ,
`display_by_default` ,
`required` ,
`display_order`
)
VALUES (
NULL ,  'assigned_users',  'Assigned Users',  'true',  'text',  '',  'false',  '0',  '41'
);
");

$q->execute();


$q = $dbh->prepare("SELECT * FROM cm");

$q->execute();

$cases = $q->fetchAll(PDO::FETCH_ASSOC);

foreach ($cases as $case) {

	update_case_with_users($dbh,$case['id']);
}




echo "Upgrade successful";





