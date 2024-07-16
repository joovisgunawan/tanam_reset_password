<?php
$servername = "tanam.software";
$username = "tanam";
$password = "t4nAm_mariadb";
$dbname = "tanam";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
