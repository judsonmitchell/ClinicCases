<?php
session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
include 'db.php';


?>

<span id="close"><a href="#" onclick="Effect.Shrink('window1');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small.png" border="0"></a></span>
<div id="substance">
<h4>Please select the menu you would like to modify:</h4>
<div style="margin:10px 20px 20px 20px;">
<select value="choose_table" onChange="chooseTable(this.value);">
<option> --  --  --</option>
<option value="cm_courts">Courts</option>
<option value="cm_dispos">Dispositions</option>
<option value="cm_case_types">Case Types</option>
<option value="cm_referral">Referral Sources</option>

</select>
</div>
</div>
