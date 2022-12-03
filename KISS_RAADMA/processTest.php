<?php 
	/*processTest.php*/


	require_once($_SERVER['DOCUMENT_ROOT']."/QUOTA/classes/Database.php");
	$db = new Database();
	
     $db_name = "TEST_DB";

     $array_of_values = array();
        
        $conn_test_db = $db->connect_to_database($db_name);
        
        $query_string = "SELECT name FROM test_tbl_1"; 
        
        $query_executed  = $db->executeQuery($query_string, $conn_test_db);
        $array_of_values = array();

        $count = 0;
        while($rows = mssql_fetch_assoc($query_executed)){
            $array_of_values["Name_".$count] =  $rows['name'];
           // $array_of_values[$count] =  $rows['email_address'];
            $count++;
        }



       // $response_array['users'] = $array_of_values;
     

        //$array_test = array("Name" =>'test2');


        //$response_array['message1'] = $message;
        $assign_string_count = 0;
        $string_text = null;
       foreach ($array_of_values as $key => $value) {

       		if($assign_string_count == 0){
       	 		 $string_text =  '[{"Name" : "' . $value .'"}';
       		 }else{
       		 	$string_text = $string_text . ', {"Name" : "'. $value.'"}'; 
       		 }

       		 $assign_string_count++;
       }

       $string_text = $string_text . "]";

        $my_json_encoded_response = json_encode($string_text);


        echo $my_json_encoded_response;

        //echo $string_text;
	

?>