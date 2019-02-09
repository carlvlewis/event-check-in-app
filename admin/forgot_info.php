<?php 
require_once ('../core/config.php');
require_once ('../core/sanitize.inc.php');

//start session and create math problem to protect bot
@session_start();

if (!isset($_POST['forgot_submit'])) { 
$num1 = intval(rand(2, 9));
$num2 = intval(rand(2, 9));
$_SESSION['r'] = $num1 + $num2;
} 

//Get Data
$action = (isset($_GET['action']) ? $_GET['action'] : "");
//Clean Data
$action = mysqli_real_escape_string($con_signin,$action);
//Sanitize Data
$action = sanitize_sql_string($action);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $SiteTitle; ?> | Forgot Password</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<script Language="JavaScript">
<!--
function Form1_Validator(theForm)
{
	if (theForm.forgot_email.value == "")
  {
    alert("Please enter a E-Mail Address");
    theForm.forgot_email.focus();
    return (false);
  }	
  	if (theForm.forgot_auth.value == "")
  {
    alert("Please enter a value for the \"Verification\" field.");
    theForm.forgot_auth.focus();
    return (false);
  }	
}

//--></script>	
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><?php echo $SiteTitle; ?></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
<?php
//Check for auth link to change password
//Grab and clean data
	 $auth_check = (isset($_GET['auth_check']) ? $_GET['auth_check'] : "");
	 $auth_usrID = (isset($_GET['authID']) ? $_GET['authID'] : "");
	 //Sanitize Data
	 $auth_check = sanitize_sql_string($auth_check);
	 $auth_usrID = base64_decode($auth_usrID);
if($auth_check != '' and $auth_usrID != '') {
	
//Check Auth
if($auth_check == $GEN_AUTH_ID) {
	
//Update database with new password
	$newpass = randomPassword();
	$newmd5_pass = md5($newpass);
	$chng_pass = "UPDATE users SET password = '$newmd5_pass' WHERE email = '$auth_usrID'";
	mysqli_query($con_signin, $chng_pass);
	
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    Password changed, Please check email.
                  </div>';
//create mail message
	$mailheaders = "Here is your new password below for $website_url\n";
	$mailheaders .= "------------------------------------------------------\n";
	$mailheaders .= "Password is $newpass\n";
	$mailheaders .= "------------------------------------------------------\n";
	$mailheaders .= "Remeber to change your password once you login, Thank you.\n\n\n";
	$mailheaders .= "Please do not respond to this email. This email is generated automatically and is not monitored for responses. If you have any questions or need assistance, please see our contact information at $website_url.\nPowered by Ignitepros.com";


//Email the request		
$to = "$auth_usrID";
$subject = "Alert New Password From Ignite Pros";

mail($to, $subject, $mailheaders, "From: No Reply <noreply@ignitepros.com>\n");
			  
} else {
	echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    Password link expired, Please try again.
                  </div>';	
}
				  
}		

if (isset($_POST['forgot_submit'])) { 

//Grab and clean data
$forgot_email = stripslashes(htmlspecialchars($_POST['forgot_email'], ENT_QUOTES));
$forgot_auth = stripslashes(htmlspecialchars($_POST['forgot_auth'], ENT_QUOTES));

//Sanitize Data
$forgot_email = sanitize_sql_string($forgot_email);
$forgot_auth = sanitize_int($forgot_auth);


if($forgot_auth != $_SESSION['r']) {
	header("location:forgot_info.php?action=ErrorAuth");
	exit();	
}

//Grab logged in User info
$result_forgot = mysqli_query($con_signin,"SELECT * FROM users WHERE email = '$forgot_email'");
$check_user = mysqli_num_rows($result_forgot);

if($check_user>0){ 

while($row = mysqli_fetch_array($result_forgot))
  {
  $email = $row['email']; 
  }
 
//Encode email
$hash_email = base64_encode($email);
	
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    Email Sent, Please check your inbox/spam folder for verification link.
                  </div>';			  
	
//create mail message
	$mailheaders = "Warning If you did not request your password please contact $SiteTitle.\n\n";
	$mailheaders .= "Link below will expire in 1 hour.\n";
	$mailheaders .= "------------------------------------------------------\n";
	$mailheaders .= "Please click Link to reset password $website_url/admin/forgot_info.php?auth_check=$GEN_AUTH_ID&authID=$hash_email\n";
	$mailheaders .= "------------------------------------------------------\n";
	$mailheaders .= "\n\n";
	$mailheaders .= "Please do not respond to this email. This email is generated automatically and is not monitored for responses. If you have any questions or need assistance, please see our contact information at $website_url.\nPowered by Ignitepros.com";


//Email the request		
$to = "$email";
$subject = "Alert New Password Request From $SiteTitle";

//Send Email
mail($to, $subject, $mailheaders, "From: No Reply <noreply@ignitepros.com>\n");
	
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'User requested forgot password', '$forgot_email', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);

echo "<a href='index.php' class='btn btn-primary btn-block btn-flat'>Back to Login</a>";
	exit();  
} else {
	echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    Email does not Exists, contact support.
                  </div>';				  
$num1 = intval(rand(2, 9));
$num2 = intval(rand(2, 9));
$_SESSION['r'] = $num1 + $num2;

//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'User Failed to request password', '$forgot_email', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
	} 
}

if ($action == 'EmailSent') {
echo '<div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="success" aria-hidden="true">×</button>
                                Email Sent check you Inbox.</a>.
                            </div>';
}
if ($action == 'ErrorEmail' || $action == 'session_error') {
echo '<div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Error Email not found.</a>.
                            </div>';
							
} else if ($action == 'ErrorAuth' || $action == 'session_error') {
echo '<div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Error Incorrect Verification.</a>.
                            </div>';
} 
?>	
        <p class="login-box-msg">Forgot Password</p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return Form1_Validator(this)" language="JavaScript" name="Form1">
          <div class="form-group has-feedback">
            <input type="email" name="forgot_email" class="form-control" placeholder="Email" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
		  <div class="form-group has-feedback">
            <input type="text" name="forgot_auth" class="form-control" placeholder="<?php echo $num1." + ".$num2." ="; ?>" required>
            <span class="glyphicon glyphicon-option-horizontal form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-7">
            </div>
            <div class="col-xs-5">
              <button type="submit" name="forgot_submit" class="btn btn-warning btn-block btn-flat"><i class='fa fa-arrow-right'></i> Send Info</button>
			  <br /><br />
            </div>
          </div>
        </form>
        <a href="index.php">Back to Login</a><br />
      </div>
    </div>
    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>