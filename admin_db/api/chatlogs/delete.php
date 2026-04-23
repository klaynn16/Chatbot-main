<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../../db.php";

$stmt = $conn->prepare("DELETE FROM chat_logs");

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>