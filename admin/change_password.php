<?php 
$PageTitle = 'My Profile';
require_once ('top.php'); 
?>
<script Language="JavaScript">
function Form1_Validator(theForm)
{

if (theForm.p_word.value != theForm.p_word2.value)
{
	alert("The two passwords are not the same.");
	theForm.p_word2.focus();
	return (false);
}
}
</script>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Change Password
          </h1>
        </section>
        <section class="content">
          <div class="row">
<?php
if (isset($_POST['change_usrpass'])) { 

//Clean Data
$current_password = mysqli_real_escape_string($con_signin,$_POST['current_password']);
$new_password = mysqli_real_escape_string($con_signin,$_POST['p_word']);
//Sanitize Data
$current_password = sanitize_sql_string($current_password);
$new_password = sanitize_sql_string($new_password);

//Hash Password
$getcurrent_password = md5($current_password);

//Get Current MD5 Password
$result5check = mysqli_query($con_signin,"SELECT * FROM users WHERE email = '$session_login_usr'");
while($row = mysqli_fetch_array($result5check))
  {
  $set_current_password = $row['password'];
  }
  
  
//check current password
if($getcurrent_password != $set_current_password) {
	echo '<div class="col-md-12"><div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Attention! Your Current Password is wrong.</div>';
	echo "<a href='javascript:window.history.back(-1);'><button class='btn btn-small btn-primary'><i class='fa fa-reply'></i> Back and Try Again...</button></a></div></div></div>";
	include_once ('bottom.php');
	exit();
} 

if($new_password != '') {
//Hash Password
$updatehash_password = md5($new_password);

//update user with password	
$sql_updatepass = "UPDATE users SET password = '$updatehash_password' WHERE email = '$session_login_usr'";
//Check for Demo Mode
if($demo_mode == 0) {
mysqli_query($con_signin,$sql_updatepass);
}


//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Password Changed', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);

echo '<div class="col-md-12"><div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    Password Updated.
                  </div></div>';

} else {
	echo '<div class="col-md-12"><div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    Password Update Failed. Please enter a Password.
                  </div><br />';
}
}
?> 
            <div class="col-lg-4">
              <div class="box box-primary">
      <div class="register-box-body">
        <form action="change_password.php" method="post" onSubmit="return Form1_Validator(this)" language="JavaScript" name="Form1">
		  
		  <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Current Password" name="current_password" autofocus>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
		  
		  <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="p_word">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
		  
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Retype password" name="p_word2">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="row">
              <button type="submit" class="btn btn-primary btn-block btn-flat" name="change_usrpass"><i class="fa fa-floppy-o"></i> Update Password</button>
            </div>
        </form>
      </div>
    </div>
          </div> </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>