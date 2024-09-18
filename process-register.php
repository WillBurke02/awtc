<?php

// Check if the name is provided and sanitize it
$name = isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : '';
if (empty($name)) {
    die("Name is required");
}

// Check if the email is provided and sanitize it
$email = isset($_POST["email"]) ? filter_var($_POST["email"], FILTER_SANITIZE_EMAIL) : '';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

// Check if the password is provided and sanitize it
$password = isset($_POST["pass"]) ? $_POST["pass"] : '';
if (strlen($password) < 8 || !preg_match("/[a-z]/i", $password) || !preg_match("/[0-9]/", $password)) {
    die("Password must be at least 8 characters and contain at least one letter and one number");
}

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Check if the telephone number is provided and sanitize it
$telno = isset($_POST["telno"]) ? htmlspecialchars($_POST["telno"]) : '';

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, email, telno, password_hash)
        VALUES (?, ?, ?, ?)";

$stmt = $mysqli->stmt_init(); //Prepared statement for anti SQL injection

if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("ssss", $name, $email, $telno, $password_hash);

try {
    $stmt->execute();
    header("Location: signup-success.html");
    exit;
} catch (mysqli_sql_exception $e) {
    if ($mysqli->errno === 1062) {
        die("This email is already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}

?>
