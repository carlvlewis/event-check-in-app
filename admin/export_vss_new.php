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

if (isset($_POST['export_csv'])) { 
//Required Files
require_once ('../core/config.php');
require_once ('../core/sanitize.inc.php');
require_once ('Classes/PHPExcel.php');

//Grab and clean data
$set_usr = (isset($_GET['usr']) ? $_GET['usr'] : "");
$set_authsession = (isset($_GET['authsession']) ? $_GET['authsession'] : "");

//Sanitize Data
$set_usr = sanitize_sql_string($set_usr);
$set_authsession = sanitize_sql_string($set_authsession);

//Check Auth
if($set_usr == '') {
	//exit();
}
//Check salt key
if($set_authsession != $salt_key) {
	//exit();
}

//Download the file
$add_rand = rand(0000,9999);
$filename = "visitors_export-$add_rand.xlsx";

$excel = new PHPExcel();
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$filename.'');
header('Cache-Control: max-age=0');

//Set Start from Row
$rowCount = 2;
//Grab Data from MySQL
$sql = mysqli_query($con_signin,"SELECT * FROM $table ORDER BY `$table`.`id` DESC");
 while($row = mysqli_fetch_array($sql)){

//Set Headers
$excel->setActiveSheetIndex()->SetCellValue('A1', 'ID');
$excel->setActiveSheetIndex()->SetCellValue('B1', 'Sign in Date');
$excel->setActiveSheetIndex()->SetCellValue('C1', 'Sign out Date');
$excel->setActiveSheetIndex()->SetCellValue('D1', 'Full Name');
$excel->setActiveSheetIndex()->SetCellValue('E1', 'Company');
$excel->setActiveSheetIndex()->SetCellValue('F1', 'Veh Reg');
$excel->setActiveSheetIndex()->SetCellValue('G1', 'Contact List');
$excel->setActiveSheetIndex()->SetCellValue('H1', 'Profile Image');
$excel->setActiveSheetIndex()->SetCellValue('I1', 'Signature');
 
//Clean Data 
$id_vi = $row['id'];
$signin_date = $row['signin_date'];
$signout_date = $row['signout_date'];
$full_name = $row['full_name'];
$company = $row['company'];
$veh_reg = $row['veh_reg'];
$contact_list = $row['contact_list'];
$signature_in = $row['signature_in'];
$profile_in = $row['profile_in'];

//Clean Data
$id_vi = sanitize_int($id_vi);
$signin_date = mysqli_real_escape_string($con_signin, $signin_date);
$signout_date = mysqli_real_escape_string($con_signin, $signout_date);
$full_name = mysqli_real_escape_string($con_signin, $full_name);
$company = mysqli_real_escape_string($con_signin, $company);
$veh_reg = mysqli_real_escape_string($con_signin, $veh_reg);
$contact_list = mysqli_real_escape_string($con_signin, $contact_list);
$signature_in = mysqli_real_escape_string($con_signin, $signature_in);
$profile_in = mysqli_real_escape_string($con_signin, $profile_in);



//Set Row Data
$excel->setActiveSheetIndex()->SetCellValue('A'.$rowCount, $id_vi);
$excel->setActiveSheetIndex()->SetCellValue('B'.$rowCount, $signin_date);
$excel->setActiveSheetIndex()->SetCellValue('C'.$rowCount, $signout_date);
$excel->setActiveSheetIndex()->SetCellValue('D'.$rowCount, $full_name);
$excel->setActiveSheetIndex()->SetCellValue('E'.$rowCount, $company);
$excel->setActiveSheetIndex()->SetCellValue('F'.$rowCount, $veh_reg);
$excel->setActiveSheetIndex()->SetCellValue('G'.$rowCount, $contact_list);


//Signature Image Create
$visitor_signin = $signature_in;
$imageName = 'signature_'.$id_vi.'.png';

$imageData = base64_decode($signature_in);
file_put_contents('upload_MD5IMG/export_img/'.$imageName, $imageData);

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Signature');
$objDrawing->setDescription('Signature');
$Signature = 'upload_MD5IMG/export_img/'.$imageName;
$objDrawing->setPath($Signature);  //setOffsetY has no effect
$objDrawing->setCoordinates('H'.$rowCount);
$objDrawing->setResizeProportional(false);
$objDrawing->setOffsetX(3);
$objDrawing->setOffsetY(3);
$objDrawing->setWidth(140);
$objDrawing->setHeight(110);


//Profile Image Create
$visitor_profile = $profile_in;
if($visitor_profile != '') {
	$imageData2 = base64_decode($visitor_profile);
} else {
	$imageData2 = base64_decode($visitor_profile_none);
}
	
$imageNameProfile = 'profile_'.$id_vi.'.jpg';
$source2 = imagecreatefromstring($imageData2);
$imageSave2 = imagejpeg($source2,'upload_MD5IMG/export_img/'.$imageNameProfile,90);

$objDrawing2 = new PHPExcel_Worksheet_Drawing();
$objDrawing2->setName('Profile');
$objDrawing2->setDescription('Profile');
$Profile = 'upload_MD5IMG/export_img/'.$imageNameProfile;
$objDrawing2->setPath($Profile);  //setOffsetY has no effect
$objDrawing2->setCoordinates('I'.$rowCount);
$objDrawing2->setResizeProportional(false);
$objDrawing2->setOffsetX(3);
$objDrawing2->setOffsetY(3);
$objDrawing2->setWidth(140);
$objDrawing2->setHeight(140);


//Write Excel File
$objDrawing->setWorksheet($excel->getActiveSheet());
$objDrawing2->setWorksheet($excel->getActiveSheet());
//Set Column Width
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(7);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(23);

//Set Row Height
$excel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(110);

//Set row count by 1
$rowCount++;	
}
 
//Final Write to Excel
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

//This line will force the file to download
$writer->save('php://output');

//Report to Logs
$sql_log_report = "insert into logs values(LAST_INSERT_ID(), 'Visitor Excel Export Requested', '$set_usr', '$fptime', '$log_ip', '0')";
mysqli_query($con_signin, $sql_log_report);

//Delete Created Images
$files = glob('upload_MD5IMG/export_img/*');
foreach($files as $file){ 
  if(is_file($file))
    unlink($file);
}

//exit();
} else {
	header("Location: index.php?action=error_session");
}
?>