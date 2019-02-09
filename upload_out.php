<?php
//CopyRight Ignitepros.com 2017
if(!$_POST) exit();

//Required Files
require_once ('core/config.php');
require_once ('core/sanitize.inc.php');

//Grab Data
$sign_id = isset($_POST['sign_id']) ? $_POST['sign_id'] : "";
$profile_path = isset($_POST['image_profile']) ? $_POST['image_profile'] : "";

//Clean Data
$clean_sign_id = mysqli_real_escape_string($con_signin, $sign_id);
$clean_profile_path = mysqli_real_escape_string($con_signin, $profile_path);

//Grab Img Code
$output_file_encode = $_POST["image"];
$clean_data_out = substr("$output_file_encode", 22);

//Save to DB
$sql_signout = "UPDATE $table SET signout_date = '$fptime', signature_out = '$clean_data_out', profile_out = '$clean_profile_path' WHERE id = '$clean_sign_id'";
$run_quey = mysqli_query($con_signin,$sql_signout);

if($run_quey) {
//after success send user to home page
header("Location:index.php?action=success_logout");
} else {
//redirect if error	
header("Location:index.php?action=error_00001");
}
exit();
?>