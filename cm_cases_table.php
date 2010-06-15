<?php
session_start();
include 'db.php';
include './classes/get_names.php';


$sort = $_GET['sort'];

//This script is called from the js function, forcing the query to contain the searchterm when the sort is changed
if ($_GET[force])
    {
    $searchterm = $_GET['sterm'];
    $searchfield = $_GET['sfield'];
    }

        else
            {
                   $searchfield = $_GET['searchfield'];
                   $searchterm = $_GET['searchterm'];
                    }


//Direction of sort
$sortdir = $_GET['sortdir'];

if ($sortdir == "ASC")
{$newsortdir = 'DESC';}
if ($sortdir == "DESC")
{$newsortdir = 'ASC';}
if(!$sortdir)
{$newsortdir = 'ASC';}

//Show open, close, or all cases
$view = $_GET['view'];
//This deals with the situation with admin where there is nothing in the WHERE clause other than the date limiter (i.e., when it is called from "Clear Search Results")
if ($_SESSION['class'] == 'admin'  and empty($searchterm))
{
    if ($view == "closed")
{$limiter = "`date_close` != ''";}
if ($view == "open")
{$limiter = "`date_close` = ''";}
if ($view == "all")
{$limiter = "";}
if (!isset($view))
    {

        $limiter = "AND `date_close` = ''";
}

}
else
//Limiter for all other situations
{
	if ($_SESSION['class'] == 'student')
	{


		if ($view == "closed")
			{$limiter = "AND cm.date_close != ''";}
		if ($view == "open")
			{$limiter = "AND cm.date_close = ''";}
		if ($view == "all")
			{$limiter = "";}
			if (!isset($view))
    			{

        			$limiter = "AND cm.date_close = ''";
				}







		}
	else
	{
if ($view == "closed")
{$limiter = "AND date_close != ''";}
if ($view == "open")
{$limiter = "AND date_close = ''";}
if ($view == "all")
{$limiter = "";}
if (!isset($view))
    {

        $limiter = "AND date_close = ''";
	}
}
}


if (!empty($sort))
{

			if ($_SESSION['class'] == 'student')
			{$choose_sort = "cm." . $sort;}
				else
          		{$choose_sort = $sort;}
}
    else
    {
             if ($_SESSION['class'] == 'student')
             {$choose_sort = "cm.last_name";}
             else
               {$choose_sort = "last_name";}
    }


//If the searchterm is set, we do this set of queries
if (!empty($searchterm))
        {
            switch($_SESSION['class'])
            {
            case 'student':

            //if the searchfield is set, we add the searchfield to the query
            if (!empty($searchfield))
                {

                    $searchfield_mod = "cm.". $searchfield;
                    $result = mysql_query("SELECT cm.*,cm_cases_students.case_id,cm_cases_students.username FROM `cm` , `cm_cases_students` WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' AND $searchfield_mod LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 )  AND cm_cases_students.status = 'active' $limiter ORDER BY $choose_sort $newsortdir");
                         }
                         else
                         {
                //otherwise we do a simple name search on first_name and last_name
            $result = mysql_query("SELECT cm.id,cm.date_open,cm.date_close,cm.first_name,cm.last_name,cm.case_type,cm.professor,cm.dispo,cm_cases_students.case_id,cm_cases_students.username FROM `cm` , `cm_cases_students` WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' AND cm.first_name LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 ) OR cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]' AND cm.last_name LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 )  AND cm_cases_students.status = 'active' $limiter ORDER BY $choose_sort $newsortdir");
        }
			break;

            case 'prof':
            //if searchfield

                     if (!empty($searchfield))
                {
                    $result = mysql_query("SELECT * FROM `cm` WHERE `professor` LIKE '%$_SESSION[login]%' AND `$searchfield` LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 ) $limiter ORDER BY $choose_sort $newsortdir ");
                    }

                else

               {
                //otherwise a simple name search
                $result = mysql_query("SELECT * FROM `cm` WHERE `professor` LIKE '%$_SESSION[login]%' AND `first_name` LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 ) $limiter OR `professor` LIKE '%$_SESSION[login]%' AND `last_name` LIKE '%$searchterm%' $limiter ORDER BY $choose_sort $newsortdir");
               }
               break;

            case 'admin':
            //same thing here
               if (!empty($searchfield))
                {
                    $result = mysql_query("SELECT * FROM `cm`  WHERE `$searchfield` LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 ) $limiter ORDER BY $choose_sort $newsortdir ");

                    }

                else

               {

                $result = mysql_query("SELECT * FROM `cm` WHERE `first_name` LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 ) $limiter OR  `last_name` LIKE '%$searchterm%' $limiter ORDER BY $choose_sort $newsortdir");

               }
               break;

}
}

//if no searchterm is set, we just like all cases, subject to the date_close, date_open limiter ($limiter)
else {
        switch($_SESSION['class'])
            {
            case 'student':

            $result = mysql_query("SELECT cm.id,cm.date_open,cm.date_close,cm.first_name,cm.last_name,cm.case_type,cm.professor,cm.dispo,cm_cases_students.case_id,cm_cases_students.username FROM `cm` , `cm_cases_students` WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username = '$_SESSION[login]'  AND cm_cases_students.status = 'active'  $limiter ORDER BY $choose_sort $newsortdir");
          	break;

            case 'prof':
            $result = mysql_query("SELECT * FROM `cm` WHERE `professor` LIKE '%$_SESSION[login]%' $limiter ORDER BY $choose_sort $newsortdir");
            break;

            case 'admin':
		  		if($limiter == "")
				         	{$result = mysql_query("SELECT * FROM `cm` ORDER BY $choose_sort $newsortdir");}

				else
         					{$result = mysql_query("SELECT * FROM `cm` WHERE  $limiter ORDER BY $choose_sort $newsortdir");}
            break;
        }
    }

//This puts the right number of columns in the header rows
if ($_SESSION['class'] == 'admin')
	{$colspan = "9";}
		else {$colspan = "8";}

echo <<<HEADER
<table id = "display_cases" width="99.5%" style="margin:auto;border:1px dotted black;">
<thead><tr><td colspan="$colspan" style="background:url(images/grade_gray_small.jpg) repeat-x;color:black;"><b>
HEADER;
ECHO mysql_num_rows($result);

ECHO <<<HEADER

</b> $view cases found
HEADER;
if (!empty($searchterm))
{ echo " matching $searchterm";
}



ECHO <<<HEADER
.</td></tr>
<tr>
HEADER;

if ($_SESSION['class'] == 'admin')
{echo "<td><a class=\"theader\" href=\"#\" onClick = \"theSortResults('clinic_id','$newsortdir','$searchterm', '$searchfield');return false;\" title=\"Sort by this column\" alt=\"Sort by this column\" >Case No.</a></td>";}

ECHO <<<HEADER

<td><a class='theader' href="#" onClick = "theSortResults('first_name','$newsortdir','$searchterm', '$searchfield');return false;" title="Sort by this column" alt="Sort by this column" >First Name</td><td><a class='theader' href="#" onClick = "theSortResults('last_name','$newsortdir','$searchterm','$searchfield');return false;" title="Sort by this column" alt="Sort by this column">Last Name</td><td><a class='theader' href="#" onClick = "theSortResults('date_open','$newsortdir','$searchterm', '$searchfield');return false;" title="Sort by this column" alt="Sort by this column">Date Open</td><td><a class='theader' href="#" onClick = "theSortResults('date_close','$newsortdir','$searchterm', '$searchfield');return false;" title="Sort by this column" alt="Sort by this column">Date Close</td><td><a class = 'theader' href="#" onClick = "theSortResults('case_type','$newsortdir','$searchterm', '$searchfield');return false;" title="Sort by this column" alt="Sort by this column">Case Type</a></td><td><a class='theader' href="#" onClick = "theSortResults('dispo','$newsortdir','$searchterm', '$searchfield');return false;"  title="Sort by this column" alt="Sort by this column">Disposition</td><td><a class='theader' href="#" onClick = "theSortResults('professor','$newsortdir','$searchterm', '$searchfield');return false;"  title="Sort by this column" alt="Sort by this column">Professor</td>
HEADER;





if ($_SESSION['class'] != 'student')
{echo "<td></td>";}
echo <<<HEADER
</tr></thead><tbody>
HEADER;


//Now to clear the search results


if (!empty($searchterm))
{
    //Where called from advanced search:

    if(!empty($searchfield))
    {
        echo <<<CLEAR
<tr><td colspan="$colspan"><div id="clearer" style="width:100%;height:20px;background-color:#C3D9FF;text-align:center;"><a href="#" onClick="createTargets('work_space','work_space');sendDataGetAndStripe('cm_cases_table.php?view=open');$('searchterm').style.display = 'none';$('searchterm').value='Enter Search Term';$('searchfield').value='';return false;">Clear Search Results</a></div></td></tr>
CLEAR;

    }
    else
    {
    //Where called from name search
echo <<<CLEAR
<tr><td colspan="$colspan"><div id="clearer" style="width:100%;height:20px;background-color:#C3D9FF;text-align:center;"><a href="#" onClick="createTargets('work_space','work_space');sendDataGetAndStripe('cm_cases_table.php?view=open');$('searchterm').value='Search By Name';$('view_chooser').value='open';return false;">Clear Search Results</a></div></td></tr>
CLEAR;
}

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

<tr title="Click to View Case" alt="Click to View Case"  onmouseover="this.style.color='red';this.style.cursor='pointer'" onmouseout="this.style.color='black';" onClick="Effect.Grow('window1');createTargets('window1','window1');sendDataGetAndStripeNoStatus2('cm_cases_single.php?id=$d[id]');document.getElementById('view_chooser').style.display = 'none';return false;">

ROWS;
IF ($_SESSION['class'] == 'admin')
{
	echo "<td>$d[clinic_id]</td>";

}

echo <<<ROWS
<td>$d[first_name]</td><td>$d[last_name]</td><td>$new_date_open</td><td>$new_date_close</td><td>$d[case_type]</td><td>$d[dispo]</td><td>$prof_str_clip</td>
ROWS;
if ($_SESSION['class'] != 'student')
{
echo <<<EDITER
<td><a class="nobubble" href="#" title="Edit this Case" alt="Edit this Case " onClick="createTargets('window1','window1');sendDataGet('new_case_edit.php?id=$d[id]');Effect.Grow('window1');document.getElementById('view_chooser').style.display = 'none';return false;"><img src="images/report_edit.png" border="0"></a></td>
EDITER;


}


ECHO "</tr>";
//reset the prof string and run through the loop again.
$prof_str = '';

}

if (mysql_num_rows($result) < 1)
{echo "No cases found.";}
else
{
echo <<<JS
</tbody></table>
<script>stripe('display_cases','#fff','#e0e0e0');

$$("a.nobubble").invoke("observe", "click", function(e) {

	Event.stop(e);
})


$$("tr").invoke("observe", "click", function(e) {

	Event.stop(e);
})
</script>
JS;
}
?>

