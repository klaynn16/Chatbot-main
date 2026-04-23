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
    !isset($data->id) ||
    !isset($data->title) ||
    !isset($data->description) ||
    !isset($data->date) ||
    !isset($data->time) ||
    !isset($data->location) ||
    !isset($data->featured)
) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit();
}

$featured = $data->featured ? 1 : 0;

$stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, date = ?, time = ?, location = ?, featured = ? WHERE id = ?");
$stmt->bind_param("sssssii", $data->title, $data->description, $data->date, $data->time, $data->location, $featured, $data->id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>