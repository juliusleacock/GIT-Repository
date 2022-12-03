<?php

	
  require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");



	$data   =   $_POST["result"];
	$data   =    json_decode("$data", true);


	$record_id = $data['id'];


	$db = new Database();
	$db_name = RAADMA_DB;
	$conn = $db->connect_to_database($db_name);

	$query_string = "DELETE FROM ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL." WHERE id = '".$record_id."'";
	$db->executeQuery($query_string, $conn);

?>