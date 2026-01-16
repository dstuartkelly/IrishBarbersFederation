<?php
/**
 * Form Submission Handler
 * Barbers Federation of Ireland - Registration Form
 */

// Start session for CSRF protection
session_start();

// Include database configuration
require_once 'db-config.php';

// Set JSON header for response
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// CSRF Protection - verify token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit;
}

// Sanitize and validate input
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$experience = isset($_POST['experience']) ? intval($_POST['experience']) : null;

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
} elseif (strlen($name) > 255) {
    $errors[] = 'Name is too long';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
} elseif (strlen($email) > 255) {
    $errors[] = 'Email is too long';
}

if (!empty($phone) && strlen($phone) > 50) {
    $errors[] = 'Phone number is too long';
}

if ($experience !== null && ($experience < 0 || $experience > 100)) {
    $errors[] = 'Experience must be between 0 and 100 years';
}

// Return validation errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Check for duplicate email (optional - uncomment if you want to prevent duplicates)
/*
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id FROM registrations WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'This email is already registered']);
        exit;
    }
} catch (PDOException $e) {
    error_log("Duplicate check error: " . $e->getMessage());
}
*/

// Insert into database
try {
    $pdo = getDBConnection();
    
    $sql = "INSERT INTO registrations (name, email, phone, experience, ip_address, user_agent) 
            VALUES (:name, :email, :phone, :experience, :ip_address, :user_agent)";
    
    $stmt = $pdo->prepare($sql);
    
    $result = $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':experience' => $experience,
        ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ]);
    
    if ($result) {
        // Success response
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful! We will contact you soon.'
        ]);
    } else {
        throw new Exception('Failed to insert record');
    }
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
}
?>