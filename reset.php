<?php
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(0);
header('Content-Type: application/json');

require 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (!$email) {
        echo json_encode(array("success" => false, "message" => "Invalid email format."));
        exit;
    }

    if ($password !== $confirmPassword) {
        echo json_encode(array("success" => false, "message" => "Passwords do not match."));
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "UPDATE user SET user_password = ? WHERE user_email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Handle prepare statement error
        http_response_code(500);
        echo json_encode(array("success" => false, "message" => "Prepare statement error: " . $conn->error));
        exit;
    }

    $stmt->bind_param("ss", $hashedPassword, $email);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(array("success" => false, "message" => "Error executing the query: " . $stmt->error));
        exit;
    }
    // $stmt->execute();

    if ($stmt->affected_rows === 1) {
        echo json_encode(array("success" => true, "message" => "Password reset successful."));
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to reset password. User not found."));
    }

    $stmt->close();
    exit;
}

echo json_encode(array("success" => false, "message" => "Invalid request method."));
