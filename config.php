<?php
$host = 'localhost';
$db = 'sms';  // change to your actual DB name
$user = 'root';
$pass = '';  // default XAMPP MySQL password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>