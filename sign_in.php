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
<script type="text/javascript" src="js/signature_in.js"></script>
<script type="text/javascript" src="js/webcam.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="js/jquery-1.8.2.js"></script>
<script type="text/javascript">
$(function() {
setTimeout(function() { $("#testdiv").fadeOut(1800); }, 3100)
$('#btnclick').click(function() {
$('#testdiv').show();
setTimeout(function() { $("#testdiv").fadeOut(1800); }, 3100)
})
})

   function showButton()
        {
            document.getElementById("takesnapshotbutton").style.visibility = "visible";
        }

        function hideButton()
        {
            document.getElementById("takesnapshotbutton").style.visibility = "hidden";
        }

        window.onload = function()
        {
            hideButton();
            setTimeout('showButton()', 4500);
        }
</script>
</head>
<body>
<div id="testDiv">
	<div id="testTitle">
<h1><?php echo $SiteTitle; ?><br /></h1>
	</div>
	<div id="DivForm_sign">	
<form class="box validate" onSubmit="return Form1_Validator(this)" language="JavaScript" name="theForm">
		<strong>Full Name:</strong> <input type="text" id="full_name" name="full_name" class="form-control" style="height:50px;font-size:15pt;" placeholder="Full Name" autocomplete="off" required autofocus /><br />
		<strong>Company Name:</strong> <input type="text" id="company_name" name="company_name" class="form-control" style="height:50px;font-size:15pt;" placeholder="Company Name" required autocomplete="off" /><br />
		<?php if($show_veh_reg == 1) { ?>
		<strong>Vehicle Registration:</strong> <input type="text" id="veh_reg" name="veh_reg" class="form-control" style="height:50px;font-size:15pt;" autocomplete="off" /><br />
		<?php } else {
			echo '<input type="text" id="veh_reg" name="veh_reg" hidden="hidden" />';
		}
		if ($set_visitoremail == 1) { ?>
		<strong>Visiting:</strong>
		<select id="contact_list" class="form-control" style="height:55px;font-size:15pt;">
<?php 
//Grab Login Users		
$result5 = mysqli_query($con_signin,"SELECT * FROM visitor_contacts ORDER BY `con_name` ASC");
$row_cnt = mysqli_num_rows($result5);
if($row_cnt <= 0) {
echo '<option value="">No Contacts to Notify</option>';
} else { ?>
<option value="">Select Name</option>
<option value=""></option>
<?php		
while($row = mysqli_fetch_array($result5))
  {
  $con_id = $row['id'];
  $con_name = $row['con_name'];
  $con_email = $row['con_email'];
  echo "<option value='$con_id'>$con_name</option>";
  } }
		} else {
			echo '<select id="contact_list" hidden="hidden">
			<option value=""></option></select>';
			
		}
?>  
</select><br />
<?php if($show_signature == 1) { ?>
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
			<input type="text" id="get_date" size="35" value="<?php echo $fptime; ?>" hidden />
			<input type="text" id="get_ip"  size="35" value="<?php echo $get_ip; ?>" hidden />
			<input type="text" id="image_profile" value="" hidden />
			</form>
<br />			
<a href="index.php" class="btn btn-danger btn-sm" /><i class='fa fa-reply'></i> Back</a>&nbsp;&nbsp;<?php if($show_signature == 1) { ?><a onclick="signatureClear()" class="btn btn-warning btn-sm" />Clear Signature</a>&nbsp;&nbsp;<?php } ?>
<?php if($take_image == 0) { ?>
<a href="#" id="aftersnaptext2" onclick="signatureSend()" class="btn btn-primary btn-sm" style="visibility: hidden;" onclick="this.disabled=true;forms[0].submit();" /><i class='fa fa-sign-in'></i> Sign In&nbsp;</a>
<img id="testdiv" src="images/loading_cir.gif" width="50px" />
<?php } else { ?>
<a href="#" id="aftersnaptext2" onclick="signatureSend()" class="btn btn-primary btn-sm" style="visibility: visible;" onclick="this.disabled=true;forms[0].submit();" /><i class='fa fa-sign-in'></i> Sign In&nbsp;</a>
<?php } ?>
<img id="saveSignature" alt="" style="visibility: hidden;" />
<br /><br />
</div>
<?php if($take_image == 0) { ?>
<div class="img_screen">&nbsp;&nbsp;&nbsp;&nbsp;
<script language="JavaScript">
		document.write( webcam.get_html(300, 350) );
		webcam.set_api_url( 'write_snapshot.php' );
		webcam.set_hook( 'onComplete', 'my_callback_function' );
		function my_callback_function(response) {
		var resppro = response;
		document.getElementById('image_profile').value = resppro;
		
		//Grab Vars
		var snapbutton = document.getElementById("takesnapshotbutton");
		var aftersnap = document.getElementById("aftersnaptext");
		var aftersnap2 = document.getElementById("aftersnaptext2");
		
        //Set the button visibility to 'hidden' or visible 
        snapbutton.style.visibility = 'hidden';
		aftersnap.style.visibility = 'visible';
		aftersnap2.style.visibility = 'visible';
		};
	</script>
<br />
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a id="takesnapshotbutton" style="visibility: visible;" href="javascript:void(webcam.snap())" class="btn btn-primary btn-sm">Step 1: Take Photo</a>
<br />
<div id="aftersnaptext" style="visibility: hidden;"><h4>&laquo; Step 2: Fill out Form & Click Sign In</h4></div>
</div>
<br /><br />
<?php } ?>
</div>
</body>
</html>