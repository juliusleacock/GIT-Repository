<?php

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
	$can_save = false;
	$can_edit = false;
	$can_delete = false;

	if(CanUserAccessPage($users_id, $module_id)){
		$canUserAccessPage = true;
	}

	if(!$canUserAccessPage){
		header("Location: /KISS_RAADMA/logout.php");
		exit;
	}



    $db = new Database();
    $db_raadma = RAADMA_DB;
    $conn_raadma = $db->connect_to_database($db_raadma);

    $listOfEmps = GetEmployeeListForSelection();
    $listOfHours = GetListOfHoursWeeklyPaid();
    $listOfMins = GetListOfMinutesWeeklyPaid();
    $listOfShifts = GetListOfShifts();
    $listOfSupervisorResponses = GetListOfSupervisorNotifiedResponses();
    $listOfDepts = GetListOfDepartments();
    $listOfMonths = GetListOfMonths();
    $listOfYears = listOfYears();



	if($access_right == EDITOR || UserIsAdmin($users_id) == 1 || $access_right == SUPER_EDITOR){
		$can_save = true;
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
	<title>HR: Late Mins- Add Leave Date</title>
	<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/inc/pageHeader.php"); ?>
   <style type="text/css">
	.form-control:focus {
        border-color: #0061FF; /*change colour of tab glow*/
        box-shadow: 2px 3px 3px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(255, 100, 255, 0.5);
    }

    .selection:focus{
		 border-color: #0061FF;
        box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(255, 100, 255, 0.5);
    }

    .font_size_selection{
    	font-size: 11px;
    }
	div#AddLateMinsTabs{
	  width: 100%;
	}

   </style>
</head>
<body>
	<div id="test_message"></div>
	<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/menu.php"); ?>

	<?php 
		/* echo "Email Address: ".$_SESSION['email_address'];
		echo "Users Id: ".	$_SESSION['users_id'];  */

	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
			<h1>HR - Late Mins | Add Leave Data</h1>
			</div>
		</div>
	
		<div class="row">
			<div class="col-sm-12">
				<div id="AddLateMinsTabs">
					  <ul>
					    <li><a href="#late-mins-add-leave-data-tab-1">Add Leave Data</a></li>
					    <li><a href="#late-mins-add-leave-totals-tab-2">View / Edit Leave Totals (Last 50)</a></li>
					  </ul>
					  <div id="late-mins-add-leave-data-tab-1">
					   	 <?php require_once('add-late-mins-tab-1.php'); ?>
					  </div>
					  <div id="late-mins-add-leave-totals-tab-2">
						  <?php require_once('add-late-mins-tab-2.php'); ?>
					  </div>
			  </div>
		</div>
	</div>
		
</div><!-- End of Container -->



	

</body>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/inc/pageFooter.php"); ?>
 <script>


 $(document).ready(function(){
 		
 		
 	  	$('.datepicker').datepicker({
		     format: "yyyy-mm-dd",
		     autoclose: true
		});

 		var t = $('#lateMinsTable').DataTable({"order":[]});


 	  	$("#lateMinsTable").on("click", ".edit", function(){
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


          $("#lateMinsTable").on("click", ".delete", function(){
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

     



       $( "#AddLateMinsTabs").tabs();

       $( "#AddLateMinsTabs" ).tabs({ active: $.session.get('tab_session_add_late_mins') });

       $('#addNewLateMins').on('submit', function(e){
 			$.session.set('tab_session_add_late_mins', '0');
 	 	});
 	  

		$('#searchByDept').on('submit', function(e){
 			$.session.set('tab_session_add_late_mins', '0');
 	 	});

 	  

    // bind form using ajaxForm 
    $('#addNewLateMins').ajaxForm({ 
        success: function(data) { 
           	alert(data);
           	refreshAndClose();
        } 
    }); 


 });//end of document
 </script>
</html>