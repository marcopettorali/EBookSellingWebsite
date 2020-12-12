<?php
   include('managment/session.php');

   if($_SERVER["REQUEST_METHOD"] == "POST") {
    // old password and new password sent from form 

        if( isset($_POST['old_password'], $_POST['new_password']) )
        {
            
            //Password Length Check
            if (strlen($_POST['new_password']) > 20 || strlen($_POST['new_password']) < 5) {
                $error = "Password must be between 5 and 20 characters long!";

                goto end;
            }

            if ($stmt = $conn->prepare('SELECT password FROM users WHERE username = ?') ){
            
                $stmt->bind_param('s', $_SESSION['login_user']);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if($row != null) {
                    
                    //Check if hashed passwords coincide
                    if (password_verify($_POST['old_password'], $row['password'])) {
                        
                        //Update new password!!
                        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                        //Update the db
                        if ($stmt = $conn->prepare('UPDATE users SET password = ? WHERE username = ?')) {
                            // Reset the recovery code to avoid other unlegitimate requests
                            $stmt->bind_param('ss', $new_password, $_SESSION['login_user']);
                            $stmt->execute();
                        }

                        echo "Password correctly changed!";
                    }else{

                        $error = "Your password is wrong!"; //Ok this error since the user is already logged in

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
            $error = "Old password and new password are required!";
            $conn->close();
        }
        
end:

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
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Change Password</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>Old Password  :</label><input type = "password" name = "old_password" class = "box"/><br /><br />
                  <label>New Password  :</label><input type = "password" name = "new_password" class = "box" /><br/><br />
                  <label>Confirm New Password  :</label><input type = "password" class = "box" /><br/><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php if(isset($error)) echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>