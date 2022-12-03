<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/classes/Database.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");
	/*functions.php*/

	date_default_timezone_set('America/Puerto_Rico');


	function appendMessage($message, $message_to_append){
		$message = $message . $message_to_append. "<br />";
		
		return $message;
	}

	function appendMessage2($message, $message_to_append){
		$message = $message . $message_to_append;
		
		return $message;
	}

	function appendMessageArray($message, $error_array){
		array_push($error_array, $message);
		return $error_array;

	}


	function cleanString($string_value){
    
    return str_replace("'", "''", $string_value);
	}  

	

	function makeActive($modules_array){	
		$current_file_name = basename($_SERVER['REQUEST_URI']);

		if(in_array($current_file_name, $modules_array)){
			return 'active';	
		}

	}

	function makeTabActive( $requestUri){	
		$current_file_name = basename($_SERVER['REQUEST_URI']);

		if($current_file_name == $requestUri){
				return 'active';
		}

	}




	function LDAP_ServerByCompany($company){ 
	    $server_name = null; 
	     
	    switch($company){ 
	        case "BBC": 
	            $server_name = 'tt-dcsvr-01-01.tt.bgl.local'; 
	            break; 
	        case "HSL": 
	            $server_name = 'hs-dcsvr-06-01.tt.bgl.local'; 
	            break; 
	             
	    }      
	    return $server_name;   
	}

	function getUsersIdByEmail($email){
		$db = new Database();
		$db_name = RAADMA_DB;
		$users_id = null;

		if(isset($email)){
			$conn_wip = $db->connect_to_database($db_name);

			$query_string = "SELECT id FROM ".RAADMA_LIVE_USER_ACCOUNTS_TBL." WHERE email_address = '".$email."'";

			$query_executed = $db->executeQuery($query_string, $conn_wip);


			$query_results = mssql_fetch_assoc($query_executed);

			$users_id = $query_results['id'];

		}

		return $users_id;
	}

	function getListOfModulesAssigned($users_id){
		$db = new Database();
		$db_name = RAADMA_DB;
		$modules_array = array();

		if(isset($users_id)){
			$conn = $db->connect_to_database($db_name);

			$query_string = "SELECT  m.module FROM ".RAADMA_LIVE_MODULES_TBL." m , ".RAADMA_LIVE_USER_MODULES_TBL." um WHERE um.user_account_fk_id = ".$users_id." AND m.id = um.module_id_fk";

			$query_executed = $db->executeQuery($query_string, $conn);

			$count = 0;
			while($rows = mssql_fetch_assoc($query_executed)){
				$modules_array[$count] = $rows['module'];
				$count++;
			}
		}

		return $modules_array;
	}


	function GetListOfAccessRights(){

		$access_right_list = null;

		$db = new Database();
		$db_name = RAADMA_DB;
		$conn = $db->connect_to_database($db_name);

		$query_string = "SELECT id , access_right FROM ".RAADMA_LIVE_ACCESS_RIGHTS_TBL;

		$exec = $db->executeQuery($query_string, $conn);

		while($rows = mssql_fetch_assoc($exec)){
			$access_right_list[$rows['id']] = $rows['access_right'];
		}

		return $access_right_list;

	}

	function checkIfModuleIsAssignedToUser($users_id, $module_code){
		$modules_array = getListOfModulesAssigned($users_id);
		$boolean_value = 0;

		if(in_array($module_code, $modules_array)){
			$boolean_value = 1;
		}

		return $boolean_value;
	}

	function checkIfMainModuleIsAssignedToUser($users_id, $module_constant){
		$modules_array = getListOfModulesAssigned($users_id);
		$boolean_value = 0;

		$modules_in_main = unserialize ($module_constant);
		foreach ($modules_in_main as $value){
		   $boolean_value = checkIfModuleIsAssignedToUser($users_id, $value);

		   if($boolean_value == 1){
		   		break;
		   }
		}
		return $boolean_value;
	}


	function listOfMonths(){

		$month_array = array(1 => 'January' , 2 => 'Feburary' , 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June' ,  7 => 'July' , 8 => 'August', 9 => 'September' , 10 => 'October' , 11 => 'November' ,  12 => 'December');

		return $month_array;

	}




	function getKeyfromListOfMonths($month_value){
		$key = array_search($month_value, listOfMonths());

		return $key;
	}


	function listOfYears(){
		$years_array = array();

		$year_start = 2010;
		while($year_start <= 2099){
			$years_array['Year '.$year_start] = $year_start;

			$year_start++;
		}

		return $years_array;

	}


	function checkIfUserHasAccessToAHSLModule($users_id){
		$boolean_status = 0;

		if(isset($users_id)){
			$db = new Database();
			$db_name = RAADMA_DB;
			$conn = $db->connect_to_database($db_name);


			$query_string = "SELECT COUNT(*) FROM ".RAADMA_LIVE_USER_MODULES_TBL." um , ".RAADMA_LIVE_COMPANY_TBL." c, ".RAADMA_LIVE_USER_ACCOUNTS_TBL." ua  WHERE um.user_account_fk_id = ".$users_id." and ua.company_id_fk = ".KBCL_COMPANY_ID." and ua.company_id_fk = c.id";
			$query_executed = $db->executeQuery($query_string, $conn);
			$results = mssql_fetch_array($query_executed);
			$count = $results[0];

			if($count >= 1){
				$boolean_status = 1;
			}

		
		}

		return $boolean_status;
	}


	function convertMonthToInt($month){
		$value = null;
		switch($month){

			case "January":
				$value = '01';
				break;

			case "Feburary":
				$value = '02';
				break;

			case "March":
				$value = '03';
				break;

			case "April":
				$value = '04';
				break;

			case "May":
				$value = '05';
				break;

			case "June":
				$value = '06';
				break;

			case "July":
				$value = '07';
				break;

			case "August":
				$value = '08';
				break;

			case "September":
				$value = '09';
				break;

			case "October":
				$value = '10';
				break;

			case "November":
				$value = '11';
				break;

			case "December":
				$value = '12';
				break;
		}

		return $value;
	}

	function convertMonthAbbr($month){
		$month_name = null;

		switch ($month) {
			case "January":
				$month_name = "Jan.";
				break;
			case "Feburary":
				$month_name = "Feb.";
				break;
			case "March":
				$month_name = "Mar.";
				break;
			case "April":
				$month_name = "Apr.";
				break;
			case "May":
				$month_name = "May";
				break;
			case "June":
				$month_name = "June";
				break;
			case "July":
				$month_name = "July";
				break;
			case "August":
				$month_name = "Aug.";
				break;
			case "September":
				$month_name = "Sept.";
				break;
			case "October":
				$month_name = "Oct.";
				break;
			case "November":
				$month_name = "Nov.";
				break;
			case "December":
				$month_name = "Dec.";
				break;
		}

		return $month_name;
	}

	function GetListOfMonths(){
		$listOfMonths = array("01" => "January", "02" => "Feburary","03" => "March","04" => "April","05" => "May","06" => "June","07" => "July","08" => "August","09" => "September","10" => "October","11" => "November","12" => "December");
		return $listOfMonths;
	}



	function GetLastDayOfMonth($year, $month){
		$last_day = null;


		if(isset($year) && isset($month)){
			$dt = "$year-$month-01";
			$last_day = date("Y-m-t", strtotime($dt));
		}

		return $last_day;

	}

	function GetDayFromDate($date_string){
		 $date = date_create($date_string);
		 return date_format($date,"l");;
	}


	function GetLastDayOfMonthInt($year, $month){
		$last_day = null;

		if(isset($year) && isset($month)){
			$date = getLastDayOfMonth($year, $month);
			$date_array = explode("-", $date);
			$last_day = $date_array[2];
		}
		return $last_day;
	}



	function GetLastUserId(){
		$db = new Database();
		$db_name = RAADMA_DB;
		$id = null;
		$conn = $db->connect_to_database($db_name);
		$query_string = "SELECT MAX(id) FROM ".	RAADMA_LIVE_USER_ACCOUNTS_TBL;
		$query_executed = $db->executeQuery($query_string, $conn);
		$query_array_results = mssql_fetch_array($query_executed);

		$id = $query_array_results[0];

		return $id;

	}

	function GetLastIdForTable($table, $db_name){

		if(isset($table) && isset($db_name)){
			$db = new Database();

			$database_name = $db_name;

			$conn = $db->connect_to_database($database_name);

			$query_string = "SELECT MAX(id) FROM ".$table;

			$exec = $db->executeQuery($query_string, $conn);

			$query_array_results = mssql_fetch_array($exec);
			$id = $query_array_results[0];
		}

		return $id;

	}

	function DeleteRowById($id, $table, $db_name){
		if(isset($id) && isset($table) && isset($db_name)){
			$db = new Database();
			$database_name = $db_name;
			$conn = $db->connect_to_database($database_name);
			$query_string = "DELETE FROM ".$table." WHERE id = ".$id;
			$db->executeQuery($query_string, $conn);
		}
	}

	function UserExists($email_address){
		$db = new Database();
		$db_name = RAADMA_DB;
		$bool_value = 0;
		$conn = $db->connect_to_database($db_name);
		$query_string = "SELECT COUNT(*) FROM ".RAADMA_LIVE_USER_ACCOUNTS_TBL." WHERE email_address = '".$email_address."'";
		$query_executed = $db->executeQuery($query_string, $conn);
		$query_array_results = mssql_fetch_array($query_executed);

		$num_of_users = $query_array_results[0];

		if($num_of_users == 1){
			$bool_value = 1;
		}

		return $bool_value;
	}

	function CountNumOfUsers(){
		$db = new Database();
		$db_name = RAADMA_DB;
		$num_of_users = null;
		$conn = $db->connect_to_database($db_name);
		$query_string = "SELECT COUNT(*) FROM ".RAADMA_LIVE_USER_ACCOUNTS_TBL;
		$query_executed = $db->executeQuery($query_string, $conn);
		$query_array_results = mssql_fetch_array($query_executed);

		$num_of_users = $query_array_results[0];

		return $num_of_users;
	}

	function CountNumOfUsersInCompany($company_id){
		$db = new Database();
		$db_name = RAADMA_DB;
		$num_of_users =  null;

		if(isset($company_id)){
			$conn = $db->connect_to_database($db_name);
			$query_string = "SELECT COUNT(*) FROM ".RAADMA_LIVE_USER_ACCOUNTS_TBL." WHERE company_id_fk = ".$company_id;
			$exec = $db->executeQuery($query_string, $conn);
			$query_array_results = mssql_fetch_array($exec);
			$num_of_users = $query_array_results[0];
			
		}

		return $num_of_users;
	}



	/*Code for late mins weekly employees */
	function GetListOfEmployees(){
		$db = new Database();
		$db_name = HSL_DB;
		$conn_hsl = $db->connect_to_database($db_name);
		$array_list_of_emp = null;
		$query_string = "SELECT EMPLOYID, FULLNAME FROM ".HSL_WEEKLY_PAID_EMP_VIEW;
		$exec = $db->executeQuery($query_string, $conn_hsl);


		while($rows =  mssql_fetch_assoc($exec)){
			$array_list_of_emp[trim($rows['EMPLOYID'])] = $rows['FULLNAME'];
			
		}

		return $array_list_of_emp;
	}

	function GetEmployeeListForSelection()
	{
		$db = new Database();
		$db_name = HSL_DB;
		$emp_list = array();

		$conn_hsl = $db->connect_to_database($db_name);
		$query_string = "SELECT EMPLOYID, FULLNAME FROM ".HSL_WEEKLY_PAID_EMP_VIEW. " order by FULLNAME";
		$exec = $db->executeQuery($query_string, $conn_hsl);

		while($rows = mssql_fetch_array($exec)){
			$emp_list[trim($rows['EMPLOYID'])] = $rows['FULLNAME']. " - ". $rows['EMPLOYID'];
		}

		return $emp_list;

	}

	function GetEmployeeNameById($id){
		if(isset($id)){
			$db = new Database();
			$db_name = HSL_DB;
			$conn_hsl = $db->connect_to_database($db_name);
			$query_string = "SELECT FULLNAME FROM ".HSL_WEEKLY_PAID_EMP_VIEW." WHERE EMPLOYID = ".$id;
			$exec = $db->executeQuery($query_string, $conn_hsl);
			$array_results = mssql_fetch_assoc($exec);
			$emp_name = $array_results['FULLNAME'];

		}
		return $emp_name;
	} 

	function GetListOfShifts(){
		$db = new Database();
		$db_name = RAADMA_DB;
		$conn_rad_db = $db->connect_to_database($db_name);
		$array_list_of_shift_nums = null;
		$query_string = "SELECT id, description FROM ".HSL_SHIFT_NUMS_TBL;
		$exec = $db->executeQuery($query_string, $conn_rad_db);


		while($rows =  mssql_fetch_assoc($exec)){
			$array_list_of_shift_nums[$rows['id']] = $rows['description'];
			
		}

		return $array_list_of_shift_nums;
	}


	function GetListOfSupervisorNotifiedResponses(){
		$db = new Database();
		$db_name = RAADMA_DB;
		$array_list = null;
		$conn_rad_db = $db->connect_to_database($db_name);
		$query_string = "SELECT id , status  FROM ".RAADMA_LIVE_YES_NO_TBL;
		$exec = $db->executeQuery($query_string, $conn_rad_db);	

		while($rows = mssql_fetch_assoc($exec)){
			$array_list[$rows['id']] = $rows['status'];
		}

		return $array_list;
	}


	function GetListOfHoursWeeklyPaid(){
		$array_list_of_hrs = null;
		$count = 0;
		while($count <= 8){
			$array_list_of_hrs[$count] = $count;
			$count++;
		}

		return $array_list_of_hrs;
	}


	function GetListOfMinutesWeeklyPaid(){
		$array_list_of_hrs = null;
		$count = 0;
		while($count <= 59){
			$array_list_of_hrs[$count] = $count;
			$count++;
		}

		return $array_list_of_hrs;
	}


	function GetListOfCompanies(){
		$array_list_of_compaines = null;

		$db = new Database();
		$db_name = RAADMA_DB;
		$conn_raadma_live = $db->connect_to_database($db_name);

		$query_string = "SELECT id, company FROM ".RAADMA_LIVE_COMPANY_TBL;
		$exec =  $db->executeQuery($query_string, $conn_raadma_live);

		while($rows = mssql_fetch_array($exec)){
			$array_list_of_compaines[$rows['id']] = $rows['company'];
		}
		return $array_list_of_compaines;
	}


	function GetListOfModules(){
		$array_list_of_modules = null;

		$db = new Database();
		$db_name = RAADMA_DB;
		$conn_raadma_live = $db->connect_to_database($db_name);

		$query_string = "SELECT id , module FROM ".RAADMA_LIVE_MODULES_TBL;
		$exec = $db->executeQuery($query_string, $conn_raadma_live);

		while($rows = mssql_fetch_array($exec)){
			$array_list_of_modules[$rows['id']] = $rows['module'];
		}
		return $array_list_of_modules;
	}

	function GetListOfUserAccounts(){
		$array_list_of_ua_ids = array();
		$array_list_of_fullnames = array();
	    $array_list_of_user_email = array();
		$array_list_of_user_accounts = array();
		$db = new Database();
		$db_name = RAADMA_DB;
		$conn_raadma_live = $db->connect_to_database($db_name);

		$query_string = "SELECT ua.id as UA_ID, ua.first_name + ' ' + ua.last_name as fullname, ua.email_address as UA_EMAIL, ua.username as UA_USERNAME, c.company AS C_COMPANY  FROM ".RAADMA_LIVE_USER_ACCOUNTS_TBL." ua , ".RAADMA_LIVE_COMPANY_TBL
		." c WHERE ua.company_id_fk = c.id";

		$exec = $db->executeQuery($query_string, $conn_raadma_live);

		while($rows = mssql_fetch_array($exec)){
			$array_list_of_user_accounts[$rows['UA_ID']] = $rows['fullname'] . '|' . $rows['UA_EMAIL'] . '|'. $rows['C_COMPANY'];

		}

		return  $array_list_of_user_accounts;
	}


	function GetListOfDepartments(){
		$array_list_of_depts = null;

		$db = new Database();
		$db_name = HSL_DB;
		$conn_hsl_live = $db->connect_to_database($db_name);

		$query_string = "SELECT DEPRTMNT , DSCRIPTN FROM ".HSL_DEPARTMENT_TBL." ORDER BY DSCRIPTN";
		$exec = $db->executeQuery($query_string, $conn_hsl_live);

		while($rows = mssql_fetch_array($exec)){
			$array_list_of_depts[$rows['DEPRTMNT']] = $rows['DSCRIPTN'];
		}
		return $array_list_of_depts;
	}

	function GetUA($value){
		$array_to_return = array();
		$user_account = GetListOfUserAccounts();
		switch($value){
			case "UA_ID":
			foreach ($user_account as $key => $value) {
				$arr = explode("|" , $value);
				$array_to_return[$key] = $key;
			}
			break;

			case "UA_FULLNAME":
			foreach ($user_account as $key => $value) {
				$arr = explode("|" , $value);
				$array_to_return[$key] = $arr[0];
			}
			break;

			case "UA_EMAIL" :
			foreach ($user_account as $key => $value) {
				$arr = explode("|" , $value);
				$array_to_return[$key] = $arr[1];
			}
			break;


			case "UA_COMPANY":
			foreach ($user_account as $key => $value) {
				$arr = explode("|" , $value);
				$array_to_return[$key] = $arr[2];
			}
			break;

			
		}
		return $array_to_return;
	}

	function UserIsAdmin($users_id){
		$boolean_value = checkIfMainModuleIsAssignedToUser($users_id, ADMIN_MODULE);
		return $boolean_value;
	}



	function GetAccessRightForModule($user_id, $module){

		$access_right = null;

		if(isset($user_id) && isset($module)){
			$db = new Database();
			$db_name = RAADMA_DB;
			$conn = $db->connect_to_database($db_name);

			$query_string = "SELECT ar.access_right FROM ACCESS_RIGHTS ar, USER_MODULES um, MODULES m WHERE um.access_right_id_fk = ar.id AND m.id = um.module_id_fk AND um.user_account_fk_id = ".$user_id." AND m.module = '".$module."'";

			$exec = $db->executeQuery($query_string, $conn);

			$array_results = mssql_fetch_array($exec);
			$access_right = $array_results[0];

		}
		return $access_right;
		

	}

	function CanUserAccessPage($user_id, $module_id){
		$user_can_access_page = false;

		if(isset($user_id) && isset($module_id)){

			if(UserIsAdmin($user_id) == 1){
				$user_can_access_page = true;
			}else{
				$db = new Database();
				$db_name = RAADMA_DB;
				$conn = $db->connect_to_database($db_name);
				$query_string = "SELECT COUNT(*) FROM ".RAADMA_LIVE_USER_MODULES_TBL." WHERE user_account_fk_id = ".$user_id." AND module_id_fk = ".$module_id;

				$exec = $db->executeQuery($query_string, $conn);
				$array_results = mssql_fetch_array($exec);
				if($array_results[0] == 1){
					$user_can_access_page = true;
				}
			}
		}

		return $user_can_access_page;
	}

	function GetModuleIdFromName($module){
		$module_id = null;
		if(isset($module)){
			$db = new Database();
			$db_name = RAADMA_DB;
			$conn = $db->connect_to_database($db_name);
			$query_string = "SELECT id FROM ".RAADMA_LIVE_MODULES_TBL." WHERE module = '".$module."'";
			$exec = $db->executeQuery($query_string, $conn);
			$array_results = mssql_fetch_array($exec);
			$module_id = $array_results[0];
		}

		return $module_id;
	}

	function ConvertTimeToMins($hours, $mins){
		$total_mins = ($hours * 60) + $mins;
		return $total_mins;

	}


	function GetCurrentDateAndTime(){
	    $current_date_and_time = date("Y-m-d h:i:sa");  
	    return $current_date_and_time;
	}
	

	function GetLateMinsInfo($id, $col_name){
		$col_value = null;
		if(isset($id) && isset($col_name)){
				$db = new Database();
				$db_name = RAADMA_DB;
				$conn = $db->connect_to_database($db_name);
				$query_string = "SELECT ".$col_name." FROM ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL." WHERE id = '".$id."'";
				$exec = $db->executeQuery($query_string, $conn);
				$query_array_results = mssql_fetch_array($exec);
				$col_value = $query_array_results[0];

		}

		return $col_value;

	}




	function ConvertToDateTime($date_string){
		if(isset($date_string)){
			$date_create = date_create($date_string);
			$date_format = date_format($date_create, 'Y/m/d H:i:s');

			return $date_format;
		}
	}

	function GetMinsRange(){
		$array_of_mins = array();
		$start = 0;
		while($start <= 500){
			$array_of_mins[$start] = $start;
			$start++;
		}
		return $array_of_mins;
	}


	function GetDeptByCode($code){
		$dept = null;
		if(isset($code)){
			$db = new Database();
			$db_name = HSL_DB;
			$conn = $db->connect_to_database($db_name);

			$query_string = "SELECT DSCRIPTN FROM ".HSL_DEPARTMENT_TBL." WHERE DEPRTMNT = '".$code."'";

			$exec = $db->executeQuery($query_string, $conn);

			$array_results = mssql_fetch_assoc($exec);
			$dept = $array_results['DSCRIPTN'];
		}
		return $dept;
	}

	function GetDeptForEmp($emp_id){
		$dept = null;
		if(isset($emp_id)){
			$db = new Database();
			$db_name = HSL_DB;
			$conn = $db->connect_to_database($db_name);

			$query_string = "SELECT DSCRIPTN FROM ".HSL_WEEKLY_PAID_EMP_VIEW." WHERE EMPLOYID = '".$emp_id."'";

			$exec = $db->executeQuery($query_string, $conn);

			$array_results = mssql_fetch_assoc($exec);
			$dept = $array_results['DSCRIPTN'];
		}
		return $dept;
	}

	function GetShiftById($id){
		$shift_string = null;

		if(isset($id)){
			$db = new Database();
			$db_name = RAADMA_DB;
			$conn = $db->connect_to_database($db_name);

			$query_string = "SELECT description FROM ".RAADMA_LIVE_SHIFT_NUMS_TBL." WHERE shiftNumber = '".$id."'";

			$exec = $db->executeQuery($query_string, $conn);

			$array_results = mssql_fetch_assoc($exec);
			$shift_string = $array_results['description'];
		}
		return $shift_string;
	}

	/*Needs to be run only once */
	 function CreateAdminAccount($username, $password="Baker12"){
        $db = new Database();
        $db_name = RAADMA_DB;
        $conn = $db->connect_to_database($db_name);

        $firstName = "ADMIN";
        $lastName = "ADMIN";
        $email_address = null;
        $username = "ADMIN";
        $password = sha1($password);
        $created_date_time = ConvertToDateTime(GetCurrentDateAndTime());
        $created_by_username = $username;

       $query_string = "INSERT INTO ".RAADMA_LIVE_USER_ACCOUNTS_TBL. " (first_name, last_name, email_address, username, password, company_id_fk, created_date_time, created_by_username) VALUES('".$firstName."', '".$lastName."', '".$email_address."', '".$username."', '".$password."', 2, '".$created_date_time."', '".$created_by_username."')";

       $db->executeQuery($query_string, $conn);
       $user_id = GetLastUserId();

       $query_string_2 = "INSERT INTO ".RAADMA_LIVE_USER_MODULES_TBL." (user_account_fk_id, access_right_id_fk, module_id_fk, created_date_time, created_by_username) VALUES ('".$user_id."', 1, 1, '".$created_date_time."', '".$created_by_username."')";
       $db->executeQuery($query_string_2, $conn);
   }


   function GetMISUserId(){
   		$user_id = null;

   		$db = new Database();
   		$db_name = RAADMA_DB;
   		$conn = $db->connect_to_database($db_name);

   		$query_string = "SELECT id FROM ".RAADMA_LIVE_USER_ACCOUNTS_TBL. " WHERE username = 'MIS'";
   		$exec = $db->executeQuery($query_string, $conn);
   		$array_results = mssql_fetch_array($exec);

   		$user_id = $array_results[0];

   		return $user_id;
   }


   function getDayFromSelectedDate($selected_date){
   		return  date('l jS \of F Y ', strtotime($selected_date));
   }

   
   function rowCount($db_name, $table_name, $id){
   		$row_count = 0;
   		if(isset($db_name) && isset($table_name) && isset($id)){
	   		$db = new Database();
	   		$database_name = $db_name;
	   		$conn = $db->connect_to_database($database_name);

	   		$query_string = "SELECT COUNT(".$id.") FROM ".$table_name;
	   		$exec = $db->executeQuery($query_string, $conn);

	   		$array_results = mssql_fetch_array($exec);
	   		$row_count = $array_results[0];
   	     }

   	     return $row_count;
   }

   function CheckForDuplicateRecordLateMins($emp_id, $leaveDate){
   		$bool_value = false;

   		$db = new Database();
   		$db_name = RAADMA_DB;
   		$conn = $db->connect_to_database($db_name);

   		$query_string = "SELECT COUNT(employeeId) FROM ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL." WHERE leaveDate = '".$leaveDate."' AND employeeId = ".$emp_id;
   		$exec = $db->executeQuery($query_string, $conn);

   		$array_results = mssql_fetch_array($exec);
   		$count = $array_results[0];

   		if($count >= 1){
   			$bool_value = true;
   		}

   		return $bool_value;
   }

    function CheckIfRecordExistsForLateMins($emp_id, $leaveDate){
   		$bool_value = false;

   		$db = new Database();
   		$db_name = RAADMA_DB;
   		$conn = $db->connect_to_database($db_name);

   		$query_string = "SELECT COUNT(employeeId) FROM ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL." WHERE leaveDate = '".$leaveDate."' AND employeeId = ".$emp_id;
   		$exec = $db->executeQuery($query_string, $conn);

   		$array_results = mssql_fetch_array($exec);
   		$count = $array_results[0];

   		if($count >= 1){
   			$bool_value = true;
   		}

   		return $bool_value;
   }



   function GetRecordIdFromLeaveData($emp_id, $leave_date){
		$record_id = null;
		
		if(isset($emp_id) && isset($leave_date)){
			$db = new Database();
			$db_name = RAADMA_DB;
			$conn = $db->connect_to_database($db_name);
			$query_string = "SELECT id FROM ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL. " WHERE employeeId = '".$emp_id."' AND leaveDate = '".$leave_date."'";
			$exec = $db->executeQuery($query_string, $conn);

			$array_results = mssql_fetch_array($exec);
			$record_id = $array_results[0];
		}

		return $record_id;
	}

	function convertToDay($value){
		$day = null;

		if(isset($value)){
			if($value <= 9){
				$day =  "0".$value;
			}else{
				$day = $value;
			}
		}

		return $day;
	}

	function buildDate($year, $month , $day){
		return $year . "-".$month. "-" . $day;
	}




	function GetHoursFromMins($mins){
		$value =0;
		if($mins >= 60){
			$value =   intval($mins/60);
		}
		return $value;
	}

	function GetRemainderMins($mins){
		$hrs = GetHoursFromMins($mins);
		$total_hrs_mins = $hrs * 60;
		$remaining_mins = $mins - $total_hrs_mins;
		return $remaining_mins;
	}


	function buildFirstDayOfMonth($year, $month){
		return "$year-$month-01";
	}


	function buildDay($year, $month, $day){
		return "$year-$month-$day";

	}

	function GetTotalEmpForDept($dept_code){
		$total_num_of_emp = null;

		if(isset($dept_code)){
			$db = new Database();
			$db_name = HSL_DB;
			$conn = $db->connect_to_database($db_name);
			$query_string = "SELECT COUNT(*) FROM ".HSL_WEEKLY_PAID_EMP_VIEW." WHERE DEPRTMNT IN ($dept_code)";	
			$exec = $db->executeQuery($query_string, $conn);
			$array_results = mssql_fetch_array($exec);
			$total_num_of_emp = $array_results[0];

			

		}
		return $total_num_of_emp;
	}

	function NumberOfEmpAbsentFourOrMoreDaysByMonth($selected_year, $selected_month, $dept_code){
		$total_number_absent = 0;

		if(isset($selected_year) && isset($selected_month) && $dept_code){

			$db = new Database();
			$db_name = HSL_DB;
			$conn = $db->connect_to_database($db_name);

			$month = convertMonthToInt($selected_month);
			$firstDay = buildFirstDayOfMonth($selected_year, $month);
			$lastDayOfMonth = GetLastDayOfMonthInt($selected_year, $month);
			$lastDay = buildDay($selected_year, $selected_month, $lastDayOfMonth);

			$query_string = "SELECT COUNT(DISTINCT ELL.employeeId) FROM ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS ELL, ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL_W WHERE ELL.leaveDate >= '$firstDay' AND ELL.leaveDate <= '$lastDay' AND HSL_W.EMPLOYID = ELL.employeeId AND HSL_W.DEPRTMNT IN($dept_code) GROUP BY ELL.employeeId HAVING COUNT(ELL.employeeId) >= 4";
			$exec = $db->executeQuery($query_string, $conn);
			$count = 0;
			while($rows = mssql_fetch_array($exec)){
				$total_number_absent = $total_number_absent + $rows[0];
			}
		}

		return $total_number_absent;

	}


	function NumberOfAbsentDaysByMonth($selected_year, $selected_month, $dept_code){

		$total_number_absent = 0;
		if(isset($selected_year) && isset($selected_month) && $dept_code){

			$db = new Database();
			$db_name = HSL_DB;
			$conn = $db->connect_to_database($db_name);

			$month = convertMonthToInt($selected_month);
			$firstDay = buildFirstDayOfMonth($selected_year, $month);
			$lastDayOfMonth = GetLastDayOfMonthInt($selected_year, $month);
			$lastDay = buildDay($selected_year, $selected_month, $lastDayOfMonth);

			$query_string = "SELECT COUNT(DISTINCT ELL.leaveDate) FROM ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS ELL, ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL_W  WHERE ELL.leaveDate >= '$firstDay' AND ELL.leaveDate <= '$lastDay' AND HSL_W.EMPLOYID = ELL.employeeId AND HSL_W.DEPRTMNT IN($dept_code)";

			$exec = $db->executeQuery($query_string, $conn);
			$array_results = mssql_fetch_array($exec);

			$total_number_absent = $array_results[0];

		}

		return $total_number_absent;
	}


	function TotalNumberOfLateMinsByMonth($selected_year, $selected_month, $dept_code){

		$total_amt_mins_late = 0;
		if(isset($selected_year) && isset($selected_month) && $dept_code){
			$db = new Database();
			$db_name = HSL_DB;
			$conn = $db->connect_to_database($db_name);

			$month = convertMonthToInt($selected_month);
			$firstDay = buildFirstDayOfMonth($selected_year, $month);
			$lastDayOfMonth = GetLastDayOfMonthInt($selected_year, $month);
			$lastDay = buildDay($selected_year, $selected_month, $lastDayOfMonth);

			$query_string = "SELECT SUM(ELL.minutesAwayFromWork) FROM ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS ELL, ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL_W WHERE ELL.leaveDate >= '$firstDay' AND ELL.leaveDate <= '$lastDay' AND HSL_W.EMPLOYID = ELL.employeeId AND HSL_W.DEPRTMNT IN($dept_code)";

			$exec = $db->executeQuery($query_string, $conn);
			$array_results = mssql_fetch_array($exec);

			$total_amt_mins_late = $array_results[0];

			if($total_amt_mins_late == NULL || $total_amt_mins_late == ''){
				$total_amt_mins_late = 0;
			}
		}

		return $total_amt_mins_late;
	}


	function TotalNumberLateThreeOrMore($selected_year, $selected_month, $dept_code){

		$total_num_of_emp = 0;
		if(isset($selected_year) && isset($selected_month) && $dept_code){
			$db = new Database();
			$db_name = HSL_DB;
			$conn = $db->connect_to_database($db_name);

			$month = convertMonthToInt($selected_month);
			$firstDay = buildFirstDayOfMonth($selected_year, $month);
			$lastDayOfMonth = GetLastDayOfMonthInt($selected_year, $month);
			$lastDay = buildDay($selected_year, $selected_month, $lastDayOfMonth);

			$query_string = "SELECT COUNT(DISTINCT ELL.employeeId) FROM ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS ELL, ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL_W WHERE ELL.leaveDate >= '$firstDay' AND ELL.leaveDate <= '$lastDay' AND HSL_W.EMPLOYID = ELL.employeeId AND HSL_W.DEPRTMNT IN($dept_code) GROUP BY ELL.employeeId HAVING COUNT(ELL.leaveDate) >= 3 AND SUM(ELL.minutesAwayFromWork) >= 10";

			$exec = $db->executeQuery($query_string, $conn);

			while($rows = mssql_fetch_array($exec)){
				$total_num_of_emp = $total_num_of_emp + $rows[0];
			}

			
		}

		return $total_num_of_emp;
	}




?>