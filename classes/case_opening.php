<?php

class openCase{

//Creates a new case number

function createCaseNo()
{
	//This generates a new case number
	$get_next_number = mysql_query("SELECT MAX(clinic_id) FROM `cm`");
	$row = mysql_fetch_array($get_next_number);
	$number = $row["MAX(clinic_id)"];
	$add_number = $number + 1;
	
	//this locks the record for editing
	$q = mysql_query("INSERT INTO `cm` (`id`,`clinic_id`) VALUES(NULL,'$add_number')");
	$id = mysql_insert_id();
	
	$values = array($add_number,$id);
	return $values;
}

//Checks for conflicts

function checkConflicts($new_client_name,$new_adverse_name)
//Problem here is that $new_adverse_name is going to be several names possibly, so an array - 05.05.2009 20:28:40
{
	//Check new client against former adverse parties
	//$check_new_client = mysql_query("SELECT * FROM `cm_adverse_parties` WHERE MATCH (name) AGAINST ('$new_client_name')");
	$check_new_client = mysql_query("SELECT * FROM `cm_adverse_parties` WHERE `name` LIKE '%$new_client_name%'");
		
		while ($r = mysql_fetch_array($check_new_client))
		{
			$conflict = $r[name] . " was an ADVERSE PARTY in case number <a target='_new' href='cm_cases.php?direct=$r[id]'>" . $r[clinic_id] . "</a>" ;
			$conflicts_array[] = $conflict;
		}
		//this checks all new adverse parties against former clients
		if (!empty($new_adverse_name))
		{
			//slice off trailing new lines
			
			
		foreach ($new_adverse_name as $adv)
			
			{
					$new_adverse_name_parts = explode(" ", $adv);

					$check_new_adverse = mysql_query("SELECT * FROM `cm` WHERE `first_name` LIKE '%$new_adverse_name_parts[0]%' AND  `last_name` LIKE '%$new_adverse_name_parts[1]%'");

				while ($r = mysql_fetch_array($check_new_adverse))
				{
					$conflict = "We represented"  . " " . strtoupper($r[first_name]) . " " . strtoupper($r[last_name]) . " in a " . $r[case_type] . " case. Case Number: <a target='_new' href='cm_cases.php?direct=$r[id]'>" . $r[clinic_id] . "</a>";
					$conflicts_array[] = $conflict;
				}
			}
		}

			if (!empty($conflicts_array))
				{
					$response =  "\nImportant! You should consider checking the following cases for conflicts:\n";
					foreach ($conflicts_array as $item)
						{
							$response .= $item . "\n";
						}
						return $response;
				}

			else 
			{
			$no_response = "No potential conflicts found.";
			return $no_response;
		}
		//return $conflicts_array;


}


}
?>
