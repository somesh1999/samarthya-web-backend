<?php
session_start();
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$myObj = new stdClass();
$conn = returnDBCon();
$pkTeacherId = $_POST['pkTeacherId'];
$name = $_POST['name'];
$contactNo = $_POST['contactNo'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$disability = $_POST['disability'];
$district = $_POST['district'];
$school = $_POST['school'];
$class = $_POST['class'];
$workingExp = $_POST['workingExp'];
$encodedImage = $_POST['encodedImage'];
$fk_user_id = $_POST['fk_user_id'];
$file_name_temp = "";
$imageSqlQuery = "";
if(isset($_FILES['image'])){
  $file_name = $_FILES['image']['name'];
  $file_size =$_FILES['image']['size'];
  $file_tmp =$_FILES['image']['tmp_name'];
  $file_type=$_FILES['image']['type'];
  $file_ext=substr( strrchr($file_name, '.'), 1);
  $file_name_temp = "teacher_".$contactNo.time().".".$file_ext;
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
$sql = "UPDATE teacher SET name= '$name', gender='$gender', contactno='$contactNo', email='$email', disability='$disability', district='$district', class='$class', work_exp='$workingExp', school='$school'".$imageSqlQuery." WHERE id='$pkTeacherId'";
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