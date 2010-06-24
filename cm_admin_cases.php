<?php
session_start();
if (!$_SESSION)
{header('Location: index.php');}

include 'db.php';
include './classes/get_names.php';

 ?>
<html>
<head>
<title>Cases - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet" href="cm_tabs.css" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/print.js" type="text/javascript"></script>

<script src="./javascripts/table_stripe.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script src="javascripts/validations.js" type="text/javascript"></script>
<script src="./javascripts/FormProtector.js" type="text/javascript"></script>
<script src="./javascripts/ajaxUpload.js" type="text/javascript"></script>




<script>
function theSort(column,sortDir)
{

new Ajax.Updater('work_space','cm_cases_table.php',{evalScripts:true,method:'get',parameters:({'view':$F('view_chooser'),'sort':column,'sortdir':sortDir})});
}

function theSortResults(column,sortDir,st,sf)
{

new Ajax.Updater('work_space','cm_cases_table.php',{evalScripts:true,method:'get',parameters:({view:$F('view_chooser'),sort:column,sortdir:sortDir,force:'1',sterm:st,sfield:sf})});

}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}




function changeTab(chosen)
{
  var hrefs = new Array()
  hrefs[0] = 'one_href';
  hrefs[1] = 'two_href';
  hrefs[2] = 'three_href';
  hrefs[3] = 'four_href';
  hrefs[4] = 'five_href';
  var i= 0;
   for(i = 0; i < hrefs.length; i++)
     {
        var ident = hrefs[i] ;
        var dingo = document.getElementById(ident);
              if (ident !== chosen)
              {

                dingo.style.textDecoration = 'underline';
                dingo.style.color = 'black';
        dingo.style.fontWeight = 'normal';
              }
              else if (ident == chosen)
              {
        dingo.style.textDecoration = 'none';
        dingo.style.color = '#d2d2d2';
        dingo.style.fontWeight = 'bold';
              }
      }
}


function clearForm()
{
var theTextarea = document.getElementById('description');
var theHours = document.getElementById('hours');
var theMinutes = document.getElementById('minutes');
theTextarea.value = '';
theHours.value = '0';
theMinutes.value = '0';

}

function clearFormAll()
{
var theTextarea = document.getElementById('description');
var theHours = document.getElementById('hours');
var theMinutes = document.getElementById('minutes');
var theDate = document.getElementById('date1');
theTextarea.value = '';
theHours.value = '0';
theMinutes.value = '0';
theDate.value = '';
}

function deleteCaseNote(theId,theTarget,theCase)
{

 var where_to= confirm("Do you really want to delete?");
 if (where_to== true)
 {
createTargets(theTarget,theTarget);sendDataGetAndStripe2('casenote_delete.php?id='+ theId + '&case_id=' + theCase); return true;
 }
 else
 {
return false;
}};



function stopTimer(url)
{
var x = readCookie('timeron');
if (x == 'timeron') {
var answer = confirm('The timer is running.  If you continue, your time will be lost.  Click OK to continue, click Cancel to stay.');
if (answer == true)
{

var kill_time = new Date("January 1, 1970");
var kill_string = "timeron=timeron;expires=" + kill_time.toGMTString();
document.cookie = kill_string;
if (url == 'fade')
{
Effect.Fade('timer_box');

}
else
{location.href = url;}
return true;
}
else
{return false;}
}

if (x == 'timeroff')
{location.href = url;return false;}


if (!x)
{location.href = url;return false;}
}



function goLookup()
{
new Ajax.Autocompleter("to_full", "autocomplete", "messages_to_lookup.php", {tokens: ',',afterUpdateElement: updateFields});

function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("to").value = $("to").value + oResult.childNodes.item(1).innerHTML + ',';
  }
}

function goLookup2()
{
new Ajax.Autocompleter("cc1_full", "autocomplete2", "messages_to_lookup.php", {tokens: ',',afterUpdateElement: updateFields});

function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("cc1").value = $("cc1").value + oResult.childNodes.item(1).innerHTML + ',';
  }

}

function modRes(chBoxName)
{
var theChBox = document.getElementById(chBoxName);
var collector = document.getElementById('collect');
if (theChBox.checked)
{collector.value = collector.value + chBoxName + ',';}

else
{
var getCommaToo = chBoxName + ',';
collector.value = collector.value.replace(getCommaToo,'');
}
}

function killDroppables()
{

 if (document.all) { Droppables.drops = [] }

}

function fileDirect(id)
{
windw = document.getElementById('window1');

createTargets('window1','window1');
sendDataGetAndStripeNoStatus2('cm_cases_single.php?id=' + id);
document.getElementById('view_chooser').style.display = 'none';
Effect.Grow('window1');
}

function updateLeftSide(id,type)
{
doThis = function (quer,id)
{
	var myid = id;
	if (quer == 'time')
	{
updater("updater.php?type=time&id=" + myid + "",'time_notifier');

}

	if(quer == 'activity')

		{updater("updater.php?type=activity&id=" + myid + "",'activity_notifier')
		}

	if(quer == 'event')
			{updater("updater.php?type=event&id=" + myid + "",'event_notifier')}
}

if (type == 'event')
	{
setTimeout("doThis('event','" + id +"')",500);
}
		else
		{
doThis('time',id);
	setTimeout("doThis('activity','" + id +"')",500);
		}

}


new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
    frequency: 300
  });


Event.observe(window, 'load', function() {

$$("a.nobubble").invoke("observe", "click", function(e) {

	Event.stop(e);
})})

Event.observe(window, 'load', function() {

$$("tr").invoke("observe", "click", function(e) {

	Event.stop(e);
})})


</script>


</head>
<body >
<div id="notifications"></div>
<div id = "bug" style="display:none;">
</div>


<div id = "nav_container">
<div id="header">

  <ul>
   <li><a href="cm_admin_home.php"><span id="tab1">At a Glance</span></a></li>

    <li id = "current"><a href="cm_admin_cases.php"><span id="tab2">Cases</span></a></li>
    <li><a href="cm_admin_students.php"><span id="tab3">Students</span></a></li>
        <li><a href="cm_admin_users.php"><span id="tab4">Users</span></a></li>
	      <li><a href="cm_board.php"><span id="tab5">Board</span></a></li>

   <li><a href="cm_admin_preferences.php"><span id="tab5">Prefs</span></a></li>


  </ul>
</div>
<?php include 'cm_menus_for_cm_admin_cases.php';?>
</div>
<div id="content">


<div id = "table_container" style = "width:100%;height:100%">

<div id="choosers" style="width:95%;text-align:right;margin: 5px 5px;">
<div style="position:absolute;left:10px;top:5px;"><a href="#" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('new_case.php');document.getElementById('view_chooser').style.display = 'none';return false;" alt="Open a New Case" title="Open a New Case"><img src="images/new_file.png" border="0"></a></div>

<form style="display:inline;" id="search_form" name="search_form" onSubmit="new Ajax.Updater('work_space','cm_cases_table.php',{evalScripts:true,method:'get',parameters:Form.serialize('search_form')});return false;">

View: <select id = "view_chooser" name = "view" onFocus = "this.style.color = 'black';" onChange="new Ajax.Updater('work_space','cm_cases_table.php',{evalScripts:true,method:'get',parameters:{view:$('view_chooser').value},onLoading:function(){$('notifications').style.display='block';$('notifications').update('Loading...');
},onSuccess:function(){Effect.Fade('notifications',{duration:1});$('searchterm').value='Enter Search Term';}});">

<option value = "open" <?php
if ($_SESSION['class'] == 'prof')
{ echo "selected=\"selected\" ";}
?>
>Open Cases Only</option>
<option value = "closed">Closed Cases Only</option>
<option value = "all">All Cases</option>

</select>


<select name="searchfield" id="searchfield" style="display:none;" onChange="$('searchterm').style.display = 'inline';$('searchterm').value='Enter Search Term';">
<option value = "" selected="selected">Select A Field to Search</option>
<option value="clinic_id">Case Number</option>
<option value="first_name">First Name</option>
<option value="last_name">Last Name</option>
<option value="m_initial">Middle Initial</option>
<option value="date_open">Date Open</option>
<option value="date_close">Date Close</option>
<option value="case_type">Case Type</option>
<option value="professor">Professor</option>

<option value="address1">Address 1</option>
<option value="address 1">Address 2</option>
<option value="city">City</option>
<option value="state">State</option>
<option value="zip">Zip</option>
<option value="phone1">Phone 1</option>
<option value="phone2">Phone 2</option>
<option value="email">Email</option>
<option value="ssn">SSN</option>
<option value="dob">Date of Birth</option>
<option value="age">Age</option>
<option value="gender">Gender</option>
<option value="race">Race</option>
<option value="judge">Judge</option>
<option value="pl_or_def">Plaintiff/Defendant/Other</option>
<option value="court">Court Name</option>
<option value="section">Court Section</option>
<option value="ct_case_no">Court Case Number</option>
<option value="notes">Notes</option>
<option value="dispo">Dispositon </option>
<option value="close_code">Closing Code</option>
<option value="close_notes">Closing Notes</option>
<option value="referral">Referral Source</option>



</select>


<input type = "text" width="40"  id = "searchterm" name = "searchterm" onFocus = "this.value = '';this.style.color= 'black';" style="display:inline;" value="Search By Name">

<div id="autocomplete_case" style="display:none"></div>



<span id= "search_choice">
<span id="advanced_choice">
<a href="#" onClick="$('searchfield').style.display = 'inline';$('searchterm').style.display ='none';ajac.disabled='false';$('advanced_choice').style.display = 'none';$('normal_choice').style.display = 'inline';$('choosers').morph('background:rgb(224, 224, 224)');return false;">Advanced Search</a>
</span>
<span id="normal_choice" style="display:none">
<a href="#" onClick="document.getElementById('searchfield').style.display = 'none';$('searchterm').style.display ='inline';$('searchterm').value='Search By Name';$('normal_choice').style.display = 'none';$('advanced_choice').style.display = 'inline';$('choosers').morph('background:rgb(255, 255, 255)');$('view_chooser').value='open';ajac.disabled='true';return false;">Return to Name Search</a>
</span>


</span>

</form>

<script>
//The autocompleter for advanced search

ajac = new Ajax.Autocompleter("searchterm", "autocomplete_case", "cm_cases_lookup.php", {method: 'get', callback:function(){return 'searchterm=' + $F('searchterm') + '&searchfield=' + $F('searchfield') + '&view='  + $F('view_chooser');}, afterUpdateElement:function(){

	$('autocomplete_case').style.display='none';
	new Ajax.Updater('work_space','cm_cases_table.php',{evalScripts:true,method:'get',parameters:{searchfield:$F('searchfield'),searchterm:$F('searchterm'),view:$F('view_chooser')}});
}});

//Is initially disabled for simple name search
ajac.disabled = 'true';

</script>

<a alt="Print this data" title="Print this Data" href="#" onClick="printDiv('work_space');return false;"><img src="images/print.png" border="0" class="print_image"></a>
</div>
<?php
$limiter = "WHERE `date_close` = ''";


/* End */

$result = mysql_query("SELECT * FROM `cm`  $limiter ORDER BY `last_name`");


?>
<div id = "work_space" style="width:99.8%;height:90%;overflow:auto;">
<div id="the_info" style="width:100%;height:30px;display:none;"></div>
<table id = "display_cases">
<thead><tr><td colspan="9" style="background:url(images/grade_gray_small.jpg) repeat-x;color:black;"><b><?php echo mysql_num_rows($result); ?></b> cases found.</td></tr><tr><td><a class='theader' href="#" onClick = "theSort('clinic_id','ASC');return false;" title="Sort by this column" alt="Sort by this column" >Case No.</td><td><a class='theader' href="#" onClick = "theSort('first_name','ASC');return false;" title="Sort by this column" alt="Sort by this column" >First Name</td><td><a class='theader' href="#" onClick = "theSort('last_name','ASC');return false;" title="Sort by this column" alt="Sort by this column">Last Name</td><td><a class='theader' href="#" onClick = "theSort('date_open','ASC');return false;" title="Sort by this column" alt="Sort by this column">Date Open</td><td><a class='theader' href="#" onClick = "theSort('date_close','ASC');return false;" title="Sort by this column" alt="Sort by this column">Date Close</td><td><a class = 'theader' href="#" onClick = "theSort('case_type','ASC');return false;" title="Sort by this column" alt="Sort by this column">Case Type</a></td><td><a class='theader' href="#" onClick = "theSort('dispo','ASC');return false;"  title="Sort by this column" alt="Sort by this column">Disposition</td><td><a class='theader' href="#" onClick = "theSort('professor','ASC');return false;"  title="Sort by this column" alt="Sort by this column">Professor</td><td></td></tr></thead><tbody>
<?php


while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($result,$i);
        $d[$field] = $col_value;
        $i++;

    }

$get_date_open = explode('-',$d[date_open]);
$month = $get_date_open[1];
$day = $get_date_open[2];
$year = $get_date_open[0];
$new_date_open = "$month" . "/" . "$day" . "/" . "$year";

if ($d[date_close])
{$get_date_close = explode('-',$d[date_close]);
$month = $get_date_close[1];
$day = $get_date_close[2];
$year = $get_date_close[0];
$new_date_close = "$month" . "/" . "$day" . "/" . "$year";}
//format prof names
$plist = explode(",",substr($d[professor],0,-1));
					foreach ($plist as $v)
					{
						$p = new get_names;$px = $p->get_users_name_initial($v); 
						$prof_str .= $px . ", ";
					}	
					
	//take out trailing comma
	$prof_str_clip = substr($prof_str,0,-2);
echo <<<ROWS

<tr  title="Click to View Case" alt="Click to View Case" onmouseover="this.style.color='red';this.style.cursor='pointer'" onmouseout="this.style.color='black';" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGetAndStripeNoStatus2('cm_cases_single.php?id=$d[id]');document.getElementById('view_chooser').style.display = 'none';return false;"><td>$d[clinic_id]</td><td>$d[first_name]</td><td>$d[last_name]</td><td>$new_date_open</td><td>$new_date_close</td><td>$d[case_type]</td><td>$d[dispo]</td><td>$prof_str_clip</td>
<td><a class="nobubble" href="#" title="Edit this Case" alt="Edit this Case " onClick="createTargets('window1','window1');sendDataGet('new_case_edit.php?id=$d[id]');Effect.Grow('window1');document.getElementById('view_chooser').style.display = 'none';return false;"><img src="images/report_edit.png" border="0"></a></td></tr>
ROWS;

//reset the prof string and run through the loop again.
$prof_str = '';
}

?>
</tbody></table></div><script>stripe('display_cases','#fff','#e0e0e0');</script>


</div>

<div id="window1" style="display:none;">


</div>

<div id="messaging_window" style="display:none;">

</div>
<script>
new Draggable('messaging_window');
</script>


</div>
<div id="timer_box" class="box" style="position:absolute;top:30px;left:70px;z-index:1000;width:280px;height:280px;background:white;border:3px ridge #bbf;display:none;" onMouseOver="this.style.cursor='move';">
<script>
 new Draggable('timer_box');

</script>



</div>
<script type="text/JavaScript" src="javascripts/scw.js"></script>
</body>
</html>
