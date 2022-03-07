<?php
session_start();
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$myObj = new stdClass();
$conn = returnDBCon();
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
$file_name_temp = "dummy-profile.png";
if(isset($_FILES['image'])){
  $file_name = $_FILES['image']['name'];
  $file_size =$_FILES['image']['size'];
  $file_tmp =$_FILES['image']['tmp_name'];
  $file_type=$_FILES['image']['type'];
  $file_ext=substr( strrchr($file_name, '.'), 1);
  $file_name_temp = "user_".$username.time().".".$file_ext;
  move_uploaded_file($file_tmp,"images/".$file_name_temp);
}
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  $myObj->message = "Failed to connect to Database";
  $myObj->success = false;
  echo json_encode($myObj);
  die();
}
$sql = "INSERT INTO user (name, username, password, email, contact_no, is_active, fk_user_role, disability, district, school, picture_name, encoded_image)
VALUES ('$name', '$username', '$password', '$email', '$contactNo', '$status', '$userrole', '$disability', '$district', '$school', '$file_name_temp', '$encodedImage')";
if ($conn->query($sql) === TRUE) {
    $myObj->message = "User inserted successfully";
    $myObj->success = true;    
    echo json_encode($myObj);
} else {
    $myObj->name = $name;
    $myObj->message = "Failed to create user";
    $myObj->success = false;    
    echo json_encode($myObj);
}

?>