<?php 
$PageTitle = 'Visiting Contacts';
require_once ('top.php');

//Check Admin Rank
if($rank != 1) {
	echo "<div class='col-xs-12'>Error: You do not have permissions.</div></div></section></div>";
	include_once ('bottom.php');
	exit();
}

//Grab Data
$cid = (isset($_GET['cid']) ? $_GET['cid'] : "");
//Clean Data
$cid = mysqli_real_escape_string($con_signin,$cid);
//Grab Numbers Only
$cid = sanitize_int($cid);

$get_cid = $cid;

$cid = mysqli_query($con_signin,"SELECT * FROM visitor_contacts WHERE id = '$cid' LIMIT 1");
$cid = mysqli_fetch_assoc($cid);

$main_id = $cid['id'];
$con_name = $cid['con_name'];
$con_email = $cid['con_email'];
?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Edit Employee Contact
          </h1>
        </section>
        <section class="content">
<?php
if (isset($_POST['update_usr_id'])) { 

//Grab and clean data
$update_usr_id = stripslashes(htmlspecialchars($_POST['update_usr_id'], ENT_QUOTES));
$con_name = stripslashes(htmlspecialchars($_POST['con_name'], ENT_QUOTES));
$con_email = stripslashes(htmlspecialchars($_POST['con_email'], ENT_QUOTES));

//Sanitize Data
$update_usr_id = sanitize_int($update_usr_id);
$con_name = sanitize_sql_string($con_name);
$con_email = sanitize_sql_string($con_email);

//Update Contact
$sql_update_usr = "UPDATE visitor_contacts SET con_name = '$con_name', con_email = '$con_email' WHERE id = '$update_usr_id'";
//Check for Demo Mode
if($demo_mode == 0) {
$sql_update_usrnow = mysqli_query($con_signin, $sql_update_usr);
} else {
$sql_update_usrnow = 1;
}


if($sql_update_usrnow) {
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    Contact has been Updated.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Contact $update_usr_id Updated', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
echo '<br /><a href="visiting_contacts.php" type="submit" class="btn btn-warning">Back</a>';
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
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Contact Failed to Updated $update_usr_id', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
	}
  
}
?>
          <div class="row">
		  <div class="col-md-6">
		  <div class="box box-primary">
               <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <div class="box-body">		  
Are you sure you want to Update Contact below? Warning this can not be undone.<br /><br /><h4>
<?php 
echo "<label>Full Name:</label> <br /><input type='text' name='con_name' class='form-control' value='$con_name' required><br />";
echo "<label>Email:</label> <br /><input type='email' name='con_email' class='form-control' value='$con_email' required><br />";
?>	

<input type="text" name="update_usr_id" value="<?php echo $main_id; ?>" hidden>
                   <button type="submit" class="btn btn-warning" onclick="goBack()"><i class='fa fa-reply'></i> Back</button> <button type="submit" class="btn btn-primary"><i class='fa fa-floppy-o'></i> Update Contact</button>
                  </div></div>
                </form>
          </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>