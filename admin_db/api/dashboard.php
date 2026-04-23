<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../db.php";

$response = [
    "totalChats" => 0,
    "kbEntries" => 0,
    "announcements" => 0,
    "upcomingEvents" => 0,
    "weeklyUsage" => [
        ["day" => "Mon", "count" => 0],
        ["day" => "Tue", "count" => 0],
        ["day" => "Wed", "count" => 0],
        ["day" => "Thu", "count" => 0],
        ["day" => "Fri", "count" => 0],
        ["day" => "Sat", "count" => 0],
        ["day" => "Sun", "count" => 0]
    ]
];

$chatResult = $conn->query("SELECT COUNT(*) AS total FROM chat_logs");
if ($chatResult) {
    $response["totalChats"] = (int)$chatResult->fetch_assoc()["total"];
}

$kbResult = $conn->query("SELECT COUNT(*) AS total FROM knowledge_base");
if ($kbResult) {
    $response["kbEntries"] = (int)$kbResult->fetch_assoc()["total"];
}

$annResult = $conn->query("SELECT COUNT(*) AS total FROM announcements");
if ($annResult) {
    $response["announcements"] = (int)$annResult->fetch_assoc()["total"];
}

$eventResult = $conn->query("SELECT COUNT(*) AS total FROM events WHERE date >= CURDATE()");
if ($eventResult) {
    $response["upcomingEvents"] = (int)$eventResult->fetch_assoc()["total"];
}

$usageMap = [
    "Mon" => 0,
    "Tue" => 0,
    "Wed" => 0,
    "Thu" => 0,
    "Fri" => 0,
    "Sat" => 0,
    "Sun" => 0
];

$weeklyQuery = "
    SELECT DAYOFWEEK(timestamp) AS day_num, COUNT(*) AS total
    FROM chat_logs
    WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DAYOFWEEK(timestamp)
";

$weeklyResult = $conn->query($weeklyQuery);

if ($weeklyResult) {
    while ($row = $weeklyResult->fetch_assoc()) {
        $dayNum = (int)$row["day_num"];
        $count = (int)$row["total"];

        $dayName = match ($dayNum) {
            1 => "Sun",
            2 => "Mon",
            3 => "Tue",
            4 => "Wed",
            5 => "Thu",
            6 => "Fri",
            7 => "Sat"
        };

        $usageMap[$dayName] = $count;
    }
}

$response["weeklyUsage"] = [
    ["day" => "Mon", "count" => $usageMap["Mon"]],
    ["day" => "Tue", "count" => $usageMap["Tue"]],
    ["day" => "Wed", "count" => $usageMap["Wed"]],
    ["day" => "Thu", "count" => $usageMap["Thu"]],
    ["day" => "Fri", "count" => $usageMap["Fri"]],
    ["day" => "Sat", "count" => $usageMap["Sat"]],
    ["day" => "Sun", "count" => $usageMap["Sun"]]
];

echo json_encode($response);
?> 