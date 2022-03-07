<?php
/*require __DIR__ . '/db.php';
header('Content-Type: application/json');
$myObj = new stdClass();
$conn = returnDBCon();
$results = mysqli_query($conn, "SELECT u.user_id, ROW_NUMBER() OVER (ORDER BY u.user_id) AS userId , u.name, u.contact_no, u.email, ur.role_name, u.username, u.is_active, u.encoded_image FROM user AS u INNER JOIN user_role AS ur ON ur.user_role_id=u.fk_user_role");
$data = array();
while ($row = mysqli_fetch_assoc($results)) {
   $data[] = $row;
}
echo json_encode($data);*/

?>

<?php
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$count = 1;
$myObj = new stdClass();
$conn = returnDBCon();
$results = mysqli_query($conn, "SELECT u.user_id , u.name, u.contact_no, u.email, ur.role_name, u.username, u.is_active, u.encoded_image, u.picture_name, u.password, u.fk_user_role, u.disability, (SELECT d.name FROM disability AS d WHERE d.disability_id = u.disability) AS disability_name, u.district, u.school FROM user AS u INNER JOIN user_role AS ur ON ur.user_role_id=u.fk_user_role");
$data = array();
while ($row = mysqli_fetch_assoc($results)) {
   $row['userId'] = $count++;
   $data[] = $row;
}
echo json_encode($data);

?>