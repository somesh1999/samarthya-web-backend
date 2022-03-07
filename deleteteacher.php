<?php
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$myObj = new stdClass();
$conn = returnDBCon();
$id = $_POST['id'];
$sql =  "DELETE FROM teacher WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    $myObj->message = "Teacher deleted successfully";
    $myObj->success = true;    
    echo json_encode($myObj);
} else {
    $myObj->message = "Failed to delete teacher";
    $myObj->success = false;    
    echo json_encode($myObj);
}

?>