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
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="./javascripts/table_stripe.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script src="./javascripts/print.js" type="text/javascript"></script>

<script>

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
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
<body>
<div id = "bug" style="display:none;">
</div>

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
   <li><a href="cm_board.php"><span id="tab4">Board</span></a></li>

  <li id="current"><a href="cm_utilities.php"><span id="tab5">Utilities</span></a></li>

    <li><a href="cm_preferences.php"><span id="tab6">Prefs</span></a></li>

  </ul>

</div>
<?php include 'cm_menus.php';?>
</div>

<div id="content"><div class = "box_menu">
<p style="margin-left:15px;"><a href="#" onClick = "Effect.Grow('window1');new Ajax.Updater('window1', 'reports.php', {evalScripts:true,method:'get'});return false;">Reports</a> | <a href="#" onClick = "Effect.Grow('window1');new Ajax.Updater('window1', 'casenote_noncase.php', {evalScripts:true,method:'get'});return false;"> Manage Non-Case Time  </a> |</p></div>

<div id="window1" style="display:none;">
</div>

</div><script type="text/JavaScript" src="javascripts/scw.js"></script>

<?php
if ($_GET[reports_force])
echo<<<rf
<script>
new Ajax.Updater('window1','reports.php',{evalScripts:true,method:'get'});
$('window1').setStyle({display:'block'})

</script>

rf;

?>
</body>
</html>
