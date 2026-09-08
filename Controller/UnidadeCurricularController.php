<?php
declare(strict_types=1);

namespace Controller;

use Model\UnidadeCurricular;

class UnidadeCurricularController
{
    private UnidadeCurricular $model;

    public function __construct() { $this->model = new UnidadeCurricular(); }

    public function listar(): array { return $this->model->listar(); }
    public function porId(int $id): ?array { return $this->model->porId($id); }
    public function cadastrar(string $codigo, string $nome, int $carga, string $periodo): int
    {
        return $this->model->cadastrar($codigo, $nome, $carga, $periodo);
    }
    public function atualizar(int $id, string $codigo, string $nome, int $carga, string $periodo): bool
    {
        return $this->model->atualizar($id, $codigo, $nome, $carga, $periodo);
    }
    public function excluir(int $id): bool { return $this->model->excluir($id); }
}
