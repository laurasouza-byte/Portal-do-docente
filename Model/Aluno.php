<?php
declare(strict_types=1);

namespace Model;

use PDO;

class Aluno
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function listar(string $busca = ''): array
    {
        $sql = 'SELECT a.*, COUNT(n.id) AS total_notas
                FROM alunos a
                LEFT JOIN notas n ON n.aluno_id = a.id';
        $params = [];

        if ($busca !== '') {
            $sql .= ' WHERE a.nome LIKE :busca OR a.matricula LIKE :busca';
            $params['busca'] = '%' . $busca . '%';
        }

        $sql .= ' GROUP BY a.id ORDER BY a.nome';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function porId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM alunos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function cadastrar(string $nome, string $matricula, string $email, string $curso): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO alunos (nome, matricula, email, curso) VALUES (:nome, :matricula, :email, :curso)'
        );
        $stmt->execute(compact('nome', 'matricula', 'email', 'curso'));
        return (int)$this->db->lastInsertId();
    }

    public function atualizar(int $id, string $nome, string $matricula, string $email, string $curso): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE alunos SET nome = :nome, matricula = :matricula, email = :email, curso = :curso WHERE id = :id'
        );
        return $stmt->execute(compact('id', 'nome', 'matricula', 'email', 'curso'));
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM alunos WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function total(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM alunos')->fetchColumn();
    }
}
