<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/vendor/autoload.php';

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">';
}

function csrf_valid(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf']) &&
        hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
}

function require_login(): void
{
    if (empty($_SESSION['usuario_id'])) {
        header('Location: /portal-docente/index.php');
        exit;
    }
}

function require_docente(): void
{
    require_login();
    if (!in_array($_SESSION['usuario_tipo'] ?? '', ['admin', 'docente'], true)) {
        http_response_code(403);
        exit('Acesso não autorizado.');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}
