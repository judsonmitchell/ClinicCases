<?php
session_start();
include 'db.php';
$view = $_GET['view'];
$sort = $_GET['sort'];
$searchterm = $_GET['searchterm'];
$sortdir = $_GET['sortdir'];

if ($sortdir == "ASC")
{$newsortdir = 'DESC';}
if ($sortdir == "DESC")
{$newsortdir = 'ASC';}
if(!$sortdir)
{$newsortdir = 'ASC';}

if ($view == "closed")
{$limiter = "AND `date_close` != ''";}
if ($view == "open")
{$limiter = "AND `date_close` = ''";}
if ($view == "all") 
{$limiter = "";}
if (!isset($view))
{$limiter = "AND `date_close` = ''";}

if (isset($sort))
{
$choose_sort = $sort;

}
else
{
$choose_sort = "last_name";
}

/* NOTE the LIKE operator below.  Will it cause problems if one prof is jdingo and another professor is jdingi? THE LIKE IS HERE SO THAT THE WILDCARD OPERATOR WILL WORK! */
if (isset($searchterm))
{
if ($_SESSION['class'] == 'student')
{
$result = mysql_query("SELECT cm.id,cm.date_open,cm.date_close,cm.first_name,cm.last_name,cm.case_type,cm.professor,cm.dispo,cm_cases_students.case_id,cm_cases_students.username FROM `cm` , `cm_cases_students` WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' AND cm.first_name LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 ) OR cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' AND cm.last_name LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 )");
}
else
{
$result = mysql_query("SELECT * FROM `cm` WHERE `professor` LIKE '$_SESSION[login]' AND `first_name` LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 ) OR `professor` LIKE '$_SESSION[login]' AND `last_name` LIKE '%$searchterm%' OR `professor2` LIKE '$_SESSION[login]' AND `first_name` LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 ) OR `professor2` LIKE '$_SESSION[login]' AND `last_name` LIKE '%$searchterm%' ORDER BY `last_name` ");}
}
elseif ($_SESSION['class'] == 'student')
{
$result = mysql_query("SELECT cm.id,cm.date_open,cm.date_close,cm.first_name,cm.last_name,cm.case_type,cm.professor,cm.dispo,cm_cases_students.case_id,cm_cases_students.username FROM `cm` , `cm_cases_students` WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' $limiter ORDER BY cm.last_name $newsortdir");
}
else
{
$result = mysql_query("SELECT * FROM `cm` WHERE `professor` = '$_SESSION[login]' $limiter OR `professor2` = '$_SESSION[login]' $limiter ORDER BY `$choose_sort` $newsortdir");
}

echo <<<HEADER
<table id = "display_cases" width="99.5%" style="margin:auto;border:1px dotted black;">
<thead><tr><td colspan="8" style="background:url(images/grade_gray_small.jpg) repeat-x;color:black;"><b>
HEADER;
ECHO mysql_num_rows($result);
ECHO <<<HEADER

</b> cases found.</td></tr><tr><td><a class='theader' href="#" onClick = "theSort('first_name','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column" >First Name</td><td><a class='theader' href="#" onClick = "theSort('last_name','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column">Last Name</td><td><a class='theader' href="#" onClick = "theSort('date_open','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column">Date Open</td><td><a class='theader' href="#" onClick = "theSort('date_close','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column">Date Close</td><td><a class = 'theader' href="#" onClick = "theSort('case_type','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column">Case Type</a></td><td><a class='theader' href="#" onClick = "theSort('dispo','$newsortdir');return false;"  title="Sort by this column" alt="Sort by this column">Disposition</td><td><a class='theader' href="#" onClick = "theSort('professor','$newsortdir');return false;"  title="Sort by this column" alt="Sort by this column">Professor</td>
HEADER;
if ($_SESSION['class'] != 'student')
{echo "<td></td>";}
echo <<<HEADER
</tr></thead><tbody>
HEADER;


if (isset($searchterm))
{
echo <<<CLEAR
<tr><td colspan="8"><div id="clearer" style="width:100%;height:20px;background-color:#C3D9FF;text-align:center;"><a href="#" onClick="createTargets('work_space','work_space');sendDataGetAndStripe('cm_cases_table.php?view=open');">Clear Search Results</a></div></td></tr>
CLEAR;

}

while ($d = mysql_fetch_array($result)) {
    

$get_date_open = explode('-',$d[date_open]);
$month = $get_date_open[1];
$day = $get_date_open[2];
$year = $get_date_open[0];
$new_date_open = "$month" . "/" . "$day" . "/" . "$year";

if (!empty($d[date_close]))
{$get_date_close = explode('-',$d[date_close]);
$month_c = $get_date_close[1];
$day_c = $get_date_close[2];
$year_c = $get_date_close[0];
$new_date_close = "$month_c" . "/" . "$day_c" . "/" . "$year_c";}
else
{$new_date_close = "";}


echo <<<ROWS

<tr title="Double-Click to View Case" alt="Double-Click to View Case"  onmouseover="this.style.color='red';this.style.cursor='pointer'" onmouseout="this.style.color='black';" ondblclick="Effect.Grow('window1');createTargets('window1','window1');sendDataGetAndStripeNoStatus('cm_cases_single.php?id=$d[id]');document.getElementById('view_chooser').style.display = 'none';return false;"><td>$d[first_name]</td><td>$d[last_name]</td><td>$new_date_open</td><td>$new_date_close</td><td>$d[case_type]</td><td>$d[dispo]</td><td>$d[professor]</td>
ROWS;
if ($_SESSION['class'] != 'student')
{
echo <<<EDITER
<td><a href="#" title="Edit this Case" alt="Edit this Case " onClick="createTargets('window1','window1');sendDataGet('new_case_edit.php?id=$d[id]');Effect.Grow('window1');document.getElementById('view_chooser').style.display = 'none';return false;"><img src="images/report_edit.png" border="0"></a></td>
EDITER;


}


ECHO "</tr>";


}

if (mysql_num_rows($result) < 1)
{echo "No cases found.";}
else
{
echo "</tbody></table>

";}
?> 

