<?php

	/*access-rights-assign-late-mins-tab-1.php   */

	
	$logged_in_user = $_SESSION['username'];



?>
<form class="form-horizontal" id="AccessRightsAssignUserToLateMins" method="POST" action="/KISS_RAADMA/process/Administration/processAssignLateMinsModuleToUser.php">
	  <fieldset class="form-group">
	    <legend>Access Rights: Assign Late Mins Module</legend>

		<div class="form-group">
    		<label class="control-label col-sm-2">First Name:</label>
		    <div class="col-sm-4"> 
		      <input type="text" class="form-control" name="first_name" id="pwd" placeholder="Enter First Name" required>
		    </div>
		    <div class="col-sm-8"> 
		    	&nbsp;
		    </div>
		  </div>

		  <div class="form-group">
    		<label class="control-label col-sm-2">Last Name:</label>
		    <div class="col-sm-4"> 
		      <input type="text" class="form-control" name="last_name" id="pwd" placeholder="Enter Last Name" required>
		    </div>
		    <div class="col-sm-8"> 
		    	&nbsp;
		    </div>
		  </div>


	     <div class="form-group">
    		<label class="control-label col-sm-2">Username:</label>
		    <div class="col-sm-4"> 
		      <input type="text" class="form-control" name="username" id="pwd" placeholder="Enter Email Username" required>
		    </div>
		    <div class="col-sm-8"> 
		    	&nbsp;
		    </div>
		  </div>

		  <div class="form-group">
		  		<label class="control-label col-sm-2" for="pwd">Access Right:</label>
			    <div class="col-sm-4"> 
			      <select class="form-control" name="access_right">
					<?php 
						foreach ($listOfAccessRights as $key => $value) {
						?>
						<option value="<?php echo  $key?>"><?php echo $value ?></option>
					<?php
						}
					?>			      		
			      </select>
			    </div>
			    <div class="col-sm-8"> 
			    	&nbsp;
		    	</div>
		  </div>
			<!--Hidden Fields -->
			  <input type="hidden" name="logged_in_user" value="<?php echo $logged_in_user; ?>">
                 

		  <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" class="btn btn-success" value="Submit" id="submit_user"/>
      </div>
    </div>
       </fieldset>
</form>

<div class="alert alert-info" role="alert" id="targetMessageTab1" style="display:none">
</div>



