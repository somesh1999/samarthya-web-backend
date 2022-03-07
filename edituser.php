<?php
session_start();
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$myObj = new stdClass();
$conn = returnDBCon();
$pkUserId = $_POST['pkUserId'];
$name = $_POST['name'];
$contactNo = $_POST['contactNo'];
$email = $_POST['email'];
$status = $_POST['status'];
$userrole = $_POST['userrole'];
$disability = $_POST['disability'];
$district = $_POST['district'];
$school = $_POST['school'];
$username = $_POST['username'];
$password = $_POST['password'];
$encodedImage = $_POST['encodedImage'];
$file_name_temp = "";
$imageSqlQuery = "";
if(isset($_FILES['image'])){
  $file_name = $_FILES['image']['name'];
  $file_size =$_FILES['image']['size'];
  $file_tmp =$_FILES['image']['tmp_name'];
  $file_type=$_FILES['image']['type'];
  $file_ext=substr( strrchr($file_name, '.'), 1);
  $file_name_temp = "user_".$username.time().".".$file_ext;
  move_uploaded_file($file_tmp,"images/".$file_name_temp);
  $imageSqlQuery = ", picture_name = '$file_name_temp', encoded_image= '$encodedImage'";
}
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  $myObj->message = "Failed to connect to Database";
  $myObj->success = false;
  echo json_encode($myObj);
  die();
}
$sql = "UPDATE user SET name= '$name', contact_no='$contactNo', email='$email', is_active='$status', fk_user_role='$userrole', disability='$disability', district='$district', school='$school', username='$username', password='$password'".$imageSqlQuery." WHERE user_id='$pkUserId'";
if ($conn->query($sql) === TRUE) {
    $myObj->message = "Record updated successfully";
    $myObj->success = true;    
    echo json_encode($myObj);
} else {
    $myObj->name = $name;
    $myObj->message = "Failed to update record";
    $myObj->success = false;    
    echo json_encode($myObj);
}

?>