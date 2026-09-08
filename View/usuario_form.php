<?php
require_once __DIR__ . '/../bootstrap.php';
require_login();
if (($_SESSION['usuario_tipo'] ?? '') !== 'admin') { http_response_code(403); exit('Acesso não autorizado.'); }

use Model\Usuario;

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) $erro = 'Token de segurança inválido.';
    else {
        $nome = trim((string)$_POST['nome']);
        $email = trim((string)$_POST['email']);
        $senha = (string)$_POST['senha'];
        $tipo = in_array($_POST['tipo'] ?? '', ['docente','admin'], true) ? $_POST['tipo'] : 'docente';
        try {
            $u = new Usuario();
            if ($u->porEmail($email)) throw new RuntimeException('E-mail já cadastrado.');
            $u->cadastrar($nome,$email,$senha,$tipo);
            flash('success','Usuário criado com sucesso.');
            header('Location: dashboard.php'); exit;
        } catch (\Throwable $e) { $erro = $e->getMessage(); }
    }
}
$titulo = 'Novo usuário | Portal Docente';
require __DIR__ . '/_header.php';
?>
<div class="page-header"><div><span class="eyebrow">ADMINISTRAÇÃO</span><h1>Novo usuário</h1><p>Crie contas para docentes ou administradores.</p></div></div>
<div class="panel">
<?php if ($erro): ?><div class="alert alert-danger"><?= e($erro) ?></div><?php endif; ?>
<form method="post"><?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Nome</label><input required name="nome" class="form-control"></div>
<div class="col-md-6"><label class="form-label">E-mail</label><input required type="email" name="email" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Senha</label><input required minlength="8" type="password" name="senha" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Tipo</label><select name="tipo" class="form-select"><option value="docente">Docente</option><option value="admin">Administrador</option></select></div>
</div>
<div class="text-end mt-4"><button class="btn btn-primary">Criar usuário</button></div>
</form></div>
<?php require __DIR__ . '/_footer.php'; ?>
