<?php

$data = [
    "username" => "admin",
    "password" => "admin123"
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json",
        "method"  => "POST",
        "content" => json_encode($data),
    ]
];

$context  = stream_context_create($options);

$result = file_get_contents("http://localhost/admin_db/api/login.php", false, $context);

echo $result;

?>