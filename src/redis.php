<?php 
include('predis/autoload.php');
   $redis = new Redis(); 
   $redis->connect('localhost', 6379); 
   echo "Connection to server sucessfully"; 
   //check whether server is running or not 
   echo "Server is running: ".$redis->ping();

phpinfo();
?>
