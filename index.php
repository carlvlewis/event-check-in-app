<?php
//Required Files
require_once ('core/config.php');
require_once ('core/sanitize.inc.php');

//Check if system has been installed
if(!$con_signin) {
header("Location: install.php");
exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $SiteTitle; ?></title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/main.css">
<script src='js/jquery-1.8.2.js'></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script Language="JavaScript">
window.setTimeout(function() {
    $(".alert").fadeTo(950, 0).slideUp(950, function(){
        $(this).remove(); 
    });
}, 5000);
</script>
</head>
<body>
<div id="testDiv">
	<div id="testTitle">
<h1 align="center"><?php echo $SiteTitle; ?><br /><hr></h1>
	</div>
	<div id="DivForm">
<?php
//Get Data
$action = isset($_GET['action']) ? $_GET['action'] : "";
//Clean Data
$action = sanitize_sql_string($action);

if($action == 'success_login') {
echo '<div class="alert alert-success alert-dismissable" align="center">
<h3>Welcome to '.$SiteTitle.', Sign In was <a href="#" class="alert-link">Successful</a>.</h3></div>';
} else if($action == 'success_logout') {
echo '<div class="alert alert-success alert-dismissable" align="center">
<h3>Have a Good Day, Sign Out was <a href="#" class="alert-link">Successful</a>.</h3></div>';
} else if($action == 'error_00001') {
echo '<div class="alert alert-danger alert-dismissable" align="center">
<h3>An error has occurred. Please contact an Employee for assistants.</h3></div>';

} else if($action == 'error_00002') {
echo '<div class="alert alert-warning alert-dismissable" align="center">
<h3>Error you are already signed in please sign out first before you signin.</h3></div>';

}
?>
<p class="alignC">
<img src='admin/upload_MD5IMG/<?php echo $company_logo; ?>' /><br /><br />
<?php if($site_active != 1) { ?>
<strong><?php echo $company_agreement; ?></strong>
<br /><br /><hr></p><br />
<center><a href="sign_in.php" class="btn btn-success btn-lg" /><i class='fa fa-sign-in'></i> Sign In&nbsp;</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="sign_out.php" class="btn btn-danger btn-lg" /><i class='fa fa-sign-out'></i> Sign Out</a></center>
<?php } else { ?>
Sorry, Visitor Signin System is offline. Please contact your Administrator.
<?php } ?>
</div>
</div>
</body>
</html>