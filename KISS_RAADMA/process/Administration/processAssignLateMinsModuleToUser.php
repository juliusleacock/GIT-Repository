<?php

	/* processAssignLateMinsModuleToUser.php */


	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");

    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $username = $_POST['username'];
    $email_address = $username. DOMAIN;
    $access_right = $_POST['access_right'];
    $created_by_username = $_POST['logged_in_user'];

    $current_number_of_users = CountNumOfUsers();


    $db = new Database();
    $db_name = RAADMA_DB;
    $conn = $db->connect_to_database($db_name);

    $created_date_time = ConvertToDateTime(GetCurrentDateAndTime());

    $query_string = "INSERT INTO ".RAADMA_LIVE_USER_ACCOUNTS_TBL. " (first_name, last_name, email_address, username, company_id_fk, created_date_time, created_by_username) VALUES('".$firstName."', '".$lastName."', '".$email_address."', '".$username."', 2, '".$created_date_time."', '".$created_by_username."')";

    $exec = $db->executeQuery($query_string, $conn);
    $number_of_users = CountNumOfUsers();

    if($number_of_users > $current_number_of_users){
    	$user_id = GetLastUserId();
    	$late_mins_module_number = 3;
    	$last_module_id = GetLastIdForTable(RAADMA_LIVE_USER_MODULES_TBL, RAADMA_DB);

    	$query_string_2 = "INSERT INTO ".RAADMA_LIVE_USER_MODULES_TBL."(user_account_fk_id, access_right_id_fk, module_id_fk, created_date_time, created_by_username) VALUES(".$user_id.", ".$access_right.", ".$late_mins_module_number.", '".$created_date_time."', '".$created_by_username."')";
    	$db->executeQuery($query_string_2, $conn);

    	$new_last_module_id = GetLastIdForTable(RAADMA_LIVE_USER_MODULES_TBL, RAADMA_DB);

    	if($new_last_module_id > $last_module_id){
    		echo "Success: User has been created and assigned to the Late Mins Module";
    	}else{
    		/*Delete last created user account record */
    		DeleteRowById($user_id, RAADMA_LIVE_USER_ACCOUNTS_TBL, RAADMA_DB);
    		echo "Error: User has not been assigned or created";	
    	}


    }else{
    	echo "Error: User has not been assigned or created";
    }



?>