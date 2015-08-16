<?php
//Script to evaluate potential conflicts of interest
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/names.php');
require('../utilities/convert_times.php');

//function to sort the activities array by subkey - date

function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC) {

    foreach ($array as $subarray) {

        $keys[] = $subarray[$subkey];
    }

    array_multisort($keys, $sortType, $array);
}


$id = $_POST['case_id'];

if (isset($_POST['type']))
{$type = $_POST['type'];}
else
{$type='display';}

//what kind of data are we going to check?
//1. New client name against previous adverse parties
//2. New adverse parties against all previous client names
//3. This cases's contacts against all previous client names
//4. This case's contacts against all previous adverse parties
//Script must be run 1) on case load 2) when new contact is entered,
//and 3) when case data is edited

//1.
//Get this client's name
$q = $dbh->prepare("SELECT first_name,middle_name,last_name FROM cm WHERE id = ?");

$q->bindParam(1,$id);

$q->execute();

$new_client = $q->fetch(PDO::FETCH_ASSOC);

$new_client_name = $new_client['first_name'] . ' ' . $new_client['middle_name'] . ' ' . $new_client['last_name'];

//Get all adverse parties
$q = $dbh->prepare("SELECT * FROM cm_adverse_parties");

$q->execute();

$adverse = $q->fetchAll(PDO::FETCH_ASSOC);

$conflicts = array();

foreach ($adverse as $ad) {

	similar_text($new_client_name, $ad['name'], $per);

	if ($per >= 80)
	{
		$conflicts[] = array('percentage' => $per,'text' => "A party named <strong> " . htmlspecialchars($ad['name'], ENT_QUOTES,'UTF-8') . " </strong> was adverse in the <a href='index.php?i=Cases.php#cases/" . $ad['case_id'] . "' target='_new'>" .
		case_id_to_casename ($dbh,$ad['case_id']) . "</a> case.  ("  .  round($per,2) . " % match)");
	}

}

//2.
//Get this cases's adverse parties
$q = $dbh->prepare("SELECT * FROM cm_adverse_parties WHERE case_id = ?");

$q->bindParam(1,$id);

$q->execute();

$this_adverse_parties = $q->fetchAll();

if ($q->rowCount() > 0)
{

	//Get all client names and put in an array
	$q = $dbh->prepare("SELECT id,first_name,middle_name,last_name FROM cm");

	$q->execute();

	$clients = $q->fetchAll(PDO::FETCH_ASSOC);

	$acs = array();

	foreach ($clients as $client) {

	$acs[] = array('case_id' => $client['id'],'name' => $client['first_name'] . ' ' .
	$client['middle_name'] . ' ' . $client['last_name']);

	}

	//Now do the name comparison
	foreach ($this_adverse_parties as $ap) {

		foreach ($acs as $ac) {

			similar_text($ap['name'], $ac['name'], $per);

			if ($per >= 80)
			{
				$conflicts[] = array('percentage' => $per,'text' =>
				"We represented a party named <strong>" . htmlspecialchars($ap['name'], ENT_QUOTES,'UTF-8') . " </strong> in the <a href='index.php?i=Cases.php#cases/"
				. $ac['case_id'] . "' target='_new'>" .
				case_id_to_casename ($dbh,$ac['case_id']) . "</a> case. " . htmlspecialchars($ap['name'], ENT_QUOTES,'UTF-8') . " is adverse in this case. ("  .
				round($per,2) . " % match)");
			}

		}
	}
}

//3.
//Get this cases's contacts
$q = $dbh->prepare("SELECT * FROM cm_contacts WHERE assoc_case = ?");

$q->bindParam(1,$id);

$q->execute();

$contacts = $q->fetchAll(PDO::FETCH_ASSOC);

$contact_number = $q->rowCount();

if ($contact_number > 0)
{
	//Get all client names and put in an array
	$q = $dbh->prepare("SELECT id,first_name,middle_name,last_name FROM cm");

	$q->execute();

	$clients = $q->fetchAll(PDO::FETCH_ASSOC);

	$acs = array();

	foreach ($clients as $client) {

	$acs[] = array('case_id' => $client['id'],'name' => $client['first_name'] . ' ' .
	$client['middle_name'] . ' ' . $client['last_name']);

	}

	//Now do the name comparison
	foreach ($contacts as $contact) {

		foreach ($acs as $ac) {

			$contact_name = $contact['first_name'] . ' ' . $contact['last_name'];

			if (!$contact['type'])
			{
				$contact_type = "contact";
			}
			else
			{
				$contact_type = $contact['type'];
			}

			similar_text($contact_name, $ac['name'], $per);

			if ($per >= 80)
			{
				$conflicts[] = array('percentage' => $per,'text' =>
				"We represented a party named <strong>" . htmlspecialchars($contact_name ,ENT_QUOTES,'UTF-8'). "</strong> in the <a href='index.php?i=Cases.php#cases/"
				. $ac['case_id'] . "' target='_new'>" .
				case_id_to_casename ($dbh,$ac['case_id']) . "</a> case." .  htmlspecialchars($contact_name ,ENT_QUOTES,'UTF-8'). " is a
				$contact_type in this case. ("  . round($per,2) . " % match)");
			}

		}
	}
}

//4.
//use the previously generated contacts
if ($contact_number >0)
{
	$q = $dbh->prepare("SELECT * FROM cm_adverse_parties");

	$q->execute();

	$adverse = $q->fetchAll(PDO::FETCH_ASSOC);

	foreach ($adverse as $ad) {

		$contact_name = $contact['first_name'] . ' ' . $contact['last_name'];

		if (!$contact['type'])
			{
				$contact_type = "contact";
			}
			else
			{
				$contact_type = $contact['type'];
			}

		similar_text($contact_name, $ad['name'], $per);

		if ($per >= 80)
		{
			$conflicts[] = array('percentage' => $per,'text' => "A party named <strong> " . htmlspecialchars($ad['name'], ENT_QUOTES,'UTF-8') . " </strong> was adverse in the <a href='index.php?i=Cases.php#cases/" . $ad['case_id'] . "' target='_new'>" .
			case_id_to_casename ($dbh,$ad['case_id']) . "</a> case. " .  htmlspecialchars($contact_name ,ENT_QUOTES,'UTF-8'). " is a " . 
			htmlspecialchars($contact_type ,ENT_QUOTES,'UTF-8') . " in this case. ("  .  round($per,2) . " % match)");
		}

	}
}

//Return the data
$count = count($conflicts);

if ($type === 'alert')
{
	if ($count > 0)
	{
		$return = array('conflicts' => true,'number' => $count);
		echo json_encode($return);
	}
	else
	{
		$return = array('conflicts' => false);
		echo json_encode($return);
	}
}
else
{
	if ($count > 0)
	{
		sortBySubkey($conflicts,'percentage');
	}

	include('../../../html/templates/interior/cases_conflicts.php');
}
