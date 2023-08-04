<?php
try {
  @session_start();
  require('../../../db.php');
  require(CC_PATH . '/lib/php/auth/session_check.php');
  include(CC_PATH . '/lib/php/utilities/thumbnails.php');
  include(CC_PATH . '/lib/php/utilities/names.php');
  include(CC_PATH . '/lib/php/utilities/convert_times.php');
  include(CC_PATH . '/lib/php/auth/last_login.php');
  include(CC_PATH . '/lib/php/html/gen_select.php');
  include(CC_PATH . '/lib/php/utilities/format_text.php');
  include(CC_PATH . '/lib/php/users/user_data.php');

  //function to sort the activities array by subkey - date
  function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC)
  {

    foreach ($array as $subarray) {

      $keys[] = $subarray[$subkey];
    }

    array_multisort($keys, $sortType, $array);
  }

  $username = $_SESSION['login'];

  $phpdate = strtotime('-60 days');

  $mysqldate = date('Y-m-d H:i:s', $phpdate);

  $type = '';
  if (isset($_GET['type'])) {
    $type = $_GET['type'];
  }


  //Types of events covered by this:
  // 1. Cases opened
  // 2. Cases closed
  // 3. Casenotes entered
  // 4. Documents uploaded or edited
  // 5. Journal added
  // 6. Events added
  // 7. Being assigned to a case
  // 8. Board post
  // 9. Being assigned to an event

  // Info to be abstracted:
  // 1. User who did the action
  // 2. Time action was done
  // 3. Title of action (what was it?)
  // 4. Substance of action (casenote description)
  // 5. Link to the resource

  //Case notes
  switch ($type) {
    case 'case':
      $get_notes = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
      cm_case_notes.id as note_id,
      cm_case_assignees.username as assign_user,
      cm_case_notes.username as note_user
      FROM cm_case_assignees,cm_case_notes
      WHERE cm_case_assignees.username = '$username'
      AND cm_case_assignees.status = 'active'
      AND cm_case_notes.case_id = cm_case_assignees.case_id
      AND cm_case_notes.datestamp >= '$mysqldate'");

      $get_notes->execute();

      $casenotes = $get_notes->fetchAll(PDO::FETCH_ASSOC);

      foreach ($casenotes as $note) {
        $activity_type = 'casenote';

        if ($note['note_user'] === $username) {
          $by = 'You';
        } else {
          $by = username_to_fullname($dbh, $note['note_user']);
        }

        $thumb = return_thumbnail($dbh, $note['note_user']);
        $action_text = " added a case note to ";
        $casename = case_id_to_casename($dbh, $note['case_id']);
        $time_done = $note['datestamp'];
        $time_formatted = extract_date_time($note['datestamp']);
        $id = $note['note_id'];
        $what = snippet(35, htmlentities($note['description']));
        $follow_url = 'index.php?i=Cases.php#cases/' . $note['case_id'];
        $mobile_url = 'index.php?i=Case.php&id=' . $note['case_id'];

        $item = array(
          'activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
          'action_text' => $action_text, 'casename' => $casename, 'id' => $id,
          'what' => $what, 'follow_url' => $follow_url, 'time_done' => $time_done,
          'time_formatted' => $time_formatted, 'mobile_url' => $mobile_url
        );

        $activities[] = $item;
      }

      break;

    case 'non_case':
      //Get any non-case time
      $get_noncase = $dbh->prepare("SELECT * FROM cm_case_notes
	WHERE username = '$username'
	AND case_id = 'NC'
	AND datestamp >= '$mysqldate'");

      $get_noncase->execute();


      $noncases = $get_noncase->fetchAll(PDO::FETCH_ASSOC);

      foreach ($noncases as $noncase) {
        $activity_type = 'non-case';

        $by = 'You';
        $thumb = return_thumbnail($dbh, $noncase['username']);
        $action_text = " added non-case activity ";
        $casename = '';
        $time_done = $noncase['datestamp'];
        $time_formatted = extract_date_time($noncase['datestamp']);
        $id = $noncase['id'];
        $what = snippet(35, htmlentities($noncase['description']));
        $follow_url = 'index.php?i=Cases.php#cases/' . $noncase['case_id'];
        $mobile_url = 'index.php?i=Case.php&id=' . $noncase['case_id'];

        $item = array(
          'activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
          'action_text' => $action_text, 'casename' => $casename, 'id' => $id,
          'what' => $what, 'follow_url' => $follow_url, 'time_done' => $time_done,
          'time_formatted' => $time_formatted, 'mobile_url' => $mobile_url
        );

        $activities[] = $item;
      }

      break;
    default:
      # code...
      break;
  }

  if (!empty($activities)) {
    sortBySubkey($activities, 'time_done');
  }

  include('../../../html/templates/interior/home_activities.php');
} catch (Exception $e) {
  echo $e->getMessage();
}
