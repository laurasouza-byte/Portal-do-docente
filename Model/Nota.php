<?php
declare(strict_types=1);

namespace Model;

use PDO;
use InvalidArgumentException;

class Nota
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    private function valida(?float $av1, ?float $av2, ?float $av3, ?float $rec): void
    {
        if ($av1 !== null && ($av1 < 0 || $av1 > 3)) {
            throw new InvalidArgumentException('AV1 deve estar entre 0 e 3.');
        }
        if ($av2 !== null && ($av2 < 0 || $av2 > 3)) {
            throw new InvalidArgumentException('AV2 deve estar entre 0 e 3.');
        }
        if ($av3 !== null && ($av3 < 0 || $av3 > 4)) {
            throw new InvalidArgumentException('AV3 deve estar entre 0 e 4.');
        }
        if ($rec !== null && ($rec < 0 || $rec > 10)) {
            throw new InvalidArgumentException('REC deve estar entre 0 e 10.');
        }
    }

    public function listar(?int $ucId = null, string $busca = ''): array
    {
        $sql = 'SELECT n.*, a.nome AS aluno_nome, a.matricula,
                       u.codigo AS uc_codigo, u.nome AS uc_nome
                FROM notas n
                INNER JOIN alunos a ON a.id = n.aluno_id
                INNER JOIN unidades_curriculares u ON u.id = n.uc_id
                WHERE 1=1';
        $params = [];

        if ($ucId !== null) {
            $sql .= ' AND n.uc_id = :uc_id';
            $params['uc_id'] = $ucId;
        }

        if ($busca !== '') {
            $sql .= ' AND (a.nome LIKE :busca OR a.matricula LIKE :busca)';
            $params['busca'] = '%' . $busca . '%';
        }

        $sql .= ' ORDER BY u.nome, a.nome';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function porId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT n.*, a.nome AS aluno_nome, a.matricula, u.nome AS uc_nome, u.codigo AS uc_codigo
             FROM notas n
             JOIN alunos a ON a.id = n.aluno_id
             JOIN unidades_curriculares u ON u.id = n.uc_id
             WHERE n.id = :id'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function salvar(int $alunoId, int $ucId, ?float $av1, ?float $av2, ?float $av3, ?float $rec): bool
    {
        $this->valida($av1, $av2, $av3, $rec);

        $stmt = $this->db->prepare(
            'INSERT INTO notas (aluno_id, uc_id, av1, av2, av3, rec)
             VALUES (:aluno_id, :uc_id, :av1, :av2, :av3, :rec)
             ON DUPLICATE KEY UPDATE
                av1 = VALUES(av1), av2 = VALUES(av2), av3 = VALUES(av3), rec = VALUES(rec)'
        );

        return $stmt->execute([
            'aluno_id' => $alunoId,
            'uc_id' => $ucId,
            'av1' => $av1,
            'av2' => $av2,
            'av3' => $av3,
            'rec' => $rec,
        ]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM notas WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function total(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM notas')->fetchColumn();
    }

    public function aprovados(): int
    {
        return (int)$this->db->query(
            'SELECT COUNT(*) FROM notas
             WHERE (COALESCE(av1,0)+COALESCE(av2,0)+COALESCE(av3,0)) >= 6
                OR (COALESCE(av1,0)+COALESCE(av2,0)+COALESCE(av3,0)) < 6 AND rec >= 6'
        )->fetchColumn();
    }
}
