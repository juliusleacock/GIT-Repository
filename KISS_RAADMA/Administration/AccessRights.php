<?php 
	/* AccessRights.php */
	session_start();

	if(!isset($_SESSION['email_address'])){
		header("Location: /KISS_RAADMA");
		exit;
	}

    require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");

    	$listOfAccessRights = GetListOfAccessRights();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Administration: Access Rights</title>

	<link rel="stylesheet" href="/KISS_RAADMA/css/bootstrap/bootstrap.min.css">
   <link rel="stylesheet" href="/KISS_RAADMA/css/bootstrap/bootstrap-theme.min.css">
   <link rel="stylesheet" type="text/css" href="/KISS_RAADMA/css/main.css">
   <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
   <link rel="stylesheet" type="text/css" href="/KISS_RAADMA/css/datepicker/css/bootstrap-datepicker.min.css">
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

   <style type="text/css">
	#AdminModuleAccessTab{
		border:none;
	}
   </style>
</head>
<body>
		<?php require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/menu.php"); ?>

		<div class="container">
			<div class="row">
				<h1>Administration - Access Rights</h1>
			</div>

				 	<div id="AdminModuleAccessTab">
						  <ul>
						    <li><a href="#admin-tab-1">Admin: Assign Late Mins To User</a></li>
						    <li><a href="#admin-tab-2">Search : Existing Accounts</a></li>
						  </ul>
						  <div id="admin-tab-1">
						   	 <?php require_once('access-rights-assign-late-mins-tab-1.php'); ?>
						  </div>
						  <div id="admin-tab-2">
								 <?php require_once('access-rights-assign-late-mins-tab-2.php'); ?>
						  </div>
				    </div>

				   <div class="modal fade" id="change_access_right_late_mins" style="overflow:none" role ="dialog"></div>
		</div>
	
</body>
<script src="/KISS_RAADMA/js/jquery-3.1.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/KISS_RAADMA/js/bootstrap/bootstrap.min.js"></script>
  <script src="/KISS_RAADMA/js/bootstrap/bootbox.min.js"></script>
 <script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
 <script type="text/javascript" src="/KISS_RAADMA/js/datepicker/js/bootstrap-datepicker.min.js"></script>
 <script type="text/javascript" src="/KISS_RAADMA/js/jquery_session/jquery.session.js"></script>
  <script type="text/javascript" src="/KISS_RAADMA/js/jQueryFormPlugin/jquery.form.min.js"></script>

 <script type="text/javascript">
	$(document).ready(function(){

		$('#accounts_list_table').DataTable();

		$("#AdminModuleAccessTab").tabs();

		$("#AccessRightsAssignUserToLateMins").ajaxForm({
              target: '#targetMessageTab1', 
                success: function(data) { 
                	$('#targetMessageTab1').css("display", "block");
                    var message = $('#targetMessageTab1').html();

                    //$('#targetMessageTab1').show();
                    $('#targetMessageTab1').fadeIn('slow');
                    $('#targetMessageTab1').delay(3000).fadeOut('slow'); 
                }   
        });  //end of ajaxForm

		/*$('#submit_user').click(function(){
			alert("submit user btn clicked");
		});*/



 	  	$("#accounts_list_table").on("click", ".change_access_right_late_mins", function(){
 	  		 var current_row_id = $(this).prop('id');
 	  		ModalFormChangeAccessRight(current_row_id);
 	  		//alert(current_row_id);
 	  	});

 	  	function ModalFormChangeAccessRight(id){
 	  		var record_id = id;
                var info = {'record_id' : record_id};
                                    $.ajax({
                                       type: "POST",
                                       cache: false,
                                       data: {
                                         result: JSON.stringify(info)
                                       },
                                       url: "/KISS_RAADMA/modal_forms/Administration/ChangeAccessRightForLateMins.php"
                                     }).done(function(response) {
                                     $("#change_access_right_late_mins").html(response);
                        			});



 	  	}

	}); 

 </script>
</html>