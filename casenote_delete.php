<?php
session_start();
include 'session_error.php';
include 'db.php';

$id = $_GET['id'];
$case_id = $_GET['case_id'];

$del_query = mysql_query("DELETE FROM `cm_case_notes` WHERE `id` = '$id' LIMIT 1");
$rand = rand();

if (isset($_GET[nc]))
{
header('Location: display_noncase.php?notifydelete=1&id=' . $case_id . '&ieyousuck=' . $rand );

}
else
{header('Location: display_casenotes.php?notifydelete=1&id=' . $case_id . '&ieyousuck=' . $rand );
}
?>
