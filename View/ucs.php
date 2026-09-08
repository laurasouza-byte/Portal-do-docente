<?php
require_once __DIR__ . '/../bootstrap.php';
require_docente();

use Controller\UnidadeCurricularController;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    if (csrf_valid()) {
        try { (new UnidadeCurricularController())->excluir((int)$_POST['excluir_id']); flash('success','Unidade curricular excluída.'); }
        catch (\Throwable $e) { flash('danger','Não foi possível excluir esta UC porque há notas vinculadas.'); }
    }
    header('Location: ucs.php'); exit;
}

$ucs = (new UnidadeCurricularController())->listar();
$titulo = 'Unidades Curriculares | Portal Docente';
require __DIR__ . '/_header.php';
?>
<div class="page-header"><div><span class="eyebrow">ESTRUTURA ACADÊMICA</span><h1>Unidades Curriculares</h1><p>Defina as disciplinas usadas no lançamento de notas.</p></div><a href="uc_form.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nova UC</a></div>
<div class="panel table-responsive"><table class="table align-middle">
<thead><tr><th>Código</th><th>Unidade curricular</th><th>Carga horária</th><th>Período</th><th>Lançamentos</th><th></th></tr></thead>
<tbody>
<?php foreach ($ucs as $u): ?>
<tr><td><span class="uc-badge"><?= e($u['codigo']) ?></span></td><td><strong><?= e($u['nome']) ?></strong></td><td><?= e($u['carga_horaria']) ?> h</td><td><?= e($u['periodo']) ?></td><td><?= e($u['total_lancamentos']) ?></td><td class="text-end"><a href="uc_form.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
<form method="post" class="d-inline" onsubmit="return confirm('Excluir esta UC?');"><?= csrf_field() ?><input type="hidden" name="excluir_id" value="<?= $u['id'] ?>"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
<?php endforeach; ?>
</tbody></table></div>
<?php require __DIR__ . '/_footer.php'; ?>
