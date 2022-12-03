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
	$can_export = false;

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




	if($access_right == EDITOR || UserIsAdmin($users_id) == 1 || $access_right == SUPER_EDITOR){
		$can_export = true;
	}



	 $listOfMonths = GetListOfMonths();
    $listOfYears = listOfYears();

  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HR: Late Mins- Punctuality Report</title>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/inc/pageHeader.php"); ?>
    <style type="text/css">
		.report_titles{
			font-weight: bold;			
		}

		.report_header{
			text-decoration: underline;
			text-align: center;

		}
    </style>
</head>
<body>
	<div id="test_message"></div>
	<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/menu.php"); ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
			<h1>HR - Late Mins | Punctuality Report</h1>
			</div>
		</div>
<form id="generateReport" method='POST' action="/KISS_RAADMA/HumanResources/LateMinsPunctualityReport" class="form-inline">
		<div class="row text-center">
			<div class="col-sm-6">
				<div class="form-group">
					<label>Select Month: </label>
					<select name="selected_month" id ="selected_month" tabindex="2" class="form-control font_size_selection" required>
					<?php 
						foreach ($listOfMonths as $key => $value) {
						?>
						<option value="<?php echo $value;?>" <?php echo setDefaultMonth($value); ?>><?php echo $value; ?></option>
					<?php
						}
					?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label>Select Year: </label>
					<select name="selected_year" id ="selected_year" tabindex="2" class="form-control font_size_selection" required>
					<?php 
						foreach ($listOfYears as $key => $value) {
						?>
						<option value="<?php echo $value;?>" <?php echo setDefaultYear($value); ?>><?php echo $value; ?></option>
					<?php
						}
					?>
					</select>
				</div>	
			</div>

			
		</div>
		<br>
		<div class = "row">
			<div class = "col-sm-12 text-center">
					<button type="submit" class="btn btn-success test_btn btn-lg" style="width:40%" name = 'generateReportBtn'  id="generateReportBtn"><span class="glyphicon glyphicon-search"></span> Generate Report</button>
			</div>
    	</div>
	</form>

	<hr>

	
		<?php

			if(isset($_POST['generateReportBtn'])){ 
			$selected_month = trim($_POST['selected_month']);
			$selected_year =  trim($_POST['selected_year']);
			
			$month_abbr = convertMonthAbbr($selected_month);
			$array_totals['COL_1'] = 0;
			$array_totals['COL_2'] = 0;
			$array_totals['COL_3'] = 0;
			$array_totals['COL_4'] = 0;
			$array_totals['COL_5'] = 0;


			$array_totals['2_COL_1'] = 0;
			$array_totals['2_COL_2'] = 0;
			$array_totals['2_COL_3'] = 0;
			$array_totals['2_COL_4'] = 0;
			$array_totals['2_COL_5'] = 0;
		


		?>
		<?php if($can_export){ ?>
		<div class="row">
			<div class="col-sm-12">
				<button id="export_report" class="btn btn-info pull-right"><span class="glyphicon glyphicon-export"></span> Export Data</button>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-sm-12">
			<table class="table table-bordered text-center" style="border-top-color:white !important;" id="LateMinsPunctualityReport">
				<thead>
					<th colspan="4" style="border-top-color:white !important; border-left-color:white !important; border-right-color:white !important;">PUNCTUALITY REPORT WEEKLY PAID EMPLOYEES - <?php echo "$month_abbr $selected_year"; ?></th>
				</thead>
				<tr class="report_titles">
					<td>Department</td>
					<td># of Weekly Paid Employees In Department For <?php echo $month_abbr. " ".$selected_year ?></td>
					<td>Total Number Of Late Mins (<?php echo $month_abbr. " ".$selected_year ?>) (weekly paid category)</td>
					<td>Total number of Employees with 4 occasions or for an accumulated total of more than 60 minutes for month of <?php echo $month_abbr. " ".$selected_year ?></td>
				</tr>

				<?php $total_amt_late_mins_snack = TotalNumberOfLateMinsByMonth_ByDepartment($selected_year, $selected_month);?>

				<?php while ($row = mssql_fetch_assoc($total_amt_late_mins_snack)) { ?>
					<tr>
							<td><?php echo $row['Department']; ?></td>
							
							<td><?php echo $row['Num_Employees']; ?></td>
							<td><?php echo $row['Tot_Late_Mins']; ?></td>
							<td><?php echo $row['Tot_Late_Mins_Over_Three']; ?></td>
						
					</tr>
					<?php //next($total_amt_late_mins_snack); ?>
				<?php } ?>

			</table>
			</div>
		</div><!--end of row -->

		<?php
			}

		 ?>
	</div><!-- End of Container -->



	

</body>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/inc/pageFooter.php"); ?>
  <!--Export Documeber in pdf -->
  <script type="text/javascript" src="/KISS_RAADMA/js/export/tableExport/tableExport.js"></script>
<script type="text/javascript" src="/KISS_RAADMA/js/export/tableExport/jquery.base64.js"></script>


<script type="text/javascript">
 	$(document).ready(function(){
		$('#export_report').on("click", function(){
					$('#LateMinsPunctualityReport').tableExport({type:'excel',escape:'false'});
					//alert('test');
			});

	});
</script>
</html>

<?php 

	/*helper functions*/
	/*function addTotals($total_type, $last_value, $next_value){
		$value = null;
		$array_totals = null;
		switch ($total_type) {
			case "COL_1":
				$array_totals['COL_1'] = $last_value + $next_value;
			break;

			case "COL_2":
				$array_totals['COL_2'] = $last_value + $next_value;
				break;
		}



		return $array_totals;
	}

	$array_totals['COL_1'] = 2 + 17;
	$array_totals['COL_2'] = 2 + 10;

	echo "Col 1: ". $array_totals['COL_2']; */

	function selectOption(){
		return "selected";
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







?>