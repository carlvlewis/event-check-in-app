<?php 
$PageTitle = 'Visitors';
require_once ('top.php');
?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Force Visitors Sign Out
          </h1>
        </section>
        <section class="content">		  
<?php		  
//Get Data
$visitor_id = isset($_POST['visitor_id']) ? $_POST['visitor_id'] : "";
//Clean Data
$visitor_id = mysqli_real_escape_string($con_signin, $visitor_id);

$myArrays = explode(',', $visitor_id);
foreach ($myArrays as $visitor_id)
{
//Grab Numbers Only
$del_visitor_id = sanitize_int($visitor_id);


//Signout Visitor
$sql_delete_visitor = "UPDATE signin_tablet SET signout_date = '$fptime' WHERE id = '$del_visitor_id'";
$sql_check = mysqli_query($con_signin, $sql_delete_visitor);

if($sql_check) {
	echo "<div class='alert alert-success alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                    <h4><i class='icon fa fa-check'></i> Success!</h4>
                    Visitors #$del_visitor_id has been Signed Out.
                  </div>";
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Visitor Forced Signed Out $del_visitor_id', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
echo '<br /><a href="visitors.php" type="submit" class="btn btn-warning"><i class="fa fa-reply"></i> Back</a><br /><hr><br />';
		  
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

echo '</div></div>
        </section>
      </div>';
?>
          </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>