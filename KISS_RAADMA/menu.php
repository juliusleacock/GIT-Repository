

<!-- menu.php -->

<?php 
	$users_id = $_SESSION['users_id'];


  $boolean_user_is_admin = UserIsAdmin($users_id);
	$boolean_hr_module_access =  checkIfMainModuleIsAssignedToUser($users_id, HR_MODULE);
  $boolean_hr_late_mins_access = checkIfModuleIsAssignedToUser($users_id, 'HR_LATE_MINS_WEEKLY_PAID_EMPLOYEES');
  //$boolean_admin_module_access =  checkIfMainModuleIsAssignedToUser($users_id, ADMIN_MODULE);

  $hr_module_array = array("LateMinsAddLeaveData" , "LateMinsLeaveTotalsWeeklyPaid", "LateMinsModifyLeaveData", "LateMinsPunctualityReport");

  $admin_module_array = array("AccessRights");
?>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">RAADMA</a>
    </div>


      <?php if($boolean_user_is_admin == 1){ ?>
        <ul class="nav navbar-nav">
          <li class="<?php echo makeActive($admin_module_array);?> dropdown" id="admin"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Administration<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="<?php echo makeTabActive('AccessRights' );?>" ><a href="/KISS_RAADMA/Administration/AccessRights">Administration: Access Rights</a></li>
          </ul>
        </li> 
      </ul> 
    <?php } ?>



    <?php if($boolean_hr_module_access== 1 || $boolean_user_is_admin == 1){?>
    <ul class="nav navbar-nav">
        <li class="<?php echo makeActive($hr_module_array);?> dropdown" id="hr_late_mins"><a class="dropdown-toggle" data-toggle="dropdown" href="#">HR <span class="caret"></span></a>
        <ul class="dropdown-menu">
        <?php if($boolean_user_is_admin == 1 || $boolean_hr_late_mins_access == 1){ ?>
          <li class="<?php echo makeTabActive('LateMinsAddLeaveData' );?>" ><a href="/KISS_RAADMA/HumanResources/LateMinsAddLeaveData">Late Mins: Add Leave A Data</a></li>
             <li class="<?php echo makeTabActive('LateMinsModifyLeaveData' );?>" ><a href="/KISS_RAADMA/HumanResources/LateMinsModifyLeaveData">Late Mins: Modify Leave Data</a></li>
          <li class="<?php echo makeTabActive('LateMinsLeaveTotalsWeeklyPaid');?>" ><a href="/KISS_RAADMA/HumanResources/LateMinsLeaveTotalsWeeklyPaid">Late Mins: Leave Totals Weekly Paid</a></li>
          <li class="<?php echo makeTabActive('LateMinsPunctualityReport');?>" ><a href="/KISS_RAADMA/HumanResources/LateMinsPunctualityReport">Late Mins: Punctuality Report</a></li>
          <?php } ?>
        </ul>
      </li> 
    </ul> 
     <?php } ?>

  

    <ul class="nav navbar-nav navbar-right">
        <li><a href="/KISS_RAADMA/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>

