<?php 
session_start();
include '../db.php';
include '../get_name.php';
include '../get_client_name.php';
include '../classes/format_dates_and_times.class.php';
if (isset($_GET[s]))
{$start = $_GET[s];}
else
{$start = "0";}
?>
<html>
<head>
<title>ClinicCases Mobile - Recent Activity</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<h1>ClinicCases <span style="color:gray;font-style:italic;">Mobile</span></h1>
<a href="cm_home_m.php">Main Menu</a><br>
<strong>Recent Activity </strong>
<ul>
<?php
$query = mysql_query("SELECT * FROM `cm_case_notes` WHERE `prof` = '$_SESSION[login]' ORDER BY `datestamp` desc LIMIT $start,8");
while ($r = mysql_fetch_array($query))
{

	
echo <<<ITEM
<li><a href="recent_activity_single_m.php?id=$r[id]">
ITEM;
getClient($r[case_id]);
ECHO <<<ITEM
 case, by 
ITEM;
getName($r[username]);
ECHO <<<ITEM
 on 
ITEM;
 formatDate($r[date]);
 ECHO <<<ITEM
 
 </li>
ITEM;


}

?>
</ul>
<CENTER>
<?php 
$new_start = $start + 8; 
$newer = $start - 8;
if ($start > 0)
{echo <<<BACK
<a href="recent_activity_m.php?s=$newer">>>>Newer</a>   | 
BACK;
}


?>

<a href="recent_activity_m.php?s=<?php echo $new_start ?>">Older >>></a>
</CENTER>
</body>
</html>

