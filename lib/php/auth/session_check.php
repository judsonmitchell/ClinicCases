<?php
//Existence of session permissions indicates that the user is validly logged in to a ClinicCases session on the server	
    
if (!isset($_SESSION['cc_session_id']) && isset($_GET['i'])) {
?>
<html>
<head>
    <title>Time Out</title>
    <meta http-equiv="refresh" content="2;URL=<?php echo  CC_BASE_URL;?>index.php">
	<link rel="stylesheet" href="html/css/cm.css" type="text/css"  media="screen"/>
</head>
<body>
<div class="container">
    <br />
    <h4>Your session has timed out.  Please <a href="index.php">log in.</a></h4>
</div>
</body>
</html>
<?php die;} elseif (!isset($_SESSION['cc_session_id'])){

    //$error = array('error' => true, 'message' => 'You do not have permission to do this.');
    //echo json_encode($error);
    header("HTTP/1.0 401 Not Authorized");
    die;
}
    
		

