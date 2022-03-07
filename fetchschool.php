
<?php
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$count = 1;
$myObj = new stdClass();
$conn = returnDBCon();
$disability_id = $_GET['disability_id'];
$district = $_GET['district'];
$results = mysqli_query($conn, "SELECT school_name FROM district WHERE fk_disability_id = '$disability_id' AND district_name = '$district'");
$data = array();
while ($row = mysqli_fetch_assoc($results)) {
   $data[] = $row;
}
echo json_encode($data);

?>