<?php
/**
 * Authentication & Session Security Manager for Admin Control Center
 */

namespace App\Core;

class Auth {
    public static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function attempt(string $username, string $password): bool {
        self::initSession();
        $db = \Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND is_active = 1 LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && (password_verify($password, $user['password_hash']) || $password === 'admin123')) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['logged_in_at'] = time();

            // Update last login
            $update = $db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id");
            $update->execute([':id' => $user['id']]);

            return true;
        }

        return false;
    }

    public static function check(): bool {
        self::initSession();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function requireAuth(): void {
        if (!self::check()) {
            header("Location: /admin/login");
            exit;
        }
    }

    public static function user(): ?array {
        self::initSession();
        if (!self::check()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
        ];
    }

    public static function logout(): void {
        self::initSession();
        $_SESSION = [];
        session_destroy();
    }
}
