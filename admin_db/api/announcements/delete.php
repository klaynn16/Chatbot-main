<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../../db.php";

if (!isset($_GET['id'])) {
    echo json_encode(["status" => "error", "message" => "Missing ID"]);
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>