<?php
//CopyRight 2015 Ignitepros.com
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();
session_start();

if(!(isset($_SESSION['login_usr']) && $_SESSION['login_usr'] != '')) {
header("Location: index.php?action=error_session");
exit();
}
require_once ('../core/sanitize.inc.php');
//Get Data
$check_hash_id = isset($_GET['cid']) ? $_GET['cid'] : "";
$check_usr = isset($_GET['action']) ? $_GET['action'] : "";
$check_decode = isset($_GET['decode']) ? $_GET['decode'] : "";
//Sanitize Data
$check_hash_id = sanitize_sql_string($check_hash_id);
$check_usr = sanitize_sql_string($check_usr);
$check_decode = sanitize_sql_string($check_decode);

if($check_usr == '') {
echo "Error: No Access.";
exit();
}
if($check_decode == '') {
echo "Error: No Access.";
exit();
}
if($check_hash_id == '') {
echo "Error: Wrong ID.";
exit();
}

require_once ('../core/config.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $SiteTitle; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/select2.min.css">	
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<?php
$result_dbview_more = mysqli_query($con_signin,"SELECT * FROM signin_tablet WHERE id = '$check_hash_id'");
while($row = mysqli_fetch_array($result_dbview_more))
{
  $sign_id_more = $row['id'];
  $signin_date_more = $row['signin_date'];
  $signout_date_more = $row['signout_date'];
  $signature_in = $row['signature_in'];
  $signature_out = $row['signature_out'];
  $full_name = $row['full_name'];
  $company = $row['company'];
  $input_ip_more = $row['ip'];
  $profile_in = $row['profile_in'];
  //$profile_out = $row['profile_out'];
  }
	
//Show image if none is in db  
if($profile_in == '') {
$profile_in = $visitor_profile_none;	
}   
?>
<script type="text/javascript">
    function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("printpagebutton");
		var printButton2 = document.getElementById("printpagebutton2");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
		printButton2.style.visibility = 'hidden';
        //Print the page content
        window.print()
        //Set the print button to 'visible' again 
        //[Delete this line if you want it to stay hidden after printing]
        printButton.style.visibility = 'visible';
		printButton2.style.visibility = 'visible';
    }
</script>
</head>
<body>                              
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        </div>
                                       <table width='300px' border='0'>
										<tr><td>
										&nbsp;<strong><?php echo $SiteTitle; ?></strong>
										<br />
										<br />&nbsp;<strong>Visitor ID # <?php echo $sign_id_more ?></strong>
										<br />&nbsp;<strong><?php echo $full_name."<br />&nbsp;".$company ?></strong>
										</td>
										<td align="center">
										<?php echo '<img src="data:image/gif;base64,' . $profile_in . '" width="120px" style="border-radius: 6px;" />'; ?>
										</td>
									   </table>								   
                                        <div class="modal-footer">
                                           <button id="printpagebutton" type="button" onclick="printpage()" class="btn btn-warning btn" /><i class='fa fa-print'></i> Print Visitor Badge</button> <button id="printpagebutton2" type="button" class="btn btn-default" data-dismiss="modal" onClick="window.close();"><i class='fa fa-close'></i> Close</button>
                                        </div>
                                    </div>  
</body>
</html>         