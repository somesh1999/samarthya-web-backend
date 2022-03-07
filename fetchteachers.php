<?php
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$count = 1;
$myObj = new stdClass();
$conn = returnDBCon();
$role_id = $_GET['role_id'];
$user_id= $_GET['user_id'];
$district= $_GET['district'];
$disability= $_GET['disability'];
$school= $_GET['school'];
if($role_id == 0){
    $results = mysqli_query($conn, "SELECT t.id, t.district, (SELECT d.name FROM disability AS d WHERE d.disability_id = t.disability) AS disability, t.school, t.name, t.contactno, t.email, t.gender, t.work_exp, t.picture_name, t.class, t.disability AS disability_id FROM teacher AS t WHERE t.disability='$disability' AND t.district='$district' AND t.school='$school'");
}else{
    $results = mysqli_query($conn, "SELECT t.id, t.district, (SELECT d.name FROM disability AS d WHERE d.disability_id = t.disability) AS disability, t.school, t.name, t.contactno, t.email, t.gender, t.work_exp, t.picture_name, t.class, t.disability AS disability_id FROM teacher AS t");
}
$data = array();
while ($row = mysqli_fetch_assoc($results)) {
   $row['userId'] = $count++;
   $data[] = $row;
}
echo json_encode($data);

?>