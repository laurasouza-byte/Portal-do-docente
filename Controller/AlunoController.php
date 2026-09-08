<?php
declare(strict_types=1);

namespace Controller;

use Model\Aluno;

class AlunoController
{
    private Aluno $model;

    public function __construct()
    {
        $this->model = new Aluno();
    }

    public function listar(string $busca = ''): array { return $this->model->listar($busca); }
    public function porId(int $id): ?array { return $this->model->porId($id); }
    public function cadastrar(string $nome, string $matricula, string $email, string $curso): int
    {
        return $this->model->cadastrar($nome, $matricula, $email, $curso);
    }
    public function atualizar(int $id, string $nome, string $matricula, string $email, string $curso): bool
    {
        return $this->model->atualizar($id, $nome, $matricula, $email, $curso);
    }
    public function excluir(int $id): bool { return $this->model->excluir($id); }
}
