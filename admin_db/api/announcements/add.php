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

if (
    !$data ||
    !isset($data->title) ||
    !isset($data->body) ||
    !isset($data->source) ||
    !isset($data->featured)
) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit();
}

$date = date("Y-m-d");
$featured = $data->featured ? 1 : 0;

$stmt = $conn->prepare("INSERT INTO announcements (title, body, source, date, featured) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $data->title, $data->body, $data->source, $date, $featured);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>