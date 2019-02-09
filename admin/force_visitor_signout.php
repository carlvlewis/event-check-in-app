<?php 
$PageTitle = 'Visitors';
require_once ('top.php');

//Grab Data
$cid = (isset($_GET['cid']) ? $_GET['cid'] : "");
//Clean Data
$cid = mysqli_real_escape_string($con_signin,$cid);
//Grab Numbers Only
$cid = sanitize_int($cid);
//Grab Visitor Details
$cid = mysqli_query($con_signin,"SELECT * FROM signin_tablet WHERE id = '$cid' LIMIT 1");
$checkfor_cid = mysqli_num_rows($cid);
$cid = mysqli_fetch_assoc($cid);

$main_id = $cid['id'];
$full_name = $cid['full_name'];
?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
<?php 
		if (!isset($_POST['del_visitor_id'])) { 
		  if($checkfor_cid == 0) {
		  echo "Error: Visitor does not exist.";
		  echo "</section></div>";
		  include_once ('bottom.php');
		  exit();
		  } 
				  }
?>
            Force Visitor Sign Out
          </h1>
        </section>
        <section class="content">
<?php
if (isset($_POST['del_visitor_id'])) { 

//Grab and clean data
$del_visitor_id = stripslashes(htmlspecialchars($_POST['del_visitor_id'], ENT_QUOTES));
//Grab Numbers Only
$del_visitor_id = sanitize_int($del_visitor_id);

//Signout Visitor
$sql_delete_visitor = "UPDATE signin_tablet SET signout_date = '$fptime' WHERE id = '$del_visitor_id'";
$sql_check = mysqli_query($con_signin, $sql_delete_visitor);

if($sql_check) {
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    Visitor has been Signed Out.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Visitor Forced Signed Out $del_visitor_id', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
echo '<br /><a href="visitors.php" type="submit" class="btn btn-warning"><i class="fa fa-reply"></i> Back</a>';
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
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Visitor Failed to Sign out $del_visitor_id', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
	}
  
}
?>
          <div class="row">
		  <div class="col-md-6">
		  <div class="box box-primary">
               <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <div class="box-body">
				  
Are you sure you want to Force Visitor Sign Out below? Warning this can not be undone.<br /><br /><h4>
<?php 
echo "Visitor ID: " .$main_id. "<br />";
echo "Full Name: " .$full_name. "<br />";
?>				  
</h4>				 
<br /><br />
<input type="text" name="del_visitor_id" value="<?php echo $main_id; ?>" hidden>
                   <button type="submit" class="btn btn-warning" onclick="goBack()"><i class="fa fa-reply"></i> Back</button> <button type="submit" class="btn btn-danger"><i class="fa fa-sign-out"></i> Sign out Visitor</button>
                  </div></div>
                </form>
          </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>