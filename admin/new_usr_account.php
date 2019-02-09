<?php 
$PageTitle = 'New User';
require_once ('top.php');
?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Create New User
          </h1>
        </section>
        <section class="content">
<?php
//Check Admin Rank
if($rank != 1) {
	echo "<div class='col-xs-12'>Error: You do not have permissions.</div></div></section></div>";
	include_once ('bottom.php');
	exit();
}

if (isset($_POST['new_usr'])) { 

//Grab and clean data
$usr_firstname = stripslashes(htmlspecialchars($_POST['usr_firstname'], ENT_QUOTES));
$usr_lastname = stripslashes(htmlspecialchars($_POST['usr_lastname'], ENT_QUOTES));
$usr_email = stripslashes(htmlspecialchars($_POST['usr_email'], ENT_QUOTES));
$usr_password = stripslashes(htmlspecialchars($_POST['usr_password'], ENT_QUOTES));
$usr_is_active = stripslashes(htmlspecialchars($_POST['usr_is_active'], ENT_QUOTES));
$usr_rank = stripslashes(htmlspecialchars($_POST['usr_rank'], ENT_QUOTES));

//Sanitize Data
$usr_firstname = sanitize_sql_string($usr_firstname);
$usr_lastname = sanitize_sql_string($usr_lastname);
$usr_email = sanitize_sql_string($usr_email);
$usr_password = sanitize_sql_string($usr_password);
$usr_is_active = sanitize_sql_string($usr_is_active);
$usr_rank = sanitize_sql_string($usr_rank);

//Check for Email
if($usr_email == '') {
	echo '<div class="col-xs-12"><div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    You must have a email.
                  </div><button type="submit" class="btn btn-warning" onclick="goBack()"><i class="fa fa-reply"></i> Back</button></div></section></div>';
	include_once ('bottom.php');
	exit();
}

//Check for password
if($usr_password == '') {
	echo '<div class="col-xs-12"><div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    You must have a password.
                  </div><button type="submit" class="btn btn-warning" onclick="goBack()">Back</button></div></section></div>';
	include_once ('bottom.php');
	exit();
}

//Hash Password
$usr_password = md5($usr_password);

//Insert User
$sql_insert_usr = "INSERT INTO users values('', '$usr_firstname', '$usr_lastname', '$usr_email', '$usr_password', '', '', '$usr_is_active', '$usr_rank', '', '', '', '')";
$sql_insert_usrnow = mysqli_query($con_signin, $sql_insert_usr);

if($sql_insert_usrnow) {
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    New user created.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'User $usr_firstname created', '$session_login_usr', '$fptime', '$log_ip', '0')";
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
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'User Failed to create $usr_firstname', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
	}
  
}
?>
          <div class="row">
		  <div class="col-md-6">
		  <div class="box box-primary">
               <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <div class="box-body">
				  <br /><h4>
<?php 
echo "<label>Firstname:</label> <br /><input type='text' name='usr_firstname' class='form-control' autofocus><br />";
echo "<label>Lastname:</label> <br /><input type='text' name='usr_lastname' class='form-control'><br />";
echo "<label>Email:</label> <br /><input type='email' name='usr_email' class='form-control'><br />";
echo "<label>Password:</label> <br /><input type='password' name='usr_password' class='form-control'><br />";
?>	
<label>Active</label>
                      <select class="form-control" name="usr_is_active">
                        <option value="1" selected>Active Account</option>
						<option value="0">Not Active Account</option>
					  </select>
					  <br />
<label>Rank Type</label>
                      <select class="form-control" name="usr_rank">
                      <option value="1">Admin</option>
					  <option value="3" selected>User</option>

					  </select>					  
</h4>				 
<br /><br />
<input type="text" name="new_usr" value="create_newusr" hidden>
                   <button type="submit" class="btn btn-warning" onclick="goBack()"><i class="fa fa-reply"></i> Back</button> <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Create User</button>
                  </div></div>
                </form>
          </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>