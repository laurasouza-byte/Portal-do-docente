<?php
require_once __DIR__ . '/../bootstrap.php';
require_docente();

use Controller\UnidadeCurricularController;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$c = new UnidadeCurricularController();
$uc = $id ? $c->porId($id) : null;
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) $erro = 'Token de segurança inválido.';
    else {
        try {
            $codigo = strtoupper(trim((string)$_POST['codigo']));
            $nome = trim((string)$_POST['nome']);
            $carga = (int)$_POST['carga_horaria'];
            $periodo = trim((string)$_POST['periodo']);
            if ($id) $c->atualizar($id,$codigo,$nome,$carga,$periodo);
            else $c->cadastrar($codigo,$nome,$carga,$periodo);
            flash('success','Unidade curricular salva.');
            header('Location: ucs.php'); exit;
        } catch (\Throwable $e) { $erro = 'Não foi possível salvar. O código pode já existir.'; }
    }
}

$titulo = ($id ? 'Editar UC' : 'Nova UC') . ' | Portal Docente';
require __DIR__ . '/_header.php';
?>
<div class="page-header"><div><span class="eyebrow">CADASTRO</span><h1><?= $id ? 'Editar unidade curricular' : 'Nova unidade curricular' ?></h1></div><a href="ucs.php" class="btn btn-outline-secondary">Voltar</a></div>
<div class="panel">
<?php if ($erro): ?><div class="alert alert-danger"><?= e($erro) ?></div><?php endif; ?>
<form method="post"><?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-3"><label class="form-label">Código</label><input required name="codigo" class="form-control" placeholder="UC001" value="<?= e($uc['codigo'] ?? '') ?>"></div>
<div class="col-md-6"><label class="form-label">Nome da UC</label><input required name="nome" class="form-control" placeholder="Banco de Dados" value="<?= e($uc['nome'] ?? '') ?>"></div>
<div class="col-md-3"><label class="form-label">Carga horária</label><input required type="number" min="1" name="carga_horaria" class="form-control" value="<?= e($uc['carga_horaria'] ?? 40) ?>"></div>
<div class="col-md-4"><label class="form-label">Período</label><input name="periodo" class="form-control" placeholder="2026.2" value="<?= e($uc['periodo'] ?? '') ?>"></div>
</div>
<div class="text-end mt-4"><button class="btn btn-primary">Salvar UC</button></div>
</form></div>
<?php require __DIR__ . '/_footer.php'; ?>
