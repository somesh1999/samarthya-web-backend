
<?php
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$count = 1;
$myObj = new stdClass();
$conn = returnDBCon();
$user_id = $_GET['user_id'];
$results = mysqli_query($conn, "SELECT disability, (SELECT d.name FROM disability AS d WHERE d.disability_id = u.disability) AS disability_name, district, school FROM user AS u WHERE `user_id` = '$user_id'");
$data = array();
while ($row = mysqli_fetch_assoc($results)) {
   $data[] = $row;
}
echo json_encode($data);

?>