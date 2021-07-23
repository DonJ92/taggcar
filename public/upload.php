<?php
header('Access-Control-Allow-Origin: *' ) ;
$target_path = "assets/img/avatar/";

$target_path = $target_path . basename( $_FILES['file']['tmp_name']).'.jpg';

if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
 echo basename($_FILES['file']['tmp_name']).'.jpg';
} else {
echo $target_path;
 echo "There was an error uploading the file, please try again!";
}
?>