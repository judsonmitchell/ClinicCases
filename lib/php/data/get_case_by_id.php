<?php //scripts for case data tab in case detail
$_POST = json_decode(file_get_contents("php://input"), true);


try {
  @session_start();
  require_once dirname(__FILE__) . '/../../../db.php';
  // require_once('lib/php/auth/session_check.php');
  // require_once('lib/php/utilities/convert_times.php');
  if (isset($_POST['id'])) {
    $case_id = $_POST['id'];
  }

  //Get case data
  $q = $dbh->prepare("SELECT * FROM cm WHERE id = ?");
  $q->bindParam(1, $case_id);
  $q->execute();
  $case_data = $q->fetch(PDO::FETCH_ASSOC);

  //Get columns config
  $q = $dbh->prepare("SELECT * from cm_columns ORDER BY display_order ASC");
  $q->execute();
  $columns = $q->fetchAll(PDO::FETCH_ASSOC);

  $dta = null;


  foreach ($columns as $col) {
    //push the value of the field in case_data onto $columns
    if ($col['db_name'] !== 'assigned_users' && $col['db_name'] !== 'id') { //we don't want assigned users in this view
      $field =  $col['db_name'];
      $field_value = $case_data[$field];
      $col['value'] = $field_value;
      $dta[] = $col;
    }
  }

  echo json_encode($case_data);
} catch (Exception $e) {
  echo $e->getMessage();
}
