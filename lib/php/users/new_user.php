<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';

function genKey() //generates user's private key
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
$temp_username = rand();

$private_key = genKey();

$q = $dbh->prepare("INSERT INTO `cm_users` (`id`, `first_name`, `last_name`, `email`, `mobile_phone`, `office_phone`, `home_phone`, `grp`, `username`, `password`, `supervisors`, `picture_url`, `timezone_offset`, `status`, `new`, `date_created`, `pref_case`, `pref_journal`, `pref_case_prof`, `evals`, `private_key`, `force_new_password`) VALUES (NULL, '', '', '', '', '', '', '', '$temp_username', '', '', 'people/no_picture.png', '1', 'inactive', '', CURRENT_TIMESTAMP, 'on', 'on', 'on', '', '$private_key', '0');");

$q->execute();

$error = $q->errorInfo();

if ($error[1])
{print_r($error);die;
	$response = array('error' => true, "message" => "Sorry, there was an error creating the new user.");

	echo json_encode($response);
}

else
{
	$last_id = $dbh->lastInsertId();

	$response = array('id' => $last_id);

	echo json_encode($response);

}



