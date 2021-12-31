<?php
//script to edit and delete cases
session_start();
require('../auth/session_check.php');
require('../../../db.php');

function bindPostVals($query_string,$open_close)
{
	$cols = '';
	$values = array();
	foreach ($query_string as $key => $value) {
		if ($key !== 'action')//'action' is not in the table column, so ignore it
		{
			$key_name = ":" . $key;
			$cols .= "`$key` = " . "$key_name,";
			$values[$key_name] = trim($value);
		}
	}

	//Time opened and closed is not presented to user.  So, we add the values.
	$now =  date('Y-m-d H:i:s');

	if ($open_close === 'open')
		{
			$cols .= "`time_opened` = :time_opened";
			$values[':time_opened'] = $now;
		}

	if ($open_close === 'close')
		{
			$cols .= "`time_closed` = :time_closed,`closed_by` = :closed_by";
			$values[':time_closed'] = $now;
			$values[':closed_by'] = $_SESSION['login'];

		}
		//If $open_close is 'edit', we don't need to add these fields

	$columns = rtrim($cols,',');

	return array('columns'=>$columns,'values' => $values);
}

$action = $_POST['action'];

if (isset($_POST['id']))
	{$id = $_POST['id'];}

//check for json in post values; convert to serialized array
foreach ($_POST as $key => $value) {

	if (substr($value, 0,1) === "{" || substr($value, 0,1 ==="["))
	//this is to stop php from turning integers (like id) into json
	{
		$json_test = @json_decode($value);

		if ($json_test)
		{
			$serialize = serialize($json_test);

			$_POST[$key] = $serialize;
		}
	}
}

switch ($action) {

	case 'update_new_case':

		//Because we don't know all the table columns, we rely on an helper function,
		//bindPostVals().  This was very helpful http://stackoverflow.com/q/3773406/49359

		//First, determine if we are editing or closing a case
		if (!empty($_POST['date_close']))
			{$open_close = 'close';}
		else
			{$open_close = 'open';}

		$post = bindPostVals($_POST,$open_close);

		$q = $dbh->prepare("UPDATE cm SET " . $post['columns'] . " WHERE id = :id");

		$q->execute($post['values']);

		$error = $q->errorInfo();

			//now put adverse parties in cm_adverse_parties table
			if (!$error[1])
			{
				if (isset($_POST['adverse_parties']))
				{

					$ap = unserialize($_POST['adverse_parties']);

					foreach ($ap as $key => $a) {

						$q = $dbh->prepare("INSERT INTO cm_adverse_parties (id, case_id, name) VALUES (NULL, :case_id, :name);");

						$data = array('case_id' => $_POST['id'],'name' => $key);

						$q->execute($data);
					}
				}
			}

	break;

	case 'edit':

		//First, determine if we are opening or closing a case
		if (!empty($_POST['date_close']))
			{$open_close = 'close';}
		else
			{$open_close = 'edit';}

		$post = bindPostVals($_POST,$open_close);

		$q = $dbh->prepare("UPDATE cm SET " . $post['columns'] . " WHERE id = :id");

		$q->execute($post['values']);

		$error = $q->errorInfo();

		if ($error[1])
			{print_r($error);}

			//deal with any changes to adverse parties
			if (!$error[1])
			{

				if (isset($_POST['adverse_parties']))
				{
					//remove old adverse parties
					$q = $dbh->prepare("DELETE FROM cm_adverse_parties WHERE case_id = ?");

					$q->bindParam(1, $_POST['id']);

					$q->execute();

					//put in new adverse parties
					$ap = unserialize($_POST['adverse_parties']);

					foreach ($ap as $key => $a) {

						$q = $dbh->prepare("INSERT INTO cm_adverse_parties (id, case_id, name) VALUES (NULL, :case_id, :name);");

						$data = array('case_id' => $_POST['id'],'name' => $key);

						$q->execute($data);
					}

				}

			}


	break;

	case 'delete':

        //1. Look to see if there is higher id than this one
        $check = $dbh->prepare("SELECT id from cm where id > ?");
		$check->bindParam(1, $id);
        $check->execute();
        $check_arr = $check->fetchAll(PDO::FETCH_ASSOC);
        //2. If so, update this case to display "Deleted"
        //This allows admin to keep their case numbers sequential.
        if (!empty($check_arr)){
            //We will keep certain fields for record purposes; find what
            //fields we have other than those and clear them out. Then update
            //remaining fields with the values we want.
            $q = $dbh->prepare('SELECT `COLUMN_NAME` 
            FROM `INFORMATION_SCHEMA`.`COLUMNS` 
            WHERE `TABLE_SCHEMA`="'. CC_DATABASE_NAME . '" 
                AND `TABLE_NAME`="cm";');
            $q->execute();
            $cols = $q->fetchAll();
            $col_array = array();
            $req_cols = array('id','clinic_id','first_name','middle_name','last_name','organization','date_open','date_close','opened_by','time_opened','time_closed');
            foreach ($cols as $c) {
               $col_array[] = $c['COLUMN_NAME'];  
            }
            //Here are the columns that must be emptied out
            $to_be_cleared = array_diff($col_array,$req_cols);
            $cleared_sql = "UPDATE cm set ";
            foreach($to_be_cleared as $t){
                $cleared_sql .= $t . "='',";
            }
            $cleared_sql_trim = rtrim($cleared_sql, ',');
            $cleared_sql_trim .= " where id = $id";
            $q = $dbh->prepare($cleared_sql_trim);
            $q->execute();
            $q = $dbh->prepare("UPDATE `cm` SET `first_name` = 'DELETED', `middle_name` = '', 
            `last_name` = 'DELETED', `organization` = 'DELETED', `date_close` = CURDATE(), 
            `closed_by` = ?, time_closed = NOW()  WHERE `id` = ?;");
            $q->bindParam(1, $_SESSION['login']);
            $q->bindParam(2, $id);
            $q->execute();
        } else {
        //3. If not, we can just delete this case
            $q = $dbh->prepare("DELETE FROM cm WHERE id = ?");
            $q->bindParam(1, $id);
            $q->execute();
        }

		$error = $q->errorInfo();
        //4. Assuming successful deletion from cm, next delete all assoc_case data 
        if ($error[1]){
            $return = array('message' => 'Sorry, there was an error deleting the case. Please try again.','error' => true);
            echo json_encode($return);
            die();
        } else {
            $del_assoc_data = $dbh->prepare('DELETE FROM cm_adverse_parties WHERE case_id = :id; 
            DELETE FROM `cm_case_assignees` where case_id = :id;
            DELETE FROM `cm_case_notes` where case_id = :id;
            DELETE FROM `cm_contacts` where assoc_case = :id;
            DELETE FROM `cm_documents` where case_id = :id;
            DELETE FROM `cm_messages` where assoc_case = :id;
            ');

            $data = array('id' => $id);
            $del_assoc_data->execute($data);
            $error = $del_assoc_data->errorInfo();
            if ($error[1]){
                $return = array('message' => 'Sorry, there was an error deleting associated case data. Some data may remain.','error' => true);
                echo json_encode($return);
            } else {
                //events are handled separately            
                $q = $dbh->prepare('SELECT * FROM cm_events where case_id = ?');
                $q->bindParam(1, $id);
                $q->execute();
                $resp = $q->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($resp)){
                    foreach($resp as $r){
                        $q = $dbh->prepare("DELETE FROM cm_events_responsibles where  event_id = :eid");
                        $data = array('eid' => $r['id']);
                        $q->execute($data);
                    }

                    $q = $dbh->prepare("DELETE FROM cm_events where case_id = ?");
                    $q->bindParam(1,$id);
                    $q->execute();
                } 
            }
        }

	break;

}

if ($error[1])
{
	$return = array('message' => 'Sorry, there was an error. Please try again.','error' => true);
	echo json_encode($return);
}
else
{
	switch ($action) {

		case 'update_new_case':
			if (empty($_POST['first_name']) && empty($_POST['last_name']))
				{
					$case_name = $_POST['organization'];
				}
				else
				{
					$case_name = $_POST['first_name'] . " " . $_POST['middle_name']
					. " " . $_POST['last_name'];
				}

			if ($open_close === 'open')
				{$text = 'opened';}
			else
				{$text = 'closed';}

			$return = array("message" => htmlspecialchars($case_name ,ENT_QUOTES,'UTF-8') . " is now $text.","error" => false);
			echo json_encode($return);

		break;

		case 'edit':
			if (empty($_POST['first_name']) && empty($_POST['last_name']))
				{
					$case_name = $_POST['organization'];
				}
				else
				{
					$case_name = $_POST['first_name'] . " " . $_POST['middle_name']
					. " " . $_POST['last_name'];
				}

				if ($open_close === 'edit')
				{$text = 'edited';}
					else
				{$text = 'closed';}

			$return = array("message" => htmlspecialchars($case_name ,ENT_QUOTES,'UTF-8') . " case $text.","error" => false);
			echo json_encode($return);

		break;

		case 'delete':

			$return = array('message' => 'Case deleted.','error' => false);
			echo json_encode($return);

		break;
	}
}
