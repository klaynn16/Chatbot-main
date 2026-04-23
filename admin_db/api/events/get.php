<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../../db.php";

$result = $conn->query("SELECT * FROM events ORDER BY id DESC");

if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
    exit();
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>