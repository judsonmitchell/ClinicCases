<?php
$pre = "mitchell";
$amp = "@";
$suffix = "threepipeproblem";
$suffix2 = "com";
$add = $pre . $amp . $suffix . '.' . $suffix2;

header("location: mailto:$add");

?>
