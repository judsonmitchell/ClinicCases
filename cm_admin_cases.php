<?php 
session_start();
include 'db.php';
if (!$_SESSION)
{header('Location: index.php');}

 ?>
<html>
<head>
<title>Cases - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet" href="cm_tabs.css" type="text/css">

<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/print.js" type="text/javascript"></script>

<script src="./javascripts/table_stripe.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>

<script>
function theSort(column,sortDir)
{
createTargets('work_space','work_space');
sendDataGetAndStripe('cm_admin_cases_table.php?view=' + document.getElementById('view_chooser').value + '&sort=' + column + '&sortdir=' + sortDir);
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





</script>

<script>

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php?sid=<?php echo $_COOKIE[PHPSESSID]; ?>', {
    method: 'get',
    frequency: 300 
  });



</script>


</head>
<body >
<div id = "bug" style="display:none;">
</div>

<?php include 'cm_menus_for_cm_admin_cases.php';?>
<div id = "nav_container">
<div id="header">

  <ul>
   <li><a href="cm_admin_home.php"><span id="tab1">At a Glance</span></a></li>
   
    <li id = "current"><a href="cm_admin_cases.php"><span id="tab2">Cases</span></a></li>
    <li><a href="cm_admin_students.php"><span id="tab3">Students</span></a></li>
        <li><a href="cm_admin_users.php"><span id="tab4">Users</span></a></li>
   <li><a href="cm_admin_preferences.php"><span id="tab5">Preferences</span></a></li>


  </ul>
</div>
</div>
<div id="content">


<div id = "table_container" style = "width:100%;height:100%">

<div id="choosers" style="width:95%;text-align:right;margin: 5px 5px;">
<div style="position:absolute;left:10px;top:5px;"><a href="#" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGet('new_case.php');document.getElementById('view_chooser').style.display = 'none';return false;" alt="Open a New Case" title="Open a New Case"><img src="images/new_file.png" border="0"></a></div>
<form style="display:inline;" onSubmit="createTargets('work_space','work_space');sendDataGetAndStripe('cm_admin_cases_table.php?searchterm=' + document.getElementById('search').value);return false;"><input type = "text" width="35" value="Search By Name" id = "search" name = "search" onFocus = "this.value = '';this.style.color= 'black';" style="display:inline;">
<a href="#" onClick="createTargets('work_space','work_space');sendDataGetAndStripe('cm_admin_cases_table.php?searchterm=' + document.getElementById('search').value);return false;"><img src="./images/check.png" border="0"  class = "submit_image"></a></form>


View: <select id = "view_chooser" name = "view_chooser" onFocus = "this.style.color = 'black';" onChange = "createTargets('work_space','work_space');sendDataGetAndStripe('cm_admin_cases_table.php?view=' + document.getElementById('view_chooser').value);">
<option value = "open" selected="selected">Open Cases Only</option>
<option value = "closed">Closed Cases Only</option>
<option value = "all">All Cases</option>

</select>
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
echo <<<ROWS

<tr  title="Double-Click to View Case" alt="Double-Click to View Case" onmouseover="this.style.color='red';this.style.cursor='pointer'" onmouseout="this.style.color='black';" ondblclick="Effect.Grow('window1');createTargets('window1','window1');sendDataGetAndStripeNoStatus2('cm_cases_single.php?id=$d[id]');document.getElementById('view_chooser').style.display = 'none';return false;"><td>$d[clinic_id]</td><td>$d[first_name]</td><td>$d[last_name]</td><td>$new_date_open</td><td>$new_date_close</td><td>$d[case_type]</td><td>$d[dispo]</td><td>$d[professor]</td>
<td><a href="#" title="Edit this Case" alt="Edit this Case " onClick="createTargets('window1','window1');sendDataGet('new_case_edit.php?id=$d[id]');Effect.Grow('window1');document.getElementById('view_chooser').style.display = 'none';return false;"><img src="images/report_edit.png" border="0"></a></td></tr>
ROWS;


}

?>
</tbody></table></div><script>stripe('display_cases','#fff','#e0e0e0');</script>


</div>

<div id="window1" style="display:none;">


</div>
 
</div>

</body>
</html>
