-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema baquiz
-- -----------------------------------------------------
-- Datamodel for the "Baquiz" platform
-- Copyright (c) 2026 All rigths reserved
-- ====== Xsam Technologies ======
-- https://xsamtech.com
DROP SCHEMA IF EXISTS `baquiz` ;

-- -----------------------------------------------------
-- Schema baquiz
--
-- Datamodel for the "Baquiz" platform
-- Copyright (c) 2026 All rigths reserved
-- ====== Xsam Technologies ======
-- https://xsamtech.com
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `baquiz` DEFAULT CHARACTER SET utf8mb4 ;
USE `baquiz` ;

-- -----------------------------------------------------
-- Table `baquiz`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`users` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`users` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `firstname` VARCHAR(255) NULL,
  `lastname` VARCHAR(255) NULL,
  `surname` VARCHAR(255) NULL,
  `organization_name` VARCHAR(255) NULL COMMENT 'If the user is an organization',
  `about` TEXT NULL,
  `gender` VARCHAR(45) NULL,
  `birthdate` DATE NULL,
  `country` VARCHAR(255) NULL,
  `city` VARCHAR(255) NULL,
  `address_1` TEXT NULL,
  `address_2` TEXT NULL,
  `p_o_box` VARCHAR(45) NULL,
  `currency` VARCHAR(45) NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(45) NULL,
  `email_verified_at` DATETIME NULL,
  `phone_verfied_at` DATETIME NULL,
  `username` VARCHAR(255) NULL,
  `password` TEXT NULL,
  `remember_token` VARCHAR(100) NULL,
  `api_token` TEXT NULL,
  `api_key` TEXT NULL,
  `avatar_url` TEXT NULL,
  `cover_url` TEXT NULL,
  `promo_code` VARCHAR(45) NULL,
  `two_factor_secret` TEXT NULL,
  `two_factor_recovery_codes` TEXT NULL,
  `two_factor_email_confirmed_at` TIMESTAMP NULL,
  `two_factor_phone_confirmed_at` TIMESTAMP NULL,
  `tips_at_every_login` TINYINT NOT NULL DEFAULT 1,
  `is_online` TINYINT NOT NULL DEFAULT 1,
  `certified_at` DATETIME NULL,
  `status` ENUM('created', 'activated', 'disabled', 'blocked', 'deleted') NOT NULL DEFAULT 'created',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_users_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `email_users_UNIQUE` (`email` ASC) VISIBLE,
  UNIQUE INDEX `phone_users_UNIQUE` (`phone` ASC) VISIBLE,
  UNIQUE INDEX `username_users_UNIQUE` (`username` ASC) VISIBLE,
  UNIQUE INDEX `uuid_users_UNIQUE` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`roles` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`roles` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `role_name` JSON NOT NULL,
  `role_description` JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_roles_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`role_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`role_user` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`role_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `role_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `is_selected` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_roleuser_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_roleuser_roles_idx` (`role_id` ASC) VISIBLE,
  INDEX `fk_roleuser_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_roleuser_roles`
    FOREIGN KEY (`role_id`)
    REFERENCES `baquiz`.`roles` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_roleuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`password_reset_tokens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`password_reset_tokens` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`password_reset_tokens` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(45) NULL,
  `token` VARCHAR(45) NULL,
  `former_password` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_passwordresettokens_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`personal_access_tokens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`personal_access_tokens` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`personal_access_tokens` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `tokenable_type` VARCHAR(255) NOT NULL,
  `tokenable_id` BIGINT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `token` VARCHAR(64) NOT NULL,
  `abilities` TEXT NULL,
  `last_used_at` TIMESTAMP NULL,
  `expires_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_personalaccesstokens_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`payments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`payments` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`payments` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `reference` VARCHAR(45) NULL,
  `provider_reference` VARCHAR(45) NULL,
  `order_number` TEXT NULL,
  `amount` DECIMAL(12,2) NULL,
  `amount_customer` DECIMAL(12,2) NULL,
  `phone` VARCHAR(45) NULL,
  `currency` VARCHAR(45) NULL,
  `channel` VARCHAR(45) NULL,
  `type` INT NOT NULL,
  `status` INT NULL,
  `reason` ENUM('clash_create', 'clash_participate', 'clash_boost', 'user_certfied', 'ad') NULL,
  `entity` ENUM('clash', 'user') NULL,
  `entity_id` BIGINT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_payments_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_payments_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_payments_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`sessions` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`sessions` (
  `id` VARCHAR(255) NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NULL,
  `last_activity` INT NULL,
  `latitude` DECIMAL(10,7) NULL,
  `longitude` DECIMAL(10,7) NULL,
  `city` VARCHAR(255) NULL,
  `region` VARCHAR(255) NULL,
  `country` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_sessions_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_sessions_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_sessions_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`levels`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`levels` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`levels` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `level_name` JSON NOT NULL,
  `min_score` INT NULL,
  `max_score` INT NULL,
  `icon` VARCHAR(255) NULL,
  `color` VARCHAR(255) NULL,
  `for_subject` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_levels_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_levels_UNIQUE` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`fields`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`fields` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`fields` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `field_name` JSON NOT NULL,
  `field_description` JSON NULL,
  `icon` VARCHAR(255) NULL,
  `color` VARCHAR(255) NULL,
  `group` ENUM('evaluation', 'vocational_guidance', 'survey') NOT NULL DEFAULT 'evaluation',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_fields_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_fields_UNIQUE` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`clashs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`clashs` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`clashs` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `clash_code` TEXT NULL,
  `clash_description` LONGTEXT NULL,
  `start_at` DATETIME NULL,
  `end_at` DATETIME NULL,
  `price` DECIMAL(12,2) NULL,
  `currency` VARCHAR(45) NULL COMMENT 'Regardless of the currency the user enters for participation in the clash, the price will be displayed according to the default currency of the user.',
  `is_competition` TINYINT NOT NULL DEFAULT 0,
  `type` ENUM('public', 'private') NOT NULL DEFAULT 'public',
  `last_boost_at` TIMESTAMP NULL,
  `boost_type` ENUM('daily', 'weekly', 'monthly', 'yearly') NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `field_id` BIGINT NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_clashs_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_clashs_fields_idx` (`field_id` ASC) VISIBLE,
  INDEX `fk_clashs_users_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_clashs_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_clashs_fields`
    FOREIGN KEY (`field_id`)
    REFERENCES `baquiz`.`fields` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_clashs_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`subjects`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`subjects` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`subjects` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `subject_name` TEXT NOT NULL,
  `subject_description` LONGTEXT NULL,
  `max_rating` DECIMAL(12,2) NULL,
  `weighting` DECIMAL(3,2) NULL,
  `status` ENUM('created', 'activated', 'disabled', 'blocked', 'deleted') NOT NULL DEFAULT 'created',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `level_id` BIGINT NULL,
  `clash_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_subjects_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_subjects_levels_idx` (`level_id` ASC) VISIBLE,
  INDEX `fk_subjects_clashs_idx` (`clash_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_subjects_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_subjects_levels`
    FOREIGN KEY (`level_id`)
    REFERENCES `baquiz`.`levels` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_subjects_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `baquiz`.`clashs` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`domains`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`domains` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`domains` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `domain_name` JSON NOT NULL,
  `domain_description` JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_domains_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_domains_UNIQUE` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`questions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`questions` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`questions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `question_content` LONGTEXT NULL,
  `expected_time` INT NULL COMMENT 'Time in seconds',
  `percentages_removed` DECIMAL(3,2) NULL COMMENT 'The percentages that are subtracted from a student who gives a correct answer, but exceeds the expected time to answer.',
  `max_rating` DECIMAL(12,2) NULL,
  `correct_assertions_count` INT NULL COMMENT 'For multiple-choice questions, the quiz master must specify the number of assertions to be choosen.',
  `assertion_rating` DECIMAL(12,2) NULL COMMENT 'Assign a rating to each statement. This will allow rating to be deducted if user forget to check a box.',
  `assertions_combination_required` TINYINT NULL DEFAULT 0 COMMENT 'If the value in this column is \"1\", then as soon as the user checks an incorrect assertion, even if he has checked all the correct statements, it invalidates its entire answer.',
  `type` ENUM('input_data', 'single_check', 'multiple_check') NOT NULL DEFAULT 'input_data',
  `status` ENUM('created', 'activated', 'disabled', 'blocked', 'deleted') NOT NULL DEFAULT 'created',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `subject_id` BIGINT NULL COMMENT 'The question concerns a specific subject',
  `domain_id` BIGINT NULL COMMENT 'When a user selects a domain to define his competence and level, there will be questions to test his knowledge in that domain',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_questions_UNIQUE` (`id` ASC) INVISIBLE,
  INDEX `fk_questions_subjects_idx` (`subject_id` ASC) VISIBLE,
  INDEX `fk_questions_domains_idx` (`domain_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_questions_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_questions_subjects`
    FOREIGN KEY (`subject_id`)
    REFERENCES `baquiz`.`subjects` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_questions_domains`
    FOREIGN KEY (`domain_id`)
    REFERENCES `baquiz`.`domains` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`assertions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`assertions` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`assertions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `assertion_content` LONGTEXT NOT NULL,
  `is_real_answer` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `question_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_assertions_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_assertions_questions_idx` (`question_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_assertions_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_assertions_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `baquiz`.`questions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`answers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`answers` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`answers` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `answer_content` LONGTEXT NULL,
  `time_taken` INT NULL COMMENT 'Time in seconds',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `question_id` BIGINT NOT NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_answers_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_answers_questions_idx` (`question_id` ASC) VISIBLE,
  INDEX `fk_answers_users_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_answers_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_answers_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `baquiz`.`questions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_answers_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`comments` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`comments` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `comment_content` LONGTEXT NULL,
  `answered_for` BIGINT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `clash_id` BIGINT NOT NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_comments_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_comments_clashs_idx` (`clash_id` ASC) VISIBLE,
  INDEX `fk_comments_users_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_comments_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_comments_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `baquiz`.`clashs` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comments_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`circles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`circles` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`circles` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `circle_name` TEXT NOT NULL,
  `profile_photo` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_circles_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_circles_users_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_circles_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_circles_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`messages` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`messages` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `message_content` LONGTEXT NULL,
  `event_title` TEXT NULL,
  `event_description` LONGTEXT NULL,
  `event_start_at` DATETIME NULL,
  `event_end_at` DATETIME NULL,
  `event_place` TEXT NULL,
  `answered_for` BIGINT NULL,
  `type` ENUM('text', 'poll', 'event', 'contact', 'voice_note', 'file', 'call_audio', 'call_video') NOT NULL DEFAULT 'text',
  `call_type` ENUM('outgoing', 'incoming', 'missed') NULL COMMENT 'Useful for « call_audio » or « call_video » type messages',
  `status` ENUM('read', 'unread') NOT NULL DEFAULT 'unread',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NULL,
  `addressee_user_id` BIGINT NULL,
  `addressee_circle_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_messages_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_messages_users_1_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_messages_users_2_idx` (`addressee_user_id` ASC) VISIBLE,
  INDEX `fk_messages_circles_idx` (`addressee_circle_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_messages_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_messages_users_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_users_2`
    FOREIGN KEY (`addressee_user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_circles`
    FOREIGN KEY (`addressee_circle_id`)
    REFERENCES `baquiz`.`circles` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`files`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`files` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`files` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `file_name` VARCHAR(255) NULL,
  `file_url` TEXT NOT NULL,
  `file_description` LONGTEXT NULL COMMENT 'This might be useful for describing advertisements, for example',
  `file_type` ENUM('video', 'photo', 'audio', 'document', 'id_card', 'ad', 'qr_code') NOT NULL DEFAULT 'photo',
  `mime_type` VARCHAR(100) NULL,
  `file_size` BIGINT NULL,
  `width` INT NULL,
  `height` INT NULL,
  `duration` INT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `question_id` BIGINT NULL,
  `assertion_id` BIGINT NULL,
  `answer_id` BIGINT NULL,
  `clash_id` BIGINT NULL,
  `user_id` BIGINT NULL,
  `subject_id` BIGINT NULL,
  `field_id` BIGINT NULL,
  `comment_id` BIGINT NULL,
  `domain_id` BIGINT NULL,
  `message_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_files_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_files_questions_idx` (`question_id` ASC) VISIBLE,
  INDEX `fk_files_assertions_idx` (`assertion_id` ASC) VISIBLE,
  INDEX `fk_files_answers_idx` (`answer_id` ASC) INVISIBLE,
  INDEX `fk_files_clashs_idx` (`clash_id` ASC) VISIBLE,
  INDEX `fk_files_users_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_files_subjects_idx` (`subject_id` ASC) VISIBLE,
  INDEX `fk_files_fields_idx` (`field_id` ASC) VISIBLE,
  INDEX `fk_files_comments_idx` (`comment_id` ASC) VISIBLE,
  INDEX `fk_files_domains_idx` (`domain_id` ASC) VISIBLE,
  INDEX `fk_files_messages_idx` (`message_id` ASC) VISIBLE,
  CONSTRAINT `fk_files_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `baquiz`.`questions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_assertions`
    FOREIGN KEY (`assertion_id`)
    REFERENCES `baquiz`.`assertions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_answers`
    FOREIGN KEY (`answer_id`)
    REFERENCES `baquiz`.`answers` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `baquiz`.`clashs` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_subjects`
    FOREIGN KEY (`subject_id`)
    REFERENCES `baquiz`.`subjects` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_fields`
    FOREIGN KEY (`field_id`)
    REFERENCES `baquiz`.`fields` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_comments`
    FOREIGN KEY (`comment_id`)
    REFERENCES `baquiz`.`comments` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_domains`
    FOREIGN KEY (`domain_id`)
    REFERENCES `baquiz`.`domains` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_messages`
    FOREIGN KEY (`message_id`)
    REFERENCES `baquiz`.`messages` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`notifications`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`notifications` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`notifications` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `type` ENUM('welcome_new_user', 'user_mention', 'user_birthday', 'clash_invitation', 'new_clash_attendee', 'clash_created', 'clash_started', 'clash_ended', 'clash_liked', 'medal_awarded', 'new_follower', 'payment_pending', 'payment_successful', 'payment_failed') NULL,
  `is_read` TINYINT NOT NULL DEFAULT 0,
  `from_user_id` BIGINT NULL,
  `to_user_id` BIGINT NULL,
  `clash_id` BIGINT NULL,
  `comment_id` BIGINT NULL,
  `message_id` BIGINT NULL,
  `question_id` BIGINT NULL,
  `assertion_id` BIGINT NULL,
  `answer_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_notifications_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_notifications_users1_idx` (`from_user_id` ASC) VISIBLE,
  INDEX `fk_notifications_users2_idx` (`to_user_id` ASC) VISIBLE,
  INDEX `fk_notifications_clashs_idx` (`clash_id` ASC) VISIBLE,
  INDEX `fk_notifications_comments_idx` (`comment_id` ASC) VISIBLE,
  INDEX `fk_notifications_messages_idx` (`message_id` ASC) VISIBLE,
  INDEX `fk_notifications_questions_idx` (`question_id` ASC) VISIBLE,
  INDEX `fk_notifications_assertions_idx` (`assertion_id` ASC) VISIBLE,
  INDEX `fk_notifications_answers_idx` (`answer_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_notifications_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_notifications_users1`
    FOREIGN KEY (`from_user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_users2`
    FOREIGN KEY (`to_user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `baquiz`.`clashs` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_comments`
    FOREIGN KEY (`comment_id`)
    REFERENCES `baquiz`.`comments` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_messages`
    FOREIGN KEY (`message_id`)
    REFERENCES `baquiz`.`messages` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `baquiz`.`questions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_assertions`
    FOREIGN KEY (`assertion_id`)
    REFERENCES `baquiz`.`assertions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_answers`
    FOREIGN KEY (`answer_id`)
    REFERENCES `baquiz`.`answers` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`subject_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`subject_user` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`subject_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `subject_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `rating` DECIMAL(12,2) NULL COMMENT 'Save the total rating of the user on a specific subject.',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_subjectuser_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_subjectuser_subjects_idx` (`subject_id` ASC) VISIBLE,
  INDEX `fk_subjectuser_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_subjectuser_subjects`
    FOREIGN KEY (`subject_id`)
    REFERENCES `baquiz`.`subjects` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_subjectuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
COMMENT = 'Display a total rating of a member';


-- -----------------------------------------------------
-- Table `baquiz`.`promo_codes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`promo_codes` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`promo_codes` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `code` VARCHAR(45) NOT NULL,
  `validity` INT NOT NULL,
  `status` ENUM('active', 'expired') NOT NULL DEFAULT 'expired',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_promocodes_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_promocodes_users_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_promocodes_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_promocodes_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`blocked_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`blocked_users` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`blocked_users` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `complaint` LONGTEXT NULL,
  `is_unlocked` TINYINT NOT NULL DEFAULT 0,
  `reason_uuid` CHAR(36) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_blockedusers_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_blockedusers_users_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_blockedusers_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_blockedusers_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`clash_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`clash_user` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`clash_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `clash_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `participated` TINYINT NOT NULL DEFAULT 0,
  `reaction` ENUM('like', 'funny', 'difficult', 'informative', 'perfect') NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_clashuser_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_clashuser_clashs_idx` (`clash_id` ASC) VISIBLE,
  INDEX `fk_clashuser_users_idx` (`user_id` ASC) INVISIBLE,
  CONSTRAINT `fk_clashuser_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `baquiz`.`clashs` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_clashuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`money_transfers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`money_transfers` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`money_transfers` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `has_commission` TINYINT NOT NULL DEFAULT 0,
  `commission_amount` DECIMAL(12,2) NULL,
  `status` ENUM('done', 'failed') NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `payment_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_moneytransfers_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_moneytransfers_payments_idx` (`payment_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_moneytransfers_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_moneytransfers_payments`
    FOREIGN KEY (`payment_id`)
    REFERENCES `baquiz`.`payments` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
COMMENT = 'Manage user money transfers';


-- -----------------------------------------------------
-- Table `baquiz`.`pricings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`pricings` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`pricings` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `pricing_name` JSON NOT NULL,
  `pricing_type` ENUM('money', 'percentage') NOT NULL DEFAULT 'money' COMMENT 'The user must pay directly or pay a commission (percentage) on the payment they receive',
  `reason` ENUM('clash_create', 'clash_participate', 'clash_boost', 'user_certfied', 'ad') NULL,
  `pricing_cost` DECIMAL(12,2) NULL COMMENT 'The cost is always in USD.',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_pricings_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_pricings_UNIQUE` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`pricing_descriptions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`pricing_descriptions` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`pricing_descriptions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `description_title` JSON NOT NULL,
  `description_content` JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  `pricing_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_pricingdescriptions_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_pricingdescriptions_pricings_idx` (`pricing_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_pricingdescriptions_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_pricingdescriptions_pricings`
    FOREIGN KEY (`pricing_id`)
    REFERENCES `baquiz`.`pricings` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`medals`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`medals` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`medals` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `medal_type` ENUM('elite', 'prestige', 'ultima') NOT NULL,
  `medal_color` VARCHAR(45) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_medals_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_medals_UNIQUE` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`medal_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`medal_user` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`medal_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `medal_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `clash_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_medaluser_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_medaluser_medals_idx` (`medal_id` ASC) VISIBLE,
  INDEX `fk_medaluser_users_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_medaluser_clashs_idx` (`clash_id` ASC) VISIBLE,
  CONSTRAINT `fk_medaluser_medals`
    FOREIGN KEY (`medal_id`)
    REFERENCES `baquiz`.`medals` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_medaluser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_medaluser_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `baquiz`.`clashs` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`histories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`histories` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`histories` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `word` TEXT NULL COMMENT 'This refers to a search history of a user',
  `entity` ENUM('clash', 'course', 'subject', 'user') NULL,
  `entity_id` BIGINT NULL,
  `action` ENUM('search', 'view', 'reaction', 'comment', 'report') NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_histories_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_histories_users_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_histories_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_histories_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`subscriptions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`subscriptions` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`subscriptions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `follower_id` BIGINT NOT NULL,
  `granted` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_subscriptions_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_subscriptions_users1_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_subscriptions_users2_idx` (`follower_id` ASC) VISIBLE,
  CONSTRAINT `fk_subscriptions_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_subscriptions_users2`
    FOREIGN KEY (`follower_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`reasons`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`reasons` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`reasons` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `reason_content` JSON NOT NULL,
  `entity` ENUM('clash', 'subject', 'question', 'user') NOT NULL,
  `max_reports` INT NULL COMMENT 'Determine the maximum number of reports required to block a user.',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_reasons_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_reasons_UNIQUE` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`reports`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`reports` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`reports` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `entity` ENUM('clash', 'course', 'subject', 'question', 'user') NULL,
  `entity_id` BIGINT NULL,
  `report_content` TEXT NULL,
  `muted` TINYINT NOT NULL DEFAULT 0 COMMENT 'This is not a report, just a mute',
  `for_user_id` BIGINT NULL COMMENT 'When a member muted something, if he does so for a specific user, this column will be useful.',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reason_id` BIGINT NULL,
  `user_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_reports_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_reports_reasons_idx` (`reason_id` ASC) VISIBLE,
  INDEX `fk_reports_users_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_reports_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_reports_reasons`
    FOREIGN KEY (`reason_id`)
    REFERENCES `baquiz`.`reasons` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_reports_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`hashtags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`hashtags` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`hashtags` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `keyword` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtags_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`hashtag_clash`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`hashtag_clash` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`hashtag_clash` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `clash_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagclash_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_hashtagclash_hashtags_idx` (`hashtag_id` ASC) VISIBLE,
  INDEX `fk_hashtagclash_clashs_idx` (`clash_id` ASC) VISIBLE,
  CONSTRAINT `fk_hashtagclash_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `baquiz`.`hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtagclash_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `baquiz`.`clashs` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`assertion_answer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`assertion_answer` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`assertion_answer` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `assertion_id` BIGINT NOT NULL,
  `answer_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_assertionanswer_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_assertionanswer_assertions_idx` (`assertion_id` ASC) VISIBLE,
  INDEX `fk_assertionanswer_answers_idx` (`answer_id` ASC) VISIBLE,
  CONSTRAINT `fk_assertionanswer_assertions`
    FOREIGN KEY (`assertion_id`)
    REFERENCES `baquiz`.`assertions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_assertionanswer_answers`
    FOREIGN KEY (`answer_id`)
    REFERENCES `baquiz`.`answers` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
COMMENT = 'Some questions may require a single assertion, while others may require several';


-- -----------------------------------------------------
-- Table `baquiz`.`competences`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`competences` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`competences` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `competence_name` JSON NOT NULL,
  `competence_description` JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_competences_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_competences_UNIQUE` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`recommendations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`recommendations` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`recommendations` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `recommendation_content` LONGTEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `domain_id` BIGINT NULL,
  `competence_id` BIGINT NULL,
  `level_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_recommendations_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_recommendations_domains_idx` (`domain_id` ASC) VISIBLE,
  INDEX `fk_recommendations_competences_idx` (`competence_id` ASC) VISIBLE,
  INDEX `fk_recommendations_levels_idx` (`level_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_recommendations_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_recommendations_domains`
    FOREIGN KEY (`domain_id`)
    REFERENCES `baquiz`.`domains` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_recommendations_competences`
    FOREIGN KEY (`competence_id`)
    REFERENCES `baquiz`.`competences` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_recommendations_levels`
    FOREIGN KEY (`level_id`)
    REFERENCES `baquiz`.`levels` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`competence_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`competence_user` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`competence_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `competence_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `score` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `domain_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_competenceuser_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_competenceuser_competences_idx` (`competence_id` ASC) VISIBLE,
  INDEX `fk_competenceuser_users_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_competenceuser_domains_idx` (`domain_id` ASC) VISIBLE,
  CONSTRAINT `fk_competenceuser_competences`
    FOREIGN KEY (`competence_id`)
    REFERENCES `baquiz`.`competences` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_competenceuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_competenceuser_domains`
    FOREIGN KEY (`domain_id`)
    REFERENCES `baquiz`.`domains` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
COMMENT = 'When a member selects a field, they must indicate their level and competence';


-- -----------------------------------------------------
-- Table `baquiz`.`circle_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`circle_user` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`circle_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `circle_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `is_admin` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_circleuser_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_circleuser_circles_idx` (`circle_id` ASC) VISIBLE,
  INDEX `fk_circleuser_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_circleuser_circles`
    FOREIGN KEY (`circle_id`)
    REFERENCES `baquiz`.`circles` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_circleuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`cache`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`cache` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  INDEX `cache_expiration_index` (`expiration` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`cache_locks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`cache_locks` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  INDEX `cache_locks_expiration_index` (`expiration` ASC) INVISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`failed_jobs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`failed_jobs` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `failed_jobs_uuid_unique` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`jobs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`jobs` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `jobs_queue_index` (`queue` ASC) INVISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`job_batches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`job_batches` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`account_switches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`account_switches` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`account_switches` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `session_id` VARCHAR(255) NULL,
  `latitude` DECIMAL(10,7) NULL,
  `longitude` DECIMAL(10,7) NULL,
  `city` VARCHAR(255) NULL,
  `region` VARCHAR(255) NULL,
  `country` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `from_user_id` BIGINT NOT NULL,
  `to_user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_accountswitches_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_accountswitches_users1_idx` (`from_user_id` ASC) VISIBLE,
  INDEX `fk_accountswitches_users2_idx` (`to_user_id` ASC) VISIBLE,
  CONSTRAINT `fk_accountswitches_users1`
    FOREIGN KEY (`from_user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_accountswitches_users2`
    FOREIGN KEY (`to_user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`hashtag_comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`hashtag_comment` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`hashtag_comment` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `comment_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagcomment_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_hashtagcomment_hashtags_idx` (`hashtag_id` ASC) VISIBLE,
  INDEX `fk_hashtagcomment_comments_idx` (`comment_id` ASC) INVISIBLE,
  CONSTRAINT `fk_hashtag_comment_hashtags1`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `baquiz`.`hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtag_comment_comments1`
    FOREIGN KEY (`comment_id`)
    REFERENCES `baquiz`.`comments` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`hashtag_message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`hashtag_message` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`hashtag_message` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `message_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagmessage_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_hashtagmessage_hashtags_idx` (`hashtag_id` ASC) VISIBLE,
  INDEX `fk_hashtagmessage_messages_idx` (`message_id` ASC) INVISIBLE,
  CONSTRAINT `fk_hashtagmessage_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `baquiz`.`hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtagmessage_messages`
    FOREIGN KEY (`message_id`)
    REFERENCES `baquiz`.`messages` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`hashtag_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`hashtag_question` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`hashtag_question` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `question_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagquestion_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_hashtagquestion_hashtags_idx` (`hashtag_id` ASC) VISIBLE,
  INDEX `fk_hashtagquestion_questions_idx` (`question_id` ASC) VISIBLE,
  CONSTRAINT `fk_hashtagquestion_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `baquiz`.`hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtagquestion_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `baquiz`.`questions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`hashtag_assertion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`hashtag_assertion` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`hashtag_assertion` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `assertion_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagassertion_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_hashtagassertion_hashtags_idx` (`hashtag_id` ASC) VISIBLE,
  INDEX `fk_hashtagassertion_assertions_idx` (`assertion_id` ASC) VISIBLE,
  CONSTRAINT `fk_hashtagassertion_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `baquiz`.`hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtagassertion_assertions`
    FOREIGN KEY (`assertion_id`)
    REFERENCES `baquiz`.`assertions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`hashtag_answer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`hashtag_answer` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`hashtag_answer` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `answer_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_hashtaganswer_hashtags_idx` (`hashtag_id` ASC) VISIBLE,
  INDEX `fk_hashtaganswer_answers_idx` (`answer_id` ASC) VISIBLE,
  CONSTRAINT `fk_hashtaganswer_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `baquiz`.`hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtaganswer_answers`
    FOREIGN KEY (`answer_id`)
    REFERENCES `baquiz`.`answers` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`pollchoices`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`pollchoices` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`pollchoices` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `choice_content` TEXT NOT NULL,
  `image_url` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_pollchoices_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_pollchoices_messages_idx` (`message_id` ASC) VISIBLE,
  UNIQUE INDEX `uuid_pollchoices_UNIQUE` (`uuid` ASC) VISIBLE,
  CONSTRAINT `fk_pollchoices_messages`
    FOREIGN KEY (`message_id`)
    REFERENCES `baquiz`.`messages` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`pollchoice_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`pollchoice_user` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`pollchoice_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `pollchoice_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_pollchoiceuser_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_pollchoiceuser_pollchoices_idx` (`pollchoice_id` ASC) VISIBLE,
  INDEX `fk_pollchoiceuser_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_pollchoiceuser_pollchoices`
    FOREIGN KEY (`pollchoice_id`)
    REFERENCES `baquiz`.`pollchoices` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_pollchoiceuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`ai_conversations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`ai_conversations` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`ai_conversations` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `assistant` VARCHAR(50) NOT NULL,
  `system_prompt` LONGTEXT NULL,
  `last_message_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `archived_at` TIMESTAMP NULL,
  `user_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_aiconversations_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_aiconversations_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_aiconversations_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`ai_messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`ai_messages` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`ai_messages` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `role` ENUM('system', 'user', 'assistant', 'tool') NOT NULL,
  `content` LONGTEXT NOT NULL,
  `model` VARCHAR(100) NULL,
  `prompt_tokens` INT UNSIGNED NULL,
  `completion_tokens` INT UNSIGNED NULL,
  `total_tokens` INT UNSIGNED NULL,
  `response_time_ms` INT UNSIGNED NULL,
  `error_message` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `conversation_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_aimessages_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_aimessages_aiconversations_idx` (`conversation_id` ASC) VISIBLE,
  CONSTRAINT `fk_aimessages_aiconversations`
    FOREIGN KEY (`conversation_id`)
    REFERENCES `baquiz`.`ai_conversations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`ai_message_files`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`ai_message_files` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`ai_message_files` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_aimessagefiles_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_ai_message_files_files1_idx` (`file_id` ASC) VISIBLE,
  CONSTRAINT `fk_ai_message_files_files1`
    FOREIGN KEY (`file_id`)
    REFERENCES `baquiz`.`files` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`ai_tool_calls`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`ai_tool_calls` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`ai_tool_calls` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `tool_name` VARCHAR(100) NOT NULL,
  `arguments` JSON NULL,
  `response` JSON NULL,
  `status` ENUM('pending', 'success', 'failed') NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ai_message_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_aitoolcalls_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_aitoolcalls_aimessages_idx` (`ai_message_id` ASC) VISIBLE,
  CONSTRAINT `fk_aitoolcalls_aimessages`
    FOREIGN KEY (`ai_message_id`)
    REFERENCES `baquiz`.`ai_messages` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`ai_settings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`ai_settings` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`ai_settings` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `provider` VARCHAR(50) NOT NULL,
  `model` VARCHAR(100) NOT NULL,
  `temperature` DECIMAL(3,2) NOT NULL,
  `max_tokens` INT UNSIGNED NOT NULL,
  `stream` TINYINT NOT NULL,
  `enabled` TINYINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idai_settings_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `baquiz`.`websites`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `baquiz`.`websites` ;

CREATE TABLE IF NOT EXISTS `baquiz`.`websites` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `website_name` VARCHAR(255) NOT NULL,
  `website_url` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_websites_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_websites_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_websites_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `baquiz`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
