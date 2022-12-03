<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RAADMA (KISS BAKING CO.)</title>
  <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap/bootstrap-theme.min.css">
  <link rel="stylesheet" href="css/index_home.css">

  <style type="text/css">
      .center-block{
        position: absolute;
        top: 10px;
        left: 20px;
        opacity: 0.25;
        float:left;
      }

      .left-text{
        top: 10px;
        left: 20px;
        opacity: 0.5;
      }

      .login-page{
        margin-top:45%;
      }
  </style>
</head>
<body>
 


 <!-- <div class="center-block">
    <h4 class="left-text">Bermudez Biscuit Company</h4>
  </div>  -->

  <div class="center-block">
    <!-- <img src="/QUOTA/pics/company_logos/bbc/bermudez-no-colour-logo.png"> -->
  </div> 



  <div class="container">

  <div class="login-page">
    <!--<h1><span id='q_letter'>Q</span><span id='u_letter'>U</span>O<span id='t_letter'>T</span><span id='a_letter'>A</span></h1>  -->
    <h1 style="color:#186A3B">RAADMA (KISS BAKING CO.)</h1>
  <div class="form">
    <form class="login-form">
      <input type="text" placeholder="email address" name="username" id="username"/>
      <input type="password" placeholder="password" name="password" id = "password"/>
      
      <button id='submit'>login</button>
      <!--<p class="message">Not registered? <a href="#">Create an account</a></p>-->
    </form>
  </div>
</div>
</div>
</body>
 <script src="js/jquery-3.1.1.min.js"></script>
  <script src="js/bootstrap/bootstrap.min.js"></script>
  <script src="js/bootstrap/bootbox.min.js"></script>

  <script>
  $('#submit').click(function(e){
                e.preventDefault();
                var username = $('#username').val();
                var password = $('#password').val();
                Login(username, password);
  });

     function Login(username, password){
              var info = {'username' : username, 'password': password};
               $.ajax({
                        type: "POST",
                        data:  {result:JSON.stringify(info)},
                        dataType: 'json',
                        url: "process/Login/processLogin.php",
                        success: function(data){
                             //alert(data.message);
                            if(data.message == 'none'){
                              var url = 'HumanResources/LateMinsAddLeaveData';
                              $(location).attr('href', url);
                              //alert("Correct Credentials");
                            }else{
                              alert(data.message);
                            }
                        }
                });
          }

  </script>

</html>

