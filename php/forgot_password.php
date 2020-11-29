<?php
   include("managment/config.php");
   //session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 

      if( isset($_POST['username']) )
      {
         
         //Check if username exist, has been activated and if a valid recovery code has been already generated
         if ($stmt = $conn->prepare('SELECT email,activation_code,recovery_code,timestamp_creation_recovery_code FROM users WHERE username = ?') ){
         
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
   
            if($row != null) {
               //The username exists
               $email = $row['email'];

               //Check if account has been activated, otherwise no way to reinsert a new password
               if ($row['activation_code'] != 'activated'){
                  //Not activated yet
                  goto end;
               }
                  
               //CHECK HOW COMPARE DATE
               
               if($row['timestamp_creation_recovery_code'] != NULL){

                  //CHECK IF EXPIRED OR NOT.. SUPPOSE 1 day valid
                  if(strtotime('+1 day' , $row['timestamp_creation_recovery_code']) > time() ){
                     
                     //echo "Not expired!"; DO NOT SEND TOO MUCH INFO
                     //Hence send it again
                     $recovery_code = $row['recovery_code'];
                     goto send_mail;
                  }
                  
               }
   
            }
   
   
         }

         //If arrived here -> insert a new recovery code and send it via email

         if ($stmt = $conn->prepare('UPDATE users SET recovery_code = ?, timestamp_creation_recovery_code = ? WHERE username = ?')) {
            // Set the new activation code to 'activated', this is how we can check if the user has activated their account.
            $recovery_code = bin2hex(random_bytes(16));
            $current_timestamp = time();

            echo $current_timestamp;

            $stmt->bind_param('sss', $recovery_code,$current_timestamp,$_POST['username']);
            $stmt->execute();
            
         }else{
            echo "Error in prepared statement!";
         }

        //Send the recovery_code via email
send_mail:
         echo $recovery_code;
         echo $email;

         //Send mail
         include("managment/mailconfig.php");
         $mail->IsHTML(true);
         $mail->AddAddress($email, $_POST['username']);
         $mail->SetFrom("systemhackingproject@gmail.com", "Ebook_Forgot_Password");
         //$mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
         //$mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
         $mail->Subject = "Follow this link to recover your account";

         $recovery_link = 'http://localhost/ebook/php/managment/recovery.php?email=' . $email . '&recovery_code=' . $recovery_code;
         $message = '<p>Please click the following link to recover your account: <a href="' . $recovery_link . '">' . $recovery_link . '</a></p>';

         $mail->MsgHTML($message); 
         if(!$mail->Send()) {
            echo "Error while sending Email.";
            var_dump($mail);
         } else {
            echo "Email sent successfully";
         }



         echo 'Please check your email to reinsert a new password!';

end:
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
      <title>Forgot Password Page</title>
      
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
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Forgot Password</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>