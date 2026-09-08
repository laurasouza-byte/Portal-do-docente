<?php
require_once __DIR__ . '/../bootstrap.php';
require_docente();

use Controller\AlunoController;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controller = new AlunoController();
$aluno = $id ? $controller->porId($id) : null;
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $erro = 'Token de segurança inválido.';
    } else {
        try {
            $dados = [
                'nome' => trim((string)$_POST['nome']),
                'matricula' => trim((string)$_POST['matricula']),
                'email' => trim((string)$_POST['email']),
                'curso' => trim((string)$_POST['curso']),
            ];
            if ($id) $controller->atualizar($id, ...array_values($dados));
            else $controller->cadastrar(...array_values($dados));
            flash('success', 'Aluno salvo com sucesso.');
            header('Location: alunos.php'); exit;
        } catch (\Throwable $e) { $erro = 'Não foi possível salvar. A matrícula pode já estar cadastrada.'; }
    }
}

$titulo = ($id ? 'Editar aluno' : 'Novo aluno') . ' | Portal Docente';
require __DIR__ . '/_header.php';
?>
<div class="page-header"><div><span class="eyebrow">CADASTRO</span><h1><?= $id ? 'Editar aluno' : 'Novo aluno' ?></h1></div><a href="alunos.php" class="btn btn-outline-secondary">Voltar</a></div>
<div class="panel">
<?php if ($erro): ?><div class="alert alert-danger"><?= e($erro) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Nome completo</label><input required name="nome" class="form-control" value="<?= e($aluno['nome'] ?? '') ?>"></div>
<div class="col-md-3"><label class="form-label">Matrícula</label><input required name="matricula" class="form-control" value="<?= e($aluno['matricula'] ?? '') ?>"></div>
<div class="col-md-3"><label class="form-label">E-mail</label><input type="email" required name="email" class="form-control" value="<?= e($aluno['email'] ?? '') ?>"></div>
<div class="col-md-6"><label class="form-label">Curso</label><input required name="curso" class="form-control" placeholder="Ex.: Desenvolvimento de Sistemas" value="<?= e($aluno['curso'] ?? '') ?>"></div>
</div>
<div class="text-end mt-4"><button class="btn btn-primary">Salvar aluno</button></div>
</form></div>
<?php require __DIR__ . '/_footer.php'; ?>
