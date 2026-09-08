<?php
declare(strict_types=1);

namespace Controller;

use Model\Usuario;

class AuthController
{
    private Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function login(string $email, string $senha): bool
    {
        $user = $this->usuario->porEmail($email);

        if (!$user || !(bool)$user['ativo'] || !password_verify($senha, $user['senha'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['usuario_id'] = (int)$user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        $_SESSION['usuario_email'] = $user['email'];
        $_SESSION['usuario_tipo'] = $user['tipo'];

        return true;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
