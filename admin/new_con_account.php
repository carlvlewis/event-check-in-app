<?php 
$PageTitle = 'Visiting Contacts';
require_once ('top.php');
?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Create New Visiting Contact
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
$con_name = stripslashes(htmlspecialchars($_POST['con_name'], ENT_QUOTES));
$con_email = stripslashes(htmlspecialchars($_POST['con_email'], ENT_QUOTES));

//Sanitize Data
$con_name = sanitize_sql_string($con_name);
$con_email = sanitize_sql_string($con_email);

//Check for Name
if($con_name == '') {
	echo '<div class="col-xs-12"><div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    You must have a name.
                  </div><button type="submit" class="btn btn-warning" onclick="goBack()"><i class="fa fa-reply"></i> Back</button></div></section></div>';
	include_once ('bottom.php');
	exit();
}

//Check for Email
if($con_email == '') {
	echo '<div class="col-xs-12"><div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    You must have a email.
                  </div><button type="submit" class="btn btn-warning" onclick="goBack()"><i class="fa fa-reply"></i> Back</button></div></section></div>';
	include_once ('bottom.php');
	exit();
}



//Insert User
$sql_insert_usr = "INSERT INTO visitor_contacts values(LAST_INSERT_ID(), '$con_name', '$con_email', '$session_login_usr', '$fptime')";
$sql_insert_usrnow = mysqli_query($con_signin, $sql_insert_usr);

if($sql_insert_usrnow) {
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    New contact created.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Contact $con_name created', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
echo '<br /><a href="visiting_contacts.php" type="submit" class="btn btn-warning"><i class="fa fa-reply"></i> Back</a>';
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
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Contact Failed to create $con_name', '$session_login_usr', '$fptime', '$log_ip', '0')";
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
echo "<label>Full Name:</label> <br /><input type='text' name='con_name' class='form-control' required autofocus><br />";
echo "<label>Email:</label> <br /><input type='email' name='con_email' class='form-control' required><br />";
?>				  
</h4>				 
<br /><br />
<input type="text" name="new_usr" value="create_newusr" hidden>
                   <button type="submit" class="btn btn-warning" onclick="goBack()"><i class="fa fa-reply"></i> Back</button> <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Create Contact</button>
                  </div></div>
                </form>
          </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>