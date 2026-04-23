<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include "../../db.php";

$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->question) || !isset($data->answer)) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO knowledge_base (question, answer) VALUES (?, ?)");
$stmt->bind_param("ss", $data->question, $data->answer);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>