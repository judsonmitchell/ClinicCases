<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include('../utilities/thumbnails.php');
include('../utilities/names.php');
include('../utilities/convert_times.php');

//Types of events covered by this:
// 1. Cases opened
// 2. Cases closed
// 3. Casenotes entered
// 4. Documents uploaded or edited
// 5. Journal added
// 6. Events added
// 7. Being assigned to a case
// 8. Board post

	// Info to be abstracted:
	// 1. User who did the action
	// 2. Time action was done
	// 3. Title of action (what was it?)
	// 4. Substance of action (casenote description)
	// 5. Link to the resource

$activity_data = array();



include('../../../html/templates/interior/home_activities.php');