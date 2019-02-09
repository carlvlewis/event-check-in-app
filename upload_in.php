<?php
//CopyRight Ignitepros.com 2017
if(!$_POST) exit();

//Required Files
require_once ('core/config.php');
require_once ('core/sanitize.inc.php');
 
//Grab Data
$full_name = isset($_POST['full_name']) ? $_POST['full_name'] : "";
$company_name = isset($_POST['company_name']) ? $_POST['company_name'] : "";
$veh_reg = isset($_POST['veh_reg']) ? $_POST['veh_reg'] : "";
$contact_list = isset($_POST['contact_list']) ? $_POST['contact_list'] : "";
$get_date = isset($_POST['get_date']) ? $_POST['get_date'] : "";
$get_ip = isset($_POST['get_ip']) ? $_POST['get_ip'] : "";
$profile_path = isset($_POST['image_profile']) ? $_POST['image_profile'] : "";

//Clean Data
$clean_full_name = mysqli_real_escape_string($con_signin, $full_name);
$clean_company_name = mysqli_real_escape_string($con_signin, $company_name);
$veh_reg = mysqli_real_escape_string($con_signin, $veh_reg);
$contact_list = mysqli_real_escape_string($con_signin, $contact_list);
$clean_get_date = mysqli_real_escape_string($con_signin, $get_date);
$clean_get_ip = mysqli_real_escape_string($con_signin, $get_ip);
$clean_profile_path = mysqli_real_escape_string($con_signin, $profile_path);

//Grab Image
$output_file_encode = $_POST["image"];
$clean_data_in = substr("$output_file_encode", 22);

//Check for same name and stop double insert
$insert_check_profile = mysqli_query($con_signin,"SELECT * FROM $table WHERE full_name = '$clean_full_name' and signature_out = ''");
$count_profile_checkin = mysqli_num_rows($insert_check_profile);

if($count_profile_checkin == 0) {
	
//Grab Contact Email		
$result_conemail = mysqli_query($con_signin,"SELECT * FROM visitor_contacts WHERE id = '$contact_list'");	
while($row = mysqli_fetch_array($result_conemail))
  {
  $get_con_email = $row['con_email'];
  } 
	

//Save to DB
$sql_sign = "INSERT INTO $table VALUES (LAST_INSERT_ID(), '$clean_get_date', '', '$clean_get_ip', '$clean_full_name', '$clean_company_name', '$veh_reg', '$contact_list', '$clean_data_in', '', '$clean_profile_path', '')";
$run_quey = mysqli_query($con_signin,$sql_sign);


if($run_quey) {
//after success send user to home page
//create mail message
	$header = "From:noreply@ignitepros.com\r\n";
	$header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";
	$mailheaders = "Hi, User<br /><br />A new Visitor ($clean_full_name) from company ($clean_company_name) has signed into the signature pad.<br />To see Signin, Login to $website_url<br />";
	$mailheaders .= "<br /><br />Thank you<br />";
	$mailheaders .= "Automatic signin system<br />";
	
//Email the request		
$subject = "New Signin from Visitor Signin System";

//Send Email to Admin contact
if($email_notice == 1) {
mail($contact_email,$subject,$mailheaders,$header);
}

//Email the request		
$con_subject = "New visitor coming to see you";

//If {GuestName} or {CompanyName} is found then add name to text
$set_emailbody = str_replace('{GuestName}',$clean_full_name,$set_emailbody);
$set_emailbody = str_replace('{CompanyName}',$clean_company_name,$set_emailbody);

//Send Email to contact
mail($get_con_email,$con_subject,$set_emailbody,$header);

//Redirect
header("Location:index.php?action=success_login");
} else {
//Failed insert	send email and redirect
//create mail message
	$header = "From:noreply@ignitepros.com \r\n<br />";
	$header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n"; 
	$mailheaders = "Hi, User<br /><br />An error has occurred at sign in system.<br /><br />";
	$mailheaders .= "Automatic signin system<br />";
$subject = "Error Signin System";
//Send Error Email
mail($contact_email,$subject,$mailheaders,$header);
//redirect
header("Location:index.php?action=error_00001");
} 
} else {
	header("Location:index.php?action=error_00002");
}
exit();
?>