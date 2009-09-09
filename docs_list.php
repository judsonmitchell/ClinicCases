<?php
include 'db.php';
$case_id = $_GET['id'];
$get_docs= mysql_query("SELECT * FROM `cm_documents` WHERE `case_id` = '$case_id' AND `folder` = ''");
while ($line = mysql_fetch_array($get_docs, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_docs,$i);
        $d[$field] = $col_value;
        $i++;

    }
echo "<div id=\"$d[id]\" style=\"width:300px;height:20px;z-index:3000;\" onMouseOver=\"this.style.cursor='move';new Draggable('$d[id]');\"><a href=\"$d[url]\">$d[name]</a></div>";

}

?>
