<?php 

	/*add-late-mins-tab-2.php*/


	/*
	//Below is the original query string to feature the fields comments, leave type and supervisor notified
	$query_string_leave_logs = "SELECT TOP 50 RAD_EMP_LEAVE.id, HSL.EMPLOYID as emp_id, HSL.FULLNAME as emp_name, HSL.DEPRTMNT as dept, RAD_EMP_LEAVE.leaveDate as leave_dt ,	RAD_EMP_LEAVE.minutesAwayFromWork as minAwyFromWrk, RAD_LEAVE_TYP.leave_type as leave_type, RAD_SHIFT_NUM.description as shift_number, RAD_YES_NO.status as supervisorNotified, RAD_EMP_LEAVE.comments as comments FROM HSL.dbo.KBCLWeeklyEmployees HSL, ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS RAD_EMP_LEAVE, ".RAADMA_DB.".dbo.LEAVE_TYPES RAD_LEAVE_TYP , ".RAADMA_DB.".dbo.SHIFT_NUMBERS RAD_SHIFT_NUM, ".RAADMA_DB.".dbo.YES_NO RAD_YES_NO WHERE  HSL.EMPLOYID = RAD_EMP_LEAVE.EmployeeId AND RAD_LEAVE_TYP.id = RAD_EMP_LEAVE.leaveId AND RAD_SHIFT_NUM.id = RAD_EMP_LEAVE.shiftNUmber AND RAD_EMP_LEAVE.supervisorNotified = RAD_YES_NO.id order by id desc"; 

	*/

	$query_string_leave_logs = "SELECT TOP 50 RAD_EMP_LEAVE.id, HSL.EMPLOYID as emp_id, HSL.FULLNAME as emp_name, HSL.DEPRTMNT as dept, RAD_EMP_LEAVE.leaveDate as leave_dt ,	RAD_EMP_LEAVE.minutesAwayFromWork as minAwyFromWrk, RAD_SHIFT_NUM.description as shift_number, RAD_YES_NO.status as supervisorNotified FROM ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL, ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS RAD_EMP_LEAVE, ".RAADMA_DB.".dbo.LEAVE_TYPES RAD_LEAVE_TYP , ".RAADMA_DB.".dbo.SHIFT_NUMBERS RAD_SHIFT_NUM, ".RAADMA_DB.".dbo.YES_NO RAD_YES_NO WHERE  HSL.EMPLOYID = RAD_EMP_LEAVE.EmployeeId AND RAD_LEAVE_TYP.id = RAD_EMP_LEAVE.leaveId AND RAD_SHIFT_NUM.id = RAD_EMP_LEAVE.shiftNUmber AND RAD_EMP_LEAVE.supervisorNotified = RAD_YES_NO.id order by id desc";
	$exec_leave_logs_tbl = $db->executeQuery($query_string_leave_logs, $conn_raadma);

	




?>



<div class="container" id="late_mins_table_container">
	   <div class ="row" id="late_mins">
			<table class="table table-bordered display" id="lateMinsTable" width:"100%">
				<thead> 
					<tr>
						<th>Employee Id</th>
						<th>Name</th>
						<th>Department</th>
						<th>Leave Date</th>
						<th>Minutes Away (min)</th>
						<!--<th>Leave Type</th>-->
						<th>Shift</th>
						<!--<th>Supervisor Notified</th> -->
						<!--<th>Comments</th>-->
						<?php if($can_edit){ ?>
						<th>&nbsp;</th>
							<?php } ?>
						<?php if($can_delete){ ?>
						<th>&nbsp;</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					
					   <?php 
					   		
					   			while($row = mssql_fetch_assoc($exec_leave_logs_tbl)){

					    ?>

					    	<tr>
					    		<td><?php echo  $row['emp_id']; ?></td>
					    		<td><?php echo $row['emp_name']; ?></td>
					    		<td><?php echo GetDeptByCode($row['dept']); ?></td>
					    		<td><?php echo $row['leave_dt']; ?></td>
					    		<td><?php echo $row['minAwyFromWrk']; ?></td>
					    		<!--<td><?php //echo $row['leave_type'];?></td>-->
					    		<td><?php echo  $row['shift_number']; ?></td>
					    		<!--<td><?php //echo $row['supervisorNotified']; ?></td> -->
					    		<!--<td><?php //echo  $row['comments']; ?></td>-->
					    		<?php if($can_edit){ ?>
					    		<td><button type="button" class="btn btn-info edit" id="<?php echo $row['id']; ?>"  data-toggle="modal" data-target="#editLateMin"><span class="glyphicon glyphicon-pencil"></span> Edit</button></td>
					    		<?php } ?>
					    		<?php if($can_delete){ ?>
					    		<td><button type="button" class="btn btn-danger delete" id="<?php echo $row['id']; ?>"><span class="glyphicon glyphicon-remove"></span> Delete</button></td>
					    		<?php } ?>
					    		<input type="hidden" name="username" id="username" value="<?php echo $_SESSION['username']; ?>">
					    	</tr>

					    <?php 
					    	}
					    ?>
				</tbody>
			</table>
		 <div class="modal fade" id="editLateMin" style="overflow:none" role ="dialog"></div>
		</div><!-- end of row -->
	</div><!--End of Container -->

