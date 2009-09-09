<?php
session_start();
?>

<html>
<head>
<title>ClinicCases Mobile - Main</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>




</body>
<h1>ClinicCases <span style="color:gray;font-style:italic;">Mobile</span></h1>
<strong>Choose an Activity</strong>
<ul>
<li><a href="messages_m.php">Messages</a></li>
<li><a href="cases_m.php">Your Cases</a></li>
<li><a href="recent_activity_m">Recent Activity</a></li>
<li><a href="upcoming_events_m.php">Upcoming Events</a></li>
<?php
if ($_SESSION['class'] = 'prof')
echo "<li><a href=\"\">Your Students</a>";

?>
<li><a href="logout_m.php">Logout</a></li>
</ul>
</body>
</html>

