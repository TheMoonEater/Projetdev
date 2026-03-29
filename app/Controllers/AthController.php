<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Db;
use App\Core\Csrf;

class AuthController
{
    public function showLogin(): void
    {
        View::render('auth/login', [
            'title' => 'Connexion',
            'metaDescription' => 'Connexion à la plateforme',
        ]);
    }

    public function login(): void
    {
        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            echo "CSRF invalide";
            return;
        }

        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $stmt = Db::pdo()->prepare("SELECT id, email, password_hash, role FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            View::render('auth/login', ['error' => 'Identifiants invalides', 'title' => 'Connexion']);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        header('Location: /');
        exit;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();

        header('Location: /login');
        exit;
    }
}
