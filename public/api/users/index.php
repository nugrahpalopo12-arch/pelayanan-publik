<?php
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../middleware.php';

header('Content-Type: application/json');

$user = auth_guard(); // Validate Token & Get User Info
$method = $_SERVER['REQUEST_METHOD'];
$pdo = DB::get();

// Basic Role Check: Only Admin can list/delete
// Users can see their own profile or update (maybe?)
// For this task, let's assume Admin has full power, User has limited.

if ($method === 'GET') {
    // List Users (Admin only for full list)
    if ($user['role'] !== 'admin') {
        // Return only self if not admin
        // Actually, let's strictly limit to Admin for the "Manajemen Pengguna" feature
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} elseif ($method === 'POST') {
    // Create User (Admin only)
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (empty($input['name']) || empty($input['email']) || empty($input['password'])) {
        http_response_code(400); 
        echo json_encode(['error' => 'Missing fields']); exit;
    }

    // Check email
    $chk = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $chk->execute([$input['email']]);
    if ($chk->fetch()) {
        http_response_code(409); 
        echo json_encode(['error' => 'Email exists']); exit;
    }

    $hash = password_hash($input['password'], PASSWORD_DEFAULT);
    $role = $input['role'] ?? 'user';
    
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$input['name'], $input['email'], $hash, $role])) {
        echo json_encode(['status' => 'success', 'id' => $pdo->lastInsertId()]);
    } else {
        http_response_code(500); echo json_encode(['error' => 'DB Error']);
    }

} elseif ($method === 'PUT') {
    // Update User
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (empty($input['id'])) {
        http_response_code(400); echo json_encode(['error' => 'ID required']); exit;
    }

    // Prepare update fields
    $fields = [];
    $params = [];

    if (!empty($input['name'])) { $fields[] = 'name=?'; $params[] = $input['name']; }
    if (!empty($input['email'])) { $fields[] = 'email=?'; $params[] = $input['email']; } // Should check unique if changed
    if (!empty($input['role'])) { $fields[] = 'role=?'; $params[] = $input['role']; }
    if (!empty($input['password'])) { 
        $fields[] = 'password=?'; 
        $params[] = password_hash($input['password'], PASSWORD_DEFAULT); 
    }

    if (empty($fields)) {
        echo json_encode(['status' => 'no change']); exit;
    }

    $params[] = $input['id'];
    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        echo json_encode(['status' => 'updated']);
    } else {
        http_response_code(500); echo json_encode(['error' => 'Update failed']);
    }

} elseif ($method === 'DELETE') {
    // Delete User
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400); echo json_encode(['error' => 'ID required']); exit;
    }
    
    if ($id == $user['id']) {
        http_response_code(400); echo json_encode(['error' => 'Cannot delete self']); exit;
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$id]);
    echo json_encode(['status' => 'deleted']);

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
