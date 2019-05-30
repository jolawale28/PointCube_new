<?php
   /** Database connection  **/
   
   // include constant.php
   require_once("constant.php");
   // create mysql server connection
   $connection = mysqli_connect(SERVER_NAME,USER,PASSWORD);
   if(!$connection){
       die("failed to create database connection: ").mysqli_error();    
   }
   // Select mysql database
   $db_select = mysqli_select_db($connection,DB_NAME);
   
   if(!$db_select){
       die("database selection failed: "). mysqli_error();
   }
?>
