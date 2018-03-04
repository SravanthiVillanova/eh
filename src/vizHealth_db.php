<?php
              $username="root"; 
              $password="";  // Your credentials to connect to the database 
              $database="eHealth";
 
               //mysql_connect('localhost',$username,$password);  // Connecting to your Database 
              //@mysql_select_db($database) or die( "Unable to select database"); // Selecting your Database 
              //connect to mysql database
              $con = mysqli_connect("localhost", $username, $password, $database) or die("Error " . mysqli_error($con));              
?> 