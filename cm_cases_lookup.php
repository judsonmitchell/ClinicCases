<?php
session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');
die;}
include 'db.php';

//This powers the autocomplete for advanced searches on the cases page

$view = $_GET['view'];
if ($view == "closed")
{$limiter = "AND `date_close` != ''";}
if ($view == "open")
{$limiter = "AND `date_close` = ''";}
if ($view == "all")
{$limiter = "";}
if (!isset($view))
{$limiter = "AND `date_close` = ''";}


if ($_SESSION['class'] == 'student')
    {
    $field = "cm." . $_GET['searchfield'];
    }
        else
            {
            $field = $_GET['searchfield'];
            }
$searchterm = $_GET['searchterm'];
switch ($_SESSION['class']){

    case "prof":
    $query = mysql_query("SELECT DISTINCT $field as target FROM cm WHERE $field LIKE '%$searchterm%' AND `professor` LIKE'%$_SESSION[login]%' $limiter");
    break;

    case "admin":
    $query = mysql_query("SELECT DISTINCT $field as target FROM cm WHERE $field LIKE '%$searchterm%' $limiter");
    break;

    case "student":
    $query = mysql_query("SELECT DISTINCT $field as target, cm.id,cm_cases_students.case_id,cm_cases_students.username FROM `cm`, `cm_cases_students` WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' AND $field LIKE '%$searchterm%' $limiter");
    break;
}


echo "<ul>";
while ($r=mysql_fetch_array($query))
{
    echo "<li >$r[target]</li>";
}
if (mysql_num_rows($query)<1)
	{echo "<li>No matching terms found</li>";}
echo "</ul>";


?>
