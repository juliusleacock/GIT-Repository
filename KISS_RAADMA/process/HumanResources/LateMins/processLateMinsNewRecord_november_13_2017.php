<?php 
	/* processLateMinsNewRecord.php */

	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/classes/WeeklyEmployeeLeave.php");


	$records_created = 0;
	$emp_count = $_POST['results_count'];
	$index_value = 0;
	$message = '';
	$initial_row_count = rowCount(RAADMA_DB, RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL, 'id');
	$number_of_records_not_inserted = 0;

	while($index_value < $emp_count){

	$selected_hrs = $_POST['selected_hours'][$index_value];
	$selected_mins = $_POST['selected_mins'][$index_value];
	if($selected_hrs == 0 && $selected_mins == 0){
		$index_value++;
		continue;
	}else{ 
		$employee = $_POST['emp_id'][$index_value];
		$leave_date = $_POST['leaveDate'];
		$hours = $_POST['selected_hours'][$index_value];
		$mins = $_POST['selected_mins'][$index_value];
		$shift = $_POST['selected_shift'];
		$supervisor_response = $_POST['selected_notification'][$index_value];
		$comments = $_POST['comments'][$index_value];
		$username = $_POST['username'];
		$total_mins = ConvertTimeToMins($hours, $mins);

		if(CheckForDuplicateRecordLateMins($employee, $leave_date)){
			$index_value++;
			$message = appendMessage2($message," |" .GetEmployeeNameById($employee));
			$number_of_records_not_inserted++;
			continue;
		}

		$emp_class = new WeeklyEmployeeLeave($employee, $leave_date, $total_mins, $shift, $supervisor_response, $comments, $username);

		$db = new Database();
		$db_name = RAADMA_DB;
		$conn_raadma = $db->connect_to_database($db_name);

		$query_string = "INSERT INTO ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL."(employeeId, leaveDate, minutesAwayFromWork, leaveId, shiftNumber, supervisorNotified, comments, createdByUsername, createdDateTime) VALUES ('".$emp_class->getEmpId()."', '".$emp_class->getLeaveDate()."', '".$emp_class->getMinsAwayFromWork()."', '".$emp_class->getLeaveId()."', '".$emp_class->getShiftNumber()."', '".$emp_class->getSupervisorNotified()."', '".$emp_class->getComments()."', '".$emp_class->getCreatedByUsername()."', '".$emp_class->getCreatedDateTime()."')"; 

		$db->executeQuery($query_string, $conn_raadma);

		$records_created++;
	}

	$index_value++;

	}

	$current_row_count= rowCount(RAADMA_DB, RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL, 'id');

	if($current_row_count > $initial_row_count){
		echo checkValue($records_created);
	}else{
		echo "No new rows have been added\n";
	}

	if(!empty($message)){
		if($number_of_records_not_inserted > 1){
			echo $number_of_records_not_inserted." records were already added for employees: ".$message."\n";
		}else{
			echo $number_of_records_not_inserted." record was already added for employee: ".$message."\n";
		}
	}

	function checkValue($value){
		$string_text = 'records';
		if($value == 1){
			$string_text = "Success ".$value." new record has been added!\n";
		}else{
			$string_text = "Success ".$value." new records have been added!\n";
		}
		return $string_text;
	}

	//echo $message;

?>