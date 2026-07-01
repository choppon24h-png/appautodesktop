-- ============================================================
-- APPAUTO SaaS — Schema Completo do Banco de Dados
-- Versão: 2.0.0 | MySQL 5.7 / MariaDB | Hostgator
-- Charset: utf8 | Collation: utf8_unicode_ci
-- ⚠️  Executar em ordem — sem PROCEDURE, FUNCTION, TRIGGER
-- ⚠️  Verificar DESCRIBE tabela; antes de qualquer ALTER
-- ============================================================

SET NAMES utf8;
SET CHARACTER SET utf8;

-- ============================================================
-- 1. RAMOS DE ATIVIDADE (lookup table)
-- ============================================================
CREATE TABLE `ramos_atividade` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `codigo` VARCHAR(20) NOT NULL,
    `nome` VARCHAR(100) NOT NULL,
    `descricao` VARCHAR(255) NULL,
    `icone` VARCHAR(50) NULL DEFAULT 'fa-wrench',
    `ativo` TINYINT(1) NOT NULL DEFAULT 1,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_ramos_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ramos de atividade disponíveis no sistema';

-- ============================================================
-- 2. USUÁRIOS (pessoas físicas — controle por email)
-- ============================================================
CREATE TABLE `usuarios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome_completo` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `senha` VARCHAR(255) NOT NULL,
    `cpf` VARCHAR(14) NULL,
    `telefone` VARCHAR(20) NULL,
    `tipo_conta` ENUM('pessoal','negocio') NOT NULL DEFAULT 'pessoal',
    `tipo_documento` ENUM('cpf','cnpj') NOT NULL DEFAULT 'cpf',
    `perfil` ENUM('admin','usuario','admin_negocio') NOT NULL DEFAULT 'usuario',
    `status` ENUM('pendente','ativo','inativo','bloqueado') NOT NULL DEFAULT 'pendente',
    `token_validacao` VARCHAR(10) NULL,
    `token_expira_em` DATETIME NULL,
    `email_verificado` TINYINT(1) NOT NULL DEFAULT 0,
    `ultimo_login` DATETIME NULL,
    `ip_cadastro` VARCHAR(45) NULL,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `atualizado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_usuarios_email` (`email`),
    KEY `idx_usuarios_cpf` (`cpf`),
    KEY `idx_usuarios_status` (`status`),
    KEY `idx_usuarios_perfil` (`perfil`),
    KEY `idx_usuarios_tipo_conta` (`tipo_conta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Usuários do sistema — controle central por email';

-- ============================================================
-- 3. NEGÓCIOS / EMPRESAS (multi-tenant)
-- ============================================================
CREATE TABLE `negocios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario_id` INT(11) NOT NULL,
    `ramo_atividade_id` INT(11) NOT NULL,
    `razao_social` VARCHAR(255) NOT NULL,
    `nome_fantasia` VARCHAR(255) NULL,
    `cnpj` VARCHAR(18) NULL,
    `inscricao_estadual` VARCHAR(30) NULL,
    `inscricao_municipal` VARCHAR(30) NULL,
    `telefone` VARCHAR(20) NULL,
    `celular` VARCHAR(20) NULL,
    `email` VARCHAR(255) NULL,
    `site` VARCHAR(255) NULL,
    `cep` VARCHAR(9) NULL,
    `logradouro` VARCHAR(255) NULL,
    `numero` VARCHAR(20) NULL,
    `complemento` VARCHAR(100) NULL,
    `bairro` VARCHAR(100) NULL,
    `cidade` VARCHAR(100) NULL,
    `estado` CHAR(2) NULL,
    `logo_path` VARCHAR(500) NULL,
    `status` ENUM('pendente','ativo','inativo','suspenso') NOT NULL DEFAULT 'pendente',
    `plano` ENUM('gratuito','basico','profissional','enterprise') NOT NULL DEFAULT 'gratuito',
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `atualizado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `uk_negocios_cnpj` (`cnpj`),
    KEY `idx_negocios_usuario` (`usuario_id`),
    KEY `idx_negocios_ramo` (`ramo_atividade_id`),
    KEY `idx_negocios_status` (`status`),
    CONSTRAINT `fk_negocios_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_negocios_ramo` FOREIGN KEY (`ramo_atividade_id`) REFERENCES `ramos_atividade` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Empresas/negócios cadastrados no sistema';

-- ============================================================
-- 4. MEMBROS DO NEGÓCIO (usuários vinculados a um negócio)
-- ============================================================
CREATE TABLE `negocio_membros` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `negocio_id` INT(11) NOT NULL,
    `usuario_id` INT(11) NOT NULL,
    `cargo` VARCHAR(100) NULL,
    `permissao` ENUM('dono','admin','operador','visualizador') NOT NULL DEFAULT 'operador',
    `ativo` TINYINT(1) NOT NULL DEFAULT 1,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_membro_negocio_usuario` (`negocio_id`,`usuario_id`),
    KEY `idx_membros_negocio` (`negocio_id`),
    KEY `idx_membros_usuario` (`usuario_id`),
    CONSTRAINT `fk_membros_negocio` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_membros_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Membros vinculados a cada negócio';

-- ============================================================
-- 5. VEÍCULOS
-- ============================================================
CREATE TABLE `veiculos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario_id` INT(11) NOT NULL,
    `negocio_id` INT(11) NULL,
    `placa` VARCHAR(8) NOT NULL,
    `formato_placa` ENUM('antigo','mercosul') NOT NULL DEFAULT 'mercosul',
    `renavam` VARCHAR(11) NULL,
    `chassi` VARCHAR(17) NULL,
    `marca` VARCHAR(100) NULL,
    `modelo` VARCHAR(100) NULL,
    `versao` VARCHAR(100) NULL,
    `ano_fabricacao` YEAR NULL,
    `ano_modelo` YEAR NULL,
    `cor` VARCHAR(50) NULL,
    `combustivel` ENUM('gasolina','etanol','flex','diesel','gnv','eletrico','hibrido') NULL,
    `categoria` ENUM('passeio','comercial_leve','comercial_pesado','moto','caminhao','onibus','especial') NULL,
    `tipo_veiculo` ENUM('carro','moto','caminhao','van','onibus','outros') NOT NULL DEFAULT 'carro',
    `cilindrada` VARCHAR(10) NULL,
    `potencia` VARCHAR(20) NULL,
    `municipio_emplacamento` VARCHAR(100) NULL,
    `uf_emplacamento` CHAR(2) NULL,
    `situacao` VARCHAR(50) NULL,
    `restricoes` TEXT NULL,
    `foto_principal` VARCHAR(500) NULL,
    `foto_documento` VARCHAR(500) NULL,
    `observacoes` TEXT NULL,
    `dados_fipe` TEXT NULL,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `atualizado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_veiculos_placa` (`placa`),
    KEY `idx_veiculos_usuario` (`usuario_id`),
    KEY `idx_veiculos_negocio` (`negocio_id`),
    KEY `idx_veiculos_chassi` (`chassi`),
    CONSTRAINT `fk_veiculos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_veiculos_negocio` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Veículos cadastrados pelos usuários';

-- ============================================================
-- 6. FOTOS DE VEÍCULOS
-- ============================================================
CREATE TABLE `veiculo_fotos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `veiculo_id` INT(11) NOT NULL,
    `caminho` VARCHAR(500) NOT NULL,
    `tipo` ENUM('exterior','interior','documento','dano','outro') NOT NULL DEFAULT 'exterior',
    `descricao` VARCHAR(255) NULL,
    `principal` TINYINT(1) NOT NULL DEFAULT 0,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_fotos_veiculo` (`veiculo_id`),
    CONSTRAINT `fk_fotos_veiculo` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Fotos dos veículos';

-- ============================================================
-- 7. HISTÓRICO DE CONSULTAS DE PLACA
-- ============================================================
CREATE TABLE `consultas_placa` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario_id` INT(11) NULL,
    `placa` VARCHAR(8) NOT NULL,
    `fonte` VARCHAR(50) NOT NULL DEFAULT 'parallelum',
    `resposta_json` TEXT NULL,
    `sucesso` TINYINT(1) NOT NULL DEFAULT 0,
    `ip` VARCHAR(45) NULL,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_consultas_placa` (`placa`),
    KEY `idx_consultas_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Histórico de consultas de placa realizadas';

-- ============================================================
-- 8. LOGS DE AUDITORIA
-- ============================================================
CREATE TABLE `audit_logs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario_id` INT(11) NULL,
    `negocio_id` INT(11) NULL,
    `acao` VARCHAR(100) NOT NULL,
    `modulo` VARCHAR(50) NULL,
    `detalhes` TEXT NULL,
    `ip` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_audit_usuario` (`usuario_id`),
    KEY `idx_audit_negocio` (`negocio_id`),
    KEY `idx_audit_acao` (`acao`),
    KEY `idx_audit_modulo` (`modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Log de auditoria de todas as ações';

-- ============================================================
-- 9. TOKENS DE EMAIL (validação de cadastro)
-- ============================================================
CREATE TABLE `email_tokens` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario_id` INT(11) NOT NULL,
    `token` VARCHAR(10) NOT NULL,
    `tipo` ENUM('validacao','recuperacao_senha','convite') NOT NULL DEFAULT 'validacao',
    `usado` TINYINT(1) NOT NULL DEFAULT 0,
    `expira_em` DATETIME NOT NULL,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_tokens_usuario` (`usuario_id`),
    KEY `idx_tokens_token` (`token`),
    CONSTRAINT `fk_tokens_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tokens de validação enviados por email';

-- ============================================================
-- 10. SESSÕES DE ADMIN (acesso rápido sem logout)
-- ============================================================
CREATE TABLE `admin_sessoes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `admin_id` INT(11) NOT NULL,
    `usuario_impersonado_id` INT(11) NULL,
    `negocio_impersonado_id` INT(11) NULL,
    `tipo` ENUM('pessoa','negocio') NOT NULL DEFAULT 'pessoa',
    `ativo` TINYINT(1) NOT NULL DEFAULT 1,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_admin_sessoes_admin` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Sessões de impersonação do administrador';

-- ============================================================
-- SEEDS — RAMOS DE ATIVIDADE
-- ============================================================
INSERT INTO `ramos_atividade` (`codigo`, `nome`, `descricao`, `icone`) VALUES
('OFICINA_MEC',   'Oficina Mecânica',           'Manutenção e reparos mecânicos em geral',                  'fa-wrench'),
('OFICINA_ELE',   'Oficina Elétrica',            'Serviços elétricos automotivos',                           'fa-bolt'),
('PINTURA',       'Pintura Automotiva',           'Funilaria, pintura e acabamento',                          'fa-paint-brush'),
('LAVA_JATO',     'Lava Jato',                   'Lavagem e higienização de veículos',                       'fa-tint'),
('BORRACHARIA',   'Borracharia',                 'Serviços de pneus e rodas',                                 'fa-circle'),
('VIDRACARIA',    'Vidraçaria Automotiva',        'Troca e reparo de vidros automotivos',                     'fa-window-maximize'),
('SOM_ACESS',     'Som e Acessórios',             'Instalação de som, alarmes e acessórios',                  'fa-music'),
('ESTETIC',       'Estética Automotiva',          'Polimento, cristalização e detailing',                     'fa-star'),
('FUNILARIA',     'Funilaria',                   'Reparos de lataria e funilaria',                           'fa-car'),
('AR_COND',       'Ar Condicionado',              'Manutenção de ar condicionado automotivo',                 'fa-snowflake-o'),
('CAMBIO_SUSP',   'Câmbio e Suspensão',           'Especializada em câmbio e suspensão',                      'fa-cogs'),
('INJECAO',       'Injeção Eletrônica',           'Diagnóstico e reparo de injeção eletrônica',               'fa-microchip'),
('ALINHAMENTO',   'Alinhamento e Balanceamento',  'Serviços de alinhamento e balanceamento',                  'fa-balance-scale'),
('GUINCHO',       'Guincho e Reboque',            'Serviços de guincho e reboque',                            'fa-truck'),
('DESPACHANTE',   'Despachante Automotivo',       'Transferência, licenciamento e documentação',              'fa-file-text'),
('SEGURADORA',    'Seguradora / Corretora',       'Seguros e proteção veicular',                              'fa-shield'),
('CONCESSIONARIA','Concessionária',               'Venda de veículos novos',                                  'fa-building'),
('MULTIMARCAS',   'Multimarcas',                  'Venda de veículos usados',                                 'fa-car'),
('LOCADORA',      'Locadora de Veículos',         'Aluguel de veículos',                                      'fa-key'),
('PECAS',         'Autopeças',                    'Venda de peças e acessórios',                              'fa-puzzle-piece'),
('PATIO',         'Pátio / Estacionamento',       'Estacionamento e guarda de veículos',                      'fa-parking'),
('VISTORIA',      'Vistoria Veicular',            'Laudo e vistoria de veículos',                             'fa-search'),
('TRANSPORTE',    'Transportadora',               'Transporte de cargas e passageiros',                       'fa-truck'),
('MOTOTAXI',      'Mototáxi / Delivery',          'Serviços de mototáxi e entrega',                           'fa-motorcycle'),
('OUTROS',        'Outros',                       'Outros ramos do setor automotivo',                         'fa-ellipsis-h');

-- ============================================================
-- SEEDS — USUÁRIO ADMINISTRADOR DO SISTEMA
-- Login: admin@appauto.com.br | Senha: Admin259087@
-- ============================================================
INSERT INTO `usuarios` (
    `nome_completo`, `email`, `senha`, `cpf`, `telefone`,
    `tipo_conta`, `tipo_documento`, `perfil`, `status`, `email_verificado`
) VALUES (
    'Administrador AppAuto',
    'admin@appauto.com.br',
    '$2y$10$XcTCDWrAH9kgCHqm54n0xO7WQDizNzEjBu.Yd8dXA7mkq4xVMOG8O',
    NULL,
    NULL,
    'pessoal',
    'cpf',
    'admin',
    'ativo',
    1
);

-- ============================================================
-- FIM DO SCHEMA APPAUTO v2.0.0
-- ============================================================
