SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `arvoz_dm` ;
CREATE SCHEMA IF NOT EXISTS `arvoz_dm` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `arvoz_dm` ;

-- -----------------------------------------------------
-- Table `arvoz_dm`.`person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`person` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`person` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `cpf` VARCHAR(11) NULL ,
  `first_name` VARCHAR(45) NULL ,
  `last_name` VARCHAR(45) NULL ,
  `activation_code` VARCHAR(40) NULL ,
  `forgotten_password_code` VARCHAR(40) NULL ,
  `remember_code` VARCHAR(40) NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `username` VARCHAR(255) NULL ,
  `email1` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `gender` CHAR(1) NULL ,
  `email2` VARCHAR(255) NULL ,
  `email3` VARCHAR(255) NULL ,
  `phone1` VARCHAR(255) NULL ,
  `phone2` VARCHAR(255) NULL ,
  `phone3` VARCHAR(255) NULL ,
  `birthday` DATE NULL ,
  `status` INT NULL DEFAULT 1 ,
  `profile_image` VARCHAR(255) NULL ,
  `is_superadmin` VARCHAR(45) NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`company_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`company_type` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`company_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(155) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`company`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`company` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`company` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `cnpj` VARCHAR(14) NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `username` VARCHAR(45) NULL ,
  `email1` VARCHAR(45) NULL ,
  `email2` VARCHAR(45) NULL ,
  `email3` VARCHAR(45) NULL ,
  `phone1` VARCHAR(45) NULL ,
  `phone2` VARCHAR(45) NULL ,
  `phone3` VARCHAR(45) NULL ,
  `foundation` DATETIME NULL ,
  `owner_id` INT NULL ,
  `status` INT NULL DEFAULT 1 ,
  `profile_image` VARCHAR(255) NULL ,
  `company_type_id` INT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_company_person1` (`owner_id` ASC) ,
  INDEX `fk_company_company_type1` (`company_type_id` ASC) ,
  CONSTRAINT `fk_company_person1`
    FOREIGN KEY (`owner_id` )
    REFERENCES `arvoz_dm`.`person` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_company_company_type1`
    FOREIGN KEY (`company_type_id` )
    REFERENCES `arvoz_dm`.`company_type` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`selection_process`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`selection_process` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`selection_process` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `start_date` DATE NOT NULL ,
  `file_name` VARCHAR(255) NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT 0 ,
  `type_selection_process` TINYINT(1) NOT NULL DEFAULT 0 ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `company_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_selection_process_company1` (`company_id` ASC) ,
  CONSTRAINT `fk_selection_process_company1`
    FOREIGN KEY (`company_id` )
    REFERENCES `arvoz_dm`.`company` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`step_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`step_type` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`step_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `has_document` TINYINT(1) NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`step`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`step` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`step` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `step_type_id` INT NOT NULL ,
  `selection_process_id` INT NOT NULL ,
  `step_number` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `duration` INT NOT NULL ,
  `status` TINYINT(1) NULL DEFAULT 1 ,
  `evaluator_id` INT NOT NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_step_step_type1` (`step_type_id` ASC) ,
  INDEX `fk_step_selection_process1` (`selection_process_id` ASC) ,
  CONSTRAINT `fk_step_step_type1`
    FOREIGN KEY (`step_type_id` )
    REFERENCES `arvoz_dm`.`step_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_step_selection_process1`
    FOREIGN KEY (`selection_process_id` )
    REFERENCES `arvoz_dm`.`selection_process` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`selection_process_has_company`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`selection_process_has_company` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`selection_process_has_company` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `selection_process_id` INT NOT NULL ,
  `step_disqualification` INT NULL ,
  `status` TINYINT NOT NULL DEFAULT 0 ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `company_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_selection_process_has_company_selection_process1` (`selection_process_id` ASC) ,
  INDEX `fk_selection_process_has_company_step1` (`step_disqualification` ASC) ,
  INDEX `fk_selection_process_has_company_company1` (`company_id` ASC) ,
  CONSTRAINT `fk_selection_process_has_company_selection_process1`
    FOREIGN KEY (`selection_process_id` )
    REFERENCES `arvoz_dm`.`selection_process` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_selection_process_has_company_step1`
    FOREIGN KEY (`step_disqualification` )
    REFERENCES `arvoz_dm`.`step` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_selection_process_has_company_company1`
    FOREIGN KEY (`company_id` )
    REFERENCES `arvoz_dm`.`company` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`event` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`event` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `step_id` INT NOT NULL ,
  `selection_process_has_company_id` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NOT NULL ,
  `location` VARCHAR(255) NULL ,
  `date` DATETIME NULL ,
  `file_name` VARCHAR(255) NULL ,
  `file_date_sent` VARCHAR(255) NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_event_step1` (`step_id` ASC) ,
  INDEX `fk_event_selection_process_has_company1` (`selection_process_has_company_id` ASC) ,
  CONSTRAINT `fk_event_step1`
    FOREIGN KEY (`step_id` )
    REFERENCES `arvoz_dm`.`step` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_selection_process_has_company1`
    FOREIGN KEY (`selection_process_has_company_id` )
    REFERENCES `arvoz_dm`.`selection_process_has_company` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`feedback_event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`feedback_event` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`feedback_event` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `event_id` INT NOT NULL ,
  `feedback` TEXT NOT NULL ,
  `score` INT NOT NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `person_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_feedback_event_event1` (`event_id` ASC) ,
  INDEX `fk_feedback_event_person1` (`person_id` ASC) ,
  CONSTRAINT `fk_feedback_event_event1`
    FOREIGN KEY (`event_id` )
    REFERENCES `arvoz_dm`.`event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_feedback_event_person1`
    FOREIGN KEY (`person_id` )
    REFERENCES `arvoz_dm`.`person` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`activity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`activity` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`activity` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `step_id` INT NOT NULL ,
  `responsible_id` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `status` INT NOT NULL DEFAULT 0 ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_activity_step1` (`step_id` ASC) ,
  CONSTRAINT `fk_activity_step1`
    FOREIGN KEY (`step_id` )
    REFERENCES `arvoz_dm`.`step` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`plan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`plan` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`plan` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`role` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`role` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `description` TEXT NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `plan_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  INDEX `fk_role_plan1` (`plan_id` ASC) ,
  CONSTRAINT `fk_role_plan1`
    FOREIGN KEY (`plan_id` )
    REFERENCES `arvoz_dm`.`plan` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`person_login`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`person_login` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`person_login` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `ip_address` VARCHAR(16) NOT NULL ,
  `date` DATETIME NOT NULL ,
  `person_id` INT NOT NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_person_login_person1` (`person_id` ASC) ,
  CONSTRAINT `fk_person_login_person1`
    FOREIGN KEY (`person_id` )
    REFERENCES `arvoz_dm`.`person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`ci_sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`ci_sessions` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`ci_sessions` (
  `session_id` VARCHAR(40) NOT NULL DEFAULT '0' ,
  `ip_address` VARCHAR(16) NOT NULL DEFAULT '0' ,
  `user_agent` VARCHAR(120) NOT NULL ,
  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT 0 ,
  `user_data` TEXT NOT NULL ,
  PRIMARY KEY (`session_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`resource`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`resource` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`resource` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `controller` VARCHAR(45) NOT NULL ,
  `method` VARCHAR(45) NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`role_has_resource`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`role_has_resource` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`role_has_resource` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `role_id` INT NOT NULL ,
  `resource_id` INT NOT NULL ,
  `permission` TINYINT(1) NULL DEFAULT 1 ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  INDEX `fk_role_has_resource_resource1` (`resource_id` ASC) ,
  INDEX `fk_role_has_resource_role1` (`role_id` ASC) ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_role_has_resource_role1`
    FOREIGN KEY (`role_id` )
    REFERENCES `arvoz_dm`.`role` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_has_resource_resource1`
    FOREIGN KEY (`resource_id` )
    REFERENCES `arvoz_dm`.`resource` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`company_has_person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`company_has_person` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`company_has_person` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `company_id` INT NULL ,
  `person_id` INT NULL ,
  `start_date` DATE NULL ,
  `end_date` DATE NULL ,
  `status` INT NULL DEFAULT 1 ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `role_id` INT NULL ,
  `job` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_company_has_person_person1` (`person_id` ASC) ,
  INDEX `fk_company_has_person_company1` (`company_id` ASC) ,
  INDEX `fk_company_has_person_role1` (`role_id` ASC) ,
  CONSTRAINT `fk_company_has_person_company1`
    FOREIGN KEY (`company_id` )
    REFERENCES `arvoz_dm`.`company` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_company_has_person_person1`
    FOREIGN KEY (`person_id` )
    REFERENCES `arvoz_dm`.`person` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_company_has_person_role1`
    FOREIGN KEY (`role_id` )
    REFERENCES `arvoz_dm`.`role` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`institution`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`institution` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`institution` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `cnpj` VARCHAR(15) NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `username` VARCHAR(45) NULL ,
  `email1` VARCHAR(45) NULL ,
  `email2` VARCHAR(45) NULL ,
  `email3` VARCHAR(45) NULL ,
  `phone1` VARCHAR(45) NULL ,
  `phone2` VARCHAR(45) NULL ,
  `phone3` VARCHAR(45) NULL ,
  `foundation` VARCHAR(45) NULL ,
  `status` INT NULL ,
  `profile_image` VARCHAR(45) NULL ,
  `entity_manager_link` VARCHAR(45) NULL ,
  `attach1` VARCHAR(45) NULL ,
  `attach2` VARCHAR(45) NULL ,
  `attach3` VARCHAR(45) NULL ,
  `regulation` VARCHAR(45) NULL ,
  `social_law` VARCHAR(45) NULL ,
  `descriptive_memorial` VARCHAR(45) NULL ,
  `services_description` VARCHAR(45) NULL ,
  `owner_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_institution_person1` (`owner_id` ASC) ,
  CONSTRAINT `fk_institution_person1`
    FOREIGN KEY (`owner_id` )
    REFERENCES `arvoz_dm`.`person` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`site`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`site` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`site` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `url` VARCHAR(255) NULL ,
  `image` VARCHAR(255) NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `person_id` INT NULL ,
  `company_id` INT NULL ,
  `status` INT NULL DEFAULT 1 ,
  `institution_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_site_person1` (`person_id` ASC) ,
  INDEX `fk_site_company1` (`company_id` ASC) ,
  INDEX `fk_site_institution1` (`institution_id` ASC) ,
  CONSTRAINT `fk_site_person1`
    FOREIGN KEY (`person_id` )
    REFERENCES `arvoz_dm`.`person` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_site_company1`
    FOREIGN KEY (`company_id` )
    REFERENCES `arvoz_dm`.`company` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_site_institution1`
    FOREIGN KEY (`institution_id` )
    REFERENCES `arvoz_dm`.`institution` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`institution_has_company`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`institution_has_company` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`institution_has_company` (
  `institution_id` INT NULL ,
  `company_id` INT NULL ,
  `id` INT NOT NULL AUTO_INCREMENT ,
  `start_date` DATE NULL ,
  `end_date` DATE NULL ,
  `status` INT NULL DEFAULT 0 ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Institution_has_company_company1` (`company_id` ASC) ,
  INDEX `fk_Institution_has_company_Institution1` (`institution_id` ASC) ,
  CONSTRAINT `fk_Institution_has_company_Institution1`
    FOREIGN KEY (`institution_id` )
    REFERENCES `arvoz_dm`.`institution` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Institution_has_company_company1`
    FOREIGN KEY (`company_id` )
    REFERENCES `arvoz_dm`.`company` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`institution_has_person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`institution_has_person` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`institution_has_person` (
  `institution_id` INT NULL ,
  `person_id` INT NULL ,
  `status` VARCHAR(45) NULL ,
  `create_time` DATETIME NULL ,
  `update_time` DATETIME NULL ,
  `id` INT NOT NULL AUTO_INCREMENT ,
  `role_id` INT NULL ,
  `start_date` DATE NULL ,
  `end_date` DATE NULL ,
  `job` VARCHAR(45) NULL ,
  INDEX `fk_Institution_has_person_person1` (`person_id` ASC) ,
  INDEX `fk_Institution_has_person_Institution1` (`institution_id` ASC) ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Institution_has_person_role1` (`role_id` ASC) ,
  CONSTRAINT `fk_Institution_has_person_Institution1`
    FOREIGN KEY (`institution_id` )
    REFERENCES `arvoz_dm`.`institution` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Institution_has_person_person1`
    FOREIGN KEY (`person_id` )
    REFERENCES `arvoz_dm`.`person` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Institution_has_person_role1`
    FOREIGN KEY (`role_id` )
    REFERENCES `arvoz_dm`.`role` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`process_document_audit`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`process_document_audit` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`process_document_audit` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `process_document_id` INT(11) NOT NULL ,
  `key_practice_id` INT(11) NULL DEFAULT NULL ,
  `version` DECIMAL(10,2) NULL DEFAULT NULL ,
  `approval_date` DATETIME NULL DEFAULT NULL ,
  `approver` VARCHAR(250) NULL DEFAULT NULL ,
  `file_path` VARCHAR(250) NULL DEFAULT NULL ,
  `create_date` DATETIME NULL DEFAULT NULL ,
  `action` VARCHAR(50) NULL DEFAULT NULL ,
  `company_id` INT(11) NULL DEFAULT NULL ,
  `key_process_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`key_practice`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`key_practice` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`key_practice` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `create_date` DATETIME NULL DEFAULT NULL ,
  `update_date` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`process_document`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`process_document` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`process_document` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `key_process_id` INT(11) NOT NULL ,
  `key_practice_id` INT(11) NULL DEFAULT '-1' ,
  `version` DECIMAL(10,2) NOT NULL ,
  `approval_date` DATETIME NULL DEFAULT NULL ,
  `approver` VARCHAR(255) NULL DEFAULT NULL ,
  `file_path` VARCHAR(255) NOT NULL ,
  `create_date` DATETIME NOT NULL ,
  `update_date` DATETIME NULL DEFAULT NULL ,
  `company_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_document_key_process` (`key_process_id` ASC) ,
  INDEX `fk_document_key_practice` (`key_practice_id` ASC) ,
  INDEX `fk_process_document_company1` (`company_id` ASC) ,
  CONSTRAINT `fk_document_key_practice`
    FOREIGN KEY (`key_practice_id` )
    REFERENCES `arvoz_dm`.`key_practice` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_process_document_company1`
    FOREIGN KEY (`company_id` )
    REFERENCES `arvoz_dm`.`company` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`maturity_level`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`maturity_level` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`maturity_level` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `create_time` DATETIME NULL DEFAULT NULL ,
  `update_time` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`key_process`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`key_process` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`key_process` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `maturity_level_id` INT(11) NOT NULL ,
  `create_time` DATETIME NULL DEFAULT NULL ,
  `update_time` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pk_key_process_maturity_level` (`maturity_level_id` ASC) ,
  CONSTRAINT `pk_key_process_maturity_level`
    FOREIGN KEY (`maturity_level_id` )
    REFERENCES `arvoz_dm`.`maturity_level` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 17
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `arvoz_dm`.`key_practice_has_maturity_level`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `arvoz_dm`.`key_practice_has_maturity_level` ;

CREATE  TABLE IF NOT EXISTS `arvoz_dm`.`key_practice_has_maturity_level` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `key_practice_id` INT(11) NOT NULL ,
  `maturity_level_id` INT(11) NOT NULL ,
  `create_date` DATETIME NULL DEFAULT NULL ,
  `update_date` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_maturity_level` (`maturity_level_id` ASC) ,
  INDEX `fk_key_practice` (`key_practice_id` ASC) ,
  CONSTRAINT `fk_key_practice`
    FOREIGN KEY (`key_practice_id` )
    REFERENCES `arvoz_dm`.`key_practice` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_maturity_level`
    FOREIGN KEY (`maturity_level_id` )
    REFERENCES `arvoz_dm`.`maturity_level` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 19
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Placeholder table for view `arvoz_dm`.`selection_processes_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `arvoz_dm`.`selection_processes_view` (`id` INT, `company_id` INT, `company_name` INT, `name` INT, `description` INT, `start_date` INT, `file_name` INT, `active` INT, `status` INT, `selection_process_has_company_id` INT);

-- -----------------------------------------------------
-- Placeholder table for view `arvoz_dm`.`person_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `arvoz_dm`.`person_view` (`id` INT, `cpf` INT, `first_name` INT, `last_name` INT, `activation_code` INT, `forgotten_password_code` INT, `remember_code` INT, `create_time` INT, `update_time` INT, `username` INT, `email1` INT, `password` INT, `gender` INT, `email2` INT, `email3` INT, `phone1` INT, `phone2` INT, `phone3` INT, `birthday` INT, `status` INT, `profile_image` INT, `is_superadmin` INT);

-- -----------------------------------------------------
-- Placeholder table for view `arvoz_dm`.`company_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `arvoz_dm`.`company_view` (`id` INT, `name` INT, `cnpj` INT, `create_time` INT, `update_time` INT, `username` INT, `email1` INT, `email2` INT, `email3` INT, `phone1` INT, `phone2` INT, `phone3` INT, `foundation` INT, `owner_id` INT, `status` INT, `profile_image` INT, `company_type_id` INT, `description` INT);

-- -----------------------------------------------------
-- Placeholder table for view `arvoz_dm`.`company_has_person_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `arvoz_dm`.`company_has_person_view` (`id` INT, `company_id` INT, `person_id` INT, `start_date` INT, `end_date` INT, `status` INT, `create_time` INT, `update_time` INT, `role_id` INT, `job` INT);

-- -----------------------------------------------------
-- Placeholder table for view `arvoz_dm`.`institution_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `arvoz_dm`.`institution_view` (`id` INT, `name` INT, `cnpj` INT, `create_time` INT, `update_time` INT, `username` INT, `email1` INT, `email2` INT, `email3` INT, `phone1` INT, `phone2` INT, `phone3` INT, `foundation` INT, `status` INT, `profile_image` INT, `entity_manager_link` INT, `attach1` INT, `attach2` INT, `attach3` INT, `regulation` INT, `social_law` INT, `descriptive_memorial` INT, `services_description` INT, `owner_id` INT);

-- -----------------------------------------------------
-- Placeholder table for view `arvoz_dm`.`institution_has_person_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `arvoz_dm`.`institution_has_person_view` (`institution_id` INT, `person_id` INT, `status` INT, `create_time` INT, `update_time` INT, `id` INT, `role_id` INT, `start_date` INT, `end_date` INT, `job` INT);

-- -----------------------------------------------------
-- View `arvoz_dm`.`selection_processes_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `arvoz_dm`.`selection_processes_view` ;
DROP TABLE IF EXISTS `arvoz_dm`.`selection_processes_view`;
USE `arvoz_dm`;
CREATE  OR REPLACE VIEW `selection_processes_view` AS
SELECT 

SP.id AS id,

SP.company_id AS company_id,

C.name AS company_name,

SP.name AS name,

SP.description AS description,

SP.start_date AS start_date,

SP.file_name AS file_name,

SP.active AS active,

SPHC.status AS status,

SPHC.id AS selection_process_has_company_id

FROM

selection_process AS SP JOIN selection_process_has_company AS SPHC

ON SP.id = SPHC.selection_process_id

JOIN company AS C

ON SPHC.company_id = C.id;

-- -----------------------------------------------------
-- View `arvoz_dm`.`person_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `arvoz_dm`.`person_view` ;
DROP TABLE IF EXISTS `arvoz_dm`.`person_view`;
USE `arvoz_dm`;
CREATE  OR REPLACE VIEW `person_view` AS
SELECT * from person where status = 1;

-- -----------------------------------------------------
-- View `arvoz_dm`.`company_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `arvoz_dm`.`company_view` ;
DROP TABLE IF EXISTS `arvoz_dm`.`company_view`;
USE `arvoz_dm`;
CREATE  OR REPLACE VIEW `company_view` AS

SELECT * from company where status = 1;


;

-- -----------------------------------------------------
-- View `arvoz_dm`.`company_has_person_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `arvoz_dm`.`company_has_person_view` ;
DROP TABLE IF EXISTS `arvoz_dm`.`company_has_person_view`;
USE `arvoz_dm`;
CREATE  OR REPLACE VIEW `arvoz_dm`.`company_has_person_view` AS

select chp.* from company_has_person chp where chp.status = 1

;

-- -----------------------------------------------------
-- View `arvoz_dm`.`institution_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `arvoz_dm`.`institution_view` ;
DROP TABLE IF EXISTS `arvoz_dm`.`institution_view`;
USE `arvoz_dm`;
CREATE  OR REPLACE VIEW `arvoz_dm`.`institution_view` AS select * from institution where status = 1;


;

-- -----------------------------------------------------
-- View `arvoz_dm`.`institution_has_person_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `arvoz_dm`.`institution_has_person_view` ;
DROP TABLE IF EXISTS `arvoz_dm`.`institution_has_person_view`;
USE `arvoz_dm`;
CREATE  OR REPLACE VIEW `arvoz_dm`.`institution_has_person_view` AS select * from institution_has_person where status = 1;
;
USE `arvoz_dm`;

DELIMITER $$

USE `arvoz_dm`$$
DROP TRIGGER IF EXISTS `arvoz_dm`.`after_process_document_insert` $$
USE `arvoz_dm`$$


CREATE
DEFINER=`root`@`localhost`
TRIGGER `arvoz_dm`.`after_process_document_insert`
AFTER INSERT ON `arvoz_dm`.`process_document`
FOR EACH ROW
BEGIN
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
create_date = NOW(); END$$


USE `arvoz_dm`$$
DROP TRIGGER IF EXISTS `arvoz_dm`.`before_process_document_update` $$
USE `arvoz_dm`$$


CREATE
DEFINER=`root`@`localhost`
TRIGGER `arvoz_dm`.`before_process_document_update`
BEFORE UPDATE ON `arvoz_dm`.`process_document`
FOR EACH ROW
BEGIN
Insert into process_document_audit
Set action = 'update',
process_document_id = OLD.id,
company_id = NEW.company_id,
key_process_id = NEW.key_process_id,
key_practice_id = NEW.key_practice_id,
version = NEW.version,
approver = NEW.approver,
approval_date = NEW.approval_date,
create_date = NOW(); END$$


DELIMITER ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `arvoz_dm`.`person`
-- -----------------------------------------------------
START TRANSACTION;
USE `arvoz_dm`;
INSERT INTO `arvoz_dm`.`person` (`id`, `cpf`, `first_name`, `last_name`, `activation_code`, `forgotten_password_code`, `remember_code`, `create_time`, `update_time`, `username`, `email1`, `password`, `gender`, `email2`, `email3`, `phone1`, `phone2`, `phone3`, `birthday`, `status`, `profile_image`, `is_superadmin`) VALUES (1, '11111111111', 'Super', 'Admin', 'superadmin', 'superadmin2to2arvoz', NULL, '2011-12-12 12:01:01', '2011-12-12 12:01:01', NULL, 'a@a.com', '9fe6be740b2926ab663b637a77def4bca0d9248f', 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `arvoz_dm`.`person` (`id`, `cpf`, `first_name`, `last_name`, `activation_code`, `forgotten_password_code`, `remember_code`, `create_time`, `update_time`, `username`, `email1`, `password`, `gender`, `email2`, `email3`, `phone1`, `phone2`, `phone3`, `birthday`, `status`, `profile_image`, `is_superadmin`) VALUES (2, '42470871506', 'Guilherme', 'Sampaio', 'paz', 'nome cachorro', NULL, '2011-12-12 12:01:01', '2011-12-12 12:01:01', NULL, 'b@b.com', '9fe6be740b2926ab663b637a77def4bca0d9248f', 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `arvoz_dm`.`person` (`id`, `cpf`, `first_name`, `last_name`, `activation_code`, `forgotten_password_code`, `remember_code`, `create_time`, `update_time`, `username`, `email1`, `password`, `gender`, `email2`, `email3`, `phone1`, `phone2`, `phone3`, `birthday`, `status`, `profile_image`, `is_superadmin`) VALUES (3, '77411762261', 'Rafael', 'Cavalcante', 'paciencia', 'idade', NULL, '2011-12-12 12:01:01', '2011-12-12 12:01:01', NULL, 'c@c.com', '9fe6be740b2926ab663b637a77def4bca0d9248f', 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `arvoz_dm`.`company_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `arvoz_dm`;
INSERT INTO `arvoz_dm`.`company_type` (`id`, `name`) VALUES (1, 'company');
INSERT INTO `arvoz_dm`.`company_type` (`id`, `name`) VALUES (2, 'institution');

COMMIT;

-- -----------------------------------------------------
-- Data for table `arvoz_dm`.`company`
-- -----------------------------------------------------
START TRANSACTION;
USE `arvoz_dm`;
INSERT INTO `arvoz_dm`.`company` (`id`, `name`, `cnpj`, `create_time`, `update_time`, `username`, `email1`, `email2`, `email3`, `phone1`, `phone2`, `phone3`, `foundation`, `owner_id`, `status`, `profile_image`, `company_type_id`, `description`) VALUES (1, 'Lacoste', '01333673000126', '2010-11-05 15:22:44', '2010-12-18:55:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `arvoz_dm`.`company` (`id`, `name`, `cnpj`, `create_time`, `update_time`, `username`, `email1`, `email2`, `email3`, `phone1`, `phone2`, `phone3`, `foundation`, `owner_id`, `status`, `profile_image`, `company_type_id`, `description`) VALUES (2, 'Nike', '20373996000198', '2010-10-21 16:22:00', '2010-11-22 15:00:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `arvoz_dm`.`company` (`id`, `name`, `cnpj`, `create_time`, `update_time`, `username`, `email1`, `email2`, `email3`, `phone1`, `phone2`, `phone3`, `foundation`, `owner_id`, `status`, `profile_image`, `company_type_id`, `description`) VALUES (3, 'Borelli', '54060811000107', '2010-11-14 15:22:11', '2011-05-12 19:33:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `arvoz_dm`.`company` (`id`, `name`, `cnpj`, `create_time`, `update_time`, `username`, `email1`, `email2`, `email3`, `phone1`, `phone2`, `phone3`, `foundation`, `owner_id`, `status`, `profile_image`, `company_type_id`, `description`) VALUES (4, 'Aviator', '28423756000162', '2009-02-19 18:00:00', '2009-03-16 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `arvoz_dm`.`company` (`id`, `name`, `cnpj`, `create_time`, `update_time`, `username`, `email1`, `email2`, `email3`, `phone1`, `phone2`, `phone3`, `foundation`, `owner_id`, `status`, `profile_image`, `company_type_id`, `description`) VALUES (5, 'Boticario', '16888630000184', '2008-03-15 16:00:00', '2011-02-18 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `arvoz_dm`.`step_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `arvoz_dm`;
INSERT INTO `arvoz_dm`.`step_type` (`id`, `name`, `has_document`, `create_time`, `update_time`) VALUES (1, 'Apresentação', NULL, NULL, NULL);
INSERT INTO `arvoz_dm`.`step_type` (`id`, `name`, `has_document`, `create_time`, `update_time`) VALUES (2, 'Documento', 1, NULL, NULL);
INSERT INTO `arvoz_dm`.`step_type` (`id`, `name`, `has_document`, `create_time`, `update_time`) VALUES (3, 'Entrevista', NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `arvoz_dm`.`role`
-- -----------------------------------------------------
START TRANSACTION;
USE `arvoz_dm`;
INSERT INTO `arvoz_dm`.`role` (`id`, `name`, `description`, `create_time`, `update_time`, `plan_id`) VALUES (1, 'super_admin', 'Super Administrator of the system', '2011-12-09 16:56:00', '2011-12-09 16:56:00', NULL);
INSERT INTO `arvoz_dm`.`role` (`id`, `name`, `description`, `create_time`, `update_time`, `plan_id`) VALUES (2, 'admin', 'Administratator of organization', '2011-12-09 16:56:00', '2011-12-09 16:56:00', NULL);

COMMIT;
