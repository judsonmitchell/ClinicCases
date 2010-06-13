<?php
session_start();
if (!$_SESSION)
{echo "There is a login problem.  Please login again.";die;}
include 'db.php';
echo <<<CLOSER
<span id="close"><a href="#" onclick="Effect.Shrink('window1');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small.png" border="0"></a></span>
<div id="utils_window">
<span style="position:absolute;top:5px;right:5px;height:50px;"><a alt="Print this data" title="Print this Data" href="#" onClick="printDiv('utils_window');return false;"><img src="images/print_small.png" border="0" class="print_image"></a></span>
CLOSER;
if ($_SESSION['class'] == 'prof')
	{ 
ECHO <<<REPORTER

<div id="query_panel" >
<form id="report_writer" name="report_writer">
<table width="100%" border="0"><tr>
<td>
<label for "type">Report Type:</label>
<select id="type" name="type" onChange="router(this.value);">
<option selected=selected>Please Select a Report Type</option>
<option value="single_student">Individual Student Time</option>
<option value="all_students"> Comparative Student Time</option>

</select>
</td></tr>

<tr><td>
<span id="student_choose" style="display:none;">
<label for "student">Choose a Student:</label>
<select name="student" id="student" onChange="router('date_choose2');">
<option>Choose Student Name</option>
REPORTER;
$get_students = mysql_query("SELECT * FROM `cm_users` WHERE `assigned_prof` LIKE '%$_SESSION[login]%' AND `status` = 'active'");
while ($r = mysql_fetch_array($get_students))
{
echo "<option value='$r[username]'>$r[first_name] $r[last_name]</option>";

}



ECHO <<<REPORTER

</select>
</span>
<span id="case_choose" style="display:none;">
<select id="case_chooser" name="case_chooser" ">
<option value="">Select a Case</option>
REPORTER;
$get_students = mysql_query("SELECT * FROM `cm_cases_students` WHERE `student` = '$_SESSION[login]'");
while ($r2 = mysql_fetch_array($get_cases))
{
echo "<option value='$r2[case_id]'>$r[first_name] $r[last_name]</option>";

}


ECHO <<<REPORTER

</select>
</span>

</td></tr>
REPORTER;
}


elseif ($_SESSION['class'] == 'student')
{
ECHO <<<REPORTER
	
	<div id="query_panel">
<form id="report_writer" name="report_writer">
<table width="100%" border="0"><tr>
<td>
<label for "type">Report Type:</label>
<select id="type" name="type" onChange="router(this.value);">
<option selected=selected>Please Select a Report Type</option>
<option value="student_time">Your Time Entered</option>


</select>
</td></tr>
REPORTER;
	
	
	
	
	
	
	
	
	
	
}















ECHO <<<REPORTER
<tr><td>
<span id="date_choose2" style="display:none;">
<label for "date_range">Select a Date Range</label>
<select id="date_range2" name="date_range2" onChange = "
if (this.value=='date_select')
{document.getElementById('calendars').style.display = 'block';}
else
{document.getElementById('submitter').style.display = 'block';}

">
<option value ="">Please Choose a Date Range</option>
<option value="today">Today</option>
<option value="this_week">This Week</option>
<option value="this_month">This Month</option>
<option value="date_select">Choose Date Range</option>
</select>
</span>

</td></tr>
<tr><td>

<span id="calendars" style="display:none;">

<label for "start_date">Beginning Date</label>
<input type="text" name="start_date" id="start_date" value=""><a id="cals" href="#" onClick="scwShow(start_date,scwID('cals'));return false;" alt="Click to Select Date" title="Click to Select Date"><img src="images/calendar_no_bg.png" border="0"></a>

<br>
<label for "end_date">Ending Date</label>
<input type="text" name="end_date" id="end_date" value="" ><a id="cals" href="#" onClick="scwShow(end_date,scwID('cals'));document.getElementById('submitter').style.display = 'block';return false;" alt="Click to Select Date" title="Click to Select Date"><img src="images/calendar_no_bg.png" border="0"></a>

</span>


</td>
</tr></table>

<div id="cal"  >
      

</div>
<span id="submitter" style="display:none;">
<center>
<br>
<label for "graphs">Enable Graphs</label><input type="checkbox" id = "graphs" name="graphs" checked=checked><br>
<input type="button" value="Submit" onClick="document.getElementById('query_panel').style.display = 'none';createTargets('response_panel','response_panel');sendDataPost('reports_process.php','report_writer');return false;">
<br>
</center>
</span>



</form>


</div>
<div id = "response_panel">

</div>






</div>

REPORTER;
	
















?>
