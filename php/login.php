<?php
   include("managment/config.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 

      if( isset($_POST['username']) )
      {
         
         if ($stmt = $conn->prepare('SELECT password,activation_code FROM users WHERE username = ?') ){
         
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
   
            if($row != null) {
   
               //Check if account has been activated
               if ($row['activation_code'] != 'activated'){
   
                  $error = "Your Login Name or Password is invalid"; //NEVER GIVE MORE INFO THAN THIS
                  
               }
               
               //Check if hashed passwords coincide
               if (password_verify($_POST['password'], $row['password'])) {
                  // $row['password'] being the hashed password saved in the database
                  
                  $_SESSION['login_user'] = $_POST['username'];   //NEED TO BE ESCAPE????? only if concatenated in html or in mysql, so take care of it
               
                  session_regenerate_id(TRUE); //To change session_id a.k.a PHPSESSID otherwise always the same
         
                  header("location: welcome.php");
                  $conn->close();
                  die(); //aka exit()/return from this function
              }else{
   
                  $error = "Your Login Name or Password is invalid"; //NEVER GIVE MORE INFO THAN THIS
   
              }
   
            }else {
   
               $error = "Your Login Name or Password is invalid"; //NEVER GIVE MORE INFO THAN THIS
   
            }
   
   
         }else{
            // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
            echo 'Could not prepare statement!';
         }
         $conn->close();

      } else{
         $error = "Username is required!";
      }


      
/*
      $myusername = mysqli_real_escape_string($conn,$_POST['username']);
      $mypassword = mysqli_real_escape_string($conn,$_POST['password']); 
      
      $hashed_password = password_hash($mypassword, PASSWORD_DEFAULT);

      echo $hashed_password;

      $sql = "SELECT username FROM users WHERE username = '$myusername' and password = '$hashed_password'";
      $result = mysqli_query($conn,$sql);
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {

         $_SESSION['login_user'] = $myusername;
         
         session_regenerate_id(TRUE); //To change session_id a.k.a PHPSESSID otherwise always the same

         header("location: welcome.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }*/
   }
?>
<html>
   
   <head>
      <title>Login Page</title>
      
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         .box {
            border:#666666 solid 1px;
         }
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFFF">
	
      <div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>

               <a href="register.php">Register</a>
               <a href="forgot_password.php">Forgot Password?</a>
               <a href="change_password.php">Change Password?</a>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php if(isset($error)) echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>