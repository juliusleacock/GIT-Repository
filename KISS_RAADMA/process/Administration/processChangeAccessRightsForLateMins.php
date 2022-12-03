<?php


	/*processChangeAccessRightsForLateMins.php */
	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");

	$selected_access_right = $_POST['change_access_right'];
	$record_id = $_POST['record_id'];

	/*Update Access right */

	$db = new Database();
	$db_name = RAADMA_DB;
	$conn = $db->connect_to_database($db_name);

	$query_string = "UPDATE ".RAADMA_LIVE_USER_MODULES_TBL. " SET access_right_id_fk = ".$selected_access_right." WHERE module_id_fk = 3 AND user_account_fk_id =".$record_id;

	$db->executeQuery($query_string, $conn);

	echo "<strong style='color:green'>UPDATE SUCCESSFUL!</strong>";


?>