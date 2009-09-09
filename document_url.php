<?php
session_start();
if (!$_SESSION)
{die("There is a login problem.  Please login again.");}
include 'db.php';

$case_id = $_POST[case_id];
$folder = $_POST[folder];
$title = $_POST[title];
$url = $_POST[url];

$add = mysql_query("INSERT INTO `cm_documents` (
`id` ,
`name` ,
`url` ,
`folder` ,
`username` ,
`case_id` ,
`date_modified`
)
VALUES (NULL , '$title', '$url', '$folder', '$_SESSION[login]', '$case_id',
CURRENT_TIMESTAMP
);");


echo "URL added.";
?>
