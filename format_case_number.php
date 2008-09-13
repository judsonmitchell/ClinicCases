<?php
function formatCaseNo($id)
{
$info = mysql_query("SELECT * FROM `cm` WHERE `id` = '$id' LIMIT 1");
WHILE ($r = mysql_fetch_array($info))
{
$date = explode('-',$r[date_open]);
echo "$date[0]" . "-" . "$r[clinic_id]";
}
}


function formatCaseNoAsVar($id)
{
$info = mysql_query("SELECT * FROM `cm` WHERE `id` = '$id' LIMIT 1");
WHILE ($r = mysql_fetch_array($info))
{
$date = explode('-',$r[date_open]);
$cs_no = "$date[0]" . "-" . "$r[clinic_id]";
$case_num = array($cs_no);
return $case_num;
}
}
?>
