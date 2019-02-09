<?php
//CopyRight 2017 Ignitepros.com
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();
session_start();

if(!(isset($_SESSION['login_usr']) && $_SESSION['login_usr'] != '')) {
header("Location: index.php?action=error_session");
exit();
}

$export_type = (isset($_GET['export_type']) ? $_GET['export_type'] : "");

if($export_type != '') { 
require_once ('../core/config.php');
require_once ('../core/sanitize.inc.php');

//Grab and clean data
$set_usr = (isset($_GET['usr']) ? $_GET['usr'] : "");
$set_authsession = (isset($_GET['authsession']) ? $_GET['authsession'] : "");
//Clean
$set_usr = stripslashes(htmlspecialchars($set_usr, ENT_QUOTES));
$set_authsession = stripslashes(htmlspecialchars($set_authsession, ENT_QUOTES));
//Sanitize Data
$set_usr = sanitize_sql_string($set_usr);
$set_authsession = sanitize_sql_string($set_authsession);

//Check Auth
if($set_usr == '') {
	exit();
}
//Check salt key
if($set_authsession != $salt_key) {
	exit();
}		

$output = "";
$sql = mysqli_query($con_signin,"SELECT id, signin_date, signout_date, full_name, company FROM signin_tablet WHERE signature_out = '' and signout_date = ''");
$columns_total = mysqli_num_fields($sql);

//Get the field name to add to top of csv
for($i = 0; $i < $columns_total; $i++)
{
	$heading = mysqli_fetch_field_direct($sql, $i)->name;
    $output .= '"' . $heading . '",';
}
$output .="\n";

//Get data from the table
while($row = mysqli_fetch_array($sql))
{
    for($i = 0; $i < $columns_total; $i++)
    {
        $output .='"' . $row["$i"] . '",';
    }
    $output .="\n";
}

//Download the file
$add_rand = rand(0000,9999);
$filename = "emergency_visitor_export-$add_rand.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);

echo $output;

//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Emergency Export Requested', '$set_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);

exit();
} else {
	header("Location: index.php?action=error_session");
	}
?>