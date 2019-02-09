<?php
//CopyRight Ignitepros.com 2017
if(!$_POST) exit();

//Check from post at login page
if(isset($_POST['login_submit'])){ 
require_once ('../core/config.php');
require_once ('../core/sanitize.inc.php');
ini_set('session.gc_maxlifetime', $lm_timeout);
session_set_cookie_params($lm_timeout);
@session_start(); 
 
//Grab Login Post Data
$lg_email = isset($_POST['lg_email']) ? $_POST['lg_email'] : "";
$lg_password = isset($_POST['lg_password']) ? $_POST['lg_password'] : "";
$lg_login_auth = isset($_POST['login_auth']) ? $_POST['login_auth'] : "";

//Clean Data
$lg_email = mysqli_real_escape_string($con_signin,$lg_email);
$lg_password = mysqli_real_escape_string($con_signin,$lg_password);

//Sanitize Data
$lg_email = sanitize_sql_string($lg_email);
$lg_password = sanitize_sql_string($lg_password);
$lg_login_auth = sanitize_int($lg_login_auth);

//Check Auth Math
if($lg_login_auth != $_SESSION['r']) {
	header("location:index.php?action=ErrorAuth");
	exit();	
}

//MD5 Hash Password
$lg_password = MD5($lg_password);

//Checking the user
$sel_user = "select * from users where email = '$lg_email' AND password = '$lg_password'";
$run_user = mysqli_query($con_signin, $sel_user);
$check_user = mysqli_num_rows($run_user);

//Check user if valid
if($check_user>0){
		
$cid_login = mysqli_query($con_signin,"SELECT * FROM users WHERE email = '$lg_email' AND password = '$lg_password' LIMIT 1");
$cid_login = mysqli_fetch_assoc($cid_login);
$check_is_active = $cid_login['is_active'];

if($check_is_active == 0){
	header("location:index.php?action=ErrorActive");
	exit();
}

//Set session values
$_SESSION['login_usr'] = $lg_email;
$_SESSION['login_time'] = time();
 

//Update User Login Date/IP
$sql_update_usr = "UPDATE users SET ip = '$log_ip', date = '$fptime' WHERE email = '$lg_email'";
mysqli_query($con_signin,$sql_update_usr);

//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'User Login Success', '$lg_email', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);

//Redirect success login to dashboard
header("location:dashboard.php"); 
exit();

} else { 
//Redirect User to Login Error
header("location:index.php?action=ErrorLogin"); 
exit();
}

} else {
	//Redirect so page can not be called from browser without post
	header("location:index.php");
	exit();
}
?>