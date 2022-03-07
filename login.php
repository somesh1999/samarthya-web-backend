<?php
session_start();
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$myObj = new stdClass();
$conn = returnDBCon();
$username = $_POST['userName'];
$password = $_POST['password'];
// $username = "6290544002";
// $password = "Test";
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  $myObj->message = "Failed to connect to Database";
  $myObj->success = false;
  echo json_encode($myObj);
  die();
}
$sql = "SELECT * FROM user WHERE `username` = '$username'";
$result = $conn->query($sql);
if ($result->num_rows < 1) {
    $myObj->message = "User does not exists";
    $myObj->success = false;
    echo json_encode($myObj);
    die();
}

$sql = "SELECT * FROM user WHERE `username` = '$username' AND is_active = '0'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $myObj->message = "You are not an active user";
    $myObj->success = false;
    echo json_encode($myObj);
    die();
}

$sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);
if ($result->num_rows < 1) {
    $myObj->message = "Invalid credentails";
    $myObj->success = false;
    echo json_encode($myObj);
}else{
    while ($row = mysqli_fetch_assoc($result)) 
    {   
        $user_id = $row['user_id'];
        $name = $row['name'];  
        $profile_image = $row['encoded_image'];  
        $fk_user_role = $row['fk_user_role'];  
        $disability = $row['disability'];  
        $district = $row['district'];  
        $school = $row['school'];  
    }
    $fetchuserrolesql = "SELECT userAccess, teacherAddAccess FROM user_role WHERE user_role_id='$fk_user_role'";
    $fetchuserroleresult = $conn->query($fetchuserrolesql);
    while ($fetchuserrolerow = mysqli_fetch_assoc($fetchuserroleresult)) 
    {
        $userAccess = $fetchuserrolerow['userAccess'];  
        $teacherAddAccess = $fetchuserrolerow['teacherAddAccess'];  
    }
    $myObj->user_id = $user_id;
    $myObj->name = $name;
    $myObj->profile_image = $profile_image;
    $myObj->userAccess = $userAccess;
    $myObj->teacherAddAccess = $teacherAddAccess;
    $myObj->fk_user_role = $fk_user_role;
    $myObj->disability = $disability;
    $myObj->district = $district;
    $myObj->school = $school;
    $myObj->message = "Successfully logged in";
    $myObj->success = true;    
    echo json_encode($myObj);
}

?>