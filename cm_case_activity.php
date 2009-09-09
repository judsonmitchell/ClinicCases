<?php
session_start();
include 'db.php';
if (isset($_GET['id']))
{
$id = $_GET['id'];

}
/* This $date variable  is a hack to fix an ajax problem with the calendar coming back after you navigate away from this page.  The date input is given a unix timestamp to make it unique.  Solves DOM problem with javascript */
$date = time();
echo <<<TOP



<div id="case_act_menu" style="background-color:rgb(255, 255, 204);width:100%;height:35px;padding-left:5px;">Add Case Note: <a href="#" onClick="createTargets('timer_box','timer_box');Effect.Appear('timer_box');sendDataGet('timer.php?id=$id');return false;">Use Timer</a> | <a href="#" onClick="Effect.BlindDown('time_form');fp = new FormProtector('caseNotes');fp.setMessage('If you do not save, your changes will be lost.');expCon();return false;">Use Form</a></div>

<div id="time_form" style="display:none;width:100%;height:100px;background-color:rgb(255, 255, 204);z-index:3;position:relative;">
<span id="print_title" style="display:none;"><b>Case Activity</b></span>

<form id="caseNotes" name="caseNotes">

<table id="time_form_table" border="0">
<tr>
<td></td><td><label for "date">Select Date</label></td><td><label for "hours">Hours</label></td><td><label for "minutes">Minutes</label><td align="center"><label for "description">Describe What You Did</label></td><td></td><td></td></tr>
<tr><td><input type="text" id="date$date" name="date" size="10" style="border:0px;background-color:rgb(255, 255, 204);"></td><td align="center">


<a id="cal" href="#" onClick="scwShow(date$date,scwID('cal'));return false;" alt="Click to Select Date" title="Click to Select Date"><img src="images/calendar.png" border="0"></a></td><td><select name="hours" id="hours" style="border:1px solid black;">
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
<input type="hidden" name="id" value="$id">

</td><td><div id="holder"><textarea id="description" name="description" class="expand"></textarea></div></td><td>

<a href="#" onClick="var check = forceDate('date$date');if (check == true){createTargets('status','the_info');sendDataPostAndStripeNoStatus2('add_time.php','time_form');clearForm();$('description').style.height='50px';updateLeftSide($id);fp.resetAlrt(); return false;}" alt="Add This Case Note" title="Add This Case Note"><img src="images/check_yellow.png" border="0"></a></td>

<td><a title="Cancel Edit" alt="Cancel Edit" href="#" onClick="Effect.BlindUp('time_form');document.caseNotes.reset();fp.resetAlrt();return false;"><img src="images/cancel_small.png" border="0"></a></td></tr></table></form>




</div>


<div id = "the_info">
TOP;
include 'display_casenotes.php';
echo "</div>";
?>
