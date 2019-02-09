<?php
//Required Files
require_once ('core/config.php');
require_once ('core/sanitize.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $SiteTitle; ?></title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/main.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script src="js/signature_out.js"></script>
<script type="text/javascript" src="js/webcam.js"></script>
</head>
<body>
<div id="testDiv">
	<div id="testTitle">
<h1>Visitor Signin System</h1>
	</div>
	<div id="DivForm_sign" >
<form class="box validate" onSubmit="return Form1_Validator(this)" language="JavaScript" name="theForm">	
		<select id="sign_id" class="form-control" style="height:65px;font-size:30pt;">
<?php 
//Grab Login Users		
$result5 = mysqli_query($con_signin,"SELECT * FROM signin_tablet WHERE signout_date = '' ORDER BY `signin_tablet`.`id` DESC");
$row_cnt = mysqli_num_rows($result5);
if($row_cnt <= 0) {
echo '<option value="">No Visitors to Check Out</option>';
} else { ?>
<option value="">Select Name</option>
<option value=""></option>
<?php		
while($row = mysqli_fetch_array($result5))
  {
  $sign_id = $row['id'];
  $full_name = $row['full_name'];
  $company = $row['company'];
  echo "<option value='$sign_id'>$full_name, $company</option>";
  } }
?>  
</select><br />
<?php if($row_cnt <= 0) { 
echo '<br /><br /><br /><br />';
echo "<a href='index.php' class='btn btn-danger btn-sm' /><i class='fa fa-reply'></i> Back</a> <br /><br /><br /><br /><br /><br />";
} else { 
if($show_signature == 1) { ?>
		<strong>Signature:</strong>
	 <div id="canvas">
				Canvas is not supported.
			</div>
			<script>
				signatureCapture();
			</script>
<?php } else {
	echo '<canvas id="newSignature" hidden="hidden"></canvas><br />';
}
?>		
			<input type="text" id="image_profile" value="" hidden />
<br /><br /><br />
<a href="index.php" class="btn btn-danger btn-sm" /><i class='fa fa-reply'></i> Back</a>&nbsp;&nbsp;<a onclick="signatureClear()" class="btn btn-warning btn-sm" />Clear Signature</a>&nbsp;&nbsp;<a href="#" id="aftersnaptext2" onclick="signatureSend()" class="btn btn-primary btn-sm" style="visibility: visible;" /><i class='fa fa-sign-out'></i> Sign Out&nbsp;</a>
<img id="saveSignature" alt="" style="visibility: hidden;"/>
</form>	
<br /><br />
</div>
<div class="img_screen">
</div>
<?php } ?>
</p>
<br /><br />
</div>
</body>
</html>