<?php
//Logout User Now
session_start();
session_unset();
session_destroy(); 
header("location:index.php?action=logout");
exit();
?>