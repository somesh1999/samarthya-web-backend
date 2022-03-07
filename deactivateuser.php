<?php
session_start();
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$myObj = new stdClass();
$conn = returnDBCon();
$user_id = $_POST['user_id'];
$is_active = $_POST['is_active'];
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  $myObj->message = "Failed to connect to Database";
  $myObj->success = false;
  echo json_encode($myObj);
  die();
}
$sql = "UPDATE user SET is_active='$is_active' WHERE user_id='$user_id'";
if ($conn->query($sql) === TRUE) {
    $myObj->message = "User status updated";
    $myObj->success = false;
    echo json_encode($myObj);
    die();
}



?>