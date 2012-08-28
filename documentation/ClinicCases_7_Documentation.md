![ClinicCases logo](img/logo.png)
#ClinicCases 7 Documentation

## [Table of Contents](#contents)
* [Getting Started](#getting_started)
* [Home Tab](#home_tab)
* [Cases Tab](#cases_tab)
* [Group Tab](#group_tab)
* [Journals Tab](#journals_tab)
* [Users Tab](#users_tab)
* [Utilities Tab](#utilities_tab)
* [Board Tab](#board_tab)
* [Messages Tab](#messages_tab)
* [Preferences](#preferences)
* [Installation](#install)
* [Customization](#customization)
* [Source Code](#source)
* [More Information](#more_information)
* [MIT License](#license)

## Getting Started
ClinicCases is a web-based case management system designed specifically for law school clinics. It is designed to be easy-to-use with a minimal learning curve.

Once your ClinicCases is installed on your server (see [Installation](#install)), here's how to get started.  CC ships with three pre-defined user groups: Administrators, Professors, and Students.  These groups can be [customized](#customization).  You should log in using the default administrator account:

* Username: admin
* Password: admin

You will then be prompted to change your password.  Once that is done, click on the Users tab.  You should see that there is only one active user, Temp Admin.  You should add at least one new administrative user by clicking on New User in the upper-right hand corner.

![New User Button](img/new_user.png)

Be sure to include your email address in the new user information.  Check to see if your mail server is working and then check your email for an email with a temporary password to the new account.  Log on to CC with these credentials and then [change your password](#change_password).  You can now delete the Temp Admin account by going to Users, opening Temp Admin and pressing delete.

Other users can get accounts in one of two ways: 1) You can add them by clicking on "New User" and inputting the appropriate information for each user or 2) new users can sign themselves up. To do the latter, users should click on "Need an Account?" on the login page.  They will be prompted for the information.  You should then receive an email from CC notifying you that a new user has signed up.  You (or anyone with an administrative account) must approve the application before that user has access.  To do this, go to the Users tab.  You should see a dialog prompting you to approve the application like this:

![New User Prompt](img/new_user_prompt.png)

If you click yes, you will be brought to a list of new users to be activated.  You can either 1). click on each individual user, review the information, and then set his/her status to active or 2) using the menu at the bottom of the screen (where it says "With Displayed Users"), select "Make Active."  It is important that an administrator review each new user application to ensure that it is a legitimate application and not spam.

Once you have activated the new user, he or she will receive an email confirming that the account is now activated.  The user can log in with the username provided in the email and the password that he or she provided when submitting the application.

If the user application is invalid (a spam sign-up or a duplicate account, for example), you can delete the account by pressing "Delete."

Once you have your users set up, you will want to enter some [cases](#new_case).

##Home Tab <a id="home_tab"></a>

The Home tab is designed to give you a quick look at what's going on.  It's broken up into three sections, Activity, Upcoming, and Trends.

* __Activity__ - Shows you the latest actions taken on ClinicCases which are relevant to you.

    ![Home Activity View](img/home_activity.png)

	Assuming the default [groups](#customization_groups) are set, professors see every action on a case to which they are assigned and every action taken by users who they supervise.  Students see only every action taken on any case to which they are assigned.  Administrators see information about the opening and closing of cases and about new account requests.  All users see information about [board posts](#board_tab).

	An RSS feed of this activity is available.  Click the RSS icon next to the activity stream and you will be directed to your rss feed.  Add the URL of the feed to your favorite feed reader.  The feed is secured using a private key which is known only to you.  If you suspect that your feed may have been compromised, you can reset the key by going to [Preferences](#preferences) and clicking on Private Key.

* __Upcoming__ - Shows upcoming events which are relevant to you.

	![Home Upcoming View](img/home_upcoming.png)

	This is a calendar which shows all events to which you have been assigned.  Events can be added in two ways 1) From inside a case, by clicking Events and then "New Event" or 2) using the [Quick Add](#home_quick_add) button on the Home page.  You can switch between Month, Week, and Day views.  Clicking on an event will bring up a details dialog which will show who is assigned to the event and other relevant information.

	An Ical feed of your events is available by clicking on the Ical icon at the top of the calendar.  Add the URL of the feed to your calendaring program (e.g, Google Calendar).  Instructions on how to do this for your specific calendaring program are probably available by Googling "how to add Ical feed to [insert name of your calendaring program]."

* __Trends__ - Shows graphical data about user and case activity.

	Depending on your group, Trends shows you graphical information about activity on ClinicCases.  Professors will see which students are the most active over time and which cases have the most activity.  Students will see information about their activity and the activity of others in their group.  Administrators will see clinic-wide information about case and user activity.

	As of Beta 4.2, Trends is not yet implemented.  Further documentation will be added once the feature is complete.

* __Quick Add__ <a id="home_quick_add"></a> - Quickly add data to ClinicCases

	![Quick Add Dialog](img/quick_add.png)

	The Quick Add button is designed for you to quickly enter case notes and events without having to take the extra steps of navigating into a case.  Clicking on the button will bring up a dialog with a choice to add a case note or an event.

	With a case note, you select the appropriate case from the list of case to which you are assigned, add a time value, and then add a note.  After you click "Add", the case note will be automatically filed in the case.  Note that the default choice in the case list is "Non-Case Time."  This is the only place in ClinicCases to record activity not associated with a case.  Activity filed here can include things such as class time, attending orientation, etc.  As of Beta 4.2, there is no way to delete or edit non-case time.

	With an event, you enter the title of the event, where it is taking place, and the start and end times.  You can then associate the event with a case by selecting from the drop-down list.  In the field labeled "Who?", you type in the names of everyone who is responsible for this event.  If you choose a group, everyone in that group will be responsible and will see the event in their calendar feeds. Please note that if you do not add yourself to the event or you are not in one of the assigned groups, you will not see the event in your calendar.

##Cases Tab <a id="cases_tab"></a>

###Cases Table
![Cases Table](img/cases_table.png)



###Case Detail









