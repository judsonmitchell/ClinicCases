<?php
session_start();
include 'db.php';
include 'format_case_number.php';
$get_case_d = mysql_query("SELECT * FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");
while ($line = mysql_fetch_array($get_case_d, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_case_d,$i);
        $d[$field] = $col_value;
        $i++;
if (empty($col_value))
    {$d[$field] = "<span style='color:#e1e1e1'>None</span><br>";}
    }
    
    }
    
?>
<span id="print_title" style="display:none;"><b>Client Data</b></span>

<DIV ID="new_case" style="width:45%">
<p><LABEL>Case No:</label><div style="font-weight:bold;font-size:12pt;">
<?php 
list($cs_no) = formatCaseNo($d[id]);
echo $cs_no;
?>
</div></p>

<p>
<LABEL FOR "fname">First Name</label><div style="font-weight:bold;font-size:12pt;"><?php echo $d[first_name] ?></div>
</p>
<p>
<LABEL FOR "lname">Last Name</label><div style="font-weight:bold;font-size:12pt;"><?php echo $d[last_name] ?></div>
</p>
<p><LABEL FOR "address1">Address</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[address1] ?></div></p>
<p><LABEL FOR "address2">Address 2</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[address2] ?></div></p>
<p><LABEL FOR "city">City</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[city] ?></div></p>
<p><LABEL FOR "state">State</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[state] ?></div></p>
<p><LABEL FOR "zip">Zip Code</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[zip] ?></div></p>
<p><LABEL FOR "phone1">Phone 1</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[phone1] ?></div></p>
<p><LABEL FOR "phone2">Phone 2</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[phone2] ?></div></p>
<p><LABEL FOR "phone3">Phone 3</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[phone3] ?></div></p>
<p>
<table><tr><td  style="margin:0px;padding:0px;">
<LABEL FOR "DOB">DOB</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[dob] ?></div></td><td><LABEL FOR "ssn" style="width:40px;">SSN</label><div style="font-weight:bold;font-size:12pt;"><?php echo $d[ssn] ?></div></td></tr></table></p>
<p>
<table><tr><td  style="margin:0px;padding:0px;">
<LABEL FOR "gender">Gender</label><div style="font-weight:bold;font-size:12pt;">
<?php echo $d[gender]; ?></div>

</td><td><LABEL FOR "race" style="width:40px;">Race</label>
<div style="font-weight:bold;font-size:12pt;"><?php echo $d[race]; ?></div>


</td></tr></table></p>








</DIV>

<DIV ID="new_case_right">
<P>
<LABEL FOR "type1">Case Type</label><div style="font-weight:bold;font-size:12pt;"><?php echo $d[case_type]; ?> </div>
</P>
<p><LABEL FOR "case_name">Case Title</LABEL><div style="font-weight:bold;font-size:12pt;"><?php echo $d[case_name]; ?></div></p>

<P><LABEL FOR "court">Court</label><div style="font-weight:bold;font-size:12pt;"><?php  echo $d[court]; ?></div>
</p>


<p>
<table >
<tr><td style="margin:0px;padding:0px;"><label for "ct_case_no">Court Case No.</label><div style="font-weight:bold;font-size:12pt;"><?php echo $d[ct_case_no];   ?></div></td><td><label for "section" style="width:50px;">Section</label><div style="font-weight:bold;font-size:12pt;"><?php echo $d[section];   ?></div></td></tr></table></p>
<p><label for "judge">Judge</label><div style="font-weight:bold;font-size:12pt;"><?php echo $d[judge]; ?></div></p>

<p><label for "pl_or_def">Client is:</label>
<div style="font-weight:bold;font-size:12pt;"><?php echo $d[pl_or_def];?></div>
</p>

<p><label for "adverse">Adverse Parties (seperate names by commas)</label><div style="font-weight:bold;font-size:12pt;">
<?php
$get_adverse = mysql_query("SELECT * FROM `cm_adverse_parties` WHERE `clinic_id` = '$d[clinic_id]'");
while ($w = mysql_fetch_array($get_adverse))
{echo $w[name] . ",";}
if (mysql_num_rows($get_adverse)<1)
{echo "<span style='color:#e1e1e1'>None</span>";}

?>


</div></p>
<br><br>
<p><label for "professor">Professor:</label><div style="font-weight:bold;font-size:12pt;">
<?php

$get_this_prof = mysql_query("SELECT `professor` FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");
$x = mysql_fetch_array($get_this_prof);
echo $x[professor];
?>
</div></p>


<p><label for "professor2">Professor2:</label><div style="font-weight:bold;font-size:12pt;">
<?php

$get_this_prof = mysql_query("SELECT `professor2` FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");
$x = mysql_fetch_array($get_this_prof);
echo $x[professor2];
?>
</div></p>
<br /><br />
<input type="button" value="Edit" onClick="createTargets('case_single_main','case_single_main');sendDataGet('new_case_edit.php?id=<?php echo $d[id]; ?>');return false;">

</DIV>
