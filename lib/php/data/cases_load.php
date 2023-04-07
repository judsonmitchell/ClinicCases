<?php
@session_start();
require_once dirname(__FILE__) . '/../../../db.php';
// require(CC_PATH . '/lib/php/auth/session_check.php');
include(CC_PATH . '/lib/php/utilities/convert_times.php');
include(CC_PATH . '/lib/php/utilities/names.php');
function sortBySubkey(&$array, $subkey, $sortType = SORT_ASC)
{

    foreach ($array as $subarray) {
        echo $subarray[$subkey];
        $keys[] = $subarray[$subkey];
    }

    array_multisort($keys, $sortType, $array);
}

$user = $_SESSION['login'];

//Get the columns from cm_columns table

$get_columns = $dbh->prepare('SELECT * from cm_columns');
$get_columns->execute();
$col_result = $get_columns->fetchAll();

foreach ($col_result as $val) {
    if ($val[3] == "true") {
        @$col_vals_raw .= "cm." . $val[1] . ", ";
    }
}

//trim trailing comma
$col_vals = substr($col_vals_raw, 0, -2);
if ($_SESSION['permissions']['view_all_cases'] == "0") {
    $sql = "SELECT $col_vals, cm_case_assignees.case_id, cm_case_assignees.username FROM cm, cm_case_assignees WHERE cm.id = cm_case_assignees.case_id AND cm_case_assignees.username =  :username AND cm_case_assignees.status =  'active'";
} elseif ($_SESSION['permissions']['view_all_cases'] == "1") {
    //admin or super user type query - Users who can access all cases and "work" on all cases.
    $sql = "SELECT $col_vals FROM cm";
} else {
    echo "There is configuration error in your groups.";
    die;
}

$case_query = $dbh->prepare($sql);
if ($_SESSION['permissions']['view_all_cases'] == "0") {
    $case_query->bindParam(':username', $user);
}
$response = $case_query->execute();

//Create array of column names for json output
foreach ($col_result as $value) {
    if ($value[3] == "true") {
        $cols[] = $value[1];
    }
}
while ($result = $case_query->fetch(PDO::FETCH_ASSOC)) {


    $rows = array();
    foreach ($result as $key => $value) {
        $data = unserialize($value);
        if ($data != false) {
            $make_string = null;

            foreach ($data as $key => $value) {
                $make_string .= "$key, ";
            }

            $rows[$key] = rtrim($make_string, ' ,');
        } else {
            $rows[$key] = $value;
        }
    }

    //Return aaData object to DataTables
    $output['aaData'][] = $rows;
}

//If no rows found, return empty array
if ($case_query->rowCount() < 1) {
    $output['aaData'] = array($cols);
}

$json = json_encode($output);
echo $json;

