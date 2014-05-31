-- phpMyAdmin SQL Dump
-- version 3.4.9deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2012 at 06:11 PM
-- Server version: 5.1.58
-- PHP Version: 5.3.8-1+b1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `arvoz_01`
--
CREATE DATABASE `arvoz_01` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `arvoz_01`;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_role_has_p_p1` (`p_id`),
  KEY `fk_role_has_p_role1` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `step_id` int(11) NOT NULL,
  `responsible_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` int(11) NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_activity_step1` (`step_id`),
  KEY `fk_activity_p_and_p1` (`responsible_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `step_id`, `responsible_id`, `name`, `description`, `status`, `create_time`, `update_time`) VALUES
(3, 12, 3, 'Atividade 1', 'descrição atividade 1', 2, '2012-02-06 18:53:46', '2012-02-09 15:49:53'),
(4, 12, 3, 'outra atividade', 'descrição', 2, '2012-02-09 15:41:45', '2012-02-09 15:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `address3` varchar(255) DEFAULT NULL,
  `zipcode` varchar(8) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_address_p1` (`p_id`),
  KEY `fk_address_city1` (`city_id`),
  KEY `fk_address_state1` (`state_id`),
  KEY `fk_address_country1` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `addresses_view`
--
CREATE TABLE IF NOT EXISTS `addresses_view` (
`id` int(11)
,`p_id` int(11)
,`country_id` int(11)
,`state_id` int(11)
,`city_id` int(11)
,`country_acronym` varchar(45)
,`country` varchar(45)
,`state_acronym` varchar(45)
,`state` varchar(45)
,`city` varchar(45)
,`address1` varchar(255)
,`address2` varchar(255)
,`address3` varchar(255)
,`zipcode` varchar(8)
);
-- --------------------------------------------------------

--
-- Table structure for table `c_meta`
--

CREATE TABLE IF NOT EXISTS `c_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `social_reason` varchar(255) DEFAULT NULL,
  `entity_manager_link` text,
  `manage_incubator` varchar(255) DEFAULT NULL,
  `time_dedication` varchar(45) DEFAULT NULL,
  `social_bylaws` varchar(45) DEFAULT NULL,
  `internal_regulation` varchar(255) DEFAULT NULL,
  `attach_1` varchar(255) DEFAULT NULL,
  `attach_2` varchar(255) DEFAULT NULL,
  `attach_3` varchar(255) DEFAULT NULL,
  `memorial_descriptive` varchar(255) DEFAULT NULL,
  `description_service_offered` text,
  `logo` varchar(255) DEFAULT NULL,
  `institution_material` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_c_meta_company1` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `c_meta`
--

INSERT INTO `c_meta` (`id`, `social_reason`, `entity_manager_link`, `manage_incubator`, `time_dedication`, `social_bylaws`, `internal_regulation`, `attach_1`, `attach_2`, `attach_3`, `memorial_descriptive`, `description_service_offered`, `logo`, `institution_material`, `create_time`, `update_time`, `company_id`) VALUES
(1, 'asdasd', '2asdasd', '3asdasd', '4asdasd', '5asdasd', '6asdasd', NULL, NULL, NULL, '7asdasd', '8asdasd', NULL, '9asdasd', '2012-02-15 18:49:23', NULL, NULL),
(2, 'asdasd', '2asdasd', '3asdasd', '4asdasd', '5asdasd', '6asdasd', NULL, NULL, NULL, '7asdasd', '8asdasd', NULL, '9asdasd', '2012-02-15 18:50:26', '2012-02-15 18:50:26', 6),
(3, 'asdasd', '2asdasd', '3asdasd', '4asdasd', '5asdasd', '6asdasd', NULL, NULL, NULL, '7asdasd', '8asdasd', NULL, '9asdasd', '2012-02-15 18:53:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('2da019988280be1989af01ebb4a66f4f', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:10.0) Gecko/20100101 Firefox/10.0', 1329336514, 'a:4:{s:9:"user_data";s:0:"";s:9:"user_lang";s:5:"pt-br";s:9:"person_id";s:1:"1";s:4:"p_id";s:1:"1";}'),
('5c13e812a13b783417859f9e9097914d', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:10.0.1) Gecko/20100101 Firefox/10.0.1', 1329331826, 'a:4:{s:9:"user_data";s:0:"";s:9:"user_lang";s:5:"pt-br";s:9:"person_id";s:1:"1";s:4:"p_id";s:1:"1";}'),
('e67ad9bd7b24effcc31911da708f3aa7', '0.0.0.0', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:10.0.1) Gecko/20100101 Firefox/10.0.1', 1329333441, 'a:4:{s:9:"user_data";s:0:"";s:9:"user_lang";s:5:"pt-br";s:9:"person_id";s:1:"1";s:4:"p_id";s:1:"1";}');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_city_state1` (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `companies_view`
--
CREATE TABLE IF NOT EXISTS `companies_view` (
`id` int(11)
,`p_id` int(11)
,`name` varchar(45)
,`cnpj` varchar(14)
,`username` varchar(45)
,`primary_email` varchar(45)
,`birthday` date
,`image` varchar(255)
,`additional_information` text
,`create_time` datetime
,`update_time` datetime
,`pmeta_id` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `type` varchar(45) NOT NULL DEFAULT 'enterprise',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj_UNIQUE` (`cnpj`),
  KEY `fk_company_p1` (`p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `p_id`, `name`, `cnpj`, `create_time`, `update_time`, `type`) VALUES
(1, 6, 'Lacoste', '01333673000126', '2010-11-05 15:22:44', '2012-02-14 20:42:47', 'enterprise'),
(2, 7, 'Nike', '20373996000198', '2010-10-21 16:22:00', '2010-11-22 15:00:55', 'enterprise'),
(3, 8, 'Borelli', '54060811000107', '2010-11-14 15:22:11', '2011-05-12 19:33:33', 'enterprise'),
(4, 9, 'Aviator', '28423756000162', '2009-02-19 18:00:00', '2009-03-16 15:00:00', 'enterprise'),
(5, 10, 'Boticario', '16888630000184', '2008-03-15 16:00:00', '2011-02-18 19:00:00', 'enterprise'),
(6, 12, 'Empresa do Juca', '63592810000102', '2012-02-06 14:29:07', '2012-02-06 14:29:07', 'enterprise'),
(8, 15, 'Descartes', '84278828000104', '2012-02-09 17:29:48', '2012-02-09 17:29:48', 'enterprise'),
(30, 2, 'Name', 'Amor', '2012-02-15 18:15:38', NULL, 'Amor'),
(33, NULL, 'teste', '23456789', '2012-02-15 18:49:23', NULL, '1'),
(35, NULL, 'asdqwe', '123456789', '2012-02-15 19:55:46', NULL, '2');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `acronym` varchar(45) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `type_email_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_email_p1` (`p_id`),
  KEY `fk_email_type_email1` (`type_email_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`id`, `p_id`, `type_email_id`, `email`, `create_time`, `update_time`) VALUES
(1, 10, 2, 'tese@example.com', '2012-02-10 11:47:01', '2012-02-10 11:47:01');

-- --------------------------------------------------------

--
-- Stand-in structure for view `emails_view`
--
CREATE TABLE IF NOT EXISTS `emails_view` (
`type_email_name` varchar(45)
,`description` text
,`id` int(11)
,`p_id` int(11)
,`type_email_id` int(11)
,`email` varchar(255)
,`create_time` datetime
,`update_time` datetime
);
-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `step_id` int(11) NOT NULL,
  `selection_process_has_company_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_date_sent` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_event_step1` (`step_id`),
  KEY `fk_event_selection_process_has_company1` (`selection_process_has_company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_event`
--

CREATE TABLE IF NOT EXISTS `feedback_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `score` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_feedback_event_event1` (`event_id`),
  KEY `fk_feedback_event_person1` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `incubator_has_company`
--

CREATE TABLE IF NOT EXISTS `incubator_has_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `incubator_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_company_has_company_company2` (`incubator_id`),
  KEY `fk_company_has_company_company1` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `p`
--

CREATE TABLE IF NOT EXISTS `p` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL COMMENT 'slug entity for use in the url fr example',
  `primary_email` varchar(45) NOT NULL,
  `birthday` date NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `additional_information` text,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `primary_email_UNIQUE` (`primary_email`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `p`
--

INSERT INTO `p` (`id`, `username`, `primary_email`, `birthday`, `image`, `additional_information`, `create_time`, `update_time`) VALUES
(1, 'superadmin', 'superadmin@arvoz.com', '1970-12-30', '', 'The Super Administrator', '2011-11-28 16:32:56', '2012-02-10 13:09:00'),
(2, 'melhergui', 'melhergui@gmail.com', '1972-11-16', NULL, 'Guilherme Melherga', '2011-11-28 16:34:56', '2012-02-13 15:40:31'),
(3, 'rafaunique', 'rafaunique@gmail.com', '1987-01-16', NULL, 'Rafa Unique', '2011-11-28 16:35:56', '2011-11-28 16:35:56'),
(4, 'mai', 'maianedassis@gmail.com', '1991-02-16', NULL, 'Mai Maiane', '2011-11-28 16:37:56', '2011-11-28 16:37:56'),
(5, 'amor', 'amor@arqabs.com', '2000-01-01', NULL, 'Amor Amor', '2000-01-01 00:00:00', '2000-01-01 00:00:01'),
(6, 'patati', 'lacoste@gmail.com', '2003-06-19', NULL, 'Patati palhaço', '2000-01-06 00:00:02', '2012-02-10 13:07:09'),
(7, 'patata', 'nike@yahoo.com', '1999-09-09', NULL, 'Patata palhaço', '2010-02-08 12:22:08', '2010-02-09 13:00:57'),
(8, 'borelli', 'borelli@uol.com', '2008-01-06', NULL, 'El Borelli', '2008-09-14 14:26:33', '2008-10-05 13:00:32'),
(9, 'aviator', 'Aviator@aol.com', '2009-03-03', NULL, 'Aviator da Gol', '2009-12-06 16:14:46', '2009-12-16 15:00:00'),
(10, 'boticario', 'boticario@gmail.com', '2010-02-06', NULL, 'Loja Boticario', '2010-10-12 17:12:35', '2010-12-18 18:22:33'),
(11, 'juca', 'juca@example.com', '2012-12-12', NULL, NULL, NULL, NULL),
(12, 'empresa_juca', 'emp_juca@example.com', '2012-12-12', NULL, NULL, NULL, NULL),
(13, 'berenger', 'francis@example.com', '2012-12-12', NULL, NULL, NULL, NULL),
(15, 'descartes', 'descartes@example.com', '2012-12-12', NULL, NULL, NULL, NULL),
(16, 'pri', 'pri@example.com', '2012-12-12', NULL, NULL, NULL, NULL),
(17, 'aaaaaa', 'a@example.com', '2012-12-12', NULL, NULL, NULL, NULL),
(18, 'teste', 't@t.com', '0000-00-00', NULL, NULL, NULL, NULL),
(19, '', 'aaa@aa.com', '2012-02-22', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `p_and_p`
--

CREATE TABLE IF NOT EXISTS `p_and_p` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `p_id1` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_p_has_p_p2` (`p_id1`),
  KEY `fk_p_has_p_p1` (`p_id`),
  KEY `fk_p_and_p_role1` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `p_and_p`
--

INSERT INTO `p_and_p` (`id`, `p_id`, `p_id1`, `start_date`, `end_date`, `role_id`, `status`, `create_time`, `update_time`) VALUES
(1, 1, 1, '2011-12-14', NULL, 1, 0, '2011-12-14 12:40:00', NULL),
(2, 1, 10, '2011-12-14', '0000-00-00', 3, 0, NULL, NULL),
(3, 2, 10, '2012-02-06', NULL, 5, 0, '2012-02-06 14:12:32', '2012-02-06 14:12:32'),
(4, 5, 10, '2012-02-06', NULL, 5, 0, '2012-02-06 14:12:32', '2012-02-06 14:12:32'),
(5, 4, 10, '2012-02-06', NULL, 5, 0, '2012-02-06 14:12:32', '2012-02-06 14:12:32'),
(6, 3, 10, '2012-02-06', NULL, 4, 0, '2012-02-06 14:12:55', '2012-02-06 14:12:55'),
(7, 11, 12, '2012-02-06', NULL, 3, 0, '2012-02-06 14:29:07', '2012-02-06 14:29:07'),
(8, 11, 10, '2012-02-06', NULL, 6, 0, '2012-02-06 14:34:38', '2012-02-06 14:34:38'),
(9, 13, 15, '2012-02-09', NULL, 3, 0, '2012-02-09 17:29:48', '2012-02-09 17:29:48'),
(10, 16, 15, '2012-02-09', NULL, 7, 0, '2012-02-09 17:54:18', '2012-02-09 17:54:18'),
(11, 17, 15, '2012-02-09', NULL, 7, 0, '2012-02-09 17:59:02', '2012-02-09 17:59:02'),
(12, 1, 18, '2012-02-10', NULL, 3, 0, '2012-02-10 18:44:59', '2012-02-10 18:44:59');

-- --------------------------------------------------------

--
-- Stand-in structure for view `p_and_ps_view`
--
CREATE TABLE IF NOT EXISTS `p_and_ps_view` (
`id` int(11)
,`p_id_username` varchar(45)
,`p_id` int(11)
,`p_id1_username` varchar(45)
,`p_id1` int(11)
,`role_name` varchar(45)
,`role_id` int(11)
,`start_date` date
,`end_date` date
,`create_time` datetime
,`update_time` datetime
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `people_view`
--
CREATE TABLE IF NOT EXISTS `people_view` (
`id` int(11)
,`p_id` int(11)
,`first_name` varchar(45)
,`last_name` varchar(45)
,`cpf` varchar(11)
,`password` varchar(45)
,`username` varchar(45)
,`primary_email` varchar(45)
,`birthday` date
,`image` varchar(255)
,`additional_information` text
,`activation_code` varchar(40)
,`forgotten_password_code` varchar(40)
,`remember_code` varchar(40)
,`gender` char(1)
,`create_time` datetime
,`update_time` datetime
,`pmeta_id` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `password` varchar(45) NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  KEY `fk_person_p1` (`p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `p_id`, `cpf`, `password`, `first_name`, `last_name`, `gender`, `activation_code`, `forgotten_password_code`, `remember_code`, `create_time`, `update_time`) VALUES
(1, 1, '11111111111', '9fe6be740b2926ab663b637a77def4bca0d9248f', 'Super', 'Admin', 'M', 'superadmin', 'superadmin2to2arvoz', NULL, '2011-12-12 12:01:01', '2012-02-10 13:09:00'),
(2, 2, '42470871506', '9fe6be740b2926ab663b637a77def4bca0d9248f', 'Guilherme', 'Sampaio', 'M', 'paz', 'nome cachorro', NULL, '2011-12-12 12:01:01', '2012-02-13 15:40:31'),
(3, 3, '77411762261', '9fe6be740b2926ab663b637a77def4bca0d9248f', 'Rafael', 'Cavalcante', 'M', 'paciencia', 'idade', NULL, '2011-12-12 12:01:01', '2011-12-12 12:01:01'),
(4, 4, '04227438527', '9fe6be740b2926ab663b637a77def4bca0d9248f', 'Maiane', 'de Assis', 'F', 'amor', 'nascimento', NULL, '2011-12-12 12:01:01', '2011-12-12 12:01:01'),
(5, 5, '81141886421', '9fe6be740b2926ab663b637a77def4bca0d9248f', 'amor', 'pra sempre', 'M', 'paixão', 'lembrança', NULL, '2011-12-12 12:01:01', '2011-12-12 12:01:01'),
(6, 11, '91263744125', '4c543a46aed4a4b0c45165f03db93cf729b1e549', 'Juca', 'Juca', 'M', NULL, NULL, NULL, '2012-02-06 14:21:14', '2012-02-06 14:21:14'),
(7, 13, '27182147084', '4c543a46aed4a4b0c45165f03db93cf729b1e549', 'Francis', 'Berenger', 'M', NULL, NULL, NULL, '2012-02-09 16:59:33', '2012-02-09 16:59:33'),
(8, 16, '15484437350', '4c543a46aed4a4b0c45165f03db93cf729b1e549', 'Priscila', 'pri', 'M', NULL, NULL, NULL, '2012-02-09 17:44:15', '2012-02-09 17:44:15'),
(9, 17, '65832468643', '4c543a46aed4a4b0c45165f03db93cf729b1e549', 'aaaaaa', 'aaaaaa', 'F', NULL, NULL, NULL, '2012-02-09 17:56:16', '2012-02-09 17:56:16'),
(10, 19, '05691688723', '4c543a46aed4a4b0c45165f03db93cf729b1e549', 'aaa', 'aaa', 'M', NULL, NULL, NULL, '2012-02-10 18:56:35', '2012-02-10 18:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `person_login`
--

CREATE TABLE IF NOT EXISTS `person_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(16) NOT NULL,
  `date` datetime NOT NULL,
  `person_id` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_person_login_person1` (`person_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `person_login`
--

INSERT INTO `person_login` (`id`, `ip_address`, `date`, `person_id`, `create_time`, `update_time`) VALUES
(1, '127.0.0.1', '2012-02-06 14:11:55', 1, '2012-02-06 14:11:55', '2012-02-06 14:11:55'),
(2, '127.0.0.1', '2012-02-06 14:21:44', 6, '2012-02-06 14:21:44', '2012-02-06 14:21:44'),
(3, '127.0.0.1', '2012-02-06 14:23:30', 1, '2012-02-06 14:23:30', '2012-02-06 14:23:30'),
(4, '127.0.0.1', '2012-02-06 15:14:53', 1, '2012-02-06 15:14:53', '2012-02-06 15:14:53'),
(5, '0.0.0.0', '2012-02-07 10:14:07', 1, '2012-02-07 10:14:07', '2012-02-07 10:14:07'),
(6, '0.0.0.0', '2012-02-07 16:56:13', 1, '2012-02-07 16:56:13', '2012-02-07 16:56:13'),
(7, '127.0.0.1', '2012-02-08 10:25:55', 1, '2012-02-08 10:25:55', '2012-02-08 10:25:55'),
(8, '0.0.0.0', '2012-02-09 11:12:58', 1, '2012-02-09 11:12:58', '2012-02-09 11:12:58'),
(9, '0.0.0.0', '2012-02-09 17:01:39', 7, '2012-02-09 17:01:39', '2012-02-09 17:01:39'),
(10, '0.0.0.0', '2012-02-09 17:03:38', 1, '2012-02-09 17:03:38', '2012-02-09 17:03:38'),
(11, '0.0.0.0', '2012-02-09 17:57:19', 9, '2012-02-09 17:57:19', '2012-02-09 17:57:19'),
(12, '0.0.0.0', '2012-02-09 17:58:22', 9, '2012-02-09 17:58:22', '2012-02-09 17:58:22'),
(13, '127.0.0.1', '2012-02-10 09:17:18', 1, '2012-02-10 09:17:18', '2012-02-10 09:17:18'),
(14, '127.0.0.1', '2012-02-10 14:34:04', 1, '2012-02-10 14:34:04', '2012-02-10 14:34:04'),
(15, '127.0.0.1', '2012-02-10 16:35:56', 1, '2012-02-10 16:35:56', '2012-02-10 16:35:56'),
(16, '127.0.0.1', '2012-02-10 17:01:37', 1, '2012-02-10 17:01:37', '2012-02-10 17:01:37'),
(17, '127.0.0.1', '2012-02-10 17:54:03', 1, '2012-02-10 17:54:03', '2012-02-10 17:54:03'),
(18, '127.0.0.1', '2012-02-10 18:44:52', 1, '2012-02-10 18:44:52', '2012-02-10 18:44:52'),
(19, '127.0.0.1', '2012-02-10 18:57:07', 10, '2012-02-10 18:57:07', '2012-02-10 18:57:07'),
(20, '127.0.0.1', '2012-02-13 14:00:02', 1, '2012-02-13 14:00:02', '2012-02-13 14:00:02'),
(21, '127.0.0.1', '2012-02-13 14:32:38', 1, '2012-02-13 14:32:38', '2012-02-13 14:32:38'),
(22, '127.0.0.1', '2012-02-14 14:51:39', 1, '2012-02-14 14:51:39', '2012-02-14 14:51:39'),
(23, '127.0.0.1', '2012-02-15 13:55:43', 1, '2012-02-15 13:55:43', '2012-02-15 13:55:43'),
(24, '0.0.0.0', '2012-02-15 15:59:12', 1, '2012-02-15 15:59:12', '2012-02-15 15:59:12'),
(25, '127.0.0.1', '2012-02-15 16:05:36', 1, '2012-02-15 16:05:36', '2012-02-15 16:05:36'),
(26, '127.0.0.1', '2012-02-15 16:27:30', 1, '2012-02-15 16:27:30', '2012-02-15 16:27:30');

-- --------------------------------------------------------

--
-- Table structure for table `phone`
--

CREATE TABLE IF NOT EXISTS `phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `type_phone_id` int(11) NOT NULL DEFAULT '2',
  `number` varchar(20) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_phone_p1` (`p_id`),
  KEY `fk_phone_type_phone1` (`type_phone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `phone`
--

INSERT INTO `phone` (`id`, `p_id`, `type_phone_id`, `number`, `create_time`, `update_time`) VALUES
(1, 1, 1, '11234556', NULL, NULL),
(2, 2, 1, '55286331', NULL, NULL),
(3, 3, 1, '77896552', NULL, NULL),
(4, 4, 1, '95236547', NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `phones_view`
--
CREATE TABLE IF NOT EXISTS `phones_view` (
`id` int(11)
,`p_id` int(11)
,`type_phone_id` int(11)
,`type_phone_name` varchar(45)
,`description` text
,`number` varchar(20)
,`create_time` datetime
,`update_time` datetime
);
-- --------------------------------------------------------

--
-- Table structure for table `pmeta`
--

CREATE TABLE IF NOT EXISTS `pmeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pmeta_p1` (`p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(45) NOT NULL,
  `method` varchar(45) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=132 ;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`id`, `controller`, `method`, `create_time`, `update_time`) VALUES
(1, 'crud_controller', 'index', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(2, 'crud_controller', 'show', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(3, 'crud_controller', 'view', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(4, 'crud_controller', 'create', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(5, 'crud_controller', 'edit', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(6, 'crud_controller', 'delete', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(7, 'address', 'edit_profile', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(8, 'address', 'ajax_load_edit', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(9, 'address', 'index', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(10, 'address', 'show', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(11, 'address', 'view', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(12, 'address', 'create', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(13, 'address', 'edit', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(14, 'address', 'delete', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(15, 'resource', 'index', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(16, 'resource', 'show', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(17, 'resource', 'view', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(18, 'resource', 'create', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(19, 'resource', 'edit', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(20, 'resource', 'delete', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(21, 'role', 'index', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(22, 'role', 'show', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(23, 'role', 'view', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(24, 'role', 'create', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(25, 'role', 'edit', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(26, 'role', 'delete', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(27, 'type_phone', 'index', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(28, 'type_phone', 'show', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(29, 'type_phone', 'view', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(30, 'type_phone', 'create', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(31, 'type_phone', 'edit', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(32, 'type_phone', 'delete', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(33, 'email', 'edit_profile', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(34, 'email', 'ajax_load_edit', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(35, 'email', 'index', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(36, 'email', 'show', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(37, 'email', 'view', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(38, 'email', 'create', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(39, 'email', 'edit', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(40, 'email', 'delete', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(41, 'auth', 'login', '2012-02-06 14:25:03', '2012-02-06 14:25:03'),
(42, 'auth', 'logout', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(43, 'auth', 'forgotten_password', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(44, 'site', 'edit_profile', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(45, 'site', 'ajax_load_edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(46, 'site', 'index', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(47, 'site', 'show', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(48, 'site', 'view', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(49, 'site', 'create', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(50, 'site', 'edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(51, 'site', 'delete', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(52, 'person', 'create', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(53, 'person', 'create_success', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(54, 'person', 'invite_to_company', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(55, 'person', 'auto_complete', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(56, 'person', 'admin_sites', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(57, 'person', 'add_site', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(58, 'person', 'admin_emails', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(59, 'person', 'add_email', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(60, 'person', 'add_phone', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(61, 'person', 'admin_phones', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(62, 'person', 'add_address', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(63, 'person', 'admin_address', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(64, 'person', 'index', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(65, 'person', 'show', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(66, 'person', 'view', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(67, 'person', 'edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(68, 'person', 'delete', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(69, 'type_email', 'index', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(70, 'type_email', 'show', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(71, 'type_email', 'view', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(72, 'type_email', 'create', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(73, 'type_email', 'edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(74, 'type_email', 'delete', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(75, 'phone', 'edit_profile', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(76, 'phone', 'ajax_load_edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(77, 'phone', 'index', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(78, 'phone', 'show', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(79, 'phone', 'view', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(80, 'phone', 'create', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(81, 'phone', 'edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(82, 'phone', 'delete', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(83, 'p_and_p', 'index', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(84, 'p_and_p', 'show', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(85, 'p_and_p', 'view', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(86, 'p_and_p', 'create', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(87, 'p_and_p', 'edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(88, 'p_and_p', 'delete', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(89, 'role_has_resource', 'change_permissions', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(90, 'role_has_resource', 'save_permissions', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(91, 'role_has_resource', 'convert_permission_array', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(92, 'role_has_resource', 'convert_resources_array', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(93, 'role_has_resource', 'index', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(94, 'role_has_resource', 'show', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(95, 'role_has_resource', 'view', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(96, 'role_has_resource', 'create', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(97, 'role_has_resource', 'edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(98, 'role_has_resource', 'delete', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(99, 'access_error', 'access_denied', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(100, 'p', 'admin_sites', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(101, 'p', 'add_site', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(102, 'p', 'admin_emails', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(103, 'p', 'add_email', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(104, 'p', 'add_phone', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(105, 'p', 'admin_phones', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(106, 'p', 'add_address', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(107, 'p', 'admin_address', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(108, 'p', 'index', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(109, 'p', 'show', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(110, 'p', 'view', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(111, 'p', 'create', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(112, 'p', 'edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(113, 'p', 'delete', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(114, 'company', 'my_companies', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(115, 'company', 'use_as', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(116, 'company', 'admin_sites', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(117, 'company', 'add_site', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(118, 'company', 'admin_emails', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(119, 'company', 'add_email', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(120, 'company', 'add_phone', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(121, 'company', 'admin_phones', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(122, 'company', 'add_address', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(123, 'company', 'admin_address', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(124, 'company', 'index', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(125, 'company', 'show', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(126, 'company', 'view', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(127, 'company', 'create', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(128, 'company', 'edit', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(129, 'company', 'delete', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(130, 'welcome', 'index', '2012-02-06 14:25:04', '2012-02-06 14:25:04'),
(131, 'welcome', 'show', '2012-02-06 14:25:04', '2012-02-06 14:25:04');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`, `create_time`, `update_time`) VALUES
(1, 'superadministrator', 'Super Administrator of the system', '2011-12-09 16:56:00', '2011-12-09 16:56:00'),
(2, 'administrator', 'Administratator of Institution', '2011-12-09 16:56:00', '2011-12-09 16:56:00'),
(3, 'enterpreneurmanager', 'Administrator of Organization', '2011-12-09 16:56:00', '2011-12-09 16:56:00'),
(4, 'reviewer', 'Reviewer', NULL, NULL),
(5, 'evaluator', 'Evaluator', NULL, NULL),
(6, 'user', 'Empreendedor', '2012-02-06 14:33:53', '2012-02-06 14:33:53'),
(7, 'partner', 'descrição partner', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_resource`
--

CREATE TABLE IF NOT EXISTS `role_has_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `permission` tinyint(1) DEFAULT '1',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_role_has_resource_resource1` (`resource_id`),
  KEY `fk_role_has_resource_role1` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=132 ;

--
-- Dumping data for table `role_has_resource`
--

INSERT INTO `role_has_resource` (`id`, `role_id`, `resource_id`, `permission`, `create_time`, `update_time`) VALUES
(1, 3, 1, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(2, 3, 2, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(3, 3, 3, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(4, 3, 4, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(5, 3, 5, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(6, 3, 6, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(7, 3, 7, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(8, 3, 8, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(9, 3, 9, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(10, 3, 10, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(11, 3, 11, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(12, 3, 12, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(13, 3, 13, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(14, 3, 14, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(15, 3, 15, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(16, 3, 16, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(17, 3, 17, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(18, 3, 18, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(19, 3, 19, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(20, 3, 20, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(21, 3, 21, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(22, 3, 22, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(23, 3, 23, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(24, 3, 24, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(25, 3, 25, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(26, 3, 26, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(27, 3, 27, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(28, 3, 28, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(29, 3, 29, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(30, 3, 30, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(31, 3, 31, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(32, 3, 32, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(33, 3, 33, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(34, 3, 34, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(35, 3, 35, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(36, 3, 36, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(37, 3, 37, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(38, 3, 38, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(39, 3, 39, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(40, 3, 40, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(41, 3, 41, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(42, 3, 42, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(43, 3, 43, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(44, 3, 44, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(45, 3, 45, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(46, 3, 46, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(47, 3, 47, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(48, 3, 48, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(49, 3, 49, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(50, 3, 50, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(51, 3, 51, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(52, 3, 52, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(53, 3, 53, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(54, 3, 54, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(55, 3, 55, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(56, 3, 56, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(57, 3, 57, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(58, 3, 58, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(59, 3, 59, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(60, 3, 60, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(61, 3, 61, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(62, 3, 62, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(63, 3, 63, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(64, 3, 64, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(65, 3, 65, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(66, 3, 66, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(67, 3, 67, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(68, 3, 68, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(69, 3, 69, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(70, 3, 70, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(71, 3, 71, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(72, 3, 72, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(73, 3, 73, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(74, 3, 74, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(75, 3, 75, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(76, 3, 76, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(77, 3, 77, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(78, 3, 78, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(79, 3, 79, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(80, 3, 80, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(81, 3, 81, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(82, 3, 82, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(83, 3, 83, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(84, 3, 84, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(85, 3, 85, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(86, 3, 86, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(87, 3, 87, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(88, 3, 88, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(89, 3, 89, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(90, 3, 90, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(91, 3, 91, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(92, 3, 92, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(93, 3, 93, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(94, 3, 94, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(95, 3, 95, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(96, 3, 96, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(97, 3, 97, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(98, 3, 98, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(99, 3, 99, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(100, 3, 100, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(101, 3, 101, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(102, 3, 102, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(103, 3, 103, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(104, 3, 104, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(105, 3, 105, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(106, 3, 106, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(107, 3, 107, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(108, 3, 108, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(109, 3, 109, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(110, 3, 110, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(111, 3, 111, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(112, 3, 112, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(113, 3, 113, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(114, 3, 114, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(115, 3, 115, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(116, 3, 116, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(117, 3, 117, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(118, 3, 118, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(119, 3, 119, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(120, 3, 120, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(121, 3, 121, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(122, 3, 122, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(123, 3, 123, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(124, 3, 124, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(125, 3, 125, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(126, 3, 126, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(127, 3, 127, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(128, 3, 128, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(129, 3, 129, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(130, 3, 130, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06'),
(131, 3, 131, 1, '2012-02-06 14:32:06', '2012-02-06 14:32:06');

-- --------------------------------------------------------

--
-- Stand-in structure for view `role_has_resources_view`
--
CREATE TABLE IF NOT EXISTS `role_has_resources_view` (
`id` int(11)
,`role_id` int(11)
,`resource_id` int(11)
,`permission` tinyint(1)
,`role_name` varchar(45)
,`role_description` text
,`controller` varchar(45)
,`method` varchar(45)
,`create_time` datetime
,`update_time` datetime
);
-- --------------------------------------------------------

--
-- Table structure for table `selection_process`
--

CREATE TABLE IF NOT EXISTS `selection_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `start_date` date NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `type_selection_process` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_selection_process_company1` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `selection_process`
--

INSERT INTO `selection_process` (`id`, `company_id`, `name`, `description`, `start_date`, `file_name`, `active`, `type_selection_process`, `create_time`, `update_time`) VALUES
(10, 5, 'Oportunidade', 'Oportunidade empresarial', '2012-02-10', 'upload/5/edict/edital_Oportunidade_2012-02-06_15:34:10.pdf', 0, 0, '2012-02-06 15:34:11', '2012-02-06 15:34:11'),
(12, 5, 'Oportunidade', 'Oportunidade empresarial', '2012-02-10', 'upload/5/edict/edital_Oportunidade_2012-02-06_15:54:45.pdf', 0, 0, '2012-02-06 15:54:45', '2012-02-06 15:54:45'),
(13, 5, 'Genesis edital 1', 'decrição genesis edital', '2012-02-22', 'upload/5/edict/edital_Genesis edital 1_2012-02-10_12:22:39.pdf', 0, 0, '2012-02-10 12:22:39', '2012-02-10 12:47:26');

-- --------------------------------------------------------

--
-- Table structure for table `selection_process_has_company`
--

CREATE TABLE IF NOT EXISTS `selection_process_has_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selection_process_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `step_disqualification` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_selection_process_has_company_company1` (`company_id`),
  KEY `fk_selection_process_has_company_selection_process1` (`selection_process_id`),
  KEY `fk_selection_process_has_company_step1` (`step_disqualification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `selection_processes_view`
--
CREATE TABLE IF NOT EXISTS `selection_processes_view` (
`id` int(11)
,`company_id` int(11)
,`company_name` varchar(45)
,`name` varchar(255)
,`description` text
,`start_date` date
,`file_name` varchar(255)
,`active` tinyint(1)
,`status` tinyint(4)
,`selection_process_has_company_id` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `url` varchar(45) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_url_p1` (`p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`id`, `p_id`, `name`, `url`, `image`, `create_time`, `update_time`) VALUES
(1, 7, 'genesis', 'http://www.genesis.com.br', 'upload/site/genesis.png', '2012-02-10 13:44:22', '2012-02-10 13:44:22'),
(2, 1, 'teste', 'http://google.com.br', NULL, '2012-02-15 17:15:06', '2012-02-15 17:15:06'),
(3, 1, 'Terra', 'http://terra.com.br', NULL, '2012-02-15 17:15:25', '2012-02-15 17:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `acronym` varchar(45) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_state_country1` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `step`
--

CREATE TABLE IF NOT EXISTS `step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `step_type_id` int(11) NOT NULL,
  `selection_process_id` int(11) NOT NULL,
  `step_number` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `duration` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `evaluator_id` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_step_step_type1` (`step_type_id`),
  KEY `fk_step_selection_process1` (`selection_process_id`),
  KEY `fk_step_person1` (`evaluator_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `step`
--

INSERT INTO `step` (`id`, `step_type_id`, `selection_process_id`, `step_number`, `name`, `description`, `duration`, `status`, `evaluator_id`, `create_time`, `update_time`) VALUES
(12, 2, 12, 1, 'Analise', 'Descrição do processo de seleção', 11, 3, 5, '2012-02-06 15:54:45', '2012-02-14 15:39:32'),
(13, 1, 12, 2, 'etapa teste 2', 'descrição etapa teste 2', 4, 2, 4, '2012-02-07 13:25:33', '2012-02-14 15:39:32'),
(14, 3, 12, 3, 'etapa 3', 'descrição etapa 3', 3, 0, 2, '2012-02-07 14:40:45', '2012-02-14 15:39:32'),
(15, 2, 13, 1, 'Inscrição genesis 1', 'descrição da incrição', 6, 1, 4, '2012-02-10 12:22:39', '2012-02-10 12:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `step_type`
--

CREATE TABLE IF NOT EXISTS `step_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `has_document` tinyint(1) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `step_type`
--

INSERT INTO `step_type` (`id`, `name`, `has_document`, `create_time`, `update_time`) VALUES
(1, 'Apresentação', 0, '2012-02-06 15:39:18', '2012-02-06 15:39:18'),
(2, 'Documento', 1, '2012-02-06 15:42:02', '2012-02-06 15:42:02'),
(3, 'Entrevista', 0, '2012-02-06 15:42:09', '2012-02-06 15:42:09');

-- --------------------------------------------------------

--
-- Table structure for table `type_email`
--

CREATE TABLE IF NOT EXISTS `type_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `type_email`
--

INSERT INTO `type_email` (`id`, `name`, `description`, `create_time`, `update_time`) VALUES
(1, 'email pessoal', 'Descrição email pessoal', NULL, NULL),
(2, 'email do trabalho', 'Descrição email do trabalho', NULL, NULL),
(3, 'email do empreendimento', 'Descrição email do empreendimento', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `type_phone`
--

CREATE TABLE IF NOT EXISTS `type_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `type_phone`
--

INSERT INTO `type_phone` (`id`, `name`, `description`, `create_time`, `update_time`) VALUES
(1, 'residêncial', 'Descrição telefone residêncial', NULL, NULL),
(2, 'comercial', 'Descrição telefone comercial', NULL, NULL),
(3, 'celular', 'descrição do celular', NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure for view `addresses_view`
--
DROP TABLE IF EXISTS `addresses_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=``@`%` SQL SECURITY DEFINER VIEW `addresses_view` AS select `address`.`id` AS `id`,`address`.`p_id` AS `p_id`,`address`.`country_id` AS `country_id`,`address`.`state_id` AS `state_id`,`address`.`city_id` AS `city_id`,`country`.`acronym` AS `country_acronym`,`country`.`name` AS `country`,`state`.`acronym` AS `state_acronym`,`state`.`name` AS `state`,`city`.`name` AS `city`,`address`.`address1` AS `address1`,`address`.`address2` AS `address2`,`address`.`address3` AS `address3`,`address`.`zipcode` AS `zipcode` from (((`address` join `country` on((`address`.`country_id` = `country`.`id`))) join `state` on((`address`.`state_id` = `state`.`id`))) join `city` on((`address`.`city_id` = `city`.`id`)));

-- --------------------------------------------------------

--
-- Structure for view `companies_view`
--
DROP TABLE IF EXISTS `companies_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`getulio`@`%` SQL SECURITY DEFINER VIEW `companies_view` AS select `C`.`id` AS `id`,`P`.`id` AS `p_id`,`C`.`name` AS `name`,`C`.`cnpj` AS `cnpj`,`P`.`username` AS `username`,`P`.`primary_email` AS `primary_email`,`P`.`birthday` AS `birthday`,`P`.`image` AS `image`,`P`.`additional_information` AS `additional_information`,`P`.`create_time` AS `create_time`,`P`.`update_time` AS `update_time`,`PMETA`.`id` AS `pmeta_id` from ((`p` `P` join `company` `C` on((`P`.`id` = `C`.`p_id`))) left join `pmeta` `PMETA` on((`P`.`id` = `PMETA`.`p_id`)));

-- --------------------------------------------------------

--
-- Structure for view `emails_view`
--
DROP TABLE IF EXISTS `emails_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=``@`%` SQL SECURITY DEFINER VIEW `emails_view` AS select `TE`.`name` AS `type_email_name`,`TE`.`description` AS `description`,`E`.`id` AS `id`,`E`.`p_id` AS `p_id`,`E`.`type_email_id` AS `type_email_id`,`E`.`email` AS `email`,`E`.`create_time` AS `create_time`,`E`.`update_time` AS `update_time` from (`email` `E` left join `type_email` `TE` on((`E`.`type_email_id` = `TE`.`id`)));

-- --------------------------------------------------------

--
-- Structure for view `p_and_ps_view`
--
DROP TABLE IF EXISTS `p_and_ps_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`getulio`@`%` SQL SECURITY DEFINER VIEW `p_and_ps_view` AS select `PP`.`id` AS `id`,`P`.`username` AS `p_id_username`,`PP`.`p_id` AS `p_id`,`P1`.`username` AS `p_id1_username`,`PP`.`p_id1` AS `p_id1`,`R`.`name` AS `role_name`,`R`.`id` AS `role_id`,`PP`.`start_date` AS `start_date`,`PP`.`end_date` AS `end_date`,`PP`.`create_time` AS `create_time`,`PP`.`update_time` AS `update_time` from (((`p_and_p` `PP` join `p` `P` on((`PP`.`p_id` = `P`.`id`))) join `p` `P1` on((`PP`.`p_id1` = `P1`.`id`))) join `role` `R` on((`PP`.`role_id` = `R`.`id`)));

-- --------------------------------------------------------

--
-- Structure for view `people_view`
--
DROP TABLE IF EXISTS `people_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`getulio`@`%` SQL SECURITY DEFINER VIEW `people_view` AS select `PERSON`.`id` AS `id`,`P`.`id` AS `p_id`,`PERSON`.`first_name` AS `first_name`,`PERSON`.`last_name` AS `last_name`,`PERSON`.`cpf` AS `cpf`,`PERSON`.`password` AS `password`,`P`.`username` AS `username`,`P`.`primary_email` AS `primary_email`,`P`.`birthday` AS `birthday`,`P`.`image` AS `image`,`P`.`additional_information` AS `additional_information`,`PERSON`.`activation_code` AS `activation_code`,`PERSON`.`forgotten_password_code` AS `forgotten_password_code`,`PERSON`.`remember_code` AS `remember_code`,`PERSON`.`gender` AS `gender`,`P`.`create_time` AS `create_time`,`P`.`update_time` AS `update_time`,`PMETA`.`id` AS `pmeta_id` from ((`p` `P` join `person` `PERSON` on((`P`.`id` = `PERSON`.`p_id`))) left join `pmeta` `PMETA` on((`P`.`id` = `PMETA`.`p_id`)));

-- --------------------------------------------------------

--
-- Structure for view `phones_view`
--
DROP TABLE IF EXISTS `phones_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=``@`%` SQL SECURITY DEFINER VIEW `phones_view` AS select `P`.`id` AS `id`,`P`.`p_id` AS `p_id`,`P`.`type_phone_id` AS `type_phone_id`,`TP`.`name` AS `type_phone_name`,`TP`.`description` AS `description`,`P`.`number` AS `number`,`P`.`create_time` AS `create_time`,`P`.`update_time` AS `update_time` from (`phone` `P` left join `type_phone` `TP` on((`P`.`type_phone_id` = `TP`.`id`)));

-- --------------------------------------------------------

--
-- Structure for view `role_has_resources_view`
--
DROP TABLE IF EXISTS `role_has_resources_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=``@`%` SQL SECURITY DEFINER VIEW `role_has_resources_view` AS select `role_has_resource`.`id` AS `id`,`role_has_resource`.`role_id` AS `role_id`,`role_has_resource`.`resource_id` AS `resource_id`,`role_has_resource`.`permission` AS `permission`,`role`.`name` AS `role_name`,`role`.`description` AS `role_description`,`resource`.`controller` AS `controller`,`resource`.`method` AS `method`,`role_has_resource`.`create_time` AS `create_time`,`role_has_resource`.`update_time` AS `update_time` from ((`role_has_resource` join `role` on((`role_has_resource`.`role_id` = `role`.`id`))) join `resource` on((`role_has_resource`.`resource_id` = `resource`.`id`)));

-- --------------------------------------------------------

--
-- Structure for view `selection_processes_view`
--
DROP TABLE IF EXISTS `selection_processes_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=``@`%` SQL SECURITY DEFINER VIEW `selection_processes_view` AS select `SP`.`id` AS `id`,`SP`.`company_id` AS `company_id`,`C`.`name` AS `company_name`,`SP`.`name` AS `name`,`SP`.`description` AS `description`,`SP`.`start_date` AS `start_date`,`SP`.`file_name` AS `file_name`,`SP`.`active` AS `active`,`SPHC`.`status` AS `status`,`SPHC`.`id` AS `selection_process_has_company_id` from ((`selection_process` `SP` join `selection_process_has_company` `SPHC` on((`SP`.`id` = `SPHC`.`selection_process_id`))) join `company` `C` on((`SPHC`.`company_id` = `C`.`id`)));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `fk_role_has_p_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_role_has_p_p1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `fk_activity_step1` FOREIGN KEY (`step_id`) REFERENCES `step` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_activity_p_and_p1` FOREIGN KEY (`responsible_id`) REFERENCES `p_and_p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `fk_address_p1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_address_city1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_address_state1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_address_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `c_meta`
--
ALTER TABLE `c_meta`
  ADD CONSTRAINT `fk_c_meta_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `fk_city_state1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `fk_company_p1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `email`
--
ALTER TABLE `email`
  ADD CONSTRAINT `fk_email_p1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_email_type_email1` FOREIGN KEY (`type_email_id`) REFERENCES `type_email` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_event_step1` FOREIGN KEY (`step_id`) REFERENCES `step` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_event_selection_process_has_company1` FOREIGN KEY (`selection_process_has_company_id`) REFERENCES `selection_process_has_company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `feedback_event`
--
ALTER TABLE `feedback_event`
  ADD CONSTRAINT `fk_feedback_event_event1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_feedback_event_person1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `incubator_has_company`
--
ALTER TABLE `incubator_has_company`
  ADD CONSTRAINT `fk_company_has_company_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_company_has_company_company2` FOREIGN KEY (`incubator_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `p_and_p`
--
ALTER TABLE `p_and_p`
  ADD CONSTRAINT `fk_p_has_p_p1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_p_has_p_p2` FOREIGN KEY (`p_id1`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_p_and_p_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `fk_person_p1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `person_login`
--
ALTER TABLE `person_login`
  ADD CONSTRAINT `fk_person_login_person1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `phone`
--
ALTER TABLE `phone`
  ADD CONSTRAINT `fk_phone_p1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_phone_type_phone1` FOREIGN KEY (`type_phone_id`) REFERENCES `type_phone` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pmeta`
--
ALTER TABLE `pmeta`
  ADD CONSTRAINT `fk_pmeta_p1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `role_has_resource`
--
ALTER TABLE `role_has_resource`
  ADD CONSTRAINT `fk_role_has_resource_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_role_has_resource_resource1` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `selection_process`
--
ALTER TABLE `selection_process`
  ADD CONSTRAINT `fk_selection_process_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `selection_process_has_company`
--
ALTER TABLE `selection_process_has_company`
  ADD CONSTRAINT `fk_selection_process_has_company_selection_process1` FOREIGN KEY (`selection_process_id`) REFERENCES `selection_process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_selection_process_has_company_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_selection_process_has_company_step1` FOREIGN KEY (`step_disqualification`) REFERENCES `step` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `site`
--
ALTER TABLE `site`
  ADD CONSTRAINT `fk_url_p1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `state`
--
ALTER TABLE `state`
  ADD CONSTRAINT `fk_state_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `step`
--
ALTER TABLE `step`
  ADD CONSTRAINT `fk_step_step_type1` FOREIGN KEY (`step_type_id`) REFERENCES `step_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_step_selection_process1` FOREIGN KEY (`selection_process_id`) REFERENCES `selection_process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_step_person1` FOREIGN KEY (`evaluator_id`) REFERENCES `person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
