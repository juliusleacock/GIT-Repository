<?php  

  

  require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");



$data   =   $_POST["result"];
$data   =    json_decode("$data", true);


$record_id = $data['id'];
$username = $data['user'];
$emp_id = GetLateMinsInfo($record_id, 'employeeId');
$emp_name = GetEmployeeNameById($emp_id);


 $listOfEmps = GetEmployeeListForSelection();
  $listOfHours = GetListOfHoursWeeklyPaid();
    $listOfMins = GetListOfMinutesWeeklyPaid();
    $listOfShifts = GetListOfShifts();
    $listOfSupervisorResponses = GetListOfSupervisorNotifiedResponses();
    $listOfDepts = GetListOfDepartments();

    $selected = null;
?>

<style type="text/css">
  .modal-lg{
        width:500px;
      
        
    }


   .modal-body{
        overflow:hidden;
    }
    
    #targetMessage{
      text-align:center;
    }


.datepicker{z-index:1151 !important;}


 .edit_modal:focus{
     border-color: #0061FF;
        box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(255, 100, 255, 0.5);
    }

</style>



 <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">       
        <h4 class="modal-title">Employee :<?php echo $emp_name;?> | Dept : <?php echo  GetDeptForEmp($emp_id); ?></h4>
      </div>

 <div class="focusguard_modal" id="focusguard-1_modal" tabindex="11"></div>
    <form  class="form-horizontal" id="editLateMinsForm">

      <div class="modal-body">

                   
                     <div class ="form-group" style="">
                      <label for="edit_employee" class="col-sm-4 control-label">Employee:</label>
                  <select name="edit_employee" id="edit_employee" class="edit_modal edit_employee" tabindex="13" required>
                    <?php foreach($listOfEmps as $key => $value){ ?>  
                    <option value="<?php echo trim($key);?>" <?php if($emp_id == $key){ echo "selected='selected'";} ?> ><?php echo $value; ?></option>
                    <?php }?>
                  </select>

                     </div>
      
                 <div class ="form-group">
                      <label for="edit_leave_date" class="col-sm-4 control-label">Leave Date:</label>
                       <input type="text" name = "edit_leave_date" class="edit_leave_date datepicker edit_modal"  tabindex="14" id="edit_leave_date" value="<?php echo GetLateMinsInfo($record_id, 'leaveDate')?>" required> 
                     </div>


                       <div class ="form-group">
                        <label for="incentive_rate" class="col-sm-4 control-label">Shift:</label> 
                          <select name="edit_shift" tabindex="17" class="edit_modal">
                        <?php 
                            $shift_value = GetLateMinsInfo($record_id, 'shiftNumber');
                          foreach ($listOfShifts as $key=>$value){ 
                          ?>
                        <option value ="<?php echo $key; ?>" <?php if($shift_value == $key) echo "selected='selected'"; ?> ><?php echo $value; ?></option>
                        <?php } ?>
                      </select>
                      </div>
        

                  <div class ="form-group">
                        <label for="incentive_rate" class="col-sm-4 control-label">Time Away:</label>
                         hrs<select name="edit_hours" class="edit_hours edit_modal" id="edit_hours" tabindex="15">
                          <?php 
                            $mins_selected = GetLateMinsInfo($record_id, 'minutesAwayFromWork');
                            foreach($listOfHours as $value){
                          ?>
                            <option <?php if(GetHoursFromMins($mins_selected) == $value) echo "selected='selected'"; ?> > <?php echo $value; ?></option>

                            <?php
                            }
                          ?>
                        </select>

                         mins: <select name="edit_mins" tabindex="16" class="edit_modal" tabindex="5">
                    <?php 

                      foreach($listOfMins as $value){ 

                    ?>
                    <option <?php if(GetRemainderMins($mins_selected) == $value) echo "selected='selected'"; ?>><?php echo $value; ?></option>
                    <?php }?>
                  </select>
                       </div>


                      

                             <p id="targetMessage"></p>
                   
        

                   <!--Hidden fields -->
                    <input type="hidden" name="record_id" value="<?php echo $record_id?>">
                    <input type="hidden" name="username" value="<?php echo $username; ?>">

       

      </div><!--End of Modal Body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-default closeBtn" data-dismiss="modal" >Close</button>
        <button type="button" class="btn btn-success lastInput_modal edit_modal" id="edit" value="Edit" tabindex="19">Save</button>
      </div>
</form>

  <div class="focusguard_modal" id="focusguard-2_modal" tabindex="20"></div>

  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
        $( "#edit_dept").focus();

        //$.session.set('tab_session_add_late_mins', '0');
        var success  = false;

    var pathname = window.location.pathname;
    if(pathname === 'LateMinsAddLeaveData'){
        $.session.set('tab_session_add_late_mins', '0');
    }
  
 
        $("#edit").on("click", function(){
              $.ajax({
                asyn: false,
                url: "/KISS_RAADMA/process/HumanResources/LateMins/processLateMinsEditRecord.php",
                data: $("#editLateMinsForm").serialize(),
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                   processJson(data);
                }
              });
          
        });  

        function getLocation(pathname){
            var arr = mystr.split("/");
            return arr[3];
        }


      function processJson(data) { 
          
         $('#targetMessage').empty();
          if(data.message.indexOf("Success") >= 0){
            success = true;
             $('#targetMessage').append("<span style='color:green'>" + data.message + "</span>"); 
             //alert("Success exists in string");
          }else{
             $('#targetMessage').append("<span style='color:red'>" + data.message + "</span>"); 
               success = false;
               //alert("Success doesn't exist in string");
          }

              setTimeout(function(){
                       $('#targetMessage').empty();
               }, 3000);

              if(pathname === 'LateMinsAddLeaveData'){
                $.session.set('tab_session_add_late_mins', '1');
              }

            //  alert("Success doesn't exist in string");
  
              // $.session.set('tab_session_add_late_mins', '1');
      }

     /*   $('#editLateMinsForm').on('submit', function(e){
      $.session.set('tab_session_add_late_mins', '1');
      //alert('test submit');
    }); */

        $('#focusguard-2_modal').on('focus', function() {
          $('.firstInput_modal').focus();
        });

        $('#focusguard-1_modal').on('focus', function() {
          $('.lastInput_modal').focus();
        });

        $('.closeBtn').click(function(){
            if(success === true){
              window.location.reload();
            }
        });


        function refreshAndClose(){
           window.location.reload();  
        }

      var currentLeaveDateVal = $('.edit_leave_date').val();

      var change_date_bool = false;
        $(".edit_leave_date").datepicker({format:"yyyy-mm-dd"}).on('changeDate', function (ev) {
            change_date_bool = true;
            $(this).datepicker('hide');
            $( "#edit_hours").focus();
        }); 

    

           $('.edit_hours').on('focus', function(){
                $('.edit_leave_date').datepicker('hide');
         });


           $('.edit_employee').on('focus', function(){
                $('.edit_leave_date').datepicker('hide');
         });


        $('select[name="edit_dept"]').change(function(){
          selected_department = $('#edit_dept :selected').val();
          ArrayOfEmployeesByDept(selected_department);
          //alert('changes');

        });


            function ArrayOfEmployeesByDept(dept){
           var emp_array = [];
           //var test_message = '';
           var find_employees = {'process_type': 'GET_ARRAY_OF_EMP_BY_DEPT', 'selected_dept' : dept };
           $.ajax({ 
                    async: false,  //set async to false to assign result to variable
                    type: "POST",
                    data:  {result:JSON.stringify(find_employees)},
                    dataType: 'json',
                    url: "/KISS_RAADMA/process/HumanResources/LateMins/process.php",
                     success: function(data){
                        /* test_message = data.message1; */
                          //emp_array = data;
                         //var j_emp = $.parseJSON(data);
                          $("#edit_employee").empty();
            $.each(data, function(i, item) {
                $("#edit_employee").append('<option value="' + $.trim(i) + '">' + item  + " - " + $.trim(i) + '</option>');
            });
                     }
                });//end of ajax
        }


/*
        $(".edit_leave_date").focusout(function(){
            $(this).datepicker('hide');
        });
 */

      //$("#edit_employee option[value='"+emp_id+"']").prop('selected', true);
      //$('#edit_employee').val('300032').prop('selected', true);
     // alert(emp_id);
     
    });
</script>