<?php
session_start();
if (!$_SESSION){echo "You must be logged in to view this page.";die;}
include '../db.php';
?>

<html>
<head>
<title>ClinicCases Mobile - Search for <?php echo $_GET[search_term]; ?></title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<h1>ClinicCases <span style="color:gray;font-style:italic;">Mobile</span></h1>
<a href="cm_home_m.php">Main Menu</a><br><br>

<strong>Search Results</strong>
<p>Clients in Open Cases matching "<?php echo $_GET[searchterm]; ?>"<p>
<p class="result">
<?php
if ($_SESSION['class'] == 'prof')
	{
		//Get an array of all case ids from open cases
		$q = mysql_query("SELECT * FROM `cm` WHERE `date_close` = '' AND `professor` = '$_SESSION[login]' OR `professor2` = '$_SESSION[login]'");

		while ($r = mysql_fetch_array($q))
			{
				$case_id_array[] = $r[id];
			}
		}


		//Get all client contact info

		foreach  ($case_id_array as $v)
		{

			$cases = mysql_query("SELECT * FROM `cm` WHERE `first_name` LIKE '%$_GET[searchterm]%' AND `id` = '$v' OR `last_name` LIKE '%$_GET[searchterm]%' AND `id` = '$v' LIMIT 1");
			$b = mysql_fetch_object($cases);

			IF ($b->id)
			{echo "<a href='contact_m.php?type=client&id=$b->id'> $b->first_name $b->last_name</a>";}
			//increments if there are resutls
			if (mysql_num_rows($cases) > 0)
			{$num_results_b = 1;echo "<br>";}


		}

				if ($num_results_b != 1){echo "<p class='no'>No results found.</p>";}

		//Get all case contact info

		echo "</P><p>Case Contacts in Open Cases matching \"$_GET[searchterm]\"</P><p class=\"result\">
";

		foreach ($case_id_array as $c)
		{
			$contacts = mysql_query("SELECT * FROM `cm_contacts` WHERE `assoc_case` = '$c' AND `first_name` LIKE '%$_GET[searchterm]%' OR `assoc_case` = '$c' AND `last_name` LIKE '%$_GET[searchterm]%' LIMIT 1");
			$c = mysql_fetch_object($contacts);

			if ($c->id)
			{echo "<a href='contact_m.php?type=contact&id=$c->id'> $c->first_name $c->last_name</a>";}

			if (mysql_num_rows($contacts) > 0)
			{$num_results_c = 1;echo "<br>";}


		}

				if ($num_results_c != 1){echo "<p class='no'>No results found.</P>";}


		//Get all ClinicCases users

		echo "</p><p>Active ClinicCases Users matching \"$_GET[searchterm]\"</p><p class=\"result\">
";

		$users = mysql_query("SELECT * FROM `cm_users` WHERE `status` = 'active' AND `first_name` LIKE '%$_GET[searchterm]%' OR `last_name` LIKE '%$_GET[searchterm]%'");

		while ($d = mysql_fetch_array($users))
		{
			echo "<a href='contact_m.php?type=user&id=$d[id]'> $d[first_name] $d[last_name]</a><br>";
		}

			if(mysql_num_rows($users) < 1)
			{echo "<p class='no'>No results found.</p>";}





		?>
</p>
</body>
</html>
