<?php
require_once __DIR__ . '/bootstrap.php';

use Controller\AuthController;

if (!empty($_SESSION['usuario_id'])) {
    header('Location: View/dashboard.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $erro = 'Sessão expirada. Atualize a página e tente novamente.';
    } else {
        $email = trim((string)($_POST['email'] ?? ''));
        $senha = (string)($_POST['senha'] ?? '');

        if ((new AuthController())->login($email, $senha)) {
            header('Location: View/dashboard.php');
            exit;
        }
        $erro = 'E-mail ou senha inválidos.';
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Portal Docente | Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="templates/css/app.css">
</head>
<body class="login-page">
<div class="login-card">
    <div class="brand-mark"><i class="bi bi-mortarboard-fill"></i></div>
    <h1>Portal Docente</h1>
    <p class="text-muted mb-4">Gerenciamento de notas e unidades curriculares</p>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= e($erro) ?></div>
    <?php endif; ?>

    <form method="post">
        <?= csrf_field() ?>
        <label class="form-label">E-mail</label>
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="docente@escola.com" required>
        </div>

        <label class="form-label">Senha</label>
        <div class="input-group mb-4">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="senha" class="form-control" placeholder="Sua senha" required>
        </div>

        <button class="btn btn-primary w-100 py-2" type="submit">
            <i class="bi bi-box-arrow-in-right"></i> Entrar
        </button>
    </form>
</div>
</body>
</html>
