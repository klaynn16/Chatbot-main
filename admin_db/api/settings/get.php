<?php
header("Content-Type: application/json");
include "../../db.php";

$res = $conn->query("SELECT * FROM settings WHERE id=1");
echo json_encode($res->fetch_assoc());
?>