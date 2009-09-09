<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}
include 'db.php';

$create = mysql_query("INSERT INTO `cm_board` (
`id` ,
`title` ,
`body` ,
`created_by` ,
`last_modified_by` ,
`created` ,
`last_modified` ,
`attachment` ,
`locked` ,
`hidden`
)
VALUES (
NULL , '', '', '', '', '', '', '', '', ''
);
");

$id = mysql_insert_id();
echo $id;

?>
