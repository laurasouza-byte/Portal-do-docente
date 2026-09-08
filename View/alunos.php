<?php
require_once __DIR__ . '/../bootstrap.php';
require_docente();

use Controller\AlunoController;

$busca = trim((string)($_GET['busca'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    if (csrf_valid()) {
        try {
            (new AlunoController())->excluir((int)$_POST['excluir_id']);
            flash('success', 'Aluno excluído.');
        } catch (\Throwable $e) {
            flash('danger', 'Não foi possível excluir o aluno. Verifique se existem registros vinculados.');
        }
    }
    header('Location: alunos.php');
    exit;
}

$alunos = (new AlunoController())->listar($busca);
$titulo = 'Alunos | Portal Docente';
require __DIR__ . '/_header.php';
?>
<div class="page-header"><div><span class="eyebrow">CADASTROS</span><h1>Alunos</h1><p>Cadastre e organize os estudantes que receberão os lançamentos.</p></div><a href="aluno_form.php" class="btn btn-primary"><i class="bi bi-person-plus"></i> Novo aluno</a></div>
<div class="panel mb-4"><form class="d-flex gap-2"><input name="busca" value="<?= e($busca) ?>" class="form-control" placeholder="Buscar por nome ou matrícula"><button class="btn btn-dark"><i class="bi bi-search"></i></button></form></div>
<div class="panel table-responsive"><table class="table align-middle">
<thead><tr><th>Aluno</th><th>Matrícula</th><th>E-mail</th><th>Curso</th><th>Lançamentos</th><th></th></tr></thead>
<tbody>
<?php foreach ($alunos as $a): ?>
<tr><td><strong><?= e($a['nome']) ?></strong></td><td><span class="uc-badge"><?= e($a['matricula']) ?></span></td><td><?= e($a['email']) ?></td><td><?= e($a['curso']) ?></td><td><?= e($a['total_notas']) ?></td><td class="text-end"><a href="aluno_form.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
<form method="post" class="d-inline" onsubmit="return confirm('Excluir este aluno e suas notas?');"><?= csrf_field() ?><input type="hidden" name="excluir_id" value="<?= $a['id'] ?>"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
<?php endforeach; ?>
</tbody></table></div>
<?php require __DIR__ . '/_footer.php'; ?>
