<?php
include 'FCKeditor/fckeditor.php';
$case_no = $_GET["case_no"];

$issue_type = $_GET["issue_type"];
$username = $_GET["username"];
?>

<html>
  <head>
    <title><?php echo "$issue_type Issue - Document";?></title>
   <link rel="stylesheet" type="text/css" href="style.css">
  
  </head>
  <body style="background-color:rgb(229, 236, 249);">
  
  
  

<form action="notes_do.php" method="POST">
<input type="text" size="45" name="title" value="Please Name this Document" onFocus="this.value='';">
<center>
<?php
$oFCKeditor = new FCKeditor('my_text');
$oFCKeditor->BasePath = 'FCKeditor/';
$oFCKeditor->Value = '';
$oFCKeditor->Width  = '100%%' ;
$oFCKeditor->Height = '90%' ;
$oFCKeditor->Create();
?>
<?php
$temp_id = rand(1,6281893);

echo "
<input type= 'hidden' name='temp_id' value='$temp_id'>
<input type= 'hidden' name='case_no' value='$case_no'>
<input type ='hidden' name ='issue_type' value= '$issue_type'>
<input type ='hidden' name ='username' value= '$username'>
<input type ='hidden' name ='local' value= 'on'>
 
    </form>
    </center>
  </body>
</html>";



