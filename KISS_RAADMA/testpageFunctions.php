<?php 
date_default_timezone_set('America/Puerto_Rico');
    require_once($_SERVER['DOCUMENT_ROOT']."/RAADMA/functions.php");
   require_once($_SERVER['DOCUMENT_ROOT']."/RAADMA/constants.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/RAADMA/classes/WeeklyEmployeeLeave.php");


    /*echo "<br/>";
    echo "Record Id: " .GetRecordIdFromLeaveData('300322', '2017-11-06'); */
   // CreateAdminAccount("ADMIN");

    /*echo  GetTotalEmpForDept("'".SNACK_PROCESSING_WRKS_1."' , '".SNACK_PROCESSING_WRKS_2."'");


    echo NumberOfEmpAbsentFourOrMoreDaysByMonth("2017", "September", "'".SNACK_PROCESSING_WRKS_1."' , '".SNACK_PROCESSING_WRKS_2."'"); */


    $db = new Database();
    $db_name = RAADMA_DB;
    $conn = $db->connect_to_database($db_name);

    $query_test = "SELECT COUNT(*) FROM ".RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL;
    $exec = $db->executeQuery($query_test, $conn);
    $array_results = mssql_fetch_array($exec);
    echo $array_results[0];

   
?>