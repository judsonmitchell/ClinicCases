<?php
session_start();
if (!$_SESSION){echo "You must be logged in to view this page.";die;}

include '../db.php';

if ($_SESSION['class'] == "student")
{

$query = mysql_query("SELECT cm.* , cm_cases_students.case_id,cm_cases_students.username FROM cm, cm_cases_students
WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' AND cm.date_close = ' ' ORDER BY cm.last_name ASC");

}
else

{

$query = mysql_query("SELECT * FROM `cm` WHERE `date_close` = '' AND `professor` = '$_SESSION[login]' OR `professor2` = '$_SESSION[login]' ");
}


?>

<html>
<head>
<title>ClinicCases Mobile - Add Time</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png" border="0"></a></p>
<a class="nav"  href="cm_home_m.php">Main Menu</a> > <strong>Add Time</strong>
<p>
<select>
<option value="">Select Case</option>
<option value="nc">Non-Case Time</option>
<?php
while ($r = mysql_fetch_array($query))
{echo "<option value='$r[id]'>$r[id]</option>";
}




?>
</P>
