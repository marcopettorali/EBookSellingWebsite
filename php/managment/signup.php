<?php
    include("config.php");
    session_start(); //TO CHECK IF NEEDED NO in truth since no usage of _SESSION since even not registered

    //ALWAYS CHECK ALSO ON SERVER SIDE, BECAUSE JS ON CLIENT SIDE MAY BE SKIPPED!

    // Now we check if the data was submitted, isset() function will check if the data exists.
    if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
        // Could not get the data that should have been sent.
        exit('Please complete the registration form!');
    }
    // Make sure the submitted registration values are not empty.
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
        // One or more values are empty.
        exit('Please complete the registration form');
    }

    //Check properties on submitted fields

    //Email Validation
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        exit('Email is not valid!');
    }

    //Invalid Characters Validation for username
    if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
        exit('Username is not valid!');
    }

    //Password Length Check
    if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        exit('Password must be between 5 and 20 characters long!');
    }


    // We need to check if the account with that username exists.
    if ($stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?')) {

        // Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        // Store the result so we can check if the account exists in the database [only by this invocation the result og the query is transferred]
        if ($stmt->num_rows > 0) {
            // Username already exists
            echo 'Username exists, please choose another!';
        } else {
            // Insert new account
            
            if ($stmt = $conn->prepare('INSERT INTO users (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
                // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                //The $uniqud variable will generate a unique ID that we'll use for our activation code, this will be sent to the user's email address.
                $uniqid = uniqid();
                $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);

                $stmt->execute();
                //echo 'You have successfully registered, you can now login!';

                //Send mail
                include("mailconfig.php");
                $mail->IsHTML(true);
                $mail->AddAddress($_POST['email'], $_POST['username']);
                $mail->SetFrom("systemhackingproject@gmail.com", "Ebook_Registration");
                //$mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
                //$mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
                $mail->Subject = "Asking for confirmation of ebook registration";

                $activate_link = 'http://localhost/ebook/php/managment/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
                $message = '<p>Please click the following link to activate your account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';

                $mail->MsgHTML($message); 
                if(!$mail->Send()) {
                    echo "Error while sending Email.";
                    var_dump($mail);
                } else {
                    echo "Email sent successfully";
                }

                echo 'Please check your email to activate your account!';


            } else {
                // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
                echo 'Could not prepare statement!';
            }
        }

        $stmt->close();
    } else {
        // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
        echo 'Could not prepare statement!';
    }
    $conn->close();


?>