<?php

   include('managment/session.php');

?>
<html">
   
   <head>
      <title>Welcome </title>
   </head>
   
   <body>
      <h1>Welcome <?php echo htmlspecialchars($_SESSION['login_user'],ENT_QUOTES); ?></h1> <!-- Escaped -->

      <h2>Here the list of available ebook: click on them to buy</h2>

      <?php
         if ($stmt = $conn->prepare('SELECT * FROM ebook_info') ){
            
            //$stmt->bind_param('s', $_SESSION['login_user']);
            $stmt->execute();
            $result = $stmt->get_result();
      
            echo "<ul>";
            while($row = $result->fetch_assoc()){
               echo"<li>";
               echo"<a href = creditcard_info.php?ebook_id=" . $row['id'] . ">" . $row['title'] . "</a>";
               echo "<label> Price: " . $row['price'] . "€";
               echo"</li>";
            }
            echo "</ul>";        

        }else{
            // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
            echo 'Could not prepare statement!';
        }
        $conn->close();


      ?>


      <h2><a href = "logout.php">Sign Out</a></h2>
      <h3><a href = "change_password.php">Change Password</a></h3>
   </body>
   
</html>