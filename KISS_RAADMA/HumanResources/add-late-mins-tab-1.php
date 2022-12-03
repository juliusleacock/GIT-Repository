<?php 

	/*add-late-mins-tab-1.php*/

?>

<style>
	.days{
		font-weight: bold;
	}

	.input_style{
		width: 100px;
		color: red;
		font-weight:bold;
	}




</style>	
<form id="searchByDept" method='POST' action="/KISS_RAADMA/HumanResources/LateMinsAddLeaveData" class="form-inline">
	 <div class='row'>
		<div class="col-sm-6">
			<div class="form-group">
				<label>Select Employee: </label>
				<select name="selected_employee" style="width:70%" id ="selected_employee" tabindex="2" class="form-control firstInput font_size_selection" required>
									<?php foreach ($listOfEmps as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value; ?></option>
								<?php
									}
								?>
				 </select>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<label>Select Month: </label>
				<select name="selected_month" id ="selected_month" tabindex="2" class="form-control font_size_selection" required>
						<?php foreach ($listOfMonths as $key => $value){ ?>
						   		<option value="<?php echo $value;?>" <?php echo setDefaultMonth($value); ?>><?php echo $value; ?></option>
						<?php
							}
						?>
				 </select>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="form-group">
				<label>Select Year: </label>
				<select name="selected_year" tabindex="7" class="form-control font_size_selection" required>
					<?php foreach ($listOfYears as $key=>$value){ ?>
					<option value ="<?php echo $value; ?>" <?php  echo setDefaultYear($value); ?>><?php echo $value; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
    </div><!-- End of row -->
    <br>
    <div class = "row">
			<div class = "col-sm-12 text-center">
					<button type="submit" class="btn btn-info test_btn btn-lg" style="width:40%" name = 'search_tab_1'  id="search_tab_1"><span class="glyphicon glyphicon-search"></span> Search</button>
			</div>
    </div>
</form><!-- end of form -->
<hr>
<?php 
	if(isset($_POST['search_tab_1'])){
		$db = new Database();
		$db_name = HSL_DB;
		$conn = $db->connect_to_database($db_name);
		$selected_employee = trim($_POST['selected_employee']);
		$selected_month = trim($_POST['selected_month']);
		$selected_year = trim($_POST['selected_year']);
		$month = convertMonthToInt($selected_month);


		$firstDay = buildFirstDayOfMonth($selected_year, $month);
		$lastDayOfMonth = GetLastDayOfMonthInt($selected_year, $month);
		$lastDay = buildDay($selected_year, $selected_month, $lastDayOfMonth);

	?>
			
	<h4 class="text-center">Employee: <?php echo GetEmployeeNameById($selected_employee); ?> | Number Of Times Late: <?php echo  NumberOfTimesLateForTheMonth($selected_employee, $firstDay, $lastDay, $month); ?> </h4>
		<div class="table-responsive" id="addLeaveDataTable">
		<form id="addNewLateMins" method="POST" action="/KISS_RAADMA/process/HumanResources/LateMins/processLateMinsNewRecord.php">
		<table class="table table-bordered" style="width:100%">
			<thead>
				<th colspan="7" class="text-center" style="background-color: #A9A9A9"><?php echo $selected_month . " " . $selected_year; ?></th>
			</thead>
		<tbody>
			<tr>
			<td style="width:12%">Sunday</td>
			<td style="width:12%">Monday</td>
			<td style="width:12%">Tuesday</td>
			<td style="width:12%">Wednesday</td>
			<td style="width:12%">Thursday</td>
			<td style="width:12%">Friday</td>
			<td style="width:12%">Saturday</td>
		   </tr> 

			<?php 
			$numberOfRows = 6;
			$count = 1;
			$start_day = 1;
			$end_day = $lastDayOfMonth;
			$start_day_name = GetDayFromDate("$selected_year-$selected_month-01");
			$next_day =1;
			$start_day_set = false;
			
		
			while($count <= $numberOfRows){
			?>
			 <tr>
			 	<td>
			 	<?php 
			 		if($start_day_name == "Sunday" && $count == 1){
			 			echo "<div class='days text-center'>".$start_day. "</div>";
			 			$next_day++;
			 			$start_day_set = true;
			 			$day = convertToDay($start_day);
			 			$leaveDate = buildDate($selected_year, $month, $day);

			 	?>
			 		<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
			 			<div class="text-center"><select name="selected_shift[]" id="shift_<?php echo $start_day; ?>">
			 					<?php 
			 						foreach ( $listOfShifts as $key => $value) {
			 					?>
									<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?> ><?php echo $value; ?></option>
			 					<?php
			 						}
			 					?>
			 			</select></div>
			 			<br>
						<div class="text-center">
							<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
						</div>
						
			 	<?php
			 		}else if(($start_day_set == true) && ($next_day <= $end_day)){
			 			$day = convertToDay($next_day);
						
						$leaveDate = buildDate($selected_year, $month, $day);
				
			 			echo "<div style='clear:left; clear:right' class='days text-center'>". $next_day ."</div>";
			 	?>
					<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
			 		<div class="text-center"> <select name="selected_shift[]" id="shift_<?php echo $next_day; ?>">
			 					<?php 
			 						foreach ( $listOfShifts as $key => $value) {
			 					?>
									<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
			 					<?php
			 						}
			 					?>
			 			</select></div> 
			 			<br>
			 		<div class="text-center">
			 		<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
			 		</div> 
			 		
			 	<?php
			 			$next_day++;
			 		}

			 	 ?>
			 	</td>
			 	<td>
			 		<?php 
			 		if($start_day_name == "Monday" && $count == 1){
			 			echo "<div class='days text-center'>".$start_day."</div>";
			 			$next_day++;
			 			$start_day_set = true;
			 			$day = convertToDay($start_day);
						$leaveDate = buildDate($selected_year, $month, $day);
			 		?>

					<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
					<div class="text-center"><select name="selected_shift[]" id="shift_<?php echo $start_day; ?>">
			 					<?php 
			 						foreach ( $listOfShifts as $key => $value) {
			 					?>
									<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?> ><?php echo $value; ?></option>
			 					<?php
			 						}
			 					?>
			 			</select></div>
			 			<br>
						<div class="text-center">
							<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
						</div>
	
			 		<?php
			 		}else if(($start_day_set == true) && ($next_day <= $end_day)){
			 			echo "<div class='days text-center'>".$next_day."</div>";
			 			$day = convertToDay($next_day);
						$leaveDate = buildDate($selected_year, $month, $day);
			 		?>
					
					<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
					<div class="text-center"> <select name="selected_shift[]" id="shift_<?php echo $next_day; ?>">
		 					<?php 
		 						foreach ( $listOfShifts as $key => $value) {
		 					?>
								<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
		 					<?php
		 						}
		 					?>
						 </select>
						 </div>
			 			<br>
			 		<div class="text-center">
			 			<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
			 		</div>

			 	<?php
			 			$next_day++;
			 		}

			 	 ?>
			 	</td>
			 	<td>
			 		<?php 
			 		if($start_day_name == "Tuesday" && $count == 1){
			 			echo "<div class='days text-center'>".$start_day."</div>";
			 			$next_day++;
			 			$start_day_set = true;
			 			$day = convertToDay($start_day);
						$leaveDate = buildDate($selected_year, $month, $day);
			 		?>
						<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
			 			<div class="text-center"><select name="selected_shift[]" id="shift_<?php echo $start_day; ?>">
			 					<?php 
			 						foreach ( $listOfShifts as $key => $value) {
			 					?>
									<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
			 					<?php
			 						}
			 					?>
			 			</select></div>
			 			<br>
						<div class="text-center">
							<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
						</div>

			 	<?php
			 		}else if(($start_day_set == true) && ($next_day <= $end_day)){
			 			echo "<div class='days text-center'>". $next_day. "</div>";

						$day = convertToDay($next_day);
						$leaveDate = buildDate($selected_year, $month, $day);
			 ?> 
			 		<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
				<div class="text-center"> <select name="selected_shift[]" id="shift_<?php echo $next_day; ?>">
		 					<?php 
		 						foreach ( $listOfShifts as $key => $value) {
		 					?>
								<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
		 					<?php
		 						}
		 					?>
						 </select>
						 </div>
			 			<br>
			 		<div class="text-center">
			 			<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
			 		</div>
			 <?php
			 			$next_day++;
			 		}

			 	 ?>
			 	</td>
			 	<td>
					<?php 
			 		if($start_day_name == "Wednesday" && $count == 1){
			 			echo "<div class='days text-center'>".$start_day."</div>";
			 			$next_day++;
			 			$start_day_set = true;
			 			$day = convertToDay($start_day);
						$leaveDate = buildDate($selected_year, $month, $day);

			 	?>

					<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
			 		<div class="text-center"><select name="selected_shift[]" id="shift_<?php echo $start_day; ?>">
			 					<?php 
			 						foreach ( $listOfShifts as $key => $value) {
			 					?>
									<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
			 					<?php
			 						}
			 					?>
			 			</select></div>
			 			<br>
						<div class="text-center">
							<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
						</div>

			 	<?php
			 		}else if(($start_day_set == true) && ($next_day <= $end_day)){
			 			echo "<div class='days text-center'>".$next_day."</div>";

					$day = convertToDay($next_day);
					$leaveDate = buildDate($selected_year, $month, $day);
			 	?> 
					<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
			 		<div class="text-center"> <select name="selected_shift[]" id="shift_<?php echo $next_day; ?>">
		 					<?php 
		 						foreach ( $listOfShifts as $key => $value) {
		 					?>
								<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
		 					<?php
		 						}
		 					?>
						 </select>
						 </div>
			 			<br>
			 		<div class="text-center">
			 			<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
			 		</div>

			 	<?php
			 			$next_day++;
			 		}

			 	 ?>

			 	</td>
			 	<td>
			 		<?php 
			 		if($start_day_name == "Thursday" && $count == 1){
			 			echo "<div class='days text-center'>".$start_day."</div>";
			 			$next_day++;
			 			$start_day_set = true;
			 			$day = convertToDay($start_day);
						$leaveDate = buildDate($selected_year, $month, $day);
			 		?>
					<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
					<div class="text-center"><select name="selected_shift[]" id="shift_<?php echo $start_day; ?>">
			 					<?php 
			 						foreach ( $listOfShifts as $key => $value) {
			 					?>
									<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
			 					<?php
			 						}
			 					?>
			 			</select></div>
			 			<br>
						<div class="text-center">
							<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
						</div>
			 	<?php
			 		}else if(($start_day_set == true) && ($next_day <= $end_day)){
			 			echo "<div class='days text-center'>".$next_day."</div>";
			 			$day = convertToDay($next_day);
						$leaveDate = buildDate($selected_year, $month, $day);
			 	?>


					<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
					<div class="text-center"> <select name="selected_shift[]" id="shift_<?php echo $next_day; ?>">
		 					<?php 
		 						foreach ( $listOfShifts as $key => $value) {
		 					?>
								<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
		 					<?php
		 						}
		 					?>
						 </select>
						 </div>
			 			<br>
			 		<div class="text-center">
			 			<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
			 		</div>

			 	<?php
			 			$next_day++;
			 		}

			 	 ?>
			 	</td>
			 	<td>
			 		<?php 
			 		if($start_day_name == "Friday" && $count == 1){
			 			echo "<div class='days text-center'>".$start_day."</div>";
			 			$next_day++;
			 			$start_day_set = true;

			 			$day = convertToDay($start_day);
						$leaveDate = buildDate($selected_year, $month, $day);
			 	?>

						<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
						<div class="text-center"><select name="selected_shift[]" id="shift_<?php echo $start_day; ?>">
			 					<?php 
			 						foreach ( $listOfShifts as $key => $value) {
			 					?>
									<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
			 					<?php
			 						}
			 					?>
			 			</select></div>
			 			<br>
						<div class="text-center">
							<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
						</div>
						
			 	<?php
			 		}else if(($start_day_set == true) && ($next_day <= $end_day)){
			 			echo "<div class='days text-center'>".$next_day."</div>";
			 			$day = convertToDay($next_day);
						$leaveDate = buildDate($selected_year, $month, $day);

			 	?> 
					<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
						<div class="text-center"> <select name="selected_shift[]" id="shift_<?php echo $next_day; ?>">
		 					<?php 
		 						foreach ( $listOfShifts as $key => $value) {
		 					?>
								<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
		 					<?php
		 						}
		 					?>
						 </select>
						 </div>
			 			<br>
			 		<div class="text-center">
			 			<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
			 		</div>

			 	<?php
			 			$next_day++;
			 		}

			 	 ?>
			 	</td>
			 	<td>
			 		<?php 
			 		if($start_day_name == "Saturday" && $count == 1){
			 			echo "<div class='days text-center'>".$start_day."</div>";
			 			$next_day++;
			 			$start_day_set = true;

			 			$day = convertToDay($start_day);
						$leaveDate = buildDate($selected_year, $month, $day);
			 		?>

						<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
						<div class="text-center"><select name="selected_shift[]" id="shift_<?php echo $start_day; ?>">
			 					<?php 
			 						foreach ( $listOfShifts as $key => $value) {
			 					?>
									<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
			 					<?php
			 						}
			 					?>
			 			</select></div>
			 			<br>
						<div class="text-center">
							<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
						</div>
			 	<?php

			 		}else if(($start_day_set == true) && ($next_day <= $end_day)){
			 			echo "<div class='days text-center'>".$next_day."</div>";

						$day = convertToDay($next_day);
						$leaveDate = buildDate($selected_year, $month, $day);
			 		?>
			 		<input type="hidden" name="leaveDate[]" value="<?php echo $leaveDate; ?>">
						<div class="text-center"> <select name="selected_shift[]" id="shift_<?php echo $next_day; ?>">
		 					<?php 
		 						foreach ( $listOfShifts as $key => $value) {
		 					?>
								<option value="<?php echo $key; ?>" <?php echo displayShiftOption($selected_employee, $leaveDate, $key); ?>><?php echo $value; ?></option>
		 					<?php
		 						}
		 					?>
						 </select>
						 </div>
			 			<br>
			 		<div class="text-center">
			 			<input type="number" name="leave_mins[]" value="<?php echo GetMinsLate($selected_employee, $leaveDate); ?>" class="text-center input_style" min="1" step="1" max="480">
			 		</div>
			 		<?php
			 			$next_day++;
			 		}

			 	 ?>
			 	</td>
			 </tr>
			 <?php 
			 	$count++;
			}//ene of while loop
			 ?>
		</tbody>
			 
		</table>
		 <input type="hidden" value="<?php echo $selected_employee?>" id ="selected_employee" name="selected_employee">
		 <input type="hidden" value="<?php echo trim($_SESSION['username']); ?>" id ="username" name="username">
		 <input type="hidden" value="<?php echo $end_day; ?>" id ="number_of_days" name="number_of_days">
		 <?php if($can_save){	?>
		 <button type="submit" class="btn btn-info" name="addLeaveDataBtn"><span class="glyphicon glyphicon-send"></span> Save Leave Data</button>
		 <?php } ?>
	</form>
	</div>

	<?php 


	}



	function selectOption(){
		return "selected";
	}


	function getShiftNumber($emp_id, $leaveDate){
		$shiftNumber = null;

		if(isset($emp_id) && isset($leaveDate)){
			$db = new Database();
			$db_name = RAADMA_DB;
			$conn = $db->connect_to_database($db_name);

			$query_string = "SELECT shiftNumber FROM ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL." WHERE employeeId = '".$emp_id."' AND leaveDate = '".$leaveDate."'";
			$exec = $db->executeQuery($query_string, $conn);

			$array_results = mssql_fetch_array($exec);

			$shiftNumber = $array_results[0];
		}
		return $shiftNumber;
	}


	function displayShiftOption($emp_id, $leaveDate, $key){
		$option = null;
		if(CheckIfRecordExistsForLateMins($emp_id, $leaveDate) == true){ 
			if(getShiftNumber($emp_id, $leaveDate) == $key){
				$option = selectOption();
			}
		} 
		return $option;
	}


	function displayMinsOption($emp_id, $leaveDate, $key){
		$option = null;

		if(CheckIfRecordExistsForLateMins($emp_id, $leaveDate) == true){ 
			$mins = GetMinsLate($emp_id, $leaveDate);
			$rem_mins = GetRemainderMins($mins);
			if($rem_mins == $key){
				$option = selectOption();
			}
		}

		return $option;
	}

	function displayHoursOption($emp_id, $leaveDate, $key){
		$option = null;
		if(CheckIfRecordExistsForLateMins($emp_id, $leaveDate) == true){ 
			$mins = GetMinsLate($emp_id, $leaveDate);
			$cal_hrs = GetHoursFromMins($mins);
			if($cal_hrs == $key){
				$option = selectOption();
			}
		}
		return $option;
	}


	function GetMinsLate($emp_id, $leaveDate){
		$mins_late = null;

		if(isset($emp_id) && isset($leaveDate)){
			$db = new Database();
			$db_name = RAADMA_DB;
			$conn = $db->connect_to_database($db_name);

			$query_string = "SELECT minutesAwayFromWork FROM ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL." WHERE employeeId = '".$emp_id."' AND leaveDate = '".$leaveDate."'";
			$exec = $db->executeQuery($query_string, $conn);

			$array_results = mssql_fetch_array($exec);

			$mins_late = $array_results[0];
		}
		return $mins_late;
	}


	function setDefaultYear($key){
		$option = "";
		$current_year = date("Y");

		if($key == $current_year){
			$option = selectOption();
		}

		return $option;
	}


	function setDefaultMonth($key){
		$option = "";
		$current_month = date("F");

		if($key == $current_month){
			$option = selectOption();
		}

		return $option;
	}



	function NumberOfTimesLateForTheMonth($emp_id, $first_day_of_month, $last_day_of_the_month, $selected_month){
		$numberOfTimesLate = 0;

		if(isset($emp_id) && isset($first_day_of_month) && isset($selected_month)){
			$db = new Database();
			$db_name = RAADMA_DB;
			$conn = $db->connect_to_database($db_name);

			$query_string = "SELECT COUNT(*) FROM ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL." WHERE employeeId= '".$emp_id."' AND leaveDate >= '".$first_day_of_month."' AND leaveDate <=  '".$last_day_of_the_month."'";

			$exec = $db->executeQuery($query_string, $conn);
			$array_results = mssql_fetch_array($exec);

			$numberOfTimesLate = $array_results[0];

		}

		return $numberOfTimesLate;

	}






	?>


