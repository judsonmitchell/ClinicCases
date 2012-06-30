<?php
//Generate user's ical feed
require('../db.php');
require('../lib/php/utilities/iCalcreator.class.php');
require('../lib/php/utilities/names.php');

$key = $_GET['key'];

$q =$dbh->prepare("SELECT username FROM cm_users WHERE private_key = ?");

$q->bindParam(1,$key);

$q->execute();

if ($q->rowCount() < 1)
{
	die('No feed available.  Please check the URL for this feed by going to your ClinicCases preferences and clicking "Private Key."');
}

else
{
	$data = $q->fetch();

	$user = $data['username'];

	$time_zone = date_default_timezone_get();

	//Begin iCal
	$v = new vcalendar(array( 'unique_id' => CC_DOMAIN ));// initiate new CALENDAR
	$v->setConfig( '$_GET[key]', 'ClinicCases ' . CC_PROGRAM_NAME);// config with site domain
	$v->setProperty( 'X-WR-CALNAME', 'ClinicCases Upcoming Events' );// set some X-properties
	$v->setProperty( 'X-WR-CALDESC', 'Your Upcoming Events on ClinicCases. For more information: log on to your account at ' . CC_BASE_URL );
	$v->setProperty( 'method', 'PUBLISH' );
	$v->setProperty( 'X-WR-TIMEZONE', $time_zone );

	//Get events

	$q = $dbh->prepare("SELECT * FROM `cm_events_responsibles`,`cm_events`
		WHERE cm_events_responsibles.username = ?
		AND cm_events_responsibles.event_id = cm_events.id
		ORDER by cm_events.start DESC");

	$q->bindParam(1,$user);

	$q->execute();

	$events = $q->fetchAll(PDO::FETCH_ASSOC);

	foreach ($events as $event) {

		$case_name = case_id_to_casename ($dbh,$event['case_id']);

		$e = new vevent();// initiate EVENT

		if ($event['all_day'] == '1')
		{
			$start = explode(' ',$event['start']); //all-day events are date only

			$e->setProperty( 'dtstart', $start[0]);

			if ($event['end'] == null)//legacy ClinicCases 6 event
			{
				$e->setProperty( 'dtend', $start[0]);//make end same as event
			}
			else
			{
				$end = explode(' ',$event['end']);

				$e->setProperty( 'dtend', $end[0]);
			}

		}
		else
		{
			$e->setProperty( 'dtstart', $event['start']);

			$e->setProperty( 'dtend', $event['end']);
		}

		$e->setProperty( 'summary', $case_name . ": " . $event['task'] );

		$e->setProperty( 'description', $event['notes'] );

		$v->setComponent( $e );
	}

	$cal = $v->createCalendar();

	echo $cal;
}

