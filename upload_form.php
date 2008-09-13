<?php
$case_id = $_GET['id'];
$folder = $_GET['folder'];
echo <<<FORM
<html>
<head>
</head>

<body>
<form id = "form1" name="form1" method="post" action="document_upload.php" enctype="multipart/form-data">
<input type="file" name="docfile">
<input type="hidden" name="case_id" value="$case_id">
<input type="hidden" name="folder" value="$folder">
<input type="submit" name="Submit" value="Submit" > 
</form>
</body>
</html>
FORM;
?>
