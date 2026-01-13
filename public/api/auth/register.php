<?php
require_once __DIR__ . '/../../../src/db.php';
require_once __DIR__ . '/../../../src/jwt.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if (!$name || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Name, email, and password required']);
    exit;
}

$pdo = DB::get();
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['error' => 'Email already registered']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
if ($stmt->execute([$name, $email, $hash])) {
    $id = $pdo->lastInsertId();
    $payload = [
        'id' => (int)$id,
        'name' => $name,
        'role' => 'user',
        'email' => $email
    ];
    $token = JWT::encode($payload);
    
    // Sync with PHP Session for Web UI compatibility
    if(session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['user'] = $payload;

    echo json_encode(['token' => $token, 'user' => $payload]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to register']);
}
