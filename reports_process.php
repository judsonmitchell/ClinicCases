<?php
session_start();
if (!$_SESSION)
{echo "There is a login problem.  Please login again.";die;}
include 'db.php';

include 'get_name.php';
include_once './classes/format_dates_and_times.class.php';
include './classes/GphpChart.class.php';

/* A function to determine the date intervals in the graph when the user selects a date range */
function find_interval($begin,$end)
{
$begin_unix = strtotime($begin);
$end_unix = strtotime($end);
$diff = $end_unix - $begin_unix;
$one_day = "86400";

if (($diff / $one_day) < 14)
{$interval = "day";}

elseif (($diff / $one_day) > 14 AND ($diff / $one_day) < 60)
{$interval = "weeks";}

elseif (($diff / $one_day) > 60)
{$interval = "months";}
return $interval;

}

function convert_time_array($list)
{
/* Time list will look like this: 5400,6900,13500,9900,3000,14700,13500,7800,3900,900, */
foreach ($list as $part)
{
$ary = formatTime($part);
$ary2 = $ary[0] . $ary[1] . "|";

$dingo2 .=  $ary2;
$dingo = "|" .  substr($dingo2, 0, -1);

/* INCOMPLETE : CONVERT TIMES AND PUT THEM INTO A COMMA SEPERATED LIST, STRIP LAST COMMA, RETURN TEXT STRING */
}




return $dingo;

}

function put_times_in_array($list)
{
foreach ($list as $part)
{
$tt = formatTime($part);
$tt2[] = $tt[0] . $tt[1];

}
return $tt2;	
}

/* This scales values to 100 so that nothing will go off chart on Google Charts */
function scaleValues($array)
{
$maxval = max($array);
foreach ($array as $value)
{
$target = ($value / $maxval) * 100;
$array_conv[] = $target;
}	
return $array_conv;
}


function array_combine_custom($arr1,$arr2) {
   $out = array();
   foreach($arr1 as $key1 => $value1)    {
    $out[$value1] = $arr2[$key1];
   }
   return $out;
} 
/* Date Declarations */
$date = date('Y-m-d H:i:s');
$start_of_today = date('Y-m-d 00:00:00');
$this_week = strtotime("last Sunday");
$this_week_cnv = date('Y-m-d H:i:s',$this_week);
$lastweek = strtotime("-1 week");
$lastweek_cnv = date('Y-m-d H:i:s',$lastweek);
$this_month_cnv = date('Y-m-01 00:00:00',strtotime("this month")); 
$a = strtotime($_POST[start_date]);
$b = strtotime($_POST[end_date]);
$start_range = date('Y-m-d H:i:s',$a);
$end_range = date('Y-m-d H:i:s',$b);

/* Define Date Variables */
switch($_POST[date_range2]){
case "this_week":
$begin = $this_week_cnv;$end = $date;
break;
case "this_month":
$begin = $this_month_cnv;$end = $date;
break;
case "today":
$begin = $start_of_today;$end = $date;
break;
case "date_select":
$begin = $start_range;$end = $end_range;
break;
}


/* Construct Query */

switch($_POST[type]){
case "single_student":
$sql = "SELECT `username`, `date`, SUM(time)  AS `numtotal` FROM `cm_case_notes` WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_POST[student]' GROUP BY `username`";
break;
case "all_students":
$sql =  "SELECT `username`, `date`, SUM(time) AS `numtotal` FROM `cm_case_notes` WHERE `date` BETWEEN '$begin' AND '$end' AND `prof` LIKE '%$_SESSION[login]%' GROUP BY `username` ORDER BY `numtotal` DESC";
break;
case "student_case":
$sql = "students by case";
break;
case "student_time";
$sql = "SELECT `username`, `date`, SUM(time) AS `numtotal` FROM `cm_case_notes` WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_SESSION[login]' GROUP BY `username";
break;
}

$query = mysql_query($sql);

echo "<div style='margin-left:30px;'><h3>Results</h3></div>
<table width='50%' align=left border=0 style='margin-left:30px;margin-top:30px;'><tr><td valign='top'>";

while ($r = mysql_fetch_array($query))
{
$namer = getNameAsVar2($r[username]);
echo "<strong>$namer[0] $namer[1]</strong> total time:";
$time_result = formatTime($r["numtotal"]);
echo "$time_result[0] $time_result[1]<br>";

/*This for the graph */
$timer = $r["numtotal"];
$users_array[] = $r[username];
$times_array[] = $timer;

}

if (mysql_num_rows($query)<1)
{echo "No data found.";
echo <<<RESTART
<tr><td align='left'><input type="button" value="New Query" onClick="location.href='cm_utilities.php?reports_force=1';"></td></table>
<br>
 
RESTART;
die;

;}

echo "</td><td>";

if ($_POST[graphs] == 'on')
{
switch($_POST[type]){

case "single_student":
	if ($_POST[date_range2] == "this_week")
	{$graph_query = mysql_query("SELECT DAYNAME(date), SUM(time) AS `numtotal` from cm_case_notes WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_POST[student]' GROUP BY DAYNAME(date) ORDER BY `date` asc");
		while ($zz = mysql_fetch_array($graph_query))
		{
		$day_z = $zz["DAYNAME(date)"];
		$day_list[] = "$day_z";
		$time_z = $zz["numtotal"];
		$time_list[] = "$time_z,";
		}
		$time_list_conv = scaleValues($time_list);
		$my_array = array_combine_custom($day_list,$time_list_conv);
		$time_array = put_times_in_array($time_list);
		$begin_fmt = formatDate2($begin);
		$end_fmt = formatDate2($end);
		$full_name = getNameAsVar($_POST[student]);
		$name = $full_name[0] . ' ' . $full_name[1];
			 $GphpChart = new GphpChart('bvs','t'); // 'lc' stands for a line chart 
			 $GphpChart->title = "$name this week:<br> $begin_fmt to $end_fmt"; // this title will be on the chart image
			 $GphpChart->add_data(array_values($my_array)); // adding values
			 $GphpChart->add_labels('x',array_keys($my_array)); // adding x labels (horizontal axis)
			 $GphpChart->add_labels('x',array_values($time_array)); // adding y labels (left axis)
			$GphpChart->width = 800;
			  $GphpChart->set_bar_width(60,2);
			 echo $GphpChart->get_Image_String(); // and showing the image
			 
			 echo "</td></tr>";
	}
	elseif ($_POST[date_range2] == "this_month")
	{$graph_query = mysql_query("SELECT DAYOFMONTH(date), SUM(time) AS `numtotal` from cm_case_notes WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_POST[student]' GROUP BY DAYOFMONTH(date)");
	while ($zz = mysql_fetch_array($graph_query))
		{
		$day_z2 = $zz["DAYOFMONTH(date)"];
		$day_list2[] = "$day_z2";
		$time_z2 = $zz["numtotal"];
		$time_list2[] = "$time_z2,";
		}
		$time_list_conv2 = scaleValues($time_list2);
		$my_array2 = array_combine_custom($day_list2,$time_list_conv2);
		$time_array2 = put_times_in_array($time_list2);
		$begin_fmt2 = formatDate2($begin);
		$end_fmt2 = formatDate2($end);
		$full_name2 = getNameAsVar($_POST[student]);
		$name2 = $full_name2[0] . ' ' . $full_name2[1];
		 $GphpChart = new GphpChart('bvs','t'); // 'lc' stands for a line chart 
			 $GphpChart->title = "$name2 this month:<br> $begin_fmt2 to $end_fmt2"; // this title will be on the chart image
			 $GphpChart->add_data(array_values($my_array2)); // adding values
			 $GphpChart->add_labels('x',array_keys($my_array2)); // adding x labels (horizontal axis)
			 $GphpChart->add_labels('x',array_values($time_array2)); // adding y labels (left axis)
			
			 $GphpChart->width = 600;
			  $GphpChart->set_bar_width(60,2);
			   $GphpChart->height= 400;
			 echo $GphpChart->get_Image_String();
			 echo "</td></tr>";	
	}
	elseif ($_POST[date_range2] == "date_select")
	{
	$interv = find_interval($begin,$end);
		if ($interv == "day")
		{
	
		$graph_query = mysql_query("SELECT DAYNAME(date), SUM(time) AS `numtotal` from cm_case_notes WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_POST[student]' GROUP BY DAYNAME(date) ORDER BY MONTH(date) ASC");
	while ($zz = mysql_fetch_array($graph_query))
		{
		$int_z = $zz["DAYNAME(date)"];
		$int_list[] = "$int_z";
		$time_z = $zz["numtotal"];
		$time_list3[] = "$time_z,";
		}
		$time_list_conv3 = scaleValues($time_list3);
		$my_array3 = array_combine_custom($int_list,$time_list_conv3);
		$time_array3 = put_times_in_array($time_list3);
		$begin_fmt3 = formatDate2($begin);
		$end_fmt3 = formatDate2($end);
		$full_name3 = getNameAsVar($_POST[student]);
		$name3 = $full_name3[0] . ' ' . $full_name3[1];
		$GphpChart = new GphpChart('bvs','t'); // 'lc' stands for a line chart 
		$GphpChart->title = "$name3: <br>$begin_fmt3 to $end_fmt3"; // this title will be on the chart image
			 $GphpChart->add_data(array_values($my_array3)); // adding values
			 $GphpChart->add_labels('x',array_keys($my_array3)); // adding x labels (horizontal axis)
			 $GphpChart->add_labels('x',array_values($time_array3)); // adding y labels (left axis)
			
			 $GphpChart->width = 600;
			  $GphpChart->set_bar_width(60,2);
			   $GphpChart->height= 400;
			 echo $GphpChart->get_Image_String();
			 echo "</td></tr>";	
	
		}
		
		elseif ($interv == "weeks")
		{
	
		$graph_query = mysql_query("SELECT WEEKOFYEAR(date), SUM(time) AS `numtotal` from cm_case_notes WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_POST[student]' GROUP BY WEEKOFYEAR(date)");
	while ($zz = mysql_fetch_array($graph_query))
		{
		$int_z = $zz["WEEKOFYEAR(date)"];
		$int_list2[] = "$int_z";
		$time_z = $zz["numtotal"];
		$time_list4[] = "$time_z,";
		}
		$time_list_conv4 = scaleValues($time_list4);
		$my_array4 = array_combine_custom($int_list2,$time_list_conv4);
		$time_array4 = put_times_in_array($time_list4);
		$begin_fmt4 = formatDate2($begin);
		$end_fmt4 = formatDate2($end);
		$full_name4 = getNameAsVar($_POST[student]);
		$name4 = $full_name4[0] . ' ' . $full_name4[1];
		$GphpChart = new GphpChart('bvs','t'); 
		$GphpChart->title = "$name4: <br>$begin_fmt4 to $end_fmt4";
			 $GphpChart->add_data(array_values($my_array4)); 
			 $GphpChart->add_labels('x',array_keys($my_array4)); 
			 $GphpChart->add_labels('x',array_values($time_array4)); 
			
			$GphpChart->width = 600;
			  $GphpChart->set_bar_width(60,2);
			   $GphpChart->height= 400;
			 echo $GphpChart->get_Image_String();
			 echo "</td></tr>";	
		
		}
		
		elseif ($interv == "months")
		{
	
		$graph_query = mysql_query("SELECT MONTHNAME(date), SUM(time) AS `numtotal`, MONTH(date) from cm_case_notes WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_POST[student]' GROUP BY MONTHNAME(date) ORDER BY MONTH(date) ASC");
	while ($zz = mysql_fetch_array($graph_query))
		{
		$int_z = $zz["MONTHNAME(date)"];
		$int_list[] = "$int_z";
		$time_z = $zz["numtotal"];
		$time_list[] = "$time_z,";
		}
		$time_list_conv = scaleValues($time_list);
		$my_array = array_combine_custom($int_list,$time_list_conv);
		$time_array = put_times_in_array($time_list);
		$begin_fmt = formatDate2($begin);
		$end_fmt = formatDate2($end);
		$full_name = getNameAsVar($_POST[student]);
		$name = $full_name[0] . ' ' . $full_name[1];
		$GphpChart = new GphpChart('bvs','t'); // 'lc' stands for a line chart 
		$GphpChart->title = "$name: <br>$begin_fmt to $end_fmt"; // this title will be on the chart image
			 $GphpChart->add_data(array_values($my_array)); // adding values
			 $GphpChart->add_labels('x',array_keys($my_array)); // adding x labels (horizontal axis)
			 $GphpChart->add_labels('x',array_values($time_array)); // adding y labels (left axis)
			
			 $GphpChart->width = 600;
			  $GphpChart->set_bar_width(60,2);
			   $GphpChart->height= 400;
			 echo $GphpChart->get_Image_String();
			 echo "</td></tr>";	
	
		}
	
	}
	break;


case "all_students":
	$total_time = array_sum($times_array);
	$time_list_conv = scaleValues($times_array);
	foreach ($users_array as $full)
	{
		$get_last = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$full' LIMIT 1");
		$r = mysql_fetch_array($get_last);
	$users_array_full[] = $r['last_name'];
	}	
		
	$my_array = array_combine_custom($users_array_full,$time_list_conv);
$begin_fmt = formatDate2($begin);
		$end_fmt = formatDate2($end);
		$GphpChart = new GphpChart('p3','t'); 
		$GphpChart->title = "All Your Students:<br>$begin_fmt to $end_fmt"; 
		$GphpChart->add_data(array_values($my_array)); 
		$GphpChart->add_labels('x',array_keys($my_array));
		$GphpChart->add_labels('y',array_values($times_array));
		$GphpChart->width = 400;
		
			   echo "<span style='float:right;'>";
			   echo $GphpChart->get_Image_String();
			 echo "</span></td></tr>";	

break;
	

case "student_time":
	if ($_POST[date_range2] == "this_week")
	{
		$graph_query = mysql_query("SELECT DAYNAME(date), SUM(time) AS `numtotal` from `cm_case_notes` WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_SESSION[login]' GROUP BY DAYNAME(date) ORDER BY `date` asc");
		
		while ($zz = mysql_fetch_array($graph_query))
		{
			$day_z = $zz["DAYNAME(date)"];
			$day_list[] = "$day_z";
			$time_z = $zz["numtotal"];
			$time_list[] = "$time_z";
			
			}
		
		$time_list_conv = scaleValues($time_list);
		$my_array = array_combine_custom($day_list,$time_list_conv);
		$time_array = put_times_in_array($time_list);
		$begin_fmt = formatDate2($begin);
		$end_fmt = formatDate2($end);
		$full_name = getNameAsVar($_SESSION[login]);
		$name = $full_name[0] . ' ' . $full_name[1];
			 $GphpChart = new GphpChart('bvs','t'); // 'lc' stands for a line chart 
			 $GphpChart->title = "$name<br>this week:<br>$begin_fmt to $end_fmt"; // this title will be on the chart image
			 $GphpChart->add_data(array_values($my_array)); // adding values
			 $GphpChart->add_labels('x',array_keys($my_array)); // adding x labels (horizontal axis)
			 $GphpChart->add_labels('x',array_values($time_array)); // adding y labels (left axis)
			$GphpChart->width = 800;
			  $GphpChart->set_bar_width(60,2);
			 echo $GphpChart->get_Image_String(); // and showing the image
			 
			 echo "</td></tr>";
	}
	elseif ($_POST[date_range2] == "this_month")
	{$graph_query = mysql_query("SELECT DAYOFMONTH(date), SUM(time) AS `numtotal` from cm_case_notes WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_SESSION[login]' GROUP BY DAYOFMONTH(date)");
	while ($zz = mysql_fetch_array($graph_query))
		{
		$day_z2 = $zz["DAYOFMONTH(date)"];
		$day_list2[] = "$day_z2";
		$time_z2 = $zz["numtotal"];
		$time_list2[] = "$time_z2";
		}
		$time_list_conv2 = scaleValues($time_list2);
		$my_array2 = array_combine_custom($day_list2,$time_list_conv2);
		$time_array2 = put_times_in_array($time_list2);
		$begin_fmt2 = formatDate2($begin);
		$end_fmt2 = formatDate2($end);
		$full_name2 = getNameAsVar($_SESSION[login]);
		$name2 = $full_name2[0] . ' ' . $full_name2[1];
		 $GphpChart = new GphpChart('bvs','t'); // 'lc' stands for a line chart 
			 $GphpChart->title = "$name2 this month:<br> $begin_fmt2 to $end_fmt2"; // this title will be on the chart image
			 $GphpChart->add_data(array_values($my_array2)); // adding values
			 $GphpChart->add_labels('x',array_keys($my_array2)); // adding x labels (horizontal axis)
			 $GphpChart->add_labels('x',array_values($time_array2)); // adding y labels (left axis)
			
			 $GphpChart->width = 1000;
			  $GphpChart->set_bar_width(60,2);
			   $GphpChart->height= 400;
			 echo $GphpChart->get_Image_String();
			 echo "</td></tr>";	
	}
	elseif ($_POST[date_range2] == "date_select")
	{
	$interv = find_interval($begin,$end);
		if ($interv == "day")
		{
	
		$graph_query = mysql_query("SELECT DAYNAME(date), SUM(time) AS `numtotal` from cm_case_notes WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_SESSION[login]' GROUP BY DAYNAME(date) ORDER BY MONTH(date) ASC");
	while ($zz = mysql_fetch_array($graph_query))
		{
		$int_z = $zz["DAYNAME(date)"];
		$int_list[] = "$int_z";
		$time_z = $zz["numtotal"];
		$time_list3[] = "$time_z,";
		}
		$time_list_conv3 = scaleValues($time_list3);
		$my_array3 = array_combine_custom($int_list,$time_list_conv3);
		$time_array3 = put_times_in_array($time_list3);
		$begin_fmt3 = formatDate2($begin);
		$end_fmt3 = formatDate2($end);
		$full_name3 = getNameAsVar($_SESSION[login]);
		$name3 = $full_name3[0] . ' ' . $full_name3[1];
		$GphpChart = new GphpChart('bvs','t'); // 'lc' stands for a line chart 
		$GphpChart->title = "$name3:<br>$begin_fmt3 to $end_fmt3"; // this title will be on the chart image
			 $GphpChart->add_data(array_values($my_array3)); // adding values
			 $GphpChart->add_labels('x',array_keys($my_array3)); // adding x labels (horizontal axis)
			 $GphpChart->add_labels('x',array_values($time_array3)); // adding y labels (left axis)
			
			 $GphpChart->width = 600;
			  $GphpChart->set_bar_width(60,2);
			   $GphpChart->height= 400;
			 echo $GphpChart->get_Image_String();
			 echo "</td></tr>";	
	
		}
		
		elseif ($interv == "weeks")
		{
	
		$graph_query = mysql_query("SELECT WEEKOFYEAR(date), SUM(time) AS `numtotal` from cm_case_notes WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_SESSION[login]' GROUP BY WEEKOFYEAR(date)");
	while ($zz = mysql_fetch_array($graph_query))
		{
		$int_z = $zz["WEEKOFYEAR(date)"];
		$int_list2[] = "$int_z";
		$time_z = $zz["numtotal"];
		$time_list4[] = "$time_z,";
		}
		$time_list_conv4 = scaleValues($time_list4);
		$my_array4 = array_combine_custom($int_list2,$time_list_conv4);
		$time_array4 = put_times_in_array($time_list4);
		$begin_fmt4 = formatDate2($begin);
		$end_fmt4 = formatDate2($end);
		$full_name4 = getNameAsVar($_SESSION[login]);
		$name4 = $full_name4[0] . ' ' . $full_name4[1];
		$GphpChart = new GphpChart('bvs','t'); 
		$GphpChart->title = "$name4:<br>$begin_fmt4 to $end_fmt4";
			 $GphpChart->add_data(array_values($my_array4)); 
			 $GphpChart->add_labels('x',array_keys($my_array4)); 
			 $GphpChart->add_labels('x',array_values($time_array4)); 
			
			$GphpChart->width = 800;
			  $GphpChart->set_bar_width(60,2);
			   $GphpChart->height= 400;
			 echo $GphpChart->get_Image_String();
			 echo "</td></tr>";	
		
		}
		
		elseif ($interv == "months")
		{
	
		$graph_query = mysql_query("SELECT MONTHNAME(date), SUM(time) AS `numtotal`, MONTH(date) from cm_case_notes WHERE `date` BETWEEN '$begin' AND '$end' AND `username` = '$_SESSION[login]' GROUP BY MONTHNAME(date) ORDER BY MONTH(date) ASC");
	while ($zz = mysql_fetch_array($graph_query))
		{
		$int_z = $zz["MONTHNAME(date)"];
		$int_list[] = "$int_z";
		$time_z = $zz["numtotal"];
		$time_list[] = "$time_z,";
		}
		$time_list_conv = scaleValues($time_list);
		$my_array = array_combine_custom($int_list,$time_list_conv);
		$time_array = put_times_in_array($time_list);
		$begin_fmt = formatDate2($begin);
		$end_fmt = formatDate2($end);
		$full_name = getNameAsVar($_SESSION[login]);
		$name = $full_name[0] . ' ' . $full_name[1];
		$GphpChart = new GphpChart('bvs','t'); // 'lc' stands for a line chart 
		$GphpChart->title = "$name:<br>$begin_fmt to $end_fmt"; // this title will be on the chart image
			 $GphpChart->add_data(array_values($my_array)); // adding values
			 $GphpChart->add_labels('x',array_keys($my_array)); // adding x labels (horizontal axis)
			 $GphpChart->add_labels('x',array_values($time_array)); // adding y labels (left axis)
			
			 $GphpChart->width = 600;
			  $GphpChart->set_bar_width(60,2);
			   $GphpChart->height= 400;
			 echo $GphpChart->get_Image_String();
			 echo "</td></tr>";	
	
		}
	
	}

break;

case "student_case":
	echo "students by case";
break;	

}
}

echo <<<RESTART
<tr><td align='left'><input type="button" value="New Query" onClick="location.href='cm_utilities.php?reports_force=1';"></td></table>
<br>
 
RESTART;
//$interv2 = find_interval($begin,$end);
//echo $interv2;
?>
