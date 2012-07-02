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
echo $new;die;
file_put_contents('../_CONFIG.php', $new);



//See if the db works
$q = $dbh;

	//If errror, delete _CONFIG.php and rename _CONFIG.php.bak to _CONFIG.php

//Run the sql

//chmod _CONFIG.php 664

//delete _CONFIG.php.bak


