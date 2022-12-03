<?php

	/*LateMinsEditLeaveData.php */

	session_start();

	if(!isset($_SESSION['email_address'])){
		header("Location: /KISS_RAADMA/logout.php");
		exit;
	}

    require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");


    $users_id = $_SESSION['users_id'];
	$module = 'HR_LATE_MINS_WEEKLY_PAID_EMPLOYEES';
	$module_id = GetModuleIdFromName($module);
	$access_right = GetAccessRightForModule($users_id, $module);

	$canUserAccessPage = false;
	$can_edit = false;
	$can_delete = false;

	if(CanUserAccessPage($users_id, $module_id)){
		$canUserAccessPage = true;
	}

	if(!$canUserAccessPage){
		header("Location: /KISS_RAADMA/logout.php");
		exit;
	}




	if($access_right == EDITOR || UserIsAdmin($users_id) == 1 || $access_right == SUPER_EDITOR){
		$can_edit = true;

	}

	if($access_right == SUPER_EDITOR || UserIsAdmin($users_id) == 1){

		$can_delete = true;
	}



?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HR: Late Mins - Modify Leave Data</title>

	<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/inc/pageHeader.php"); ?>
</head>
<body>
	<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/menu.php"); ?>
	<div class="container">
		<div class="row">
			<h1 >HR - Late Mins | Modify Leave Data</h1>
			<hr>

			<form class="form-inline text-center" id="tab1_select_date_range_form" action="LateMinsModifyLeaveData" method="POST">	
				<div class="form-group">
					<label for="email">Select Date:</label>
					<input type="text" name="start_date" id="start_date" class="datepicker form-control" tabindex="3">
				</div>
				<button type="submit" class="btn btn-info test_btn" name = 'modify_submit' value="modify_submit"><span class="glyphicon glyphicon-search"></span> Search</button>
			</form>
			<p class='text-center' style='color:#4286f4'><strong>*Note: Date Selection is based on when the late min record was created</strong></p>
			
		</div>

		<br> <br>
		


		<div class='row'>
		
		<?php	
			 
			 	$selected_date = null;
			 	$results_message = null;

			 	if(isset($_POST['modify_submit'])){
			 	$selected_date = $_POST['start_date'];
	

			 	$results_message = "Records created on  ". $selected_date. ".";

			?>

			<h4 class='text-center'><?php 
			if(isset($results_message)){
				echo $results_message;
			} ?>
		</h4>
			<table class="table table-bordered" id="modifyLeaveData">
					<thead>
							<tr>
								<th>Employee Id</th>
								<th>Name</th>
								<th>Department</th>
								<th>Leave Date</th>
								<th>Minutes Away(min)</th>
								<!--<th>Leave Type</th>-->
								<th>Shift</th>
								<!--<th>Supervisor Notified</th>-->
								<!--<th>Comments</th>-->
								<?php if($can_edit){?>
								<th>&nbsp;</th>
								<?php } ?>
								<?php if($can_delete){ ?>
								<th>&nbsp;</th>
								<?php } ?>
							</tr>
					</thead>
					<tbody>
			<?php
			 		
	

			 		$db = new Database();
			 		$db_name = RAADMA_DB;
			 		$conn = $db->connect_to_database($db_name);


			 		$query_string ="SELECT RAD_EMP_LEAVE.id, HSL.EMPLOYID as emp_id, HSL.FULLNAME as emp_name, HSL.DSCRIPTN as dept, RAD_EMP_LEAVE.leaveDate as leave_dt ,	RAD_EMP_LEAVE.minutesAwayFromWork as minAwyFromWrk, RAD_SHIFT_NUM.description as shift_number FROM ".HSL_DB.".dbo.KBCLWeeklyEmployees HSL, ".RAADMA_DB.".dbo.EMPLOYEE_LEAVE_LOGS RAD_EMP_LEAVE, ".RAADMA_DB.".dbo.LEAVE_TYPES RAD_LEAVE_TYP , ".RAADMA_DB.".dbo.SHIFT_NUMBERS RAD_SHIFT_NUM, ".RAADMA_DB.".dbo.YES_NO RAD_YES_NO WHERE  HSL.EMPLOYID = RAD_EMP_LEAVE.EmployeeId AND RAD_LEAVE_TYP.id = RAD_EMP_LEAVE.leaveId AND RAD_SHIFT_NUM.id = RAD_EMP_LEAVE.shiftNUmber AND RAD_EMP_LEAVE.supervisorNotified = RAD_YES_NO.id AND RAD_EMP_LEAVE.createdDateTime >= '".$selected_date." 00:00:00' AND RAD_EMP_LEAVE.createdDateTime <= '".$selected_date." 23:59:59' order by RAD_EMP_LEAVE.id desc";

			 		$exec = $db->executeQuery($query_string, $conn);


			 		while($row = mssql_fetch_assoc($exec)) {
			 	

		?>
				<tr>
					<td><?php echo  $row['emp_id']; ?></td>
		    		<td><?php echo $row['emp_name']; ?></td>
		    		<td><?php echo $row['dept']; ?></td>
		    		<td><?php echo $row['leave_dt']; ?></td>
		    		<td><?php echo $row['minAwyFromWrk']; ?></td>
		    		<!--<td><?php //echo $row['leave_type'];?></td> -->
		    		<td><?php echo  $row['shift_number']; ?></td>
		    		<!--<td><?php //echo $row['supervisorNotified']; ?></td>-->
		    		<!-- <td><?php //echo  $row['comments']; ?></td>-->
		    		<?php if($can_edit) { ?>
					<td><button type="button" class="btn btn-info edit" id="<?php echo $row['id']; ?>"  data-toggle="modal" data-target="#editLateMin"><span class="glyphicon glyphicon-pencil"></span> Edit</button></td>
					<?php } ?>	
						<input type="hidden" name="username" id="username" value="<?php echo $_SESSION['username']; ?>">			
					<?php if($can_delete){ ?>
					<td><button type="button" class="btn btn-danger delete" id="<?php echo $row['id']; ?>"><span class="glyphicon glyphicon-remove"></span> Delete</button></td>
					<?php } ?>
				</tr>
				

			<?php 
				}//end of while loop

			?>
					</tbody>
				</table> 
			<?php
			  }

			?>

		
		</div><!-- end of table row -->
	</div><!-- end of container -->
			 <div class="modal fade" id="editLateMin" style="overflow:none" role ="dialog"></div>
</body>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/inc/pageFooter.php"); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.datepicker').datepicker({
		     format: "yyyy-mm-dd",
		}).on('changeDate', function (ev) {
		     $(this).datepicker('hide');
		});

		$('#modifyLeaveData').DataTable();



		 $("#modifyLeaveData").on("click", ".delete", function(){
 	  		var current_row_id = $(this).prop('id');
 	  		 if(confirm("Are you sure you which to delete this record?")){
	 	  		DeleteLatMinsRecord(current_row_id);
	 	  		refreshAndClose();
	 	  	}
 	  	  });


 	  	  function DeleteLatMinsRecord(id){
                var info = {'id' : id};
                                    $.ajax({
                                       type: "POST",
                                       async: false,
                                       data: {
                                         result: JSON.stringify(info)
                                       },
                                       url: "/KISS_RAADMA/process/HumanResources/LateMins/processDeleteLateMins.php"
                                     });

           }

            function refreshAndClose(){
           		window.location.reload();  
        	}


        	$("#modifyLeaveData").on("click", ".edit", function(){
	 	  		 var current_row_id = $(this).prop('id');
	 	  		ModalFormEdit(current_row_id);
 	  		});


        	 function ModalFormEdit(id){
 	  	 		var username = $("#username").val();
                var info = {'id' : id, 'user' : username};
                                    $.ajax({
                                       type: "POST",
                                       cache: false,
                                       data: {
                                         result: JSON.stringify(info)
                                       },
                                       url: "/KISS_RAADMA/modal_forms/HumanResources/EditLateMinsModal.php"
                                     }).done(function(response) {
                                     $("#editLateMin").html(response);
                        });

           }
	});
</script>

</html>