<?php
//CopyRight Ignitepros.com 2017

//Set date to image name
$name = date('YmdHis');
$newname = "captured/".$name.".jpg";
//Write image to folder then send back in encode
$file = file_put_contents( $newname, file_get_contents('php://input') );
if (!$file) {
	print "ERROR: Failed to write data to $newname, check permissions\n";
	exit();
}	

//encode photo data
$data = file_get_contents($newname);
$base64 = base64_encode($data);

//send data back
print "$base64\n";

//now delete image
unlink($newname);
?>
