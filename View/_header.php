<?php
require_once __DIR__ . '/../bootstrap.php';
require_login();
$flash = get_flash();
$current = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo ?? APP_NAME) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../templates/css/app.css">
</head>
<body>
<div class="app-shell">
<aside class="sidebar">
    <div class="brand"><i class="bi bi-mortarboard-fill"></i><span>Portal Docente</span></div>
    <nav>
        <a class="<?= $current === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="<?= $current === 'notas.php' || $current === 'nota_form.php' ? 'active' : '' ?>" href="notas.php"><i class="bi bi-journal-check"></i> Notas</a>
        <a class="<?= $current === 'alunos.php' || $current === 'aluno_form.php' ? 'active' : '' ?>" href="alunos.php"><i class="bi bi-people"></i> Alunos</a>
        <a class="<?= $current === 'ucs.php' || $current === 'uc_form.php' ? 'active' : '' ?>" href="ucs.php"><i class="bi bi-book"></i> Unidades Curriculares</a>
        <?php if (($_SESSION['usuario_tipo'] ?? '') === 'admin'): ?>
            <a href="usuario_form.php"><i class="bi bi-person-plus"></i> Novo usuário</a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-footer">
        <div><strong><?= e($_SESSION['usuario_nome'] ?? '') ?></strong></div>
        <small><?= e($_SESSION['usuario_tipo'] ?? '') ?></small>
        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-left"></i> Sair</a>
    </div>
</aside>
<main class="main-content">
<?php if ($flash): ?>
<div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show">
    <?= e($flash['message']) ?><button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
