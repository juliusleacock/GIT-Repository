<?php 
	/*LateMinsLeaveTotalsWeeklyPaid.php*/
	date_default_timezone_set('America/Puerto_Rico');
	session_start();

	if(!isset($_SESSION['email_address'])){
		header("Location: /KISS_RAADMA/logout.php");
		exit;
		
	}



	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");


    $canUserAccessPage = false;
    $users_id = $_SESSION['users_id'];
	$module = 'HR_LATE_MINS_WEEKLY_PAID_EMPLOYEES';
	$module_id = GetModuleIdFromName($module);
	$can_export = false;

	$access_right = GetAccessRightForModule($users_id, $module);

	if($access_right == EDITOR || UserIsAdmin($users_id) == 1 || $access_right == SUPER_EDITOR){
		$can_export = true;
	}


    if(CanUserAccessPage($users_id, $module_id)){
		$canUserAccessPage = true;
	}

	if(!$canUserAccessPage){
		header("Location: /KISS_RAADMA/logout.php");
		exit;
	}

  $listOfEmps = GetEmployeeListForSelection();
  $listOfDepts = GetListOfDepartments();
  $listOfMins = GetMinsRange();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HR: Late Mins- Leave Totals Weekly</title>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/inc/pageHeader.php"); ?>

   <style type="text/css">
		#LateMisTabs {
		    border: none;
		}

		h4{
			text-align:center;
		}

   </style>
</head>
<body>

		<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/menu.php"); ?>

		<div class="container">
		<div class="row">
			<h1>HR - Late Mins | Leave Totals</h1>
		</div>

		<div class="row">
			<div id="LateMisTabs">
			  <ul>
			    <li><a href="#late-mins-search-leave-data-tab-1">Search: Leave Data</a></li>
			    <li><a href="#late-mins-search-leave-totals-tab-2">Search : Leave Totals</a></li>
			  </ul>
			  <div id="late-mins-search-leave-data-tab-1">
			   	 <?php require_once('search-leave-data-tab-1.php'); ?>
			  </div>
			  <div id="late-mins-search-leave-totals-tab-2">
					 <?php require_once('search-leave-data-tab-2.php'); ?>
			  </div>
			</div>
		</div>
		
	
</body>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/inc/pageFooter.php"); ?>
 <script type="text/javascript" src="/KISS_RAADMA/js/export/tableExport/tableExport.js"></script>
<script type="text/javascript" src="/KISS_RAADMA/js/export/tableExport/jquery.base64.js"></script>

 <script>
 	$(document).ready(function(){


 	 	$( "#LateMisTabs" ).tabs();

 	 	var tab_number = 0;

 	 	/*Tab 1 set session*/
 	 	$('#tab1_select_all_records_form').on('submit', function(e){
 			$.session.set('tab_session', '0');
 	 	});

 	 	$('#tab1_select_employee_form').on('submit', function(e){
 			$.session.set('tab_session', '0');
 	 	});

 	 	$('#tab1_select_date_range_form').on('submit', function(e){
 			$.session.set('tab_session', '0');
 	 	});

 	 	$('#tab1_select_last_200_form').on('submit', function(e){
 			$.session.set('tab_session', '0');
 	 	});

 	 	/*Tab 2 set session*/

 	 	$('#LateMinsLeaveTotalsWeeklyPaidForm').on('submit', function(e){
 	 		$.session.set('tab_session', '1');

 	 	});


 	 	$( "#LateMisTabs" ).tabs({ active: $.session.get('tab_session') });
 	 	/*Tab 1*/
 	 	$("#tab1_select_employee_block").hide();
 	 	$("#tab1_select_date_range_block").hide();
 	 	$("#tab1_select_last_200_block").hide();
 	 	$("#tab1_select_all_records_block").hide();


 		$("input[name='selection']").click(function(){
	 		if($('#selected_employee').is(':checked')) { 
	 			$("#tab1_select_employee_block").show();
	 		}else{
	 			$("#tab1_select_employee_block").hide();
	 		}

	 		if($('#selected_date_range').is(':checked')) { 
	 			$("#tab1_select_date_range_block").show();
	 		}else{
	 			$("#tab1_select_date_range_block").hide();
	 		}


	 		if($('#selected_last_200').is(':checked')) { 
	 			$("#tab1_select_last_200_block").show();
	 		}else{
	 			$("#tab1_select_last_200_block").hide();
	 		}

	 		if($('#selected_all_records').is(':checked')) { 
	 			$("#tab1_select_all_records_block").show();
	 		}else{
	 			$("#tab1_select_all_records_block").hide();
	 		}

	 	});

	 	/*Tab 2*/


    /*$('#selected_all_records_tab_2').change(function() {
        if(this.checked) {
           alert('Select All records checkbox is checked');
        }
               
    });

    $('#selected_employee_tab_2').change(function() {
        if(this.checked) {
           alert('Select emps checkbox is checked');
        }
               
    });  */


	 	$('.datepicker').datepicker({
		     format: "yyyy-mm-dd",
		}).on('changeDate', function (ev) {
		     $(this).datepicker('hide');
		});

		$('#export_tab_1').on("click", function(){
				$('#searchLeaveDataTable').tableExport({type:'excel',escape:'false'});
		});
		
		$('#export_tab_2').on("click", function(){
				$('#searchLeaveDataTable2').tableExport({type:'excel',escape:'false'});
		});

		/*$('#tab_2_start_date').on('change', function() {
	        $('#tab_2_end_date').prop('max', $(this).val());
	    });

	    $('#tab_2_end_date').on('change', function() {
	        $('#tab_2_start_date').prop('max', $(this).val());
	    });  */
 		
 	 });
 </script>
</html>