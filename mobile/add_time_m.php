<?php
session_start();
if (!$_SESSION){echo "You must be logged in to view this page.";die;}

include '../db.php';
include '../classes/get_names.php';
include '../classes/format_dates_and_times.class.php';

if ($_POST)
{

	$hours = $_POST[hours];
	$minutes = $_POST[minutes];
	//convert to seconds
	$hours_into_seconds = ($hours * 3600);
	$minutes_into_seconds = ($minutes * 60);
	$time = $hours_into_seconds + $minutes_into_seconds;


	//A query is needed here to deal with multi-professor issue.  The question is: on this case into which the student is entering time, which professors are on this case?  If one, put in one, if two or more, put csv of all professors

	$profs_q = mysql_query("SELECT `id`,`professor` FROM `cm` WHERE `id` = '$_POST[case_id]' LIMIT 1;");
	$profs_q2 = mysql_fetch_object($profs_q);
	$profs = $profs_q2->professor;




	$query = mysql_query("INSERT INTO `cm_case_notes` (id,case_id,date,time,description,username,prof) VALUES (NULL,'$_POST[case_id]','$_POST[date]','$time','$_POST[description]','$_SESSION[login]','$profs')");

echo<<<RESP
<p> Your casenote has been added</p>
RESP;



}





if ($_SESSION['class'] == "student")
{

$query = mysql_query("SELECT cm.* , cm_cases_students.case_id,cm_cases_students.username FROM cm, cm_cases_students
WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' AND cm.date_close = ' ' ORDER BY cm.last_name ASC");

}
else

{

$query = mysql_query("SELECT * FROM `cm` WHERE `date_close` = '' AND `professor` LIKE '%$_SESSION[login]%'  ORDER BY `last_name` ASC");
}


?>

<html>
<head>
<title>ClinicCases Mobile - Add Case Note</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png" border="0"></a></p>
<a class="nav"  href="cm_home_m.php">Main Menu</a> > <strong>Add Case Note</strong>
<form name="add_case_note" action="add_time_m.php" method="post">
<p>
Case
<select name="case_id">
<option value="">Select Case</option>
<option value="nc">Non-Case Time</option>
<?php
while ($r = mysql_fetch_array($query))
{
	$n = new get_names; $client_name = $n->get_clients_name($r[id]);
	echo "<option value='$r[id]'>$client_name</option>";
}




?>
</SELECT>
</P>
<P>
Date
<SELECT name="date">
<option selected=selected value = "<?php echo date("Y") . '-'; echo  date("m") . '-'; echo date("d") . ' 00:00:00';  ?>">Today</option>
<option value = "<?php echo date("Y") . '-'; echo  date("m") . '-'; echo date("d")-1 . ' 00:00:00';  ?>">Yesterday</option>
<option value = "<?php echo date("Y") . '-'; echo  date("m") . '-'; echo date("d")-2 . ' 00:00:00';  ?>">
<?php $y = strtotime('-2 days');echo strftime("%A",$y);?></option>

<option value = "<?php echo date("Y") . '-'; echo  date("m") . '-'; echo date("d")-3 . ' 00:00:00';  ?>">
<?php $y3 = strtotime('-3 days');echo strftime("%A",$y3);?></option>

<option value = "<?php echo date("Y") . '-'; echo  date("m") . '-'; echo date("d")-4 . ' 00:00:00';  ?>">
<?php $y4 = strtotime('-4 days');echo strftime("%A",$y4);?></option>

</select>
</p>
<p>
Time
<table><thead><tr><td class="small_gray">Hours</td><td class="small_gray">Minutes</td></tr><thead>
<tbody>
<tr>
<td><select name="hours" id="hours" style="border:1px solid black;">
<option value="0" selected="selected">0</option>
<option value="1" >1</option>
<option value="2" >2</option>
<option value="3" >3</option>
<option value="4" >4</option>
<option value="5" >5</option>
<option value="6" >6</option>
<option value="7" >7</option>
<option value="8" >8</option>
</select>
</td><td><select name="minutes" id="minutes" style="border:1px solid black;">
<option value="0" selected="selected">0</option>
<option value="5">5</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="35">35</option>
<option value="40">40</option>
<option value="45">45</option>
<option value="50">50</option>
<option value="55">55</option>
</select>
</td></tr>
</tbody>
</table>
</p>
<p>
Description
</p>
<p>
<textarea name="description" cols=25 rows=5></textarea>
</p>
<p>
<input type="submit" value="Add">
</p>

</form>
