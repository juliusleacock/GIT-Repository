<?php
	/* process.php */

	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");


	$message = "";

	$response_array = array();
    //$scrap_list_array = array();

    $data   =   $_POST["result"];
    $data   =    json_decode("$data", true); 

    $process_type = $data['process_type'];

    $my_json_encoded_response  = '';

    if($process_type == 'GET_ARRAY_OF_EMP_BY_DEPT'){
    	$dept = $data['selected_dept'];

        $db = new Database();
        $db_name = HSL_DB;
        $conn = $db->connect_to_database($db_name);


        $query_string = "SELECT EMPLOYID, FULLNAME FROM ".HSL_WEEKLY_PAID_EMP_VIEW. " WHERE DEPRTMNT = '".$dept."'";

        $exec = $db->executeQuery($query_string, $conn);

        while($rows = mssql_fetch_assoc($exec)){
            $response_array[$rows['EMPLOYID']] = $rows['FULLNAME'];
        }

   	    //$response_array['message1'] = $message;
        $my_json_encoded_response = json_encode($response_array);
    }

          echo $my_json_encoded_response;

?>

	

