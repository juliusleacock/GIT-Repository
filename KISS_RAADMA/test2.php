<?php 

       require_once($_SERVER['DOCUMENT_ROOT']."/RAADMA/functions.php");

       $db = new Database();
       $db_name = "TEST_DB";
       $conn_test_tbl_1 = $db->connect_to_database($db_name);

       $query_string = "SELECT * FROM test_tbl_1 order by id desc";
       $exec = $db->executeQuery($query_string, $conn_test_tbl_1);
       $row_number = 1;
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>RAADMA: TEST PAGE: 2</title>

	<!--<link rel="stylesheet" type="text/css" href="scripts/jsGrid/jsGrid.min.css">
	<link rel="stylesheet" type="text/css" href="scripts/jsGrid/jsGrid-theme.min.css">-->
    <link rel="stylesheet" type="text/css" href="/RAADMA/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/RAADMA/css/bootstrap/bootstrap-theme.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">


    <style type="text/css">
    .table {
        border-radius: 5px;
        margin: 0px auto;
        float: none;
    }
     


     .btn-xl {
        padding: 18px 28px;
        font-size: 22px;
        border-radius: 8px;
        margin-top: 200px;
        margin-right:100px;
    }

    div.testTable{
        width:80%;
        text-align: center;
        display:inline-block;
        margin-left:10%;
        
    }

    /*th, td {
        border: 1px solid black;
        width: 70px;
    }

    .btns_column { width: 70px; } */
    </style>

	
</head>
<body>
		<!--<div id="jsGrid">
		</div>-->
        <div class="container">
                <div class ="row" id="table_row">  
          
                    <table  class="table table-bordered display" id="testTable" width:"100%">
                     <thead>
                          <tr>
                            <th>Row #</th>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                          </tr>
                    </thead>    
                    <tbody>
                        <tr>
                            <form method="post" id="newRecordForm">
                            <td>&nbsp;</td>
                            <td><input type= "text" id="nameOfUser" name="nameOfUser" required autofocus></td>
                            <td><input type= "email"  id="emailAddress"/></td>
                            <td><button type ="button" class="btn btn-success save"  id="save" >Save</button></td>
                            <td>&nbsp;</td>
                            </form>
                        </tr>

                    <?php 

                        while($row = mssql_fetch_assoc($exec)){
                    ?>

                        <tr>
                            <td><?php echo $row_number; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email_address']; ?></td>
                            <td class=""><button type="button" class="btn btn-info edit" id="<?php echo $row['id'];?>" data-toggle="modal" data-target="#editQuota">Edit</button></td>
                        <td><button type='submit' class="btn btn-danger delete" id="<?php echo $row['id'];?>" >Delete</button></td>

                        </tr>

                    <?php 
                       $row_number++;

                    }

                    ?>
                
                        </tbody>
                    </table>

                   
        	    </div>
        </div>

</body>
<script type="text/javascript" src="/RAADMA/js/jquery-3.1.1.min.js"></script>
<!--<script type="text/javascript" src="scripts/jsGrid/jsgrid.min.js"></script> -->
<script type="text/javascript" src="/RAADMA/js/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>


 $(document).ready(function(){



   var t = $('#testTable').DataTable({"order":[]});

   $("#newRecordForm").submit(function(e) {
        e.preventDefault();
    });

});//end of document
 $('#save').on('keypress', function(event){
    if(event.which == 13){
            var name = $('#nameOfUser').val();
            var email = $('#emailAddress').val();
            SaveData(name, email);
            $("#table_row").load("test2.php");             
    }  
});


 function SaveData(name, emailAddress){
        var info = {'process_type' : 'SAVE_DATA',  'name' : name, 'email_address' : emailAddress};
        $.ajax({
                type: "POST",
                data:  {result:JSON.stringify(info)},
                async: false,
                url: "/RAADMA/processTest2.php"
         });
        
    }

</script>
</html>