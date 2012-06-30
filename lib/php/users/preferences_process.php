<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../auth/pbkdf2.php';

function bindPostVals($query_string)
{
	$cols = '';
	$values = array();
	foreach ($query_string as $key => $value) {
		if ($key !== 'action')//'action' is not in the table, so ignore it
		{
			$key_name = ":" . $key;
			$cols .= "`$key` = " . "$key_name,";
			$values[$key_name] = trim($value);
		}
	}

	$columns = rtrim($cols,',');

	return array('columns'=>$columns,'values' => $values);
}

function genKey()
{
    $underscores = 2; // Maximum number of underscores allowed in password

    $length = 40; // Length of password

    $p ="";
    for ($i=0;$i<$length;$i++)
    {
        $c = mt_rand(1,7);
        switch ($c)
        {
            case ($c<=2):
                // Add a number
                $p .= mt_rand(0,9);
            break;
            case ($c<=4):
                // Add an uppercase letter
                $p .= chr(mt_rand(65,90));
            break;
            case ($c<=6):
                // Add a lowercase letter
                $p .= chr(mt_rand(97,122));
            break;
            case 7:
                 $len = strlen($p);
                if ($underscores>0&&$len>0&&$len<9&&$p[$len-1]!="_")
                {
                    $p .= "_";
                    $underscores--;
                }
                else
                {
                    $i--;
                    continue;
                }
            break;
        }
    }
    return $p;
}

$action = $_POST['action'];

switch ($action) {

	case 'update_profile':

		$post = bindPostVals($_POST);

		$q = $dbh->prepare("UPDATE cm_users SET " . $post['columns'] . " WHERE id = :id");

		$q->execute($post['values']);

		$error = $q->errorInfo();

		break;

	case 'change_password':
		//First check if user has entered correct old password
		$q = $dbh->prepare("SELECT id,password FROM cm_users WHERE id = :id AND password = :pword");

		$salt = CC_SALT;

		$hash = pbkdf2($_POST['current_pword'], $salt, 1000, 32);

		$pass = base64_encode($hash);

		$data = array('id' => $_POST['id'],'pword' => $pass);

		$q->execute($data);

		if ($q->rowCount() < 1)
		{
			$return = array('error' => true, 'message' => 'Your old password is wrong.');

			echo json_encode($return);

			die;
		}

		else
		{
			$q = $dbh->prepare("UPDATE cm_users SET password = :new_pass WHERE id = :id");

			$new_hash = pbkdf2($_POST['new_pword'], $salt, 1000, 32);

			$new_pass = base64_encode($new_hash);

			$data = array('id' => $_POST['id'],'new_pass' => $new_pass);

			$q->execute($data);
		}

		$error = $q->errorInfo();

		break;

	case 'change_picture':
		//Not yet implemented.  Admin must change picture now.
		break;

	case 'change_private_key':

		$new_key = genKey();

		$q = $dbh->prepare('UPDATE cm_users SET private_key = :private_key WHERE id = :id');

		$data = array('private_key' => $new_key,'id' => $_POST['id']);

		$q->execute($data);

		$error = $q->errorInfo();

		break;
}

if ($error[1])
{
	$return = array('error' => true, 'message' => $error[1]);

	echo json_encode($return);
}
else
{
	switch ($action) {
		case 'update_profile':
			$return = array('error' => false,'message' => 'Your profile has been updated.');
			echo json_encode($return);
			break;

		case 'change_password':
			$return = array('error' => false,'message' => 'Your password has been changed.');
			echo json_encode($return);
			break;

		case 'change_picture':
			//Not yet implemented.  Admin must change picture now.
		break;

		case 'change_private_key':
			$return = array('error' => false,'message' => 'Your private_key has been changed.');
			echo json_encode($return);
			break;
	}
}
