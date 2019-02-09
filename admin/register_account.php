<?php 
require_once ('../core/config.php');
require_once ('../core/sanitize.inc.php');

//start session and create math problem to stop bots
@session_start();

if (!isset($_POST['register_submit'])) { 
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
    <title><?php echo $SiteTitle; ?> | Register Account</title>
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
      </div>
      <div class="login-box-body">
<?php
if (isset($_POST['register_submit'])) { 

//Grab and clean data
$register_fname = stripslashes(htmlspecialchars($_POST['register_fname'], ENT_QUOTES));
$register_lname = stripslashes(htmlspecialchars($_POST['register_lname'], ENT_QUOTES));
$register_password = stripslashes(htmlspecialchars($_POST['register_password'], ENT_QUOTES));
$register_email = stripslashes(htmlspecialchars($_POST['register_email'], ENT_QUOTES));
$register_auth = stripslashes(htmlspecialchars($_POST['register_auth'], ENT_QUOTES));

//Sanitize Data
$register_fname = sanitize_sql_string($register_fname);
$register_lname = sanitize_sql_string($register_lname);
$register_password = sanitize_sql_string($register_password);
$register_email = sanitize_sql_string($register_email);
$register_auth = sanitize_sql_string($register_auth);


if($register_auth != $_SESSION['r']) {
	header("location:register_account.php?action=ErrorAuth");
	exit();	
}

if($register_fname == '') {
	header("location:register_account.php?action=ErrorMissing");
	exit();
}
if($register_lname == '') {
	header("location:register_account.php?action=ErrorMissing");
	exit();
}
if($register_password == '') {
	header("location:register_account.php?action=ErrorMissing");
	exit();
}
if($register_email == '') {
	header("location:register_account.php?action=ErrorMissing");
	exit();
}

//Grab logged in User info
$result_checkemail = mysqli_query($con_signin,"SELECT * FROM users WHERE email = '$register_email'");
$check_user = mysqli_num_rows($result_checkemail);

if($check_user == 0){ 

 $register_password = md5($register_password);
//Insert new user
$sql_insert_usr = "INSERT INTO users values('', '$register_fname', '$register_lname', '$register_email', '$register_password', '', '', '0', '3', '', '', '', '')";
$sql_insert_usrnow = mysqli_query($con_signin, $sql_insert_usr);
	
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    User Created. Admin Must Approve Account.
                  </div>';		  
	
//create mail message
	$mailheaders = "New user below registered at $SiteTitle\n";
	$mailheaders .= "Email: $register_email\n Account is waiting to be approved.";
	$mailheaders .= "\n";
	$mailheaders .= "\n";
	$mailheaders .= "$SiteTitle\n";


//Email the request		

$subject = "$SiteTitle New Signup";

mail($contact_email, $subject, $mailheaders, "From: No Reply <noreply@ignitepros.com>\n");

	
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'New user registered $register_email', '$contact_email', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);

echo "<a href='index.php' class='btn btn-primary btn-block btn-flat'>Back to Login</a>";
	exit();  
} else {
	echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    Email already Exists, contact support.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'New user registered Failed to create $register_email', '$contact_email', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
echo "<a href='register_account.php' class='btn btn-primary btn-block btn-flat'>Back to Register</a>";
exit();
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
} else if ($action == 'ErrorMissing') {
echo '<div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Error Missing Fields.</a>.
                            </div>';
} 
?>	
        <p class="login-box-msg">Register Account</p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return Form1_Validator(this)" language="JavaScript" name="Form1">
          
		  <div class="form-group has-feedback">
            <input type="text" name="register_fname" class="form-control" placeholder="Firstname" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
		  <div class="form-group has-feedback">
            <input type="text" name="register_lname" class="form-control" placeholder="Lastname" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
		  <div class="form-group has-feedback">
            <input type="password" name="register_password" class="form-control" placeholder="Password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
		  
		  <div class="form-group has-feedback">
            <input type="email" name="register_email" class="form-control" placeholder="Email" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
		  <div class="form-group has-feedback">
            <input type="text" name="register_auth" class="form-control" placeholder="<?php echo $num1." + ".$num2." ="; ?>" required>
            <span class="glyphicon glyphicon-option-horizontal form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-7">
            </div>
            <div class="col-xs-5">
              <button type="submit" name="register_submit" class="btn btn-success btn-block btn-flat"><i class='fa fa-arrow-right'></i> Register</button>
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