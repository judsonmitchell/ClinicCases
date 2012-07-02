<?php
require('../db.php');

//Check if docs file is writable

if (!is_writable($_POST['doc_path']) || !is_dir($_POST['doc_path']))
{
	$resp = array("error" => true, "message" => "<p class='config_error'>Sorry, it appears that the path to documents you specified (" . $_POST['doc_path'] . ") either does not exist or is not writable.  Please fix this and try again.</p>");

	echo json_encode($resp);die;
}

//create a backup of the default config file
$copy = copy('../_CONFIG.php','../_CONFIG.php.bak');
if (!$copy)
{
	$resp = array("error" => true, "message" => "<p class='config_error'>Sorry, I need to create a backup copy of your config file and the server wouldn't let me do that.</p>");

	echo json_encode($resp);die;
}

//Write config file
$config = file_get_contents('../_CONFIG.php');

function update_config($vals,$config)
{
	$field_name = array();
	$field_value = array();
	foreach ($vals as $key => $value) {
		$field_name[] = '%' . $key . '%';
		$field_value[] = $value;
	}

	$new = str_replace($field_name, $field_value, $config);

	return $new;
}


$new = update_config($_POST,$config);

file_put_contents('../_CONFIG.php', $new);

$sql = file_get_contents('default.sql');

//See if the db works
$q = $dbh->prepare($sql);

$q->execute();

$error = $q->errorInfo();

if ($error[1])
{
	$error_string = null;

	foreach ($error as $key => $value) {
		$error_string .= $error[2] . "<br />";
	}

	$resp = array('error' => true,'message' => 'There was an error adding data to your database.  Here is what the database said:' . $error_string);

	//delete the current config
	unlink('../_CONFIG.php');

	copy('../_CONFIG.php.bak','../_CONFIG.php');

	echo json_encode($resp);
}
else
{
	unlink('../_CONFIG.php.bak');

	chmod('../_CONFIG.php', 0664);

	$html = "<p class='good'>Installation successful.</p><p>Now you can log on to ClinicCases by going to <a href='" . $_POST['base_url']. "' target='_new'>" . $_POST['base_url'] . "</a> and logging in with the username 'admin' and the password 'admin'.  You will then be prompted to change this password.</p><p>After that, please go to the Users tab and create at least one new user who is in the Administrator group.  Then delete the Temp Admin account.  Further configuration instructions are available at the <a href='https://cliniccases.com/help'>ClinicCases site</a></p>";

	$resp = array('error' => false,'message' => 'Installation successful','html' => $html);

	echo json_encode($resp);

}