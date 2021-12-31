<?php

function deleteDir($dirPath) {

    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException('$dirPath must be a directory');
    }

    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }

    $files = glob($dirPath . '*', GLOB_MARK);

    foreach ($files as $file) {
        if (is_dir($file)) {
            $this->deleteDir($file);
        } else {
            unlink($file);
        }
    }

    rmdir($dirPath);
 }

//Check if docs file is writable

if (!is_writable($_POST['doc_path']) || !is_dir($_POST['doc_path']))
{
	$resp = array("error" => true, "message" => "<p class='config_error'>Sorry, it appears that the path to documents you specified (" . $_POST['doc_path'] . ") either does not exist or is not writable.  Please fix this and try again.</p>");

	echo json_encode($resp);die;
}

//Check if path is correct
if (!is_writable($_POST['cc_path']) || !is_dir($_POST['cc_path']))
{
	$resp = array("error" => true, "message" => "<p class='config_error'>Sorry, it appears that the path you specified (" . $_POST['cc_path'] . ") either does not exist or is not writable.  Please fix this and try again.</p>");

	echo json_encode($resp);die;
}

//See if the db works
try {
		$dbh = new PDO("mysql:host=" . $_POST['db_host'] . ";dbname=" . $_POST['db_name'] , $_POST['db_user'], $_POST['db_pass']);
    }
catch(PDOException $e)
    {
		//400 is sent to trigger an error for ajax requests.
		//header('HTTP/1.1 400 Bad Request');

		$message = "<p>I was unable to connect to your database.  Here is what the database said:</p><p>" . $e->getMessage() . "</p>";

		$resp = array('error' => true,'message' => $message,'html' => '');

		echo json_encode($resp);die();

    }

//create a backup of the default config file

$source = '../_CONFIG_template.php';

$destination = '../_CONFIG_template.php.bak';

$data = file_get_contents($source);

$handle = fopen($destination, "w");

fwrite($handle, $data);

fclose($handle);

//Write config file
$config = file_get_contents('../_CONFIG_template.php');

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

file_put_contents('../_CONFIG_template.php', $new);

$sql = file_get_contents('default.sql');

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
	unlink('../_CONFIG_template.php');

	copy('../_CONFIG_template.php.bak','../_CONFIG_template.php');

	echo json_encode($resp);
}
else
{
	unlink('../_CONFIG_template.php.bak');

	rename('../_CONFIG_template.php', '../_CONFIG.php');

	deleteDir($_POST['cc_path']. '/_INSTALL');

	$html = "<p class='good'>Installation successful.</p><p>Now you can log on to ClinicCases by going to <a href='" . $_POST['base_url']. "' target='_new'>" . $_POST['base_url'] . "</a> and logging in with the username 'admin' and the password 'admin'.  You will then be prompted to change this password.</p><p>After that, please go to the Users tab and create at least one new user who is in the Administrator group.  Then delete the Temp Admin account.  Further configuration instructions are available at the <a href='https://cliniccases.com/help'>ClinicCases site</a></p>";

	$resp = array('error' => false,'message' => 'Installation successful','html' => $html);

	echo json_encode($resp);

}
