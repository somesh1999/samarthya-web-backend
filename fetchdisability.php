
<?php
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$count = 1;
$myObj = new stdClass();
$conn = returnDBCon();
$results = mysqli_query($conn, "SELECT disability_id , name FROM disability");
$data = array();
while ($row = mysqli_fetch_assoc($results)) {
   $data[] = $row;
}
echo json_encode($data);

?>