<?php
session_start();
require 'fckeditor/fckeditor.php';

?>

<html>
  <head>
    <title><?php echo "$issue_type Issue - Document";?></title>
   <link rel="stylesheet" type="text/css" href="cm.css">
  
  </head>
  <body style="background-color:rgb(195, 217, 255)">
  
  
  

<form action="notes_do.php" method="POST">
<input type="text" size="45" name="title" value="Please Name this Document" onFocus="this.value='';">
<center>
<?php
$oFCKeditor = new FCKeditor('my_text');
$oFCKeditor->BasePath = 'fckeditor/';
$oFCKeditor->Value = '';
$oFCKeditor->Width  = '95%%' ;
$oFCKeditor->Height = '95%' ;
$oFCKeditor->Create();
?>


 
    </form>
    </center>
  </body>
</html>



