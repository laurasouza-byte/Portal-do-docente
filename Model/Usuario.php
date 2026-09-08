<?php
declare(strict_types=1);

namespace Model;

use PDO;

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function porEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function porId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, nome, email, tipo, ativo FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function cadastrar(string $nome, string $email, string $senha, string $tipo): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)'
        );
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'tipo' => $tipo,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function total(string $tipo = ''): int
    {
        if ($tipo !== '') {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM usuarios WHERE tipo = :tipo AND ativo = 1');
            $stmt->execute(['tipo' => $tipo]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$this->db->query('SELECT COUNT(*) FROM usuarios WHERE ativo = 1')->fetchColumn();
    }
}
