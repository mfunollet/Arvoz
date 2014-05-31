-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Abr 10, 2012 as 05:53 AM
-- Versão do Servidor: 5.5.8
-- Versão do PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Banco de Dados: `arvoz`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `key_practice`
--

CREATE TABLE IF NOT EXISTS `key_practice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `key_practice`
--

INSERT INTO `key_practice` (`id`, `name`, `create_date`, `update_date`) VALUES
(-1, 'Não Implantada', '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(1, 'Em Implantação', '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(2, 'Inicial', '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(3, 'Definida', '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(4, 'Estabelecida', '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(5, 'Sistematizada', '2012-02-10 00:00:00', '2012-02-10 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `key_practice_has_maturity_level`
--

CREATE TABLE IF NOT EXISTS `key_practice_has_maturity_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_practice_id` int(11) NOT NULL,
  `maturity_level_id` int(11) NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_maturity_level` (`maturity_level_id`),
  KEY `fk_key_practice` (`key_practice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Extraindo dados da tabela `key_practice_has_maturity_level`
--

INSERT INTO `key_practice_has_maturity_level` (`id`, `key_practice_id`, `maturity_level_id`, `create_date`, `update_date`) VALUES
(1, -1, 1, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(2, -1, 2, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(3, -1, 3, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(4, -1, 4, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(5, 1, 1, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(6, 1, 2, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(7, 1, 3, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(8, 1, 4, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(9, 2, 1, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(10, 3, 1, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(11, 3, 2, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(12, 4, 1, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(13, 4, 2, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(14, 4, 3, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(15, 5, 1, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(16, 5, 2, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(17, 5, 3, '2012-02-29 00:00:00', '2012-02-29 00:00:00'),
(18, 5, 4, '2012-02-29 00:00:00', '2012-02-29 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `key_process`
--

CREATE TABLE IF NOT EXISTS `key_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `maturity_level_id` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pk_key_process_maturity_level` (`maturity_level_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Extraindo dados da tabela `key_process`
--

INSERT INTO `key_process` (`id`, `name`, `maturity_level_id`, `create_time`, `update_time`) VALUES
(1, 'Sistema de Sensibilização e Prospecção.', 1, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(2, 'Sistema de Seleção', 1, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(3, 'Sistema de Planejamento', 1, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(4, 'Sistema de Qualificação', 1, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(5, 'Sistema de Assessoria e Consultoria', 1, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(6, 'Sistema de Acompanhamento, Orientação e Avaliação', 1, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(7, 'Sistema de Apoio à Graduação e Projeto Futuros', 1, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(8, 'Sistema de Gerenciamento Básico', 1, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(9, 'Sistema de Avaliação e Certificação', 2, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(10, 'Sistema de Geração de Ideias', 2, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(11, 'Sistema de Gestão Estratégica', 2, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(12, 'Sistema de Serviços a Empreendimentos', 2, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(13, 'Sistema de Apoio Ampliado aos Empreendimentos', 3, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(14, 'Sistema de Monitoramento do Desempenho da Incubadora', 3, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(15, 'Sistema de Participação no Desenvolvimento Regional Sustentável', 3, '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(16, 'Sistema de Melhoria Contínua', 4, '2012-02-10 00:00:00', '2012-02-10 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `maturity_level`
--

CREATE TABLE IF NOT EXISTS `maturity_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `maturity_level`
--

INSERT INTO `maturity_level` (`id`, `name`, `create_time`, `update_time`) VALUES
(1, 'CERNE 1', '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(2, 'CERNE 2', '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(3, 'CERNE 3', '2012-02-10 00:00:00', '2012-02-10 00:00:00'),
(4, 'CERNE 4', '2012-02-10 00:00:00', '2012-02-10 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `process_document`
--

CREATE TABLE IF NOT EXISTS `process_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL DEFAULT '1',
  `key_process_id` int(11) NOT NULL,
  `key_practice_id` int(11) NOT NULL DEFAULT '-1',
  `version` decimal(10,2) NOT NULL,
  `approval_date` datetime DEFAULT NULL,
  `approver` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_document_key_process` (`key_process_id`),
  KEY `fk_document_key_practice` (`key_practice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Extraindo dados da tabela `process_document`
--


--
-- Gatilhos `process_document`
--
DROP TRIGGER IF EXISTS `after_process_document_insert`;
DELIMITER //
CREATE TRIGGER `after_process_document_insert` AFTER INSERT ON `process_document`
 FOR EACH ROW BEGIN
Insert into process_document_audit
Set action = 'insert',
process_document_id = NEW.id,
company_id = NEW.company_id,
key_process_id = NEW.key_process_id,
key_practice_id = NEW.key_practice_id,
version = NEW.version,
approver = NEW.approver,
approval_date = NEW.approval_date,
file_path = NEW.file_path,
create_date = NOW(); END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `before_process_document_update`;
DELIMITER //
CREATE TRIGGER `before_process_document_update` BEFORE UPDATE ON `process_document`
 FOR EACH ROW BEGIN
Insert into process_document_audit
Set action = 'update',
process_document_id = OLD.id,
company_id = NEW.company_id,
key_process_id = NEW.key_process_id,
key_practice_id = NEW.key_practice_id,
version = NEW.version,
approver = NEW.approver,
approval_date = NEW.approval_date,
create_date = NOW(); END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `process_document_audit`
--

CREATE TABLE IF NOT EXISTS `process_document_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_document_id` int(11) NOT NULL,
  `key_practice_id` int(11) DEFAULT NULL,
  `version` decimal(10,2) DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `approver` varchar(250) DEFAULT NULL,
  `file_path` varchar(250) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `key_process_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Extraindo dados da tabela `process_document_audit`
--


--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `key_practice_has_maturity_level`
--
ALTER TABLE `key_practice_has_maturity_level`
  ADD CONSTRAINT `fk_key_practice` FOREIGN KEY (`key_practice_id`) REFERENCES `key_practice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_maturity_level` FOREIGN KEY (`maturity_level_id`) REFERENCES `maturity_level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `key_process`
--
ALTER TABLE `key_process`
  ADD CONSTRAINT `pk_key_process_maturity_level` FOREIGN KEY (`maturity_level_id`) REFERENCES `maturity_level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `process_document`
--
ALTER TABLE `process_document`
  ADD CONSTRAINT `fk_document_key_practice` FOREIGN KEY (`key_practice_id`) REFERENCES `key_practice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
