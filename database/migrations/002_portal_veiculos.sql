-- ============================================================
-- AppAuto SaaS — Migration 002: Portal de Veículos Completo
-- Compatível com MySQL 5.7 / MariaDB / Hostgator
-- ============================================================

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

-- ------------------------------------------------------------
-- Documentos do Veículo
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_documentos (
    id          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id  INT(11) UNSIGNED NOT NULL,
    usuario_id  INT(11) UNSIGNED NOT NULL,
    tipo        ENUM(
                    'crlv','seguro','manual','nota_fiscal',
                    'financiamento','garantia','recibo',
                    'contrato_compra','laudo_cautelar','outro'
                ) NOT NULL DEFAULT 'outro',
    titulo      VARCHAR(150) NOT NULL,
    arquivo     VARCHAR(255) NOT NULL,
    tamanho_kb  INT(11) UNSIGNED DEFAULT 0,
    observacao  TEXT,
    criado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Manutenções / Oficina
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_manutencoes (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    tipo            VARCHAR(100) NOT NULL,
    descricao       TEXT,
    data_servico    DATE NOT NULL,
    km_servico      INT(11) UNSIGNED DEFAULT 0,
    oficina_nome    VARCHAR(150),
    valor           DECIMAL(10,2) DEFAULT 0.00,
    nota_fiscal     VARCHAR(255),
    pecas           TEXT,
    observacoes     TEXT,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id),
    KEY idx_data (data_servico)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Fotos de manutenção
CREATE TABLE IF NOT EXISTS manutencao_fotos (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    manutencao_id   INT(11) UNSIGNED NOT NULL,
    arquivo         VARCHAR(255) NOT NULL,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_manutencao (manutencao_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Agenda Inteligente de Manutenção
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_agenda (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    tipo_servico    VARCHAR(100) NOT NULL,
    descricao       VARCHAR(255),
    data_prevista   DATE,
    km_previsto     INT(11) UNSIGNED DEFAULT 0,
    intervalo_km    INT(11) UNSIGNED DEFAULT 0,
    intervalo_dias  INT(11) UNSIGNED DEFAULT 0,
    notificado      TINYINT(1) NOT NULL DEFAULT 0,
    concluido       TINYINT(1) NOT NULL DEFAULT 0,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id),
    KEY idx_data (data_prevista)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Abastecimentos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_abastecimentos (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    data_abast      DATE NOT NULL,
    posto_nome      VARCHAR(150),
    cidade          VARCHAR(100),
    combustivel     ENUM('gasolina','etanol','diesel','gnv','eletrico','flex') NOT NULL DEFAULT 'gasolina',
    litros          DECIMAL(8,3) NOT NULL DEFAULT 0.000,
    valor_litro     DECIMAL(8,3) NOT NULL DEFAULT 0.000,
    valor_total     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    km_abastecido   INT(11) UNSIGNED DEFAULT 0,
    tanque_cheio    TINYINT(1) NOT NULL DEFAULT 1,
    observacao      TEXT,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id),
    KEY idx_data (data_abast)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Pneus
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_pneus (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    posicao         ENUM('dianteiro_esq','dianteiro_dir','traseiro_esq','traseiro_dir','estepe') NOT NULL,
    marca           VARCHAR(100),
    modelo          VARCHAR(100),
    medida          VARCHAR(30),
    data_instalacao DATE,
    km_instalacao   INT(11) UNSIGNED DEFAULT 0,
    valor           DECIMAL(10,2) DEFAULT 0.00,
    garantia_meses  INT(11) UNSIGNED DEFAULT 0,
    vida_util_km    INT(11) UNSIGNED DEFAULT 0,
    proximo_rodizio DATE,
    calibragem      DECIMAL(4,1) DEFAULT 32.0,
    ativo           TINYINT(1) NOT NULL DEFAULT 1,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Bateria
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_bateria (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    marca           VARCHAR(100),
    modelo          VARCHAR(100),
    amperagem       VARCHAR(20),
    data_instalacao DATE,
    km_instalacao   INT(11) UNSIGNED DEFAULT 0,
    valor           DECIMAL(10,2) DEFAULT 0.00,
    garantia_meses  INT(11) UNSIGNED DEFAULT 0,
    vida_util_meses INT(11) UNSIGNED DEFAULT 48,
    ativo           TINYINT(1) NOT NULL DEFAULT 1,
    observacao      TEXT,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Seguro
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_seguro (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    seguradora      VARCHAR(150) NOT NULL,
    apolice         VARCHAR(100),
    corretor_nome   VARCHAR(150),
    corretor_tel    VARCHAR(20),
    assistencia_tel VARCHAR(20),
    guincho_tel     VARCHAR(20),
    data_inicio     DATE,
    data_vencimento DATE,
    valor_premio    DECIMAL(10,2) DEFAULT 0.00,
    franquia        DECIMAL(10,2) DEFAULT 0.00,
    ativo           TINYINT(1) NOT NULL DEFAULT 1,
    observacao      TEXT,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id),
    KEY idx_vencimento (data_vencimento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- IPVA / Licenciamento / Multas
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_ipva (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    tipo            ENUM('ipva','licenciamento','multa','recall','vistoria') NOT NULL DEFAULT 'ipva',
    ano_referencia  YEAR NOT NULL,
    valor           DECIMAL(10,2) DEFAULT 0.00,
    data_vencimento DATE,
    data_pagamento  DATE,
    pago            TINYINT(1) NOT NULL DEFAULT 0,
    descricao       VARCHAR(255),
    observacao      TEXT,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Custos (consolidado por categoria)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_custos (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    categoria       ENUM('combustivel','seguro','ipva','manutencao','lavagem','pneus','bateria','multa','outro') NOT NULL DEFAULT 'outro',
    descricao       VARCHAR(255),
    valor           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    data_custo      DATE NOT NULL,
    referencia_id   INT(11) UNSIGNED DEFAULT NULL COMMENT 'ID da tabela de origem (opcional)',
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id),
    KEY idx_data (data_custo),
    KEY idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Galeria de Fotos do Veículo
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_galeria (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    categoria       ENUM('exterior','interior','motor','painel','nota_fiscal','outro') NOT NULL DEFAULT 'exterior',
    titulo          VARCHAR(150),
    arquivo         VARCHAR(255) NOT NULL,
    data_foto       DATE,
    observacao      TEXT,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Checklist de Viagem / Preventivo
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_checklist (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    tipo            ENUM('viagem','preventivo') NOT NULL DEFAULT 'viagem',
    titulo          VARCHAR(150) NOT NULL,
    data_checklist  DATE NOT NULL,
    observacao      TEXT,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS checklist_itens (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    checklist_id    INT(11) UNSIGNED NOT NULL,
    item            VARCHAR(150) NOT NULL,
    marcado         TINYINT(1) NOT NULL DEFAULT 0,
    status          ENUM('ok','atencao','critico') NOT NULL DEFAULT 'ok',
    observacao      VARCHAR(255),
    PRIMARY KEY (id),
    KEY idx_checklist (checklist_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Timeline do Veículo
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_timeline (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    tipo            ENUM('compra','revisao','manutencao','seguro','pneus','bateria','acidente','reforma','venda','outro') NOT NULL DEFAULT 'outro',
    titulo          VARCHAR(150) NOT NULL,
    descricao       TEXT,
    data_evento     DATE NOT NULL,
    km_evento       INT(11) UNSIGNED DEFAULT 0,
    valor           DECIMAL(10,2) DEFAULT 0.00,
    icone           VARCHAR(50) DEFAULT 'fa-car',
    cor             VARCHAR(20) DEFAULT '#3b82f6',
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id),
    KEY idx_data (data_evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Score do Veículo
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS veiculo_score (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    veiculo_id      INT(11) UNSIGNED NOT NULL,
    usuario_id      INT(11) UNSIGNED NOT NULL,
    score_total     TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
    pts_manutencao  TINYINT(3) UNSIGNED DEFAULT 0,
    pts_documentos  TINYINT(3) UNSIGNED DEFAULT 0,
    pts_pneus       TINYINT(3) UNSIGNED DEFAULT 0,
    pts_bateria     TINYINT(3) UNSIGNED DEFAULT 0,
    pts_seguro      TINYINT(3) UNSIGNED DEFAULT 0,
    pts_historico   TINYINT(3) UNSIGNED DEFAULT 0,
    calculado_em    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_veiculo (veiculo_id),
    KEY idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------
-- Marketplace (futuro — estrutura base)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS marketplace_ofertas (
    id              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    tipo            ENUM('oficina','seguradora','pneus','bateria','lavagem','outro') NOT NULL DEFAULT 'outro',
    titulo          VARCHAR(150) NOT NULL,
    descricao       TEXT,
    empresa         VARCHAR(150),
    telefone        VARCHAR(20),
    cidade          VARCHAR(100),
    estado          CHAR(2),
    url             VARCHAR(255),
    logo            VARCHAR(255),
    ativo           TINYINT(1) NOT NULL DEFAULT 1,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_tipo (tipo),
    KEY idx_cidade (cidade)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

SET foreign_key_checks = 1;
