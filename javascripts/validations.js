//This is to ensure that at least one person is resp. for an event
function checkEvent(valu)

{

var collector = document.getElementById('collect');
var date = document.getElementById(valu);
var task = document.getElementById('tasker');
if (collector.value == '')
{
alert('You must select at least one responsible party.');
return false;

}



if (date.value == '')
{
alert('Please select a date.');
return false;
}


if (task.value == '')
{
alert('What is to be done?');
return false;
}
return true;



}


// This is to check that a to: has been specified in new_message.php, forcing user to pick from drop-down list //
function checkTo()
{
var to = document.getElementById('to');
var toFull = document.getElementById('to_full');

if (toFull.value == 'All Students' || toFull.value== 'All Professors' || toFull.value == 'All Your Students' || toFull.value == 'All Users')
{
return true;
}

else
{
if (to.value == '' )
{alert ('The \"to\" line contains an invalid name. Try typing the first three letters of the recipient\'s first or last name in the \"to\" field and then choosing from the drop-down menu.');return false;}
}
return true;

}

//This is for events, allows only person who set it to modify or delete it //

function checkAuth(username,group)
{
var one = document.getElementById('set_by');
var tgt = 'prof';
if (one.value !== username  && group !== tgt)
{alert('Sorry. Events can only be deleted or modified by the person who created them.');return false;}
else
{return true;}

}

function forceDate(item)
{

var date = document.getElementById(item);
if (date.value == '')
{alert('You must select a date.  Click on the calendar icon to select the date.');return false;}
return true;
}

function allWarn()
{
chooser = document.getElementById('group');
if (chooser.value == 'All Users')
{
alert ('This will send a message to every user on the system.  If this is not what you want to do, please select a different group.');
return false;


}


}

function newUserValidate()
{
if (document.getElementById('first_name').value == '')
{alert ('First Name is required.');return false;}

if (document.getElementById('last_name').value == '')
{alert ('Last Name is required.');return false;}

if (document.getElementById('email').value == '')
{alert ('Email is required.');return false;}

if (document.getElementById('class').value == '')
{alert ('You must select a User Type');return false;}

return true;


}

function valAcct()
{
	if (document.getElementById('first_name').value == '' || document.getElementById('last_name').value == '' || document.getElementById('email').value == '')
		{alert('One of the required fields is blank.  Please review the form and add the necessary information.');return false;}
	else
		{return true;}
	
	
	
}


