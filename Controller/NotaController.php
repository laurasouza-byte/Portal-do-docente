<?php
declare(strict_types=1);

namespace Controller;

use Model\Nota;

class NotaController
{
    private Nota $model;

    public function __construct()
    {
        $this->model = new Nota();
    }

    public function listar(?int $ucId = null, string $busca = ''): array
    {
        return $this->model->listar($ucId, $busca);
    }

    public function salvar(int $alunoId, int $ucId, ?float $av1, ?float $av2, ?float $av3, ?float $rec): bool
    {
        return $this->model->salvar($alunoId, $ucId, $av1, $av2, $av3, $rec);
    }

    public function porId(int $id): ?array
    {
        return $this->model->porId($id);
    }

    public function excluir(int $id): bool
    {
        return $this->model->excluir($id);
    }
}
