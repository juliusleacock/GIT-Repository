<?php
	/* tab pages */

	/* search-leave-data-tab-2.php */

?>

<style type="text/css">
	.small_input { width: 100px !important; }
</style>

<div class="row">

<div class="col-sm-4">
<form action="LateMinsLeaveTotalsWeeklyPaid" method = "POST" id="LateMinsLeaveTotalsWeeklyPaidForm">
  <div class="form-group">
	  <fieldset class="form-group">
	    <legend>Search Criterias</legend>

		  <div class="form-check">
	      <label class="form-check-label">
		        <input type="checkbox" class="form-check-input" name="selection_checkbox_tab_2[]" id="selected_all_records_tab_2" value="all_records_tab_2">
		        All Records
		      </label>
	  		</div>

	  		 <div class="form-check">
	      <label class="form-check-label">
		        <input type="checkbox" class="form-check-input" name="selection_checkbox_tab_2[]" id="selected_employee_tab_2" value="selected_emp_tab_2">
		        By Selected Employee
		      </label>
	  		</div>

	  		 <div class="form-check">
	      <label class="form-check-label">
		        <input type="checkbox" class="form-check-input" name="selection_checkbox_tab_2[]" id="selected_date_range_tab_2" value="selected_date_range_tab_2">
		      	By Date Range
		      </label>
	  		</div>

	  		 <div class="form-check">
	      <label class="form-check-label">
		        <input type="checkbox" class="form-check-input" name="selection_checkbox_tab_2[]" id="selected_last_200" value="selected_mins_range_to_tab2" >
		       By Mins Range
		      </label>
	  		</div>

	  		 <div class="form-check">
	      <label class="form-check-label">
		        <input type="checkbox" class="form-check-input" name="selection_checkbox_tab_2[]" id="selected_last_200" value="selected_dept_tab2" >
		       By Department
		      </label>
	  		</div>
       </fieldset>
    </div>

    <button type="submit" class="btn btn-info" name = "submit_tab_2" value="submit_tab_2" id="submit_tab_2"><span class="glyphicon glyphicon-search"></span> Search</button>

</div>


<div class="col-sm-8 form-inline">
	<div class="row">
		<div class="col-sm-6">
	  <div class="form-group">
	  	<fieldset class="form-group">
	    <legend>Search By Selected Employee</legend>
		 <div class="form-group">
			<label for="email">Select Employee:</label>
			<select name="selected_employee_tab2" id="selected_employee_search_tab2" tabindex="2" class="form-control firstInput selected_employee_search_tab2">
				<?php foreach($listOfEmps as $key => $value){ ?>  
				<option value="<?php echo $key;?>"><?php echo $value; ?></option>
				<?php }?>
			</select>
		</div>
	  </div>
	</div>

	<div class="col-sm-6">
	  <div class="form-group">
	  	<fieldset class="form-group">
	    <legend>Search By Selected Dept.</legend>
		 <div class="form-group">
			<label for="email">Select Department:</label>
			<select name="selected_dept_tab2" id="selected_dept_search_tab2" tabindex="2" class="form-control firstInput selected_dept_search_tab2">
				<?php foreach($listOfDepts as $key => $value){ ?>  
				<option value="<?php echo $key;?>"><?php echo $value; ?></option>
				<?php }?>
			</select>
		</div>
	  </div>
	</div>

	</div> <!--End of row -->
		<br>
	<div class="row">
		<div class="col-sm-6">
		  <div class="form-group">
		  	<fieldset class="form-group">
		    <legend>Search By Date Range</legend>
			 <div class="form-group">
				<div class="form-group">
					<input type="text" name="tab_2_start_date"  class="datepicker form-control small_input" tabindex="3"> to
					<input type="text" name="tab_2_end_date"  class="datepicker form-control small_input" tabindex="3">
				</div>
			</div>
		  </div>
		</div>

		<div class="col-sm-6">
	  <div class="form-group">
	  	<fieldset class="form-group">
	    <legend>Search By Mins Range</legend>
		 <div class="form-group">
			<label for="selected_mins_range_start_tab2">From:</label>
			<select name="selected_mins_range_start_tab2" id="selected_mins_range_start_tab2" tabindex="2" class="form-control firstInput selected_mins_range_start_tab2">
				<?php foreach($listOfMins as $key => $value){ ?>  
				<option value="<?php echo $key;?>"><?php echo $value; ?></option>
				<?php }?>
			</select>

			<label for="selected_mins_range_end_tab2">To:</label>
			<select name="selected_mins_range_end_tab2" id="selected_mins_range_end_tab2" tabindex="2" class="form-control firstInput selected_mins_range_end_tab2">
				<?php foreach($listOfMins as $key => $value){ ?>  
				<option value="<?php echo $key;?>"><?php echo $value; ?></option>
				<?php }?>
			</select>
		</div>
	  </div>
	</div>
	</div>
	

</form>
</div>

</div>

<hr>


<div class="col-sm-12 table_search_tab2" >
 <br> 
<?php 

	$db = new Database();
	$db_name = RAADMA_DB;
	$conn = $db->connect_to_database($db_name);

	$search_criteria = null;
	$error_message = array();
	
	if(isset($_POST['submit_tab_2'])){
		if(!empty($_POST['selection_checkbox_tab_2'])){

			$all_records = null;
			$by_emp = null;
			$by_date_range = null;
			$by_mins_range = null;
			$by_dept = null;
			$query_string = null;
			foreach($_POST['selection_checkbox_tab_2'] as $selected){
				if($selected == 'all_records_tab_2'){
					$all_records = 1;
				}else if($selected == 'selected_emp_tab_2'){
					$by_emp = 1;
				}else if($selected == 'selected_date_range_tab_2'){
					$by_date_range = 1;
				}else if($selected == 'selected_mins_range_to_tab2'){
					$by_mins_range = 1;
				}else if($selected == 'selected_dept_tab2'){
					$by_dept = 1;
				}
			}//end of foreach



			if(isset($all_records) || isset($by_emp) || isset($by_date_range) || isset($by_mins_range) || isset($by_dept)){
				
				$emp_id = "employeeId";
				$date_range_start = "leaveDate";
				$date_range_end = "leaveDate";
				$dept = "DEPRTMNT";
				$mins_range_start = "minutesAwayFromWork";
				$mins_range_end = "minutesAwayFromWork";

				if(isset($all_records)){
					$query_string = "SELECT DISTINCT RAD.employeeId as empId, HSL.FULLNAME as emp_name, HSL.DEPRTMNT as dept, RAD.leaveDate, RAD.minutesAwayFromWork as awyFromWrk FROM ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS RAD, ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL WHERE HSL.EMPLOYID = RAD.employeeId order by emp_name, leaveDate";

						$by_emp = null;
						$by_date_range = null;
						$by_mins_range = null;
						$by_dept = null;
						$search_criteria = "<span style='color:green;'> All Records</span>";
				}

				if(!isset($all_records) && isset($by_emp)){
					$emp_id = $_POST['selected_employee_tab2'];

					$search_criteria = appendMessage2($search_criteria, "<span style='color:green;'> By Employee : </span>" . GetEmployeeNameById($emp_id));

				}

				if(!isset($all_records) && isset($by_dept)){
					$dept = $_POST['selected_dept_tab2'];
					$search_criteria = appendMessage2($search_criteria, "<span style='color:green;'>By Department: </span>". GetDeptByCode($dept));
				}

				if(!isset($all_records) && isset($by_date_range)){
					$date_range_start = $_POST['tab_2_start_date'];
					$date_range_end = $_POST['tab_2_end_date'];

					$start = ConvertToDateTime($date_range_start);
					$end = ConvertToDateTime($date_range_end);

					if($end >= $start){
						$search_criteria = appendMessage2($search_criteria, "<span style='color:green;'> By Date Range: </span>". $date_range_start . " to " . $date_range_end);
					}else{

						array_push($error_message, "Date Range is invalid");
						
					}
				}

				if(!isset($all_records) && isset($by_mins_range)){
					$mins_range_start = $_POST['selected_mins_range_start_tab2'];
					$mins_range_end = $_POST['selected_mins_range_end_tab2'];
					$start = $mins_range_start;
					$end = $mins_range_end;

					if($end > $start){
						$search_criteria = appendMessage2($search_criteria,  "<span style='color:green;'> By Mins Range: </span>" . $mins_range_start . " to ". $mins_range_end);
					}else{
						array_push($error_message, "Mins Range is invalid. FROM cannot be greater than TO.");
					}
				}

				if(!isset($all_records)){
					$query_string = "SELECT DISTINCT RAD.employeeId as empId, HSL.FULLNAME as emp_name, HSL.DSCRIPTN as dept, RAD.leaveDate, RAD.minutesAwayFromWork as awyFromWrk FROM ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS RAD, ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL WHERE HSL.EMPLOYID = RAD.employeeId AND RAD.employeeId = ".$emp_id." AND HSL.DEPRTMNT = ".(isset($by_dept) ? "'$dept'" :  $dept )." AND RAD.leaveDate BETWEEN ".(isset($by_date_range) ? "'$date_range_start'" : $date_range_start)." AND ".(isset($by_date_range) ? "'$date_range_end'" : $date_range_end)." AND RAD.minutesAwayFromWork >= ".$mins_range_start." AND RAD.minutesAwayFromWork <= ".$mins_range_end." order by emp_name, leaveDate";
				}


				
				$exec = $db->executeQuery($query_string, $conn);

				if(empty($error_message)){


?> 


				<h4>
				 Results : (
				 <?php 
					 if(isset($search_criteria)){
					 	echo $search_criteria;
					 }
				 ?>
				   )
				</h4>
				<?php if($can_export){ ?>
				<button id="export_tab_2" class="btn btn-info pull-right"><span class="glyphicon glyphicon-export"></span> Export Data</button>
				<?php } ?>
				<br><br><br>
				<table class="table table-striped display" id="searchLeaveDataTable2" width:"100%">
					<thead>
						<tr>
							<th>Employee Id</th>
							<th>Name</th>
							<th>Department</th>
							<th>Leave Date</th>
							<th>Duration In Minutes</th>
						</tr>
					<thead>
					<tbody>


				<?php

				while($rows = mssql_fetch_assoc($exec)){
				?>
					<tr>
						<td><?php echo $rows['empId'];?></td>
						<td><?php echo $rows['emp_name'];?></td>
						<td><?php echo $rows['dept'];?></td>
						<td><?php echo $rows['leaveDate']; ?></td>
						<td><?php echo $rows['awyFromWrk']; ?></td>
					</tr>					
			
	<?php
				}

			}

			}else{
				echo "No Values has been selected";
			}

		}
	}
	

?>
</tbody>
	</table>

</div>


<?php 
	if(!empty($error_message)){
		foreach ($error_message as $value) {
		?>

		<div class="alert alert-danger" role="alert">
		  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		  <?php echo $value; ?>
		</div>

<?php
		}
	}
?>