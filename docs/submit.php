<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "trtkm24a_8";
$password = "bT6EXxWX";
$database = "wp_trtkm24a_8";

$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

function contains_symbols($data) {
    return !preg_match('/^[a-öA-Ö\s-]+$/', $data);
}

function sanitize_input($data) {
    return trim(strip_tags($data)); 
}

$requiredFields = ['firstname', 'lastname', 'email', 'message'];
$errors = [];

$firstname = isset($_POST['firstname']) ? sanitize_input($_POST['firstname']) : '';
$lastname = isset($_POST['lastname']) ? sanitize_input($_POST['lastname']) : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        $errors[] = ucfirst($field) . " is required";
    }
}

if (contains_symbols($firstname)) {
    $errors[] = "First name cannot contain symbols";
}
if (contains_symbols($lastname)) {
    $errors[] = "Last name cannot contain symbols";
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

if (strlen($firstname) > 25) $errors[] = "First name too long";
if (strlen($lastname) > 50) $errors[] = "Last name too long";
if (strlen($email) > 75) $errors[] = "Email too long";
if (strlen($message) > 500) $errors[] = "Message too long";

if (!empty($errors)) {
    echo json_encode(["status" => "error", "message" => implode(", ", $errors)]);
    exit();
}

$sql = "INSERT INTO HotelX_booking (firstname, lastname, email, message) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    exit();
}

$stmt->bind_param("ssss", $firstname, $lastname, $email, $message);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Thank you for signing the guestbook!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error saving message"]);
}

$stmt->close();
$conn->close();
?>
