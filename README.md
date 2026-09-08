# Portal Docente — Gerenciamento de Notas

Projeto PHP + MySQL criado a partir da estrutura e padrões observados nos projetos compactados enviados, adaptado para um **Portal Docente**.

## Funcionalidades

- Login com sessão e senha criptografada.
- Perfil de usuário `docente` e `admin`.
- Dashboard com indicadores.
- Cadastro, edição e exclusão de alunos.
- Cadastro, edição e exclusão de unidades curriculares.
- Lançamento e edição de notas.
- Filtro de notas por unidade curricular e aluno/matrícula.
- Situação automática: **Aprovado** ou **Reprovado**.
- Proteção por prepared statements e token CSRF.
- Interface responsiva com Bootstrap.
- Dados de exemplo no `database.sql`.

## Regra das avaliações

Para cada aluno em cada unidade curricular:

| Campo | Valor máximo |
|---|---:|
| AV1 | 3,00 |
| AV2 | 3,00 |
| AV3 | 4,00 |
| **Total regular** | **10,00** |
| REC | 10,00 |

A nota regular é calculada por:

`AV1 + AV2 + AV3`

A regra implementada é:

- regular >= 6,00 → **Aprovado**
- regular < 6,00 e REC >= 6,00 → **Aprovado pela REC**
- regular < 6,00 e REC < 6,00 → **Reprovado**
- sem REC e regular < 6,00 → **Reprovado/Aguardando REC**

A UC fica registrada por uma chave estrangeira `uc_id` na tabela `notas`, garantindo que cada lançamento pertença a uma unidade curricular.

## Como abrir no XAMPP

1. Extraia a pasta `PortalDocente` para:
   `C:\xampp\htdocs\portal-docente`

2. Abra o XAMPP e inicie:
   - Apache
   - MySQL

3. Entre no phpMyAdmin:
   `http://localhost/phpmyadmin`

4. Importe o arquivo `database.sql`.

5. Confirme as credenciais em:
   `Config/configuration.php`

   Configuração padrão do XAMPP:
   - servidor: `localhost`
   - porta: `3306`
   - usuário: `root`
   - senha: vazia
   - banco: `portal_docente`

6. Acesse:
   `http://localhost/portal-docente/`

## Usuários de teste

**Administrador**
- E-mail: `admin@portal.local`
- Senha: `Admin123!`

**Docente**
- E-mail: `docente@portal.local`
- Senha: `Docente123!`

## Estrutura

```text
PortalDocente/
├── Config/
├── Controller/
├── Model/
├── View/
├── templates/
│   ├── css/
│   └── js/
├── vendor/
├── database.sql
├── bootstrap.php
├── composer.json
├── index.php
└── README.md
```

O projeto não exige `composer install`; foi incluído um autoloader simples em `vendor/autoload.php` para facilitar o uso no XAMPP.

## Observação

Se sua instituição utilizar outra regra de recuperação, a regra de situação pode ser alterada em `Model/Nota.php` e na apresentação de `View/notas.php`.

## Banco de Dados
CREATE DATABASE IF NOT EXISTS portal_docente
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portal_docente;

DROP TABLE IF EXISTS notas;
DROP TABLE IF EXISTS alunos;
DROP TABLE IF EXISTS unidades_curriculares;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin','docente') NOT NULL DEFAULT 'docente',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE alunos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    matricula VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL,
    curso VARCHAR(120) NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE unidades_curriculares (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(120) NOT NULL,
    carga_horaria SMALLINT UNSIGNED NOT NULL DEFAULT 40,
    periodo VARCHAR(20) NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE notas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT UNSIGNED NOT NULL,
    uc_id INT UNSIGNED NOT NULL,
    av1 DECIMAL(4,2) NULL,
    av2 DECIMAL(4,2) NULL,
    av3 DECIMAL(4,2) NULL,
    rec DECIMAL(4,2) NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_av1 CHECK (av1 IS NULL OR (av1 >= 0 AND av1 <= 3)),
    CONSTRAINT chk_av2 CHECK (av2 IS NULL OR (av2 >= 0 AND av2 <= 3)),
    CONSTRAINT chk_av3 CHECK (av3 IS NULL OR (av3 >= 0 AND av3 <= 4)),
    CONSTRAINT chk_rec CHECK (rec IS NULL OR (rec >= 0 AND rec <= 10)),

    CONSTRAINT fk_notas_aluno FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    CONSTRAINT fk_notas_uc FOREIGN KEY (uc_id) REFERENCES unidades_curriculares(id) ON DELETE RESTRICT,
    CONSTRAINT uq_aluno_uc UNIQUE (aluno_id, uc_id)
) ENGINE=InnoDB;

-- Senhas:
-- Admin123!  -> acesso administrador
-- Docente123! -> acesso docente
INSERT INTO usuarios (nome, email, senha, tipo) VALUES
('Administrador do Sistema', 'admin@portal.local', '$2y$12$TWwBtm7iP0YEZBsRh.GRjOHN.eeoXIdrenKVHlaVt3O7aDNZzyHlm', 'admin'),
('Professor(a) Demo', 'docente@portal.local', '$2y$12$5w19U2vmTv7dp0PQ0o7N3udhe6PGC13Ey6zR858IRJABbUXn8Fezq', 'docente');

INSERT INTO alunos (nome, matricula, email, curso) VALUES
('Ana Souza', '2026001', 'ana@aluno.local', 'Desenvolvimento de Sistemas'),
('Bruno Santos', '2026002', 'bruno@aluno.local', 'Desenvolvimento de Sistemas'),
('Carla Oliveira', '2026003', 'carla@aluno.local', 'Desenvolvimento de Sistemas'),
('Diego Almeida', '2026004', 'diego@aluno.local', 'Desenvolvimento de Sistemas');

INSERT INTO unidades_curriculares (codigo, nome, carga_horaria, periodo) VALUES
('BD001', 'Banco de Dados', 80, '2026.2'),
('PW001', 'Programação Web', 80, '2026.2'),
('ES001', 'Engenharia de Software', 60, '2026.2');

INSERT INTO notas (aluno_id, uc_id, av1, av2, av3, rec) VALUES
(1, 1, 2.50, 2.50, 3.50, NULL),
(2, 1, 1.50, 2.00, 2.00, 7.00),
(3, 1, 2.00, 1.00, 2.00, 5.50),
(4, 1, 3.00, 3.00, 4.00, NULL),
(1, 2, 2.00, 2.50, 3.50, NULL),
(2, 2, 1.00, 1.50, 2.00, 8.00),
(3, 3, 2.50, 2.00, 3.00, NULL);
