<?php
require_once __DIR__ . '/../bootstrap.php';
require_docente();

use Controller\NotaController;
use Model\UnidadeCurricular;

$ucId = isset($_GET['uc_id']) && $_GET['uc_id'] !== '' ? (int)$_GET['uc_id'] : null;
$busca = trim((string)($_GET['busca'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    if (!csrf_valid()) {
        flash('danger', 'Token de segurança inválido.');
    } else {
        (new NotaController())->excluir((int)$_POST['excluir_id']);
        flash('success', 'Lançamento excluído.');
    }
    header('Location: notas.php');
    exit;
}

$notas = (new NotaController())->listar($ucId, $busca);
$ucs = (new UnidadeCurricular())->listar();
$titulo = 'Notas | Portal Docente';
require __DIR__ . '/_header.php';
?>
<div class="page-header">
    <div><span class="eyebrow">ACADÊMICO</span><h1>Gerenciamento de notas</h1><p>Registre e acompanhe as avaliações de cada aluno por unidade curricular.</p></div>
    <a href="nota_form.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Novo lançamento</a>
</div>

<div class="panel mb-4">
<form class="row g-2 align-items-end">
    <div class="col-md-5">
        <label class="form-label">Unidade curricular</label>
        <select name="uc_id" class="form-select">
            <option value="">Todas as UCs</option>
            <?php foreach ($ucs as $uc): ?>
                <option value="<?= $uc['id'] ?>" <?= $ucId === (int)$uc['id'] ? 'selected' : '' ?>>
                    <?= e($uc['codigo']) ?> — <?= e($uc['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-5">
        <label class="form-label">Aluno / matrícula</label>
        <input name="busca" value="<?= e($busca) ?>" class="form-control" placeholder="Digite para pesquisar">
    </div>
    <div class="col-md-2"><button class="btn btn-dark w-100"><i class="bi bi-search"></i> Filtrar</button></div>
</form>
</div>

<div class="panel table-responsive">
<table class="table align-middle">
<thead><tr><th>Aluno</th><th>Unidade curricular</th><th>AV1 <small>/3</small></th><th>AV2 <small>/3</small></th><th>AV3 <small>/4</small></th><th>Regular <small>/10</small></th><th>REC <small>/10</small></th><th>Situação</th><th></th></tr></thead>
<tbody>
<?php if (!$notas): ?><tr><td colspan="9" class="text-center text-muted py-5">Nenhum lançamento encontrado.</td></tr><?php endif; ?>
<?php foreach ($notas as $n):
    $regular = (float)$n['av1'] + (float)$n['av2'] + (float)$n['av3'];
    $final = $regular >= 6 ? $regular : (($n['rec'] !== null && (float)$n['rec'] >= 6) ? (float)$n['rec'] : $regular);
    $aprovado = $regular >= 6 || ($n['rec'] !== null && (float)$n['rec'] >= 6);
?>
<tr>
    <td><strong><?= e($n['aluno_nome']) ?></strong><small class="d-block text-muted"><?= e($n['matricula']) ?></small></td>
    <td><span class="uc-badge"><?= e($n['uc_codigo']) ?></span> <?= e($n['uc_nome']) ?></td>
    <td><?= number_format((float)$n['av1'], 2, ',', '.') ?></td>
    <td><?= number_format((float)$n['av2'], 2, ',', '.') ?></td>
    <td><?= number_format((float)$n['av3'], 2, ',', '.') ?></td>
    <td><strong><?= number_format($regular, 2, ',', '.') ?></strong></td>
    <td><?= $n['rec'] === null ? '—' : number_format((float)$n['rec'], 2, ',', '.') ?></td>
    <td><span class="status <?= $aprovado ? 'approved' : 'failed' ?>"><?= $aprovado ? 'Aprovado' : 'Reprovado' ?></span><small class="d-block text-muted">Final: <?= number_format($final,2,',','.') ?></small></td>
    <td class="text-nowrap">
        <a class="btn btn-sm btn-outline-primary" href="nota_form.php?id=<?= $n['id'] ?>"><i class="bi bi-pencil"></i></a>
        <form method="post" class="d-inline" onsubmit="return confirm('Excluir este lançamento?');">
            <?= csrf_field() ?><input type="hidden" name="excluir_id" value="<?= $n['id'] ?>">
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
