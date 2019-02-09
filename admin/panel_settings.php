<?php 
$PageTitle = 'Panel Settings';
require_once ('top.php');

//Check Admin Rank
if($rank != 1) {
	echo "<div class='col-xs-12'>Error: You do not have permissions.</div></div></section></div>";
	include_once ('bottom.php');
	exit();
}
?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Site Settings
          </h1>
        </section>
        <section class="content">
<?php
//Update Printer Settings
if (isset($_POST['update_printer_settings'])) { 

//Grab and clean data
$set_printername = htmlspecialchars($_POST['set_printername'], ENT_QUOTES);
$set_printebadge = stripslashes(htmlspecialchars($_POST['set_printebadge'], ENT_QUOTES));

//Sanitize Data
$set_printername = sanitize_sql_string($set_printername);
$set_printebadge = sanitize_sql_string($set_printebadge);

//Update Settings (No Image Found)
$sql_update_settings = "UPDATE settings SET set_printername = '$set_printername', set_printebadge = '$set_printebadge' WHERE id = '1'";

//Check for Demo Mode
if($demo_mode == 0) {
$sql_update_setnow = mysqli_query($con_signin, $sql_update_settings);
} else {
$sql_update_setnow = 1;
}

if($sql_update_setnow) {
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    Printer Settings have been Updated.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Printer Settings Have Been Updated', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
echo '<br /><a href="panel_settings.php" type="submit" class="btn btn-warning">Back</a>';
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
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Printer Settings Failed to Update', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
	}

}

if (isset($_POST['update_settings'])) { 

//Grab and clean data
$set_websiteurl = stripslashes(htmlspecialchars($_POST['set_websiteurl'], ENT_QUOTES));
$set_sitetitle = stripslashes(htmlspecialchars($_POST['set_sitetitle'], ENT_QUOTES));
$set_contactemail = stripslashes(htmlspecialchars($_POST['set_contactemail'], ENT_QUOTES));
$set_timeout = stripslashes(htmlspecialchars($_POST['set_timeout'], ENT_QUOTES));
$set_companyagreement = stripslashes(htmlspecialchars($_POST['set_companyagreement'], ENT_QUOTES));
$set_emailbody = stripslashes(htmlspecialchars($_POST['set_emailbody'], ENT_QUOTES));
$set_image = stripslashes(htmlspecialchars($_POST['set_image'], ENT_QUOTES));
$set_visitoremail = stripslashes(htmlspecialchars($_POST['set_visitoremail'], ENT_QUOTES));
$set_newvisitors = stripslashes(htmlspecialchars($_POST['set_newvisitors'], ENT_QUOTES));
$set_show_veh_reg = stripslashes(htmlspecialchars($_POST['set_show_veh_reg'], ENT_QUOTES));
$set_signature = stripslashes(htmlspecialchars($_POST['set_signature'], ENT_QUOTES));
$upload_hashfile = isset($_FILES['file_uploadhash']['name']) ? $_FILES['file_uploadhash']['name'] : "";

//Sanitize Data
$set_websiteurl = sanitize_sql_string($set_websiteurl);
$set_sitetitle = sanitize_sql_string($set_sitetitle);
$set_contactemail = sanitize_sql_string($set_contactemail);
$set_timeout = sanitize_sql_string($set_timeout);
$set_companyagreement = sanitize_sql_string($set_companyagreement);
$set_emailbody = sanitize_sql_string($set_emailbody);
$set_image = sanitize_sql_string($set_image);
$set_visitoremail = sanitize_sql_string($set_visitoremail);
$set_show_veh_reg = sanitize_sql_string($set_show_veh_reg);
$set_signature = sanitize_sql_string($set_signature);
$set_newvisitors = sanitize_sql_string($set_newvisitors);

//New File name change
$new_namechange = $dateupload."_complogo_";
// Your file name you are uploading 
$file_name = basename($upload_hashfile);
$file_nameext = str_replace(".", "", substr($file_name, -4));

//Generate random file name 
$sdate = rand(000,999);

$new_file_name = $new_namechange.$sdate.".".$file_nameext;

$path = "upload_MD5IMG/".$new_file_name;

if (!empty($_FILES['file_uploadhash']['name'])){
    $remote_file = $path;
    if(isset($file_nameext) and in_array(strtolower($file_nameext), array("jpg","jpeg","png"))){
        move_uploaded_file($_FILES["file_uploadhash"]["tmp_name"], $path);
        echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    File Uploaded Successfully.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'File Uploaded for Company Logo', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
 
    } else {
        echo ("<script type='text/javascript'>alert('Error: Only jpg, jpeg, png files are allowed.');</script>");
		echo '<br /><a href="panel_settings.php" type="submit" class="btn btn-warning">Back</a>';
		exit();
    }
	
	
//Update Settings
$sql_update_settings = "UPDATE settings SET website_url = '$set_websiteurl', website_title = '$set_sitetitle', company_logo = '$new_file_name', contact_email = '$set_contactemail', company_agreement = '$set_companyagreement', set_emailbody = '$set_emailbody', vss_timeout = '$set_timeout', take_image = '$set_image', set_visitoremail = '$set_visitoremail', show_veh_reg = '$set_show_veh_reg', show_signature = '$set_signature', active = '$set_newvisitors' WHERE id = '1'";	
	
	
} else {
//Update Settings (No Image Found)
$sql_update_settings = "UPDATE settings SET website_url = '$set_websiteurl', website_title = '$set_sitetitle', contact_email = '$set_contactemail', company_agreement = '$set_companyagreement', set_emailbody = '$set_emailbody', vss_timeout = '$set_timeout', take_image = '$set_image', set_visitoremail = '$set_visitoremail', show_veh_reg = '$set_show_veh_reg', show_signature = '$set_signature', active = '$set_newvisitors' WHERE id = '1'";
}


//Check for Demo Mode
if($demo_mode == 0) {
$sql_update_setnow = mysqli_query($con_signin, $sql_update_settings);
} else {
$sql_update_setnow = 1;
}


if($sql_update_setnow) {
	echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    Panel Settings have been Updated.
                  </div>';
//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Panel Settings Have Been Updated', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
echo '<br /><a href="panel_settings.php" type="submit" class="btn btn-warning">Back</a>';
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
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Panel Settings Failed to Update', '$session_login_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);
	}
  
}
?>
          <div class="row">
		  <div class="col-md-7">
		  <div class="box box-primary">
               <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype='multipart/form-data'>
                  <div class="box-body">
<h4>
<?php 
echo "<label>Website URL:</label> <br /><input type='text' name='set_websiteurl' class='form-control' value='$website_url'><br />";
echo "<label>Site Title:</label> <br /><input type='text' name='set_sitetitle' class='form-control' value='$SiteTitle'><br />";
echo "<label>Contact Email: <small>Will send email on new visitors</small></label> <br /><input type='email' name='set_contactemail' class='form-control' value='$contact_email'><br />";
echo "<label>Version:</label> <br /><input type='email' name='set_version' class='form-control' value='$version_run' readonly='readonly'><br />";
?>	
<label>Company Logo <small>(Only .png and .jpg Allowed)</small></label>
<input type="file" name="file_uploadhash" class="form-control btn-primary">
<font size='2'>Current Image: <?php echo $company_logo; ?></font>
<br /><br />
<label>Login Session Timeout</label>
                      <select class="form-control" name="set_timeout">
					  <?php if($lm_timeout == 10800) { ?>
                      <option value="10800" selected>3 Hours</option>
					  <option value="43200">12 Hours</option>
					  <option value="86400">24 Hours</option>
					  <?php } else if($lm_timeout == 43200) { ?>
					  <option value="10800">3 Hours</option>
					  <option value="43200" selected>12 Hours</option>
					  <option value="86400">24 Hours</option>
					  <?php } else if($lm_timeout == 86400) { ?>
					  <option value="10800">3 Hours</option>
					  <option value="43200">12 Hours</option>
					  <option value="86400" selected>24 Hours</option>
					  <?php } else { ?>
					  <option value="10800">Active Account</option>
					  <option value="86400">Not Active Account</option>
					  <option value="" selected>Unknown</option>
					  <?php } ?>
					  </select>				 
<br />

<label>Take Visitor Image</label>
                      <select class="form-control" name="set_image">
					  <?php if($take_image == 0) { ?>
                      <option value="0" selected>On</option>
					  <option value="1">Off</option>
					  <?php } else if($take_image == 1) { ?>
					  <option value="0">On</option>
					  <option value="1" selected>Off</option>
					  <?php } else { ?>
					  <option value="0">On</option>
					  <option value="1">Off</option>
					  <option value="" selected>Unknown</option>
					  <?php } ?>
					  </select>				 
<br />
<label>Show Signature Field</label>
                      <select class="form-control" name="set_signature">
					  <?php if($show_signature == 1) { ?>
                      <option value="1" selected>On</option>
					  <option value="0">Off</option>
					  <?php } else if($show_signature == 0) { ?>
					  <option value="1">On</option>
					  <option value="0" selected>Off</option>
					  <?php } else { ?>
					  <option value="1">On</option>
					  <option value="0">Off</option>
					  <option value="" selected>Unknown</option>
					  <?php } ?>
					  </select>				 
<br />
<label>Show Visitor Contact Names <small>(Will send email to contact if on)</small></label>
                      <select class="form-control" name="set_visitoremail">
					  <?php if($set_visitoremail == 1) { ?>
                      <option value="1" selected>On</option>
					  <option value="0">Off</option>
					  <?php } else if($set_visitoremail == 0) { ?>
					  <option value="1">On</option>
					  <option value="0" selected>Off</option>
					  <?php } else { ?>
					  <option value="1">On</option>
					  <option value="0">Off</option>
					  <option value="" selected>Unknown</option>
					  <?php } ?>
					  </select>				 
<br />
<label>Show Vehicle Registration Field</label>
                      <select class="form-control" name="set_show_veh_reg">
					  <?php if($show_veh_reg == 1) { ?>
                      <option value="1" selected>On</option>
					  <option value="0">Off</option>
					  <?php } else if($show_veh_reg == 0) { ?>
					  <option value="1">On</option>
					  <option value="0" selected>Off</option>
					  <?php } else { ?>
					  <option value="1">On</option>
					  <option value="0">Off</option>
					  <option value="" selected>Unknown</option>
					  <?php } ?>
					  </select>				 
<br />
<label>Visitor Signin <small>(Maintenance Mode Used only for when you don't want new visitors signin)</small></label>
                      <select class="form-control" name="set_newvisitors">
					  <?php if($site_active == 1) { ?>
                      <option value="1" selected>On</option>
					  <option value="0">Off</option>
					  <?php } else if($site_active == 0) { ?>
					  <option value="1">On</option>
					  <option value="0" selected>Off</option>
					  <?php } else { ?>
					  <option value="1">On</option>
					  <option value="0">Off</option>
					  <option value="" selected>Unknown</option>
					  <?php } ?>
					  </select>	
<br /><br />
<label>Company Agreement <small>(Front Signin Page Text)</small></label>
<textarea name="set_companyagreement" rows="4" class="form-control"><?php echo $company_agreement; ?></textarea>		  
<br /><br />
<label>Email Body <small>(Sent to Visiting Contact when Visitor Signin) Use {GuestName} or {CompanyName}</small></label>
<textarea name="set_emailbody" rows="4" class="form-control"><?php echo $set_emailbody; ?></textarea>
<br /><br />
<button type="submit" name="update_settings" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Update Site Settings</button>
                  </div></div>
                </form>
          </div>
		  <div class="col-md-5">
		  <div class="box box-primary">
			<div class="box-header">				 
				 <h3 class="box-title">VSS Version Check</h3>
				 </div>
				 <div class="box-body">
						<?php echo version_update_check(); ?>
				  </div>
				  </div>
				  
				  <div class="box box-primary">
			<div class="box-header with-border">				 
				 <h3 class="box-title">Export Data</h3>
				 </div>
				 <div class="box-body">
				 <form action="export_vss_new.php?usr=<?php echo $session_login_usr; ?>&authsession=<?php echo $salt_key; ?>" method="post">
				<button type="submit" name="export_csv" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Visitors (Excel)</button>
				</form><br />
					
				  <form action="export_vss.php?usr=<?php echo $session_login_usr; ?>&authsession=<?php echo $salt_key; ?>" method="post">
					<button type="submit" name="export_csv" class="btn btn-primary"><i class="fa fa-download"></i> Export Visitors (CSV)</button>
				  </form>
				  <br />
				  <form action="export_vss_contacts.php?usr=<?php echo $session_login_usr; ?>&authsession=<?php echo $salt_key; ?>" method="post">
					<button type="submit" name="export_csv_contacts" class="btn btn-primary"><i class="fa fa-download"></i> Export Employee's (CSV)</button>
				  </form>
				  </div>
				  </div>
				 <!-- <div class="box box-danger">
			<div class="box-header with-border">				 
				 <h3 class="box-title">Printer Settings</h3>
				 </div>
				 <div class="box-body">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype='multipart/form-data'>				  
<?php 
echo "<label>Printer Name on Network <small>\\\\\\\SERVERNAME\\\PRINTERNAME</small></label> <br /><input type='text' name='set_printername' class='form-control' value='$printername'><br />";
?>			  
				  
				  
				<label>Auto Print Visitor Badge at Signin</label>
                      <select class="form-control" name="set_printebadge">
					  <?php if($printebadge == 1) { ?>
                      <option value="1" selected>On</option>
					  <option value="0">Off</option>
					  <?php } else if($printebadge == 0) { ?>
					  <option value="1">On</option>
					  <option value="0" selected>Off</option>
					  <?php } else { ?>
					  <option value="1">On</option>
					  <option value="0">Off</option>
					  <option value="" selected>Unknown</option>
					  <?php } ?>
					  </select>				 
<br />  
<button type="submit" name="update_printer_settings" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Update Printer Settings</button>
           </form>
				  </div>
				  </div> -->
				  </div>	  
		  </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>