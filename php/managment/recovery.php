<?php
   include("config.php");
   session_start();

   // First we check if the email and code exists...
    if(isset($_POST['password'] )){
        //Check if it is allowed

        if ($_SESSION['recovery_password'] != true) {
            // Not allowed to change the password
                header("Location: ../login.php");
                die();
        }

        //Password Length Check
        if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            exit('Password must be between 5 and 20 characters long!');
        }

        //Chaneg password
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        //Update the db
        if ($stmt = $conn->prepare('UPDATE users SET password = ?, recovery_code = NULL, timestamp_creation_recovery_code = NULL WHERE username = ?')) {
            // Reset the recovery code to avoid other unlegitimate requests
            $stmt->bind_param('ss', $password, $_SESSION['username']);
            $stmt->execute();
        }

        $_SESSION['recovery_password'] = false; //When updated

        session_destroy();

        header("Location: ../login.php");
        die();
    }


    if (isset($_GET['email'], $_GET['recovery_code'])) {
        if ($stmt = $conn->prepare('SELECT * FROM users WHERE email = ? AND recovery_code = ?')) {
            $stmt->bind_param('ss', $_GET['email'], $_GET['recovery_code']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row != null) {
                // Account exists with the requested email and recovery_code.
                //Check if recovery_code is still valid
                if($row['timestamp_creation_recovery_code'] != NULL && strtotime('+1 day' , $row['timestamp_creation_recovery_code']) > time()){

                    echo "Not expired!";
                    //Ask for new password
                    $_SESSION['recovery_password']= true; //To give chance to change the password (at next request-> resetted!)
                    $_SESSION['username']= $row['username'];
                    
                    //Send new page for inserting the new password [below]

                }

                //Update the db
                if ($stmt = $conn->prepare('UPDATE users SET recovery_code = NULL, timestamp_creating_recovery_code= NULL WHERE email = ? AND recovery_code = ?')) {
                    // Reset the recovery code to avoid other unlegitimate requests
                    $stmt->bind_param('ss', $_GET['email'], $_GET['recovery_code']);
                    $stmt->execute();
                }

            } else {
                echo 'Recovery code is not valid!';
            }
        }
    }

?>

<html lang="en">
<head>
  <title>Reset Password</title>
</head>
<body>
  <h1>Reset Password</h1>
    <form action="" method="POST">
      Password: <input type="text" name="password" /><br />
      Confirm password: <input type="text" /><br />
      <input type="submit" value="Confirm" />
    </form>
</body>
</html>
