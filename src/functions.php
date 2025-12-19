<?php
declare(strict_types=1);

/**
 * --------------------------------------------------
 * Bootstrap Session
 * --------------------------------------------------
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

/**
 * --------------------------------------------------
 * Flash Message Helper
 * --------------------------------------------------
 */
function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    if (!empty($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

/**
 * --------------------------------------------------
 * Output Escaping (XSS Protection)
 * --------------------------------------------------
 */
function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

/**
 * --------------------------------------------------
 * CSRF Protection
 * --------------------------------------------------
 */
function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_check(?string $token): bool
{
    return isset($_SESSION['_csrf'])
        && hash_equals($_SESSION['_csrf'], (string) $token);
}

/**
 * --------------------------------------------------
 * Activity Logger
 * --------------------------------------------------
 */
function log_action(
    int $user_id,
    string $action,
    ?string $meta = null
): void {
    try {
        $pdo = DB::get();
        $stmt = $pdo->prepare(
            "INSERT INTO activity_logs (user_id, action, meta)
             VALUES (:user_id, :action, :meta)"
        );

        $stmt->execute([
            ':user_id' => $user_id,
            ':action'  => $action,
            ':meta'    => $meta,
        ]);
    } catch (Throwable $e) {
        error_log('[LOG_ACTION_ERROR] ' . $e->getMessage());
    }
}

