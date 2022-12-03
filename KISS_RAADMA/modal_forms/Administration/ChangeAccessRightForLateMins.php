<?php  

	

  require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");



$data   =   $_POST["result"];
$data   =    json_decode("$data", true);


$record_id = $data['record_id'];



$listOfAccessRightsForLateMins= array(2 => 'EDITOR', 4 => 'SUPER EDITOR', 3=> 'GUEST'); 


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



</style>



 <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">       
        <h4 class="modal-title">Record Id :<?php echo $record_id;?></h4>
      </div>
    <form  class="form-horizontal" id="changeAccessRightsLateMins" action="/KISS_RAADMA/process/Administration/processChangeAccessRightsForLateMins.php" method="POST">

      <div class="modal-body">

                     <div class ="form-group">
                      <label for="change_access_right" class="col-sm-4 control-label">Access Right:</label>
                  <select name="change_access_right" id="change_access_right" class="change_access_right" tabindex="">
                    <?php foreach($listOfAccessRightsForLateMins as $key => $value){ ?>
                    <option value="<?php echo $key;?>"><?php echo $value; ?></option>
                    <?php }?>
                  </select>

                     </div>
   
                   
                        <div class="span12">
                             <p id="targetMessage"></p>
                        </div>
        

                   <!--Hidden fields -->
                    <input type="hidden" name="record_id" value="<?php echo $record_id?>">
               

       

      </div><!--End of Modal Body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-default closeBtn" data-dismiss="modal" >Close</button>
        <input type="submit" class="btn btn-success" id="update" value="Update" tabindex=""/> 
      </div>
</form>



  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

        $("#changeAccessRightsLateMins").ajaxForm({
              target:"#targetMessage",
              success: function() { 
                   $('#targetMessage').fadeIn('slow'); 
                   $('#targetMessage').delay('3000').fadeOut('slow');
                }   
        });  //end of ajaxForm


        function refreshAndClose(){
           window.location.reload();  
        }
     

        $('.closeBtn').click(function(){
            refreshAndClose();
        })
    });
</script>