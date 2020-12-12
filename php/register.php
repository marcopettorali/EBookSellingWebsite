<?php

  if($_SERVER["HTTPS"] != "on")
  {
      header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
      exit();
  }
  if(isset($_GET["error"])){
    switch ($_GET['error']) {
      case 1:
        $error = "Please complete the registration form!";
        break;
      case 2:
        $error = "Email not valid!";
        break;
      case 3:
        $error = "Username is not valid!";
        break;
      case 4:
        $error = "Password must be between 5 and 20 characters long!";
        break;
      case 5:
        $error = "Sorry, the CAPTCHA code entered was incorrect!";
        break;
      default:
        break;
  }
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
      });

      function check_equality_password() {
        var passwd = document.getElementsByName("password")[0].value;
        var confirmPasswd = document.getElementById("confirmPassword").value;
        if(passwd.localeCompare(confirmPasswd) != 0)
          document.getElementById("passwdNotEqual").style.visibility = "visible";
        else
          document.getElementById("passwdNotEqual").style.visibility = "hidden";
        return;
      }

  </script>

</head>
<body>
  <h1>Register</h1>
    <form action="managment/signup.php" method="POST">
      <label>Username: </label><input type="text" name="username" /><br />
      <label>Email: </label><input type="text" name="email" /><br />
      <!-- Password: <input type="text" name="password" /><br /> -->
      <div style="float: left;"><label>Password: </label></div><div id="myPassword"></div>
      <div ><label>Confirm password: </label><input type="password"  id ="confirmPassword" onkeyup="check_equality_password();" /><label style="visibility:hidden;font-size:20px; color:#cc0000; margin-top:10px" id="passwdNotEqual">Passwords not equal!</label></div>
     
      <!-- For captcha, to avoid spamming -->
      <p><img id="captcha" src="managment/captcha.php" width="160" height="45" border="1" alt="CAPTCHA">
                     <small><a href="#" onclick="
                        document.getElementById('captcha').src = 'managment/captcha.php?' + Math.random();
                        document.getElementById('captcha_code_input').value = '';
                        return false;
                        ">refresh</a>
                     </small>
                  </p>
      <p><input id="captcha_code_input" type="text" name="captcha" size="6" maxlength="5" onkeyup="this.value = this.value.replace(/[^\d]+/g, '');"> <small>copy the digits from the image into this box</small></p>
      <div style = "font-size:20px; color:#cc0000; margin-top:10px"><?php if(isset($error)) echo $error; ?></div>
      <input type="submit" value="Register" />
    </form>
</body>
</html>