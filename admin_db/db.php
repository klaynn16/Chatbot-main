<?php
$conn = new mysqli("localhost", "root", "", "admin_db");
if ($conn->connect_error) {
    die("DB Connection Failed");
}
?>