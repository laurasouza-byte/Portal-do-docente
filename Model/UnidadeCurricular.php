<?php
declare(strict_types=1);

namespace Model;

use PDO;

class UnidadeCurricular
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function listar(): array
    {
        return $this->db->query(
            'SELECT u.*, COUNT(n.id) AS total_lancamentos
             FROM unidades_curriculares u
             LEFT JOIN notas n ON n.uc_id = u.id
             GROUP BY u.id
             ORDER BY u.nome'
        )->fetchAll();
    }

    public function porId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM unidades_curriculares WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function cadastrar(string $codigo, string $nome, int $cargaHoraria, string $periodo): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO unidades_curriculares (codigo, nome, carga_horaria, periodo)
             VALUES (:codigo, :nome, :carga_horaria, :periodo)'
        );
        $stmt->execute([
            'codigo' => $codigo,
            'nome' => $nome,
            'carga_horaria' => $cargaHoraria,
            'periodo' => $periodo,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function atualizar(int $id, string $codigo, string $nome, int $cargaHoraria, string $periodo): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE unidades_curriculares
             SET codigo = :codigo, nome = :nome, carga_horaria = :carga_horaria, periodo = :periodo
             WHERE id = :id'
        );
        return $stmt->execute(compact('id', 'codigo', 'nome', 'cargaHoraria', 'periodo'));
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM unidades_curriculares WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function total(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM unidades_curriculares')->fetchColumn();
    }
}
