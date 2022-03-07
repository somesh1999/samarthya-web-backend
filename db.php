<?php
function returnDBCon(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "samarthya";
    $conn = new mysqli($servername, $username, $password, $dbname);
    return $conn;
}

?>