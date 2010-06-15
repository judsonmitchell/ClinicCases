<?php
session_start();
include 'session_error.php';
include 'db.php';
include_once './classes/get_names.php';


/* This is a hack to fix an ajax problem with the calendar coming back after you navigate away from this page.  The date input is given a unix timestamp to make it unique.  Solves DOM problem with javascript */
$date = time();
?>
<span id="close"><a href="#" onclick="Effect.Shrink('window1');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small.png" border="0"></a></span>
<div id="substance">
<h4>Use this form to enter time not related to a case (e.g., class time)</h4>
<br>
<div style="border:1px solid gray;width:95%;height:87%;margin:0% 2% 2.5% 2%;">

<div id="time_form" style="width:100%;height:100px;background-color:rgb(255, 255, 204);z-index:3;position:relative;>
<?php
//Find out if student has multiple professors.
	
	$prof_q = mysql_query("SELECT `username`,`assigned_prof` FROM `cm_users` WHERE `username` = '$_SESSION[login]' LIMIT 1");
	$line = mysql_fetch_object($prof_q);
	$prof_string_f = $line->assigned_prof;
	$prof_string = substr($prof_string_f,0,-1);
	$prof_arr = explode(",",$prof_string);
	$size = count($prof_arr);
		
		if ($size > 1)
			{
				$prof_chooser = "<select name='professor'><option selected='selected'>--</option>";
				foreach ($prof_arr as $p)
					{
						$fn = new get_names;$full_name=$fn->get_users_name_initial($p);
						$prof_chooser .= "<option value='$p,'>$full_name</option>";
					}
				
				$prof_chooser .= "</select>";
				echo $prof_chooser;
			
			}
			
			else
			
			{ 
				
				$prof_chooser =  "<input type='hidden' name='professor' value='$prof_string_f'>";	
			}


?>

<form id="time_form">
<table id="time_form_table" border="0" >
<tr>
<td></td><td><label for "date">Select Date</label></td><td><label for "hours">Hours</label></td><td><label for "minutes">Minutes</label><td align="center"><label for "description">Describe What You Did</label></td><td>
<?php
if ($size > 1)
{echo "<label for 'professor'>Which professor did you do this for?</label>";}
?>
</td><td></td></tr>
<tr><td><input type="text" id="date<?php echo $date; ?>" name="date" size="10" style="border:0px;background-color:rgb(255, 255, 204);"></td><td align="center">


<a id="cal" href="#" onClick="scwShow(date<?php echo $date; ?>,scwID('cal'));return false;" alt="Click to Select Date" title="Click to Select Date"><img src="images/calendar.png" border="0"></a></td><td><select name="hours" id="hours" style="border:1px solid black;">
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

</td><td><textarea cols="38" rows="2" id="description" name="description"></textarea></td><td><input type="hidden" name="id" value="NC">
<?php echo $prof_chooser; ?>
</td><td>
<a href="#" onClick="var check = forceDate('date<?php echo $date; ?>');if (check == true){createTargets('status','the_info');sendDataPostAndStripeNoStatus2('add_time.php','time_form');clearForm();return false;}" alt="Add This Case Note" title="Add This Case Note"><img src="images/check_yellow.png" border="0"></a></td></tr></table></form></div>
<div id = "the_info" style="height:60%;width:100%;">
<?php
include 'display_noncase.php';
?>
</div>

</div>
</div>
