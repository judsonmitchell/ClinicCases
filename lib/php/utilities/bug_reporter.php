<?php
//Submit bug reports to ClinicCases.com

// $url = "https://cliniccases.com/bug-reporter/report.php";

// $content = json_encode($_POST);

// $curl = curl_init($url);
// curl_setopt($curl, CURLOPT_HEADER, false);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_HTTPHEADER,
//         array("Content-type: application/json"));
// curl_setopt($curl, CURLOPT_POST, true);
// curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

// $json_response = curl_exec($curl);

// $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// if ( $status != 201 ) {
//     die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
// }

// curl_close($curl);

// $response = json_decode($json_response, true);

$postdata = http_build_query(
   $_POST
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

$context  = stream_context_create($opts);

$result = file_get_contents('https://cliniccases.com/bug-reporter/reporter.php', false, $context);

echo $result;
