<?php 
	/*processTest.php*/


	require_once($_SERVER['DOCUMENT_ROOT']."/RAADMA/classes/Database.php");

  require_once($_SERVER['DOCUMENT_ROOT']."/RAADMA/functions.php");
  
	
   $message = "";

$response_array = array();
    //$scrap_list_array = array();

    $data   =   $_POST["result"];
    $data   =    json_decode("$data", true); 
    $processType = $data['process_type'];  

        $my_json_encoded_response  = ''; 

    if($processType == "SAVE_DATA"){
      $name = cleanString($data['name']);
      $email = cleanString($data['email_address']);

      $db = new Database();
      $db_name = "TEST_DB";

      $conn_test_tbl_1 = $db->connect_to_database($db_name);

      $query_string = "INSERT INTO test_tbl_1(name, email_address) VALUES('".$name."' ,  '".$email."')";

      $db->executeQuery($query_string, $conn_test_tbl_1);
    }

    echo $my_json_encoded_response;
	

?>