<?php
//Retrieves all data for case detail window when initially called.
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include '../utilities/thumbnails.php';
$id = $_GET['id'];
$username = $_SESSION['login'];

function array_searchRecursive($needle, $haystack, $strict = false, $path = array())
{
    if (!is_array($haystack)) {
        return false;
    }

    foreach ($haystack as $key => $val) {
        if (is_array($val) && $subPath = array_searchRecursive($needle, $val, $strict, $path)) {
            $path = array_merge($path, array($key), $subPath);
            return $path;
        } elseif ((!$strict && $val == $needle) || ($strict && $val === $needle)) {
            $path[] = $key;
            return $path;
        }
    }
    return false;
}







//Get the data for the case
$case_query = $dbh->prepare("SELECT * FROM cm WHERE id = ? LIMIT 1");
$case_query->bindParam(1, $id);
$case_query->execute();
$case_data = $case_query->fetch(PDO::FETCH_OBJ);

//Get the case types
$case_types = $dbh->prepare("SELECT * FROM cm_case_types");
$case_types->execute();
$case_types_data = $case_types->fetchAll(PDO::FETCH_OBJ);


//Get the clinic types
$clinic_types = $dbh->prepare("SELECT * FROM cm_clinic_type");
$clinic_types->execute();
$clinic_types_data = $clinic_types->fetchAll(PDO::FETCH_OBJ);

//Get the courts
$courts = $dbh->prepare("SELECT * FROM cm_courts");
$courts->execute();
$courts_data = $courts->fetchAll(PDO::FETCH_OBJ);

//Get the referrals
$referrals = $dbh->prepare("SELECT * FROM cm_referral");
$referrals->execute();
$referrals_data = $referrals->fetchAll(PDO::FETCH_OBJ);

//Get the dispositions
$dispositions = $dbh->prepare("SELECT * FROM cm_dispos");
$dispositions->execute();
$dispositions_data = $dispositions->fetchAll(PDO::FETCH_OBJ);

$response = (object) [
    'courts' => json_encode($courts_data),
    'clinic_types' => json_encode($clinic_types_data),
    'case_data' => json_encode($case_data),
    'case_types' => json_encode($case_types_data),
    'referrals' => json_encode($referrals_data),
    'dispositions' => json_encode($dispositions_data)
];

echo json_encode($response);

//Get everybody who is assigned to the case	and their user data
$assigned_users_query = $dbh->prepare("SELECT cm_case_assignees.id as assign_id,cm_case_assignees.case_id, cm_case_assignees.status as user_case_status, cm_case_assignees.username, cm_case_assignees.date_assigned, cm_users . * FROM cm_case_assignees, cm_users WHERE cm_case_assignees.case_id =  ? AND cm_users.username = cm_case_assignees.username ORDER BY cm_case_assignees.date_assigned desc");

$assigned_users_query->bindParam(1, $id);

$assigned_users_query->execute();

$assigned_users_data = $assigned_users_query->fetchAll(PDO::FETCH_ASSOC);

//Check to see if the user has permission to view the case selected.  This is for the situation when a case is called via url.
$check_permission = array_searchRecursive($username, $assigned_users_data);

if (!$check_permission and !$_SESSION['permissions']['view_all_cases'] == '1') {
    echo "Sorry, you do not have permission to view this case. <br /><br />If you need to see this case, please <a href='mailto:" . CC_ADMIN_EMAIL . "'> ask your administrator you to assign you to the case temporarily.";
    die;
}





// include '../../../html/templates/interior/cases_detail.php';
