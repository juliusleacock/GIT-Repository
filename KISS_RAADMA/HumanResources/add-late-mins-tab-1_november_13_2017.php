<?php 

	/*add-late-mins-tab-1.php*/

?>


		
<form id="searchByDept" method='POST' action="/KISS_RAADMA/HumanResources/LateMinsAddLeaveData" class="form-inline" id="searchByDept">
	 <div class='row'>
		<div class="col-sm-4">
			<div class="form-group">
				<label>Select Department: </label>
				<select name="selected_department" id ="selected_department" tabindex="2" class="form-control firstInput font_size_selection" required>
									<?php foreach ($listOfDepts as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value; ?></option>
								<?php
									}
								?>
				 </select>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label>Select Date: </label>
				<input type="text" name="leaveDate" id= "leaveDate" class="datepicker form-control font_size_selection" tabindex="4" required>
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<label>Select Shift: </label>
				<select name="selected_shift" tabindex="7" class="form-control font_size_selection" required>
					<?php foreach ($listOfShifts as $key=>$value){ ?>
					<option value ="<?php echo $key; ?>"><?php echo $value; ?></option>
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
		$selected_dept = $_POST['selected_department'];
		$selected_date = $_POST['leaveDate'];
		$selected_shift = $_POST['selected_shift'];

		$query_string = "SELECT EMPLOYID, FULLNAME FROM ".HSL_WEEKLY_PAID_EMP_VIEW." WHERE DEPRTMNT = '".$selected_dept."' order by FULLNAME";
		$exec = $db->executeQuery($query_string, $conn);
	?>
			<h4 class='text-center'>Department- <?php echo GetDeptByCode($selected_dept);?> | Date: <?php echo $selected_date; ?> | Shift: <?php echo GetShiftById($selected_shift); ?>  </h4>
			<form method="POST" action="/KISS_RAADMA/process/HumanResources/LateMins/processLateMinsNewRecord.php" id="addNewLateMins">

		<table class="table table-bordered display">
			<thead>
				<th>&nbsp;</th>
				<th><?php echo getDayFromSelectedDate($selected_date); ?></th>
				<th>Supervisor Notified</th>
				<th>Comments</th>
			</thead>
			<tbody>
		
		<?php
		$results_count = 0;
		while($rows = mssql_fetch_assoc($exec)){

		?>
		<tr>
			<td><?php echo $rows['FULLNAME']; ?></td>
				<input type="hidden" name="emp_id[]" id="emp_id" value="<?php echo trim($rows['EMPLOYID']); ?>">
			<td>hrs<select name="selected_hours[]" class="selected_hours selection" id="selected_hours" tabindex="5">
					 <?php 
						foreach($listOfHours as $value){
						?>
						<option><?php echo $value; ?></option>
						<?php
						}
					?>
				</select>
				mins<select name="selected_mins[]" tabindex="6" class="selection font_size_selection">
								<?php foreach($listOfMins as $value){ ?>
								<option><?php echo $value; ?></option>
								<?php }?>
							</select>

			</td>
			<td>
				<select  id ="selected_notification" tabindex="2" class="form-control firstInput font_size_selection" name='selected_notification[]'>
									<?php foreach ($listOfSupervisorResponses as $key => $value) { ?>
										<option value="<?php echo trim($key);?>"><?php echo $value; ?></option>
								<?php
									}
								?>
				 </select>
			</td>
			<td><textarea class="form-control font_size_selection" rows="3" id="comments" name="comments[]" tabindex="9"></textarea></td>
		</tr>
			
			
<?php

			$results_count++;
		}

?> 
			</tbody>
		</table>

			<!-- hidden values -->



			<input type="hidden" name="username" id="username" value="<?php  echo trim($_SESSION['username']); ?>">

				<input type="hidden" name="leaveDate" id="leaveDate" value="<?php echo trim($selected_date); ?>">

				<input type="hidden" name="selected_shift" id="selected_shift" value="<?php echo trim($selected_shift); ?>">

			<input type="hidden" name="results_count" id="results_count" value="<?php echo $results_count; ?>">
		
			<button type="submit" class="btn btn-success" name="add_new_leave_data" id="add_new_leave_data">Add New Leave Data</button>
		</form>
<?php
	}


	
?>
