<?php

  if($_SERVER["HTTPS"] != "on")
  {
      header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
      exit();
  }

?>

<html lang="en">
<head>
  <title>User Registration</title>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script src="../js/password_strength-master/password_strength/password_strength_lightweight.js"></script>
  <link rel="stylesheet" href="../js/password_strength-master/password_strength/password_strength.css">
  <style>
    input {
      font-size: 25px;
    }
    input[type=submit] {
      font-size: 20px;
    }
    label{
      font-size: 20px;
    }
  </style>

  <script>
            
      $(document).ready(function($) {
          $('#myPassword').strength_meter();

          /*$('#mySecondPassword').strength_meter({
              inputClass: 'c_strength_input',
              strengthMeterClass: 'c_strength_meter',
              toggleButtonClass: 'c_button_strength'
          });

          $("#myThirdPassword").strength_meter({
              strengthMeterClass: 't_strength_meter'
          });*/
      });

  </script>

</head>
<body>
  <h1>Register</h1>
    <form action="managment/signup.php" method="POST">
      <label>Username: </label><input type="text" name="username" /><br />
      <label>Email: </label><input type="text" name="email" /><br />
      <!-- Password: <input type="text" name="password" /><br /> -->
      <div style="float: left;"><label>Password: </label></div><div id="myPassword"></div>
      <div><label>Confirm password: </label><input type="password" /><br /></div>
      <input type="submit" value="Register" />
    </form>
</body>
</html>