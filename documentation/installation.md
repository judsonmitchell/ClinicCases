# Installation

## New Installation

Here are the instructions to install ClinicCases 7 on your server:

Either download the latest source package from [Github](https://github.com/judsonmitchell/ClinicCases/releases) or, using git, issue the command:

    https://github.com/judsonmitchell/ClinicCases.git

If you downloaded the package, then next extract the ClinicCases package to your web root.

Create a directory outside of your web root called cc_docs (e.g., /var/cc_docs). If you do not have access to anything outside of your web root, you can put the directory in your web root (e.g., /var/www/cliniccases/cc_docs), but be warned that this is insecure.  Anybody with the specific file url can access your clinic's documents! In either case, make sure that this directory is writable (e.g. sudo chmod 777 cc_docs)

Go to http://YOURSERVER/cliniccases/_INSTALL/ and follow the instructions.

When the installation is complete, log in to your installation of ClinicCases using the username 'admin' and the password 'admin'.  You will be prompted to change the password on this temporary account. Next, go to the users tab and add a new user account for yourself.  Make sure that you put yourself in the Administrator group, that you make the account 'active', and that you provide your valid email address.  Next, check your email. You should receive an email from your server which contains a temporary password. (Check spam folder as well!).

Log out of ClinicCases and then log in again using your new user name and the password provided. You can then change this password by clicking on the Preferences link in the upper right hand corner.

## Upgrade from ClinicCases 6

* Make a full and complete backup of your current ClinicCases installation.This should include your db and everything in the docs/ and people/ directories. Put the backup someplace safe (out your webroot).  You will need this data later in the upgrade.

* Get the package files by either downloading from https://github.com/judsonmitchell/ClinicCases/releases or (if you have git installed), issuing this command:
    git clone https://github.com/judsonmitchell/ClinicCases.git 

* Make a copy (not delete) of the _UPGRADE directory and put it in your web root.

* Delete the old ClinicCases tables from your db and leave an empty database

* Run the install script located at _/INSTALL/index.php

* Check to make sure that the new install is working well.

* Here comes the fun part: Delete all the newly installed tables from your database

* Put the old tables from your backup into your database

* Move the copy of the _UPGRADE directory you created in step 3 into the root directory of your new ClinicCases installation.

Note: The following scripts can be run in your browser, but it is probably a better
idea to run them from the command line.  Some of them may take a very long time to execute
and may run up against max execution time limits if you run them in the browser.

* Execute upgrade_from_cc6.php

* Execute upgrade_case_numbers.php

* Double check to make sure that your documents backup is good before executing the next step.

* Open upgrade_documents.php and on line 8 insert the complete path to the directory
where your ClinicCases documents are kept (e.g., /var/www/cliniccases/docs); Now,
execute this script.

* Move the user pictures from your old /people directory to your new /people directory

