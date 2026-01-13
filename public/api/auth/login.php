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
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password required']);
    exit;
}

$pdo = DB::get();
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $payload = [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'role' => $user['role'],
        'email' => $user['email']
    ];
    $token = JWT::encode($payload);

    // Sync with PHP Session for Web UI compatibility
    if(session_status() === PHP_SESSION_NONE) session_start();
    session_regenerate_id(true);
    $_SESSION['user'] = $payload;

    echo json_encode(['token' => $token, 'user' => $payload]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
}
