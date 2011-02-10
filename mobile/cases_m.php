<?php
session_start();
if (!$_SESSION){echo "You must be logged in to view this page.";die;}
include '../db.php';
if (isset($_GET[s]))
{$start = $_GET[s];}
else
{$start = "0";}


?>
<html>
<head>
<title>ClinicCases Mobile - Your Cases</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png" border="0"></a></p>
<a href="cm_home_m.php">Main Menu</a> > <strong>Your Open Cases</strong>
<ul>
<?php
if ($_SESSION['class'] == "student")
{

$query = mysql_query("SELECT cm.* , cm_cases_students.case_id,cm_cases_students.username FROM cm, cm_cases_students
WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' AND cm.date_close = ' ' ORDER BY cm.last_name ASC");

//$query = mysql_query(" SELECT * FROM cm JOIN cm_cases_students ON cm.id = cm_cases_students.case_id
//WHERE cm_cases_students.username = '$_SESSION[login]';");

while ($r = mysql_fetch_array($query))
{

echo "<li><a href='case_single_m.php?id=$r[id]'>$r[first_name] $r[last_name]</a></li>";

}
if (mysql_num_rows($query)<1)
	{echo "No cases found.";}
}

else

{

$query = mysql_query("SELECT * FROM `cm` WHERE `date_close` = '' AND `professor` LIKE '%$_SESSION[login]%'");


while ($r = mysql_fetch_array($query))
{
echo "<li><a href='case_single_m.php?id=$r[id]'>$r[first_name] $r[last_name]</a></li>";

}
if (mysql_num_rows($query)<1)
	{echo "No cases found.";}
}
?>
</ul>
</body>
</html>
