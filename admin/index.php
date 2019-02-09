<?php 
require_once ('../core/config.php');
require_once ('../core/sanitize.inc.php');

//Check if system has been installed
if(!$con_signin) {
header("Location: install.php");
exit();
}

//Start Session and create math problem for brute force login protection
@session_start();
$num1 = intval(rand(2, 9));
$num2 = intval(rand(2, 9));
$_SESSION['r'] = $num1 + $num2;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $SiteTitle; ?> | Login</title>
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
  if (theForm.password.value == "")
  {
    alert("Please enter a Password");
    theForm.password.focus();
    return (false);
  }	
  	if (theForm.login_auth.value == "")
  {
    alert("Please enter a value for the \"Verification\" field.");
    theForm.login_auth.focus();
    return (false);
  }	
}

//--></script>		
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
	  <img src='upload_MD5IMG/<?php echo $company_logo; ?>' /><br />
        <a href="#"><?php echo $SiteTitle; ?></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
<?php
//Grab action GET request
$action = isset($_GET['action']) ? $_GET['action'] : "";
//Clean Data
$action = mysqli_real_escape_string($con_signin,$action);
//Sanitize Data
$action = sanitize_sql_string($action);

if ($action == 'ErrorLogin') {
echo '<div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Error: Incorrect E-Mail or Password</a>.
                            </div>';
}
if ($action == 'error_verification') {
echo '<div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Error: Incorrect Verification</a>.
                            </div>';
}
if ($action == 'logout') {
echo '<div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="success" aria-hidden="true">×</button>
                                Success you have been logged out.</a>.
                            </div>';
}
if ($action == 'error_session' || $action == 'session_error') {
echo '<div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Error: Session Timed Out.</a>.
                            </div>';
} else if ($action == 'forgot') {
echo '<div class="alert alert-success alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              Password Changed. Check your email.<a class="alert-link" href=""></a>
            </div>';
} else if ($action == 'ErrorActive') {
echo '<div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              Your Account has not been activated.<a class="alert-link" href=""></a>
            </div>';
} else if ($action == 'ErrorAuth') {
echo '<div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Error Incorrect Verification.</a>.
                            </div>';
} 
?>	
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="redirect.php" name="login_submit" method="post" onSubmit="return Form1_Validator(this)" language="JavaScript">
          <div class="form-group has-feedback">
            <input type="text" name="lg_email" class="form-control" placeholder="Email" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="lg_password" class="form-control" placeholder="Password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
		  <div class="form-group has-feedback">
            <input type="text" name="login_auth" class="form-control" placeholder="<?php echo $num1." + ".$num2." ="; ?>" required>
            <span class="glyphicon glyphicon-option-horizontal form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-6">
            </div>
            <div class="col-xs-6">
              <button type="submit" name="login_submit" class="btn btn-primary btn-block btn-flat"><i class='fa fa-lock'></i> Secure Login</button>
            </div>
          </div>
        </form>
        <a href="forgot_info.php">I forgot my password</a><br>
		<a href="register_account.php" class="text-center">Register a new account</a>
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