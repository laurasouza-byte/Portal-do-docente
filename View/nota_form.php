<?php
require_once __DIR__ . '/../bootstrap.php';
require_docente();

use Controller\NotaController;
use Model\Aluno;
use Model\UnidadeCurricular;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$notaController = new NotaController();
$nota = $id ? $notaController->porId($id) : null;
$alunos = (new Aluno())->listar();
$ucs = (new UnidadeCurricular())->listar();
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $erro = 'Token de segurança inválido.';
    } else {
        try {
            $parse = static function(string $key): ?float {
                $v = trim((string)($_POST[$key] ?? ''));
                return $v === '' ? null : (float)str_replace(',', '.', $v);
            };
            $notaController->salvar(
                (int)$_POST['aluno_id'],
                (int)$_POST['uc_id'],
                $parse('av1'), $parse('av2'), $parse('av3'), $parse('rec')
            );
            flash('success', 'Notas salvas com sucesso.');
            header('Location: notas.php');
            exit;
        } catch (\Throwable $e) {
            $erro = $e->getMessage();
        }
    }
}

$titulo = ($id ? 'Editar nota' : 'Lançar notas') . ' | Portal Docente';
require __DIR__ . '/_header.php';
?>
<div class="page-header">
<div><span class="eyebrow">LANÇAMENTO</span><h1><?= $id ? 'Editar notas' : 'Lançar notas' ?></h1><p>Os limites de cada avaliação são validados automaticamente.</p></div>
<a href="notas.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
</div>

<div class="row justify-content-center"><div class="col-xl-9">
<div class="panel">
<?php if ($erro): ?><div class="alert alert-danger"><?= e($erro) ?></div><?php endif; ?>
<form method="post">
<?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Aluno</label>
<select name="aluno_id" class="form-select" required>
<option value="">Selecione o aluno</option>
<?php foreach ($alunos as $a): ?>
<option value="<?= $a['id'] ?>" <?= (($nota['aluno_id'] ?? '') == $a['id']) ? 'selected' : '' ?>><?= e($a['matricula']) ?> — <?= e($a['nome']) ?></option>
<?php endforeach; ?>
</select></div>
<div class="col-md-6"><label class="form-label">Unidade curricular</label>
<select name="uc_id" class="form-select" required>
<option value="">Selecione a UC</option>
<?php foreach ($ucs as $u): ?>
<option value="<?= $u['id'] ?>" <?= (($nota['uc_id'] ?? '') == $u['id']) ? 'selected' : '' ?>><?= e($u['codigo']) ?> — <?= e($u['nome']) ?></option>
<?php endforeach; ?>
</select></div>

<div class="col-md-3"><label class="form-label">AV1 <span class="limit">máx. 3</span></label><input type="number" name="av1" class="form-control nota-input" min="0" max="3" step="0.01" value="<?= e($nota['av1'] ?? '') ?>"></div>
<div class="col-md-3"><label class="form-label">AV2 <span class="limit">máx. 3</span></label><input type="number" name="av2" class="form-control nota-input" min="0" max="3" step="0.01" value="<?= e($nota['av2'] ?? '') ?>"></div>
<div class="col-md-3"><label class="form-label">AV3 <span class="limit">máx. 4</span></label><input type="number" name="av3" class="form-control nota-input" min="0" max="4" step="0.01" value="<?= e($nota['av3'] ?? '') ?>"></div>
<div class="col-md-3"><label class="form-label">REC <span class="limit">máx. 10</span></label><input type="number" name="rec" class="form-control nota-input" min="0" max="10" step="0.01" value="<?= e($nota['rec'] ?? '') ?>"></div>
</div>

<div class="calculator-box mt-4">
<div><span>Nota regular</span><strong id="regularPreview">0,00 / 10</strong></div>
<div><span>Situação</span><strong id="situacaoPreview">—</strong></div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
<a href="notas.php" class="btn btn-light">Cancelar</a><button class="btn btn-primary"><i class="bi bi-check2-circle"></i> Salvar lançamento</button>
</div>
</form>
</div></div></div>
<?php require __DIR__ . '/_footer.php'; ?>
