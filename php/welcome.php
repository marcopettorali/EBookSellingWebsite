<?php
   include('managment/session.php');

   $a = session_id();
   echo $a;


?>
<html">
   
   <head>
      <title>Welcome </title>
   </head>
   
   <body>
      <h1>Welcome <?php echo $_SESSION['login_user']; ?></h1> 
      <h2><a href = "logout.php">Sign Out</a></h2>
      <h3><a href = "change_password.php">Change Password</a></h3>
   </body>
   
</html>