<?php
/**
 * Form Submission Handler
 * Irish Barbers Federation - Registration Form
 */

// Maximum registrations stored per IP address in the time window
const RATE_LIMIT_MAX = 10;
const RATE_LIMIT_WINDOW_MINUTES = 60;

const SUCCESS_MESSAGE = 'Registration successful! We will contact you soon.';

// Start session for CSRF protection
session_set_cookie_params([
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// Every response is JSON and must not be cached
header('Content-Type: application/json');
header('Cache-Control: no-store');

/**
 * Send a JSON response and stop.
 */
function respond(int $status, bool $success, string $message): void
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

/**
 * Read a POST value as a trimmed string ('' if missing or not a plain string).
 */
function post_string(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

// Include database configuration
// Prefer the copy above the web root (production); fall back to the repo root (local development)
$configPath = file_exists(__DIR__ . '/../db-config.php')
    ? __DIR__ . '/../db-config.php'
    : __DIR__ . '/db-config.php';

if (!file_exists($configPath)) {
    error_log('db-config.php not found');
    respond(500, false, 'Registration is temporarily unavailable. Please try again later.');
}
require_once $configPath;

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, false, 'Method not allowed');
}

// CSRF Protection - verify token
if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token'])
    || !is_string($_POST['csrf_token'])
    || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    respond(403, false, 'Invalid security token');
}

// Honeypot: real visitors never see or fill this field. Pretend it worked so bots don't adapt.
if (!empty($_POST['website'])) {
    respond(200, true, SUCCESS_MESSAGE);
}

// Sanitize and validate input
$name = post_string('name');
$email = strtolower(post_string('email'));
$phone = post_string('phone');
$experienceRaw = post_string('experience');

// Consent: the register box is required, the updates box is optional
$consentRegister = post_string('consent_register') === '1';
$consentMarketing = post_string('consent_marketing') === '1';

$errors = [];
$controlChars = '/[\x00-\x1F\x7F]/';

if ($name === '') {
    $errors[] = 'Name is required';
} elseif (strlen($name) > 255) {
    $errors[] = 'Name is too long';
} elseif (preg_match($controlChars, $name)) {
    $errors[] = 'Name contains invalid characters';
}

if ($email === '') {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
} elseif (strlen($email) > 255) {
    $errors[] = 'Email is too long';
}

if ($phone !== '') {
    if (strlen($phone) > 50) {
        $errors[] = 'Phone number is too long';
    } elseif (!preg_match('/^[0-9+().\- ]{6,50}$/', $phone)) {
        $errors[] = 'Invalid phone number';
    }
}

if (!$consentRegister) {
    $errors[] = 'Please tick the box to agree to us storing your details';
}

// An empty experience field is stored as NULL, not 0
$experience = null;
if ($experienceRaw !== '') {
    if (!ctype_digit($experienceRaw) || strlen($experienceRaw) > 3 || (int) $experienceRaw > 100) {
        $errors[] = 'Experience must be a whole number between 0 and 100 years';
    } else {
        $experience = (int) $experienceRaw;
    }
}

// Return validation errors
if (!empty($errors)) {
    respond(400, false, implode(', ', $errors));
}

// Keep the stored user agent printable ASCII and within the column length
$userAgent = preg_replace('/[^\x20-\x7E]/', '', $_SERVER['HTTP_USER_AGENT'] ?? '');
$userAgent = $userAgent === '' ? null : substr($userAgent, 0, 255);
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;

try {
    $pdo = getDBConnection();

    // Per-IP rate limit, based on registrations already stored. If the check itself fails, let the
    // registration through rather than blocking everyone.
    if ($ipAddress !== null) {
        try {
            $stmt = $pdo->prepare(
                'SELECT COUNT(*) FROM registrations
                 WHERE ip_address = ? AND registration_date > (NOW() - INTERVAL ' . (int) RATE_LIMIT_WINDOW_MINUTES . ' MINUTE)'
            );
            $stmt->execute([$ipAddress]);
            if ((int) $stmt->fetchColumn() >= RATE_LIMIT_MAX) {
                header('Retry-After: ' . (RATE_LIMIT_WINDOW_MINUTES * 60));
                respond(429, false, 'Too many registrations from your connection. Please try again later.');
            }
        } catch (PDOException $e) {
            error_log('Rate limit check error: ' . $e->getMessage());
        }
    }

    // Insert into database. The UNIQUE index on registrations.email rejects duplicates atomically (error 1062).
    $stmt = $pdo->prepare(
        'INSERT INTO registrations
            (name, email, phone, experience, ip_address, user_agent, consent_register, consent_marketing, consent_at)
         VALUES
            (:name, :email, :phone, :experience, :ip_address, :user_agent, :consent_register, :consent_marketing, NOW())'
    );
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':experience' => $experience,
        ':ip_address' => $ipAddress,
        ':user_agent' => $userAgent,
        ':consent_register' => 1,
        ':consent_marketing' => $consentMarketing ? 1 : 0,
    ]);

    respond(200, true, SUCCESS_MESSAGE);
} catch (PDOException $e) {
    // Duplicate email: respond as if it succeeded so the response doesn't reveal who is registered.
    if (($e->errorInfo[1] ?? null) === 1062) {
        respond(200, true, SUCCESS_MESSAGE);
    }

    error_log('Database error: ' . $e->getMessage());
    respond(500, false, 'Database error occurred. Please try again later.');
}
