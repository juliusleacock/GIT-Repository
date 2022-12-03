<?php

date_default_timezone_set('America/Puerto_Rico');

require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");

class WeeklyEmployeeLeave{


	//$dt_value= date('Y-m-d H:i:s')
   	private $dt_value = null;

	private $empId = null;
	private $leaveDate = null;
	private $minsAwayFromWork = 0;
	private $leaveId = 0;
	private $shiftNumber = 0;
	private $supervisorNotified = 0;
	private $comments = null;
	private $createdByUsername = null;
	private $createdDateTime = null;
	private $lastModifiedByUsername = "";
	private $lastModifiedDateTime = null;

    



	/*Used for creating a new record*/
	public function __construct($empId, $leaveDate, $minsAwayFromWork,  $shiftNumber, $supervisorNotified, $comments, $createdByUsername, $leaveId = null, $createdDateTime = null, $lastModifiedByUsername = null, $lastModifiedDateTime = null){
		
		$this->setEmpId($empId);
		$this->setLeaveDate($leaveDate);
		$this->setMinsAwayFromWork($minsAwayFromWork);
		$this->setShiftNumber($shiftNumber);
		$this->setSupervisorNotified($supervisorNotified);
		$this->setComments($comments);
		$this->setCreatedByUsername($createdByUsername);
		$this->setLeaveId($leaveId);
		$this->setCreatedDateTime($createdDateTime);
		$this->setLastModifiedByUsername($lastModifiedByUsername);
		$this->setLastModifiedDateTime($lastModifiedDateTime);
	}


	public function setEmpId($emp_id){
		if(isset($emp_id)){
			$this->empId = trim($emp_id);
		}

	}

	public function getEmpId(){
		return $this->empId;
	}

	public function setLeaveDate($leave_date){
		if(isset($leave_date)){
			$this->leaveDate = $leave_date;
		}
	}

	public function setLeaveId($leave_id){
		if(isset($leaveId)){
			$this->leaveId = $leave_id;
		}else{
			$this->leaveId = 9;
		}
	}

	public function getLeaveDate(){
		return $this->leaveDate;
	}

	public function setMinsAwayFromWork($min_away){
		if(isset($min_away)){
			$this->minsAwayFromWork = $min_away;
		}
	}

	public function getMinsAwayFromWork(){
		return $this->minsAwayFromWork;
	}

	public function getLeaveId(){
		return $this->leaveId;
	}

	public function setShiftNumber($shift_nos){
		if(isset($shift_nos)){
			$this->shiftNumber = $shift_nos;
		}
	}

	public function getShiftNumber(){
		return $this->shiftNumber;
	}


	public function setSupervisorNotified($notified){
		if(isset($notified)){
			$this->supervisorNotified = $notified;
		}
	}

	public function getSupervisorNotified(){
		return $this->supervisorNotified;
	}


	public function setComments($comments = null){
		if(isset($comments)){
			$this->comments = cleanString($comments);
		}
	}


	public function getComments(){
		return $this->comments;
	}

	public function setCreatedByUsername($username){
		if(isset($username)){
			$this->createdByUsername = $username;
		}
	}

	public function getCreatedByUsername(){
		return $this->createdByUsername;
	}



	public function setCreatedDateTime($created_date_time = null){
		$current_date = date_create(GetCurrentDateAndTime());
		if(isset($created_date_time)){
			$this->createdDateTime = $created_date_time;
		}else{
			$this->createdDateTime = date_format($current_date,'Y-m-d H:i:s');
		}
	}


	public function getCreatedDateTime(){
		return $this->createdDateTime;
	}

	public function setLastModifiedByUsername($username = null){
		if(isset($username)){
			$this->lastModifiedByUsername = $username;
		}
	}

	public function getLastModifiedByUsername(){
		return $this->lastModifiedByUsername;
	}

	public function setLastModifiedDateTime($mod_date_time = null){
		 $current_date = date_create(GetCurrentDateAndTime());
		if(isset($mod_date_time)){
			$this->lastModifiedDateTime = $mod_date_time;
		}else{
			$this->lastModifiedDateTime =  date_format($current_date,'Y-m-d H:i:s');
		}
	}

	public function getLastModifiedDateTime(){
		return $this->lastModifiedDateTime;
	}


	public function test() {
        var_dump(get_object_vars($this));
    }
	
}


?>