UPDGRADE FROM CLINICCASES 6 TO CLINICCASES 7

1. 	Make a full and complete backup of your current ClinicCases installation.
	This should include your db and everything in the docs/ and people/ directories.
	Put the backup someplace safe (out your webroot).  You will need this data later
	in the upgrade.

2. 	Get the package files by either downloading from https://code.google.com/p/cliniccases
	or (if you have git installed), issuing this command:
	git clone https://code.google.com/p/cliniccases/

3.  Make a copy (not delete) of the _UPGRADE directory and put it in your web root.

4.	Delete the old ClinicCases tables from your db and leave an empty database

5.	Run the install script located at _/INSTALL/index.php

5.  Check to make sure that the new install is working well.

6.	Here comes the fun part: Delete all the newly installed tables from your database

7.  Put the old tables from your backup into your database

8.	Move the copy of the _UPGRADE directory you created in step 3 into the root directory of
	your new ClinicCases installation.

//Note that the following scripts can be run in your browser, but it is probably a better
//idea to run them from the command line.  Some of them may take a very long time to execute
//and may run up against max execution time limits if you run them in the browser.

9.	Execute upgrade_from_cc6.php

10.	Execute upgrade_case_numbers.php

11. Double check to make sure that your documents backup is good before executing the next step.

12.	Open upgrade_documents.php and on line 8 insert the complete path to the directory
	where your ClinicCases documents are kept (e.g., /var/www/cliniccases/docs); Now,
	execute this script.

13. Move the user pictures from your old /people directory to your new /people directory