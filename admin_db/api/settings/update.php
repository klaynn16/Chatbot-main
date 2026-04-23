<?php
header("Content-Type: application/json");
include "../../db.php";

$data = json_decode(file_get_contents("php://input"));

$stmt = $conn->prepare("UPDATE settings SET bot_name=?, welcome_message=? WHERE id=1");
$stmt->bind_param("ss", $data->bot_name, $data->welcome_message);

echo json_encode(["status"=>$stmt->execute() ? "success":"error"]);
?>