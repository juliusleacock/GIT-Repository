<?php
	/* tab pages */

	/* search-leave-data-tab-1.php */

?>

<form>
  <div class="form-group">
	  <fieldset class="form-group">
	    <legend>Search Criterias</legend>
		  <div class="form-check">
	      <label class="form-check-label">
		        <input type="radio" class="form-check-input" name="selection" id="selected_all_records" value="option1">
		        All Records
		      </label>
	  		</div>

	  		 <div class="form-check">
	      <label class="form-check-label">
		        <input type="radio" class="form-check-input" name="selection" id="selected_employee" value="option1">
		        By Selected Employee
		      </label>
	  		</div>

	  		 <div class="form-check">
	      <label class="form-check-label">
		        <input type="radio" class="form-check-input" name="selection" id="selected_date_range" value="option1">
		      	By Date Range
		      </label>
	  		</div>
	  		 <div class="form-check">
	      <label class="form-check-label">
		        <input type="radio" class="form-check-input" name="selection" id="selected_last_200" value="option1" >
		       Last 200
		      </label>
	  		</div>
       </fieldset>
    </div>
</form>


<div id="tab1_select_all_records_block" style="display : none">
<form class="form-inline" id="tab1_select_all_records_form" action="LateMinsLeaveTotalsWeeklyPaid" method="POST">	
	<div class="form-group">
		<label for="email">All Records:</label>
	</div>
	<button type="submit" class="btn btn-info test_btn" name = 'submit_tab_1' value="tab_1_search_all_records"><span class="glyphicon glyphicon-search"></span> Search</button>
</form>
</div>

<div id="tab1_select_employee_block" style="display : none">
<form class="form-inline" id="tab1_select_employee_form" action="LateMinsLeaveTotalsWeeklyPaid" method="POST">	
	<div class="form-group">
		<label for="email">Select Employee:</label>
		<select name="selected_employee" id="selected_employee_search" tabindex="2" class="form-control firstInput selected_employee_search">
			<?php foreach($listOfEmps as $key => $value){ ?>  
			<option value="<?php echo $key;?>"><?php echo $value; ?></option>
			<?php }?>
		</select>
	</div>
	<button type="submit" class="btn btn-info test_btn" name = 'submit_tab_1' value="tab_1_search_emp"><span class="glyphicon glyphicon-search"></span> Search</button>
</form>
</div>

<div id="tab1_select_date_range_block" style="display : none">
<form class="form-inline" id="tab1_select_date_range_form" action="LateMinsLeaveTotalsWeeklyPaid" method="POST">	
	<div class="form-group">
		<label for="email">Select Date Range:</label>
		<input type="text" name="tab_1_start_date" id="tab_1_start_date" class="datepicker form-control" tabindex="3" required> to <input type="text" name="tab_2_end_date" id="tab_2_end_date" class="datepicker form-control" tabindex="3" required>
	</div>
	<button type="submit" class="btn btn-info test_btn" name = 'submit_tab_1' value="tab_1_search_by_date_range"><span class="glyphicon glyphicon-search"></span> Search</button>
</form>
</div>

<div id="tab1_select_last_200_block" style="display : none">
<form class="form-inline" id="tab1_select_last_200_form" action="LateMinsLeaveTotalsWeeklyPaid" method="POST">	
	<div class="form-group">
		<label for="email">Last 200:</label>
	</div>
	<button type="submit" class="btn btn-info test_btn" name = 'submit_tab_1' value="tab1_select_last_200" id="submit_tab_1"><span class="glyphicon glyphicon-search"></span> Search</button>
</form>
</div>


<hr>

<div class="col-sm-12 table_search_tab1" >
 <br> 
<?php 
	$selected_all_records = null;
	$selected_employee = null;
	$selected_date_range_start = null;
	$selected_date_range_end = null;
	$selected_last_200 = null;
	$title = null;
	$search_criteria = null;
	$error_message = null;

	$db = new Database();
	$db_name = RAADMA_DB;
	$conn = $db->connect_to_database($db_name);


			if(isset($_POST['submit_tab_1'])){
			if($_POST['submit_tab_1'] == "tab_1_search_emp"){
				$selected_employee = $_POST['selected_employee'];
				$title = "By Employee";
				$search_criteria = $selected_employee;
			}else if($_POST['submit_tab_1'] == "tab_1_search_by_date_range"){
					$selected_date_range_start = $_POST['tab_1_start_date']; 
					$selected_date_range_end = $_POST['tab_2_end_date'];
					$start = ConvertToDateTime($selected_date_range_start);
					$end = ConvertToDateTime($selected_date_range_end);
			    if($end >= $start){
					$title = "By Date Range";
					$search_criteria = $selected_date_range_start . " to " . $selected_date_range_end;
				}else{
					$error_message = "Date range is invalid";
				}
			}else if($_POST['submit_tab_1'] == "tab1_select_last_200"){
				$selected_last_200 =  200;
				$title = "Last 200 records";
			}else if($_POST['submit_tab_1'] == "tab_1_search_all_records"){
				$selected_all_records = "ALL";
				$title = "All Records";
			}
		

	  if(!isset($error_message)){
		if(isset($selected_employee)){
			$query_string =  "SELECT RAD_EMP_LEAVE.id, HSL.EMPLOYID as emp_id, HSL.FULLNAME as emp_name, HSL.DSCRIPTN as dept, RAD_EMP_LEAVE.leaveDate as leave_dt ,	RAD_EMP_LEAVE.minutesAwayFromWork as minAwyFromWrk, RAD_LEAVE_TYP.leave_type as leave_type, RAD_SHIFT_NUM.description as shift_number, RAD_YES_NO.status as supervisorNotified, RAD_EMP_LEAVE.comments as comments FROM ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL, ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS RAD_EMP_LEAVE, ".RAADMA_DB.".dbo.LEAVE_TYPES RAD_LEAVE_TYP , ".RAADMA_DB.".dbo.SHIFT_NUMBERS RAD_SHIFT_NUM, ".RAADMA_DB.".dbo.YES_NO RAD_YES_NO WHERE  HSL.EMPLOYID = RAD_EMP_LEAVE.EmployeeId AND RAD_LEAVE_TYP.id = RAD_EMP_LEAVE.leaveId AND RAD_SHIFT_NUM.id = RAD_EMP_LEAVE.shiftNUmber AND RAD_EMP_LEAVE.supervisorNotified = RAD_YES_NO.id AND HSL.EMPLOYID = ".$selected_employee." order by id desc";
		}else if(isset($selected_date_range_start) && isset($selected_date_range_end)){
			$query_string =  "SELECT RAD_EMP_LEAVE.id, HSL.EMPLOYID as emp_id, HSL.FULLNAME as emp_name, HSL.DSCRIPTN as dept, RAD_EMP_LEAVE.leaveDate as leave_dt ,	RAD_EMP_LEAVE.minutesAwayFromWork as minAwyFromWrk, RAD_LEAVE_TYP.leave_type as leave_type, RAD_SHIFT_NUM.description as shift_number, RAD_YES_NO.status as supervisorNotified, RAD_EMP_LEAVE.comments as comments FROM ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL, ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS RAD_EMP_LEAVE, ".RAADMA_DB.".dbo.LEAVE_TYPES RAD_LEAVE_TYP , ".RAADMA_DB.".dbo.SHIFT_NUMBERS RAD_SHIFT_NUM, ".RAADMA_DB.".dbo.YES_NO RAD_YES_NO WHERE  HSL.EMPLOYID = RAD_EMP_LEAVE.EmployeeId AND RAD_LEAVE_TYP.id = RAD_EMP_LEAVE.leaveId AND RAD_SHIFT_NUM.id = RAD_EMP_LEAVE.shiftNUmber AND RAD_EMP_LEAVE.supervisorNotified = RAD_YES_NO.id AND RAD_EMP_LEAVE.leaveDate >= '".$selected_date_range_start."' AND RAD_EMP_LEAVE.leaveDate <= '".$selected_date_range_end."'";
		}else if(isset($selected_last_200)){
			$query_string =  "SELECT TOP 200 RAD_EMP_LEAVE.id, HSL.EMPLOYID as emp_id, HSL.FULLNAME as emp_name, HSL.DSCRIPTN as dept, RAD_EMP_LEAVE.leaveDate as leave_dt ,	RAD_EMP_LEAVE.minutesAwayFromWork as minAwyFromWrk, RAD_LEAVE_TYP.leave_type as leave_type, RAD_SHIFT_NUM.description as shift_number, RAD_YES_NO.status as supervisorNotified, RAD_EMP_LEAVE.comments as comments FROM ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL, ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS RAD_EMP_LEAVE, ".RAADMA_DB.".dbo.LEAVE_TYPES RAD_LEAVE_TYP , ".RAADMA_DB.".dbo.SHIFT_NUMBERS RAD_SHIFT_NUM, ".RAADMA_DB.".dbo.YES_NO RAD_YES_NO WHERE  HSL.EMPLOYID = RAD_EMP_LEAVE.EmployeeId AND RAD_LEAVE_TYP.id = RAD_EMP_LEAVE.leaveId AND RAD_SHIFT_NUM.id = RAD_EMP_LEAVE.shiftNUmber AND RAD_EMP_LEAVE.supervisorNotified = RAD_YES_NO.id order by id desc";
		}else if(isset($selected_all_records)){
			$query_string =  "SELECT RAD_EMP_LEAVE.id, HSL.EMPLOYID as emp_id, HSL.FULLNAME as emp_name, HSL.DSCRIPTN as dept, RAD_EMP_LEAVE.leaveDate as leave_dt ,	RAD_EMP_LEAVE.minutesAwayFromWork as minAwyFromWrk, RAD_LEAVE_TYP.leave_type as leave_type, RAD_SHIFT_NUM.description as shift_number, RAD_YES_NO.status as supervisorNotified, RAD_EMP_LEAVE.comments as comments FROM ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL, ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS RAD_EMP_LEAVE, ".RAADMA_DB.".dbo.LEAVE_TYPES RAD_LEAVE_TYP , ".RAADMA_DB.".dbo.SHIFT_NUMBERS RAD_SHIFT_NUM, ".RAADMA_DB.".dbo.YES_NO RAD_YES_NO WHERE  HSL.EMPLOYID = RAD_EMP_LEAVE.EmployeeId AND RAD_LEAVE_TYP.id = RAD_EMP_LEAVE.leaveId AND RAD_SHIFT_NUM.id = RAD_EMP_LEAVE.shiftNUmber AND RAD_EMP_LEAVE.supervisorNotified = RAD_YES_NO.id order by id desc";
		}

		$exec = $db->executeQuery($query_string, $conn);


		if(isset($selected_employee) || ( isset($selected_date_range_start)  && isset($selected_date_range_end)) || isset($selected_last_200) || isset($selected_all_records)){

		?>
		 <h4>
		 Results : <?php echo $title; ?> 
		 <?php 
			 if(isset($search_criteria)){
			 	echo "(". $search_criteria. ")";
			 }
		 ?>
		</h4>
		<?php if($can_export){ ?>
		<button id="export_tab_1" class="btn btn-info pull-right"><span class="glyphicon glyphicon-export"></span> Export Data</button>
		<?php } ?>
		<br><br><br>
		<table class="table table-striped display" id="searchLeaveDataTable" width:"100%">
		<thead>
			<tr>
				<th>Employee Id</th>
				<th>Name</th>
				<th>Department</th>
				<th>Leave Date</th>
				<th>Minutes Away (min)</th>
				<th>Leave Type</th>
				<th>Shift</th>
			</tr>
		<thead>
		<tbody>
<?php

		while($rows = mssql_fetch_assoc($exec)){
	
?>
		<tr>
			<td><?php echo $rows['emp_id']; ?></td>
			<td><?php echo $rows['emp_name']; ?></td>
			<td><?php echo $rows['dept']; ?></td>
			<td><?php echo $rows['leave_dt']; ?></td>
			<td><?php echo $rows['minAwyFromWrk']; ?></td>
			<td><?php echo $rows['leave_type']; ?></td>
			<td><?php echo $rows['shift_number']; ?></td>
		</tr>

		<?php 
			} //end of while loop 
		?>
	</tbody>
	</table>
	<?php 
		}

	}


}



	?>
</div><!--end of table-->

<?php 
	if(isset($error_message)){
		?>
		<div class="alert alert-danger" role="alert">
		  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		  <span class="sr-only">Error:</span>
			<?php echo $error_message; ?>
		</div>

<?php
	}
?>
