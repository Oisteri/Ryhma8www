<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "trtkm24a_8";
$password = "bT6EXxWX";
$database = "wp_trtkm24a_8";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

$sql = "SELECT firstname, lastname, message, created_at FROM HotelX_booking ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database query failed"]);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();

$messages = [];
$forbidden_words = ["vittu", "saatana", "homo", "perkele", "paska", "nigger", "neekeri", "kyrpä", "penis", "huora"];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row["firstname"] = htmlspecialchars(strip_tags($row["firstname"]), ENT_QUOTES, 'UTF-8');
        $row["lastname"] = htmlspecialchars(strip_tags($row["lastname"]), ENT_QUOTES, 'UTF-8');
        $row["message"] = htmlspecialchars(strip_tags($row["message"]), ENT_QUOTES, 'UTF-8');

        foreach ($forbidden_words as $word) {
            $row["message"] = str_ireplace($word, "***", $row["message"]);
        }

        $messages[] = $row;
    }
}

echo json_encode(["status" => "success", "messages" => $messages], JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
?>