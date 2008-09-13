<?php
session_start();
if (!$_SESSION)
{header('Location: index.php');die;}
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





if ($view == "inactive")
{$limiter = "WHERE `status` = 'inactive'";}
if ($view == "active")
{$limiter = "WHERE `status` != 'inactive'";}
if ($view == 'all') 
{$limiter = '';}
if (!isset($view))
{$limiter = "WHERE `status` != 'inactive'";}





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


$result = mysql_query("SELECT * FROM `cm_users` WHERE `first_name` LIKE CONVERT( _utf8 '%$searchterm%' USING latin1 ) OR `last_name` LIKE '%$searchterm%' ORDER BY `last_name`");
}

else
{
$result = mysql_query("SELECT * FROM `cm_users`  $limiter ORDER BY `$choose_sort` $newsortdir");
}


ECHO <<<HEADER
<table id = "display_cases" width="99.5%" style="margin:auto;border:1px dotted black;">

<thead><tr><td colspan="9" style="background:url(images/grade_gray_small.jpg) repeat-x;color:black;"><b>
HEADER;
echo mysql_num_rows($result);
ECHO <<<HEADER
</b> $_GET[view] users found.</td></tr><tr><td>Face</td><td><a class='theader' href="#" onClick = "theSort('last_name','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column" >Last Name</td><td><a class='theader' href="#" onClick = "theSort('first_name','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column" >First Name</td><td><a class='theader' href="#" onClick = "theSort('class','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column">Group</td><td><a class='theader' href="#" onClick = "theSort('date_created','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column">Date Added</td><td><a class='theader' href="#" onClick = "theSort('status','$newsortdir');return false;" title="Sort by this column" alt="Sort by this column">Status</a></td></td><td></td></tr></thead><tbody>
HEADER;



if (isset($searchterm))
{
echo <<<CLEAR
<tr><td colspan="8"><div id="clearer" style="width:100%;height:20px;background-color:#C3D9FF;text-align:center;"><a href="cm_admin_users">Clear Search Results</a></div></td></tr>
CLEAR;

}

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($result,$i);
        $d[$field] = $col_value;
        $i++;

    }
$kill_time = explode(' ',$d[date_created]);
$get_date_open = explode('-',$kill_time[0]);
$month = $get_date_open[1];
$day = $get_date_open[2];
$year = $get_date_open[0];
$new_date_created = "$month" . "/" . "$day" . "/" . "$year";


echo <<<ROWS

<tr  title="Double-Click to View/Edit User" alt="Double-Click to View/Edit User" onmouseover="this.style.color='red';this.style.cursor='pointer'" onmouseout="this.style.color='black';" ondblclick="Effect.Grow('window1');createTargets('window1','window1');sendDataGetAndStripeNoStatus2('cm_users_view.php?id=$d[id]');document.getElementById('view_chooser').style.display = 'none';return false;"><td><img src="$d[picture_url]" height=35 width=35></td><td>$d[last_name]</td><td>$d[first_name]</td><td>$d[class]</td><td>$new_date_created</td><td id="deac$d[id]">$d[status]</td>
<td ><a href="#" onClick="createTargets('deac$d[id]','deac$d[id]');sendDataGet('user_change_status.php?id=$d[id]');return false;" title="Click this to either activate an inactive user or inactivate an active user" alt="Click this to either activate an inactive user or inactivate an active user">Change Status</a></td></td></tr>
ROWS;

}





if (mysql_num_rows($result) < 1)
{echo "No users found.";}
else
{
echo "</tbody></table>";}
?> 

