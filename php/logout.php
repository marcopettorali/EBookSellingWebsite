<?php

   if($_SERVER["HTTPS"] != "on")
   {
      header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
      exit();
   }

   session_start();
   
   if(session_destroy()) {
      header("Location: login.php");
   }
?>