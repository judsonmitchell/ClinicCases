<?php
include 'db.php';
$array = array('Abandonded','Acquitted','Adjustment','Adopted','Agreement','Approved','Asylum','Compensated','Completed','Conflict','Consent','Consultation','Counsel','Custody','Denied','Departure','Deported','Deposition','Difference','Dismissed','Dropped','Expunged','Fined','Granted','Guilty','Hired Attorney','Judgment','Appeal - Lost','Appeal - Won','New Counsel','Nolle Pros','None Legal','Not Achieved','Not Awarded','Not Granted','Not Guilty','Plea - Guilty As Charged','Plea - Deal','Quashed','Reconciled','Reduced','Refused Charge','Reinstated','Released','Rescinded','Resolved','R.O.C.','Settlement','
Surrender','Terminated','Vol.Departure','Withdrew','Writ Denied');

foreach ($array as $v)
{
$query = mysql_query("INSERT INTO `cm_dispos` (`id`,`dispo`) VALUES (NULL,'$v');");



}
echo "done.";

?>
