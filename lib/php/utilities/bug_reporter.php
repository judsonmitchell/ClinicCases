<?php
//Submit bug reports to ClinicCases.com

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
