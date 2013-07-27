<?php
@session_start();
require_once dirname(__FILE__) . '/../../../db.php';
require(CC_PATH . '/lib/php/auth/session_check.php');
include(CC_PATH . '/lib/php/utilities/convert_times.php');
include(CC_PATH . '/lib/php/utilities/names.php');

function sortBySubkey(&$array, $subkey, $sortType = SORT_ASC) {

    foreach ($array as $subarray) {

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
    if ($val[3] == "true")
        {@$col_vals_raw .= "cm." . $val[1] . ", ";}
}

//trim trailing comma
$col_vals = substr($col_vals_raw,0,-2);

if ($_SESSION['permissions']['view_all_cases'] == "0") {
    $sql = "SELECT $col_vals, cm_case_assignees.case_id, cm_case_assignees.username FROM cm, cm_case_assignees WHERE cm.id = cm_case_assignees.case_id AND cm_case_assignees.username =  :username AND cm_case_assignees.status =  'active'";
} elseif ($_SESSION['permissions']['view_all_cases'] == "1") {
    //admin or super user type query - Users who can access all cases and "work" on all cases.
    $sql = "SELECT $col_vals FROM cm";
} else {
    echo "There is configuration error in your groups."; die;
}

$case_query = $dbh->prepare($sql);
$case_query->bindParam(':username',$user);
$case_query->execute();

if ($_SESSION['mobile']){ //mobile does not need json, so return a php array
    $raw_results = $case_query->fetchAll();//used for mobile
    sortBySubkey($raw_results,'last_name');
} else {
    //Create array of column names for json output
    foreach ($col_result as $value)
    {
        if ($value[3] == "true") {
            $cols[] = $value[1];
        }
    }

    while ($result = $case_query->fetch(PDO::FETCH_ASSOC)) {

        $rows = array();

        //loop through results, create array, convert to json
        foreach ($cols as $col)
            {
                //First look for fields containining serialized arrays
                //and convert to strings
                $data = @unserialize($result[$col]);

                if ($data !== false) //this is a serialized array
                {
                    $make_string = null;

                    foreach ($data as $key => $value) {

                        $make_string .= "$key, ";
                    }

                    $result[$col] = rtrim($make_string, ' ,');
                }

                //Then check for rows containing dates
                if(preg_match('/^(\d\d\d\d)-(\d\d?)-(\d\d?)$/', $result[$col]))
                {

                    $result[$col] = sql_date_to_us_date($result[$col]);
                }


                $rows[] = $result[$col];
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
}
