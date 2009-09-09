<?php 
session_start();
include 'db.php';
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
 ?>
<html>
<head>
<title>Utilities - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet"  href="cm_tabs.css" type="text/css">
<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="./javascripts/table_stripe.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script src="./javascripts/print.js" type="text/javascript"></script>





<script>

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php?sid=<?php echo $_COOKIE[PHPSESSID]; ?>', {
    method: 'get',
    frequency: 300
  });

function deleteCaseNote(theId,theTarget,theCase)
{

 var where_to= confirm("Do you really want to delete?");
 if (where_to== true)
 {
createTargets(theTarget,theTarget);sendDataGetAndStripe2('casenote_delete.php?nc=y&id='+ theId + '&case_id=' + theCase); return true;
 }
 else
 {
return false;
}}

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

function changeTab(chosen)
{
  var hrefs = new Array()
  hrefs[0] = 'one_href';
  hrefs[1] = 'two_href';
 
  var i= 0;
   for(i = 0; i < hrefs.length; i++)
     {
        var ident = hrefs[i] ;    
        var dingo = document.getElementById(ident);
              if (ident !== chosen)
              {
            
                dingo.style.textDecoration = 'underline';
                dingo.style.color = '#d2d2d2';
        	dingo.style.fontWeight = 'normal';
		dingo.style.background = 'white';
              }
              else if (ident == chosen)
              {
        dingo.style.textDecoration = 'none';
        dingo.style.color = 'black';
        dingo.style.fontWeight = 'bold';
	dingo.style.background = 'rgb(195, 217, 255)';
              }
      }
}



function router(target)
{

if (target == 'single_student')
{document.getElementById('student_choose').style.display='block';}

if (target == 'all_students')
{document.getElementById('date_choose2').style.display='block';}

if (target == 'date_choose2')
{document.getElementById('date_choose2').style.display='block';}

if (target == 'student_time')
{document.getElementById('date_choose2').style.display='block';}


}
</script>
</head>
<body class=" yui-skin-sam">
<div id = "bug" style="display:none;">
</div>
<?php include 'cm_menus.php';?>
<div id = "nav_container">
<div id="header">

  <ul>
    <li><a href="cm_home.php"><span id="tab1">At A Glance</span></a></li>
         <?php 
if ($_SESSION['pref_case'] == 'on')
{ 
echo "<li><a href=\"cm_cases.php\"><span id=\"tab2\">Cases</span></a></li>";
}

if ($_SESSION['pref_journal'] == 'on')
{ 
echo "<li><a href=\"cm_journals.php\"><span id=\"tab2\">Journals</span></a></li>";
}
if ($_SESSION['class'] == 'prof')
{echo "<li><a href=\"cm_students.php\"><span id=\"tab3\">Students</span></a></li>";}
?>
  <li id="current"><a href="cm_utilities.php"><span id="tab5">Utilities</span></a></li>

    <li><a href="cm_preferences.php"><span id="tab6">Preferences</span></a></li>

  </ul>

</div>

</div>

<div id="content" style="background-color:rgb(255,255,204);">

<div id = "single_menu_utilities">
<table width="96%"  border=0>
<tr>
<td align="left" width="50%" valign="bottom">
<a class="singlenav"  style="text-decoration:none;color:black;font-weight:bold;background-color:rgb(195, 217, 255);" id="one_href" href="#" onClick="createTargets('utilities_work','utilities_work');sendDataGetAndStripe2('reports.php?&ieyousuck=' + Math.random()*5);changeTab('one_href');return false;">Reports</a> | <a class="singlenav"  style="color:#d2d2d2;" id="two_href" href="#" onClick="createTargets('utilities_work','utilities_work');sendDataGet('casenote_noncase.php');changeTab('two_href');return false;">Manage Non-Case Time</a> | 
</td>
<td width="50%" align="right" valign="top" >
<a alt="Print this data" title="Print this Data" href="#" onClick="printDiv('utilities_work');return false;"><img src="images/print_small.png" border="0" class="print_image"></a>
</td>
</tr>
</table>
</div>
<div id="utilities_work">
<?php include 'reports.php';?>
</div>


<script type="text/JavaScript" src="javascripts/scw.js"></script> 

</body>
</html>
