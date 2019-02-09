<?php 
$PageTitle = 'Users List';
require_once ('top.php');

//Check for demo
if($demo_mode == 1) {
	$add_perm_demo = "and email != 'admin'";
} else {
	$add_perm_demo = '';
}

//Grab Data
$cid = (isset($_GET['cid']) ? $_GET['cid'] : "");
//Clean Data
$cid = mysqli_real_escape_string($con_signin,$cid);
//Grab Numbers Only
$cid = sanitize_int($cid);

$get_cid = $cid;

$cid = mysqli_query($con_signin,"SELECT * FROM users WHERE id = '$cid' $add_perm_demo LIMIT 1");
$cid = mysqli_fetch_assoc($cid);

$main_id = $cid['id'];
$firstname = $cid['firstname'];
$lastname = $cid['lastname'];
$email = $cid['email'];

$usr_is_active = $cid['is_active'];
$usr_rank = $cid['rank'];
?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Edit User
          </h1>
        </section>
        <section class="content">
<?php
if (isset($_POST['update_usr_id'])) { 

//Grab and clean data
$update_usr_id = stripslashes(htmlspecialchars($_POST['update_usr_id'], ENT_QUOTES));
$usr_firstname = stripslashes(htmlspecialchars($_POST['usr_firstname'], ENT_QUOTES));
$usr_lastname = stripslashes(htmlspecialchars($_POST['usr_lastname'], ENT_QUOTES));
$usr_email = stripslashes(htmlspecialchars($_POST['usr_email'], ENT_QUOTES));
$usr_password = stripslashes(htmlspecialchars($_POST['usr_password'], ENT_QUOTES));
$usr_is_active = stripslashes(htmlspecialchars($_POST['usr_is_active'], ENT_QUOTES));
$usr_rank = stripslashes(htmlspecialchars($_POST['usr_rank'], ENT_QUOTES));

//Sanitize Data
$update_usr_id = sanitize_int($update_usr_id);
$usr_firstname = sanitize_sql_string($usr_firstname);
$usr_lastname = sanitize_sql_string($usr_lastname);
$usr_email = sanitize_sql_string($usr_email);
$usr_is_active = sanitize_sql_string($usr_is_active);
$usr_rank = sanitize_sql_string($usr_rank);


//Update User with password
if($usr_password != '') {
	$usr_password = MD5($usr_password);
	
	$sql_update_usr = "UPDATE users SET firstname = '$usr_firstname', lastname = '$usr_lastname', email = '$usr_email', password = '$usr_password', is_active = '$usr_is_active', rank = '$usr_rank' WHERE id = '$update_usr_id'";
} else {
//Update User
	$sql_update_usr = "UPDATE users SET firstname = '$usr_firstname', lastname = '$usr_lastname', email = '$usr_email', is_active = '$usr_is_active', rank = '$usr_rank' WHERE id = '$update_usr_id'";
}

$sql_update_usrnow = mysqli_query($con_signin, $sql_update_usr);

if($sql_update_usrnow) {
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    User has been Updated.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'User $update_usr_id Updated', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
echo '<br /><a href="users_list.php" type="submit" class="btn btn-warning"><i class="fa fa-reply"></i> Back</a>';
echo '</div></div>
        </section>
      </div>';
	include_once ('bottom.php');
	exit();		  
} else {
	echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    An Error occurred, contact support.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'User Failed to Updated $update_usr_id', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
	}
  
}

//Check Admin Rank
if($rank != 1 || $main_id == '') {
	echo "<div class='col-xs-12'>Error: You do not have permissions.</div></div></section></div>";
	include_once ('bottom.php');
	exit();
}
?>
          <div class="row">
		  <div class="col-md-6">
		  <div class="box box-primary">
               <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <div class="box-body">		  
Are you sure you want to Update User below? Warning this can not be undone.<br /><br /><h4>
<?php 
echo "<label>Firstname:</label> <br /><input type='text' name='usr_firstname' class='form-control' value='$firstname'><br />";
echo "<label>Lastname:</label> <br /><input type='text' name='usr_lastname' class='form-control' value='$lastname'><br />";
echo "<label>Email:</label> <br /><input type='email' name='usr_email' class='form-control' value='$email'><br />";
echo "<label>Password: <small>Password doesn't change if left blank</small></label> <br /><input type='password' name='usr_password' class='form-control'><br />";
?>	
<label>Active</label>
                      <select class="form-control" name="usr_is_active">
					  <?php if($usr_is_active == 1) { ?>
                        <option value="1" selected>Active Account</option>
						<option value="0">Not Active Account</option>
					  <?php } else if($usr_is_active == 2) { ?>
					  <option value="1">Active Account</option>
					  <option value="0" selected>Not Active Account</option>
					  <?php } else { ?>
					  <option value="1">Active Account</option>
					  <option value="0">Not Active Account</option>
					  <option value="" selected>Unknown</option>
					  <?php } ?>
					  </select>
					  <br />
<label>Rank Type</label>
                      <select class="form-control" name="usr_rank">
					  <?php if($usr_rank == 1) { ?>
                      <option value="1" selected>Admin</option>
					  <option value="3">User</option>
					  <?php } else if($usr_rank == 3) { ?>
					  <option value="1">Admin</option>
					  <option value="3" selected>User</option>
					  <?php } else { ?>
					  <option value="1">Admin</option>
					  <option value="3">User</option>
					  <option value="0" selected>Unknown</option>
					  <?php } ?>
					  </select>					  
</h4>				 
<br /><br />
<input type="text" name="update_usr_id" value="<?php echo $main_id; ?>" hidden>
                   <button type="submit" class="btn btn-warning" onclick="goBack()"><i class="fa fa-reply"></i> Back</button> <button type="submit" class="btn btn-primary">
<i class="fa fa-floppy-o"></i> Update User</button>
                  </div></div>
                </form>
          </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>