<?php 	include '../db.php'; 
		include '../lib/php/gen_select.php';
?>
<!DOCTYPE html>

<head>
	<title>ClinicCases - Online Case Management Software for Law School Clinics</title>
	<meta name="robots" content="noindex">
	<link rel="stylesheet" href="../html/css/cm.css" type="text/css">
	<script src="../lib/jqueryui/js/jquery-1.4.4.min.js" type="text/javascript"></script>
	<script src="../lib/jqueryui/js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
	<script src="../lib/javascripts/validations.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
	
		$('#email').change(function(){alert($(this).val())})
	
	
	
	})
	
	</script>

</head>

<body>
	<div id="content">
	
		<div id="content_left">
			
			<h1>Set Up Your <br>ClinicCases Account</h1>
			<p>Please apply for an account only after you have been asked to do so by your clinic instructor. If you have any questions, please contact your <a href="mailto:<?php echo $CC_admin_email; ?>">Law Clinic's adminstrator</a>.</p>

		</div>
		
		<div id="content_right">
		
			<p>Fields in <span style="color:red">red</span> are required.</p>
		
			<form name="newAccount" id="newAccount" action="index.php" method="post" onSubmit="return valAcct();">
			
			<p><label for "first_name" class="req">First Name</label>
				<input type="text" name="first_name" id="first_name"></p><br />
			
			<p><label for "last_name" class="req">Last Name</label>
				<input type="text" name="last_name" id="last_name"></p><br />
				
			<p><div><label for "email" class="req">Email</label></div>
				<input type="text" name="email" id="email"></p><br />
				
			<p><div><label for "confirm" class="req">Type again to confirm:</label></div>
				<input type="text" name="confirm" id="confirm"></p>	<br />
				
			<p><div><label for "password" class="req">Password (at least 6 characters)</label></div>
				<input type="password" name="password" id="password"></p>	<br />
				
			<p><div><label for "confirm_password" class="req">Type again to confirm:</label></div>
				<input type="password" name="confirm_password" id="confirm_password"></p>	<br />
		
			<p><div><label for "prof" class="req">Select your professors(s)</label></div>
				<select class="mult" multiple="multiple" name="assigned_prof[]" id="prof">
				
				<?php gen_select_multiple(); ?>
				
				</select></p><br />
			
			<p><div><label for "mobile" class="req">Mobile Phone (include area code)</label></div>
				<input type="text" name="mobile" id="mobile"></p><br />
			
			<p><div><label for "home_phone">Home Phone (include area code)</label></div>
				<input type="text" name="home_phone" id="home_phone"></p><br />
			
			<p><div><label for "timezone" class="req">Your Time Zone</label></div>	
				<select id="timezone" name="timezone">
				<option value = "5" selected = "selected">U.S. Central</option>
				<option value = "4">U.S. Eastern</option>
				<option value = "6">U.S. Mountain</option>
				<option value = "7">U.S. Pacific</option>
				</select>
				</p><br />
				
			<p><img src="server.php" onclick="javasript:this.src='server.php?'+Math.random();" ></p>
			
			<p><div><label for "captcha">Can't read it? Click image.</label></div>
				<input type="text" name="captcha" id="captcha" value="Enter Text in Graphic"onFocus="this.value='';"></p><br />
				
			<p><input type="button" id="sbmt" name="sbmt" value="Submit"></p>
			
			

		
			
		
		</div>
	
	</div>

</body>
