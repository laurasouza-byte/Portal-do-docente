<?php
require_once __DIR__ . '/../bootstrap.php';
require_docente();

use Model\Aluno;
use Model\UnidadeCurricular;
use Model\Nota;
use Model\Usuario;

$alunos = new Aluno();
$ucs = new UnidadeCurricular();
$notas = new Nota();
$usuarios = new Usuario();

$titulo = 'Dashboard | Portal Docente';
require __DIR__ . '/_header.php';
?>
<div class="page-header">
    <div>
        <span class="eyebrow">VISÃO GERAL</span>
        <h1>Olá, <?= e($_SESSION['usuario_nome']) ?> 👋</h1>
        <p>Gerencie avaliações, alunos e unidades curriculares em um só lugar.</p>
    </div>
    <a class="btn btn-primary" href="nota_form.php"><i class="bi bi-plus-circle"></i> Lançar notas</a>
</div>

<div class="row g-3">
    <div class="col-md-3"><div class="stat"><i class="bi bi-people"></i><span>Alunos</span><strong><?= $alunos->total() ?></strong></div></div>
    <div class="col-md-3"><div class="stat"><i class="bi bi-book"></i><span>Unidades curriculares</span><strong><?= $ucs->total() ?></strong></div></div>
    <div class="col-md-3"><div class="stat"><i class="bi bi-journal-check"></i><span>Lançamentos</span><strong><?= $notas->total() ?></strong></div></div>
    <div class="col-md-3"><div class="stat"><i class="bi bi-check2-circle"></i><span>Aprovados</span><strong><?= $notas->aprovados() ?></strong></div></div>
</div>

<div class="row g-4 mt-2">
    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-title"><h2>Regras de avaliação</h2><span class="badge text-bg-light">10 pontos</span></div>
            <div class="rules">
                <div><b>AV1</b><span>0 a 3 pontos</span></div>
                <div><b>AV2</b><span>0 a 3 pontos</span></div>
                <div><b>AV3</b><span>0 a 4 pontos</span></div>
                <div><b>REC</b><span>0 a 10 pontos</span></div>
            </div>
            <p class="small text-muted mt-3 mb-0">
                A nota regular é AV1 + AV2 + AV3. O aluno é aprovado com nota regular ≥ 6.
                Se ficar abaixo de 6, a REC pode ser usada como avaliação final; REC ≥ 6 resulta em aprovação.
            </p>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel">
            <div class="panel-title"><h2>Acesso rápido</h2></div>
            <div class="quick-links">
                <a href="notas.php"><i class="bi bi-journal-text"></i><span>Consultar notas</span><i class="bi bi-chevron-right"></i></a>
                <a href="aluno_form.php"><i class="bi bi-person-plus"></i><span>Cadastrar aluno</span><i class="bi bi-chevron-right"></i></a>
                <a href="uc_form.php"><i class="bi bi-bookmark-plus"></i><span>Cadastrar UC</span><i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
