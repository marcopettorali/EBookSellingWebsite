<?php
    include('managment/session.php');

    $a = session_id();
    echo $a;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // username and password sent from form 

        if(isset($_SESSION['path_to_file'])){ //AVOID THAT AFTER PAYMENT YOU CAN CHANGE THE ebook_id

            echo"Please download your file before issuing another one!";
            echo"Follow this link \n";
            echo"<a href = 'download.php' >DOWNLOAD</a>";

        }

        if(!isset($_SESSION['ebook_id'])){
            //Attempt to skip previous step: die()!
            header("location:welcome.php");
            die();
        }


  
        if( isset($_POST['credit_card_number'],$_POST['cardholder_name'],$_POST['ccv'],$_POST['expiration_date']) )
        {
           
            //CHECK IF CREDITC CARD INFO ARE OK AND PAY
            if($_POST['credit_card_number'] == NULL || $_POST['cardholder_name'] == NULL || $_POST['ccv'] == NULL || $_POST['expiration_date'] == NULL){
                
                $error = "All fields are necessary!!";
                goto end;

            }
            echo"Successive payment!";
            //Once paid, retrieve the path to file and set it as SESSION var
            if ($stmt = $conn->prepare('SELECT path FROM ebook_info WHERE id = ?') ){
         
                $stmt->bind_param('s', $_SESSION['ebook_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
       
                if($row != null) {
       
                   $_SESSION['path_to_file'] = $row['path'];    //HAS TO BE OUTSIDE REACHABLE DIRECTORY, i.e. htdocs!!!!!!!!!!!!!!!!!!!!!!
                   echo"Follow this link to download your ebook!\n";
                   echo"<a href = 'download.php' >DOWNLOAD</a>";
       
                }else {
       
                   $error = "Your Login Name or Password is invalid"; //NEVER GIVE MORE INFO THAN THIS
       
                }
       
       
             }else{
                // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
                echo 'Could not prepare statement!';
             }
             $conn->close();
            
  
        } else{
           $error = "All the fields are required!";
        }
  
    }

    if($_SERVER["REQUEST_METHOD"] == "GET") {

        if(isset($_SESSION['path_to_file'])){ //AVOID THAT AFTER PAYMENT YOU CAN CHANGE THE ebook_id

            echo"Please download your file before issuing another one!";
            echo"Follow this link \n";
            echo"<a href = 'download.php' >DOWNLOAD</a>";

        }
        if( isset($_GET['ebook_id']) )
        {

            $_SESSION['ebook_id'] = $_GET['ebook_id'];

        }else{
            header("location:welcome.php");
            die();
        }

    }
end:

    
?>

<html>
   
   <head>
      <title>CreditCard Info</title>
      
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
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Credi card info</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>Credit card number  :</label><input type = "text" name = "credit_card_number" class = "box"/><br /><br />
                  <label>Card holder name  :</label><input type = "text" name = "cardholder_name" class = "box" /><br/><br />
                  <label>CCV  :</label><input type = "text" name = "ccv" class = "box" /><br/><br />
                  <label>Expiration Date  :</label><input type = "date" name = "expiration_date" class = "box" /><br/><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php if(isset($error)) echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>