<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

header("Content-Type: application/json");
include "../db.php";

$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    echo json_encode(["status" => "No JSON received"]);
    exit();
}

$id = $data->id;
$password = $data->password;

// check if admin (email)
if (strpos($id, "@") !== false) {

    $sql = "SELECT * FROM admins WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            echo json_encode([
                "status" => "success",
                "user" => [
                    "role" => "admin",
                    "email" => $row['email'],
                    "name" => $row['name']
                ]
            ]);
        } else {
            echo json_encode(["status" => "wrong password"]);
        }
    } else {
        echo json_encode(["status" => "admin not found"]);
    }

} else {
    // student number
    $sql = "SELECT * FROM students WHERE student_number=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            echo json_encode([
                "status" => "success",
                "user" => [
                    "role" => "student",
                    "studentNumber" => $row['student_number'],
                    "name" => $row['name']
                ]
            ]);
        } else {
            echo json_encode(["status" => "wrong password"]);
        }
    } else {
        echo json_encode(["status" => "student not found"]);
    }
}
?>