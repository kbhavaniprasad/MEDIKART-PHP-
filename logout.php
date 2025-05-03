<?php
   setcookie('user_details', '', time() - 3600, '/');
   header("Location: index.html");
   exit();
   

?>