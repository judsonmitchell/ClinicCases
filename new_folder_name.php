<?php
include 'db.php';
$name = $_GET['name'];
$case_id = $_GET['case_id'];
$rand = rand();
/* This adds a document with no name, just a case id and folder name, so that if the user doesn't add a doc to the folder, the empty folder with the correct name will still appear next time they return */
$put_dummy_doc = mysql_query("INSERT INTO `cm_documents` (`id`,`folder`,`case_id`) VALUES (NULL,'$name','$case_id')");

header('Location: cm_docs.php?ieyousuck=' . $rand . '&id=' . $case_id)

?>
