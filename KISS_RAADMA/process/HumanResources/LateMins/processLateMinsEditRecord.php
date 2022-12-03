	<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/classes/WeeklyEmployeeLeave.php");




	$message = '';
	$response_array = array();
	$record_id = $_POST['record_id'];
	$emp_id = $_POST['edit_employee'];
	$leave_date = $_POST['edit_leave_date'];
	$hours = $_POST['edit_hours'];
	$mins = $_POST['edit_mins'];
	$shift = $_POST['edit_shift'];
	$user = $_POST['username'];
	$restrict_update = false;

	$total_mins = ConvertTimeToMins($hours, $mins);



	$emp_class = new WeeklyEmployeeLeave($emp_id, $leave_date, $total_mins, $shift, null, null, null, null, null, $user, null);


	$db = new Database();
	$db_name = RAADMA_DB;
	$conn = $db->connect_to_database($db_name);

	if($total_mins == 0 || $leave_date == ""){
		$restrict_update = true;
	}



	if($restrict_update == true){
		$message = "Cannot do update. Invalid leave data.";
	}else  if(CheckForDuplicateUpdatesLateMins($emp_id, $leave_date, $record_id)){
			$query_string= "UPDATE ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL." SET employeeId = '".$emp_class->getEmpId()."' , leaveDate = '".$emp_class->getLeaveDate()."', minutesAwayfromWork = '".$emp_class->getMinsAwayFromWork()."' , shiftNumber = '". $emp_class->getShiftNumber()."', supervisorNotified = '".$emp_class->getSupervisorNotified()."', comments = '".$emp_class->getComments()."', lastModifiedByUsername = '".$emp_class->getLastModifiedByUsername()."', lastModifiedDateTime = '".$emp_class->getLastModifiedDateTime()."' WHERE id = '".$record_id."'";

			$db->executeQuery($query_string, $conn);
			$message = "Success: Update done!";
	}else{
			$message = "Cannot do update...record possibly exists for employeee '".GetEmployeeNameById($emp_id)."' for leave date '".$leave_date."'. Please select the correct record to update!";
	}
	

	$response_array['message'] = $message;

	$my_json_encoded_response = json_encode($response_array);  




       echo $my_json_encoded_response;

	

	
/* */
	function CheckForDuplicateUpdatesLateMins($emp_id, $leave_date, $record_id_to_check){
		$proceed_with_update = false;
		if(CheckForDuplicateRecordLateMins($emp_id, $leave_date)){
			$proceed_with_update = false;
			$record_id = GetRecordIdFromLeaveData($emp_id, $leave_date);
			if($record_id == $record_id_to_check){
				$proceed_with_update = true;
			}else{
				$proceed_with_update = false;
			}

		}else{
			$proceed_with_update = true;
		}

		return $proceed_with_update;

	}





	?>