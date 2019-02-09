<?php 
$PageTitle = 'My Profile';
require_once ('top.php'); ?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            My Profile
          </h1>
        </section>
        <section class="content">
<?php
//Check to see if form is submit
if (isset($_POST['id_usr'])) {
	
//Grab and clean data
$id_usr = stripslashes(htmlspecialchars($_POST['id_usr'], ENT_QUOTES));
$fname = stripslashes(htmlspecialchars($_POST['fname'], ENT_QUOTES));
$lname = stripslashes(htmlspecialchars($_POST['lname'], ENT_QUOTES));
$email = stripslashes(htmlspecialchars($_POST['email'], ENT_QUOTES));

//Sanitize Data
$id_usr = sanitize_int($id_usr);
$fname = sanitize_sql_string($fname);
$lname = sanitize_sql_string($lname);
$email = sanitize_sql_string($email);


//Check if form was submit Blank
  if(empty($id_usr)){ 
    echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                   Invalid Account ID.
                  </div>'; 
  }  else if(empty($email)){ 
    echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                   You Must Have a email.
                  </div>'; 				  
  } else {

//Update into DB
$sql_update_account = "UPDATE users SET firstname = '$fname', lastname = '$lname' , email = '$email', last_update = '$fptime' WHERE id = '$id_usr' and email = '$session_login_usr'";
//Check for Demo Mode
if($demo_mode == 0) {
$sql_check = mysqli_query($con_signin, $sql_update_account);
} else {
$sql_check = 1;
}

if($sql_check) {
	echo '<div class="row">
		  <div class="col-md-12"><div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    User Updated.
                  </div></div></div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'User Profile Updated', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
				  
} else {
	echo '<div class="row">
		  <div class="col-md-12"><div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    An Error occurred, contact support.
                  </div></div></div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'User Profile Failed to Update', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
	}
  }
  }
  
$grab_PageID = isset($_GET['page']) ? $_GET['page'] : "";  
//Sanitize Data
$grab_PageID = sanitize_sql_string($grab_PageID);
$check_page = '';
$check_page2 = '';
?>		
		
          <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="dist/img/avatar_profile.png" alt="User profile picture">
                  <h3 class="profile-username text-center"><?php echo $first ." ". $last; ?></h3>
                  <p class="text-muted text-center">Date: <?php echo $last_date ."<br />IP: ". $last_ip; ?></p>


                  <a href="change_password.php" class="btn btn-primary btn-block"><i class="fa fa-lock"></i> Change Password</a>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li <?php if($grab_PageID == '' || $grab_PageID == 'activity') { $check_page = 'active'; echo 'class="active"'; } ?>><a href="my_profile.php?page=activity">Activity</a></li>
				  <li <?php if($grab_PageID == 'settings') { $check_page2 = 'active'; echo 'class="active"'; } ?>><a href="my_profile.php?page=settings">Settings</a></li>
                </ul>
                <div class="tab-content">
				<div class='<?php echo $check_page; ?> tab-pane' id='activity'>
				  <table id="example1" class="table table-bordered table-hover">
                    <thead>
                      <tr>
					    <th>Event ID</th>
						<th>Alert</th>
                        <th>Date</th>
						<th>IP</th>
                      </tr>
                    </thead>
                    <tbody>					
<?php
//Get logged in user logs
$logs_results = mysqli_query($con_signin,"SELECT * FROM logs WHERE user = '$session_login_usr' ORDER BY `logs`.`id` DESC LIMIT 50");
while($row = mysqli_fetch_array($logs_results)){
	
$id = $row['id'];
$alert = $row['alert'];
$date = $row['date'];
$ip = $row['ip'];

	  echo "<tr>
			<td>$id</td>
			<td>$alert</td>
			<td>$date</td>
			<td>$ip</td>
			</tr>";	
}
?>
</table>
</div>
                  <div class="<?php echo $check_page2; ?> tab-pane" id="settings">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return Form1_Validator(this)" language="JavaScript" name="Form1" class="form-horizontal">
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Firstname</label>
                        <div class="col-sm-10">
						  <input type="text" name="id_usr" value="<?php echo $usr_id; ?>" hidden>
                          <input type="text" class="form-control" name="fname" value="<?php echo $first; ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Lastname</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="lname" value="<?php echo $last; ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Update Profile</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>