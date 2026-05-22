-- -----------------------------------------------------
-- Schema baquiz
--
-- Datamodel for the "Baquiz" platform
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(255) NULL,
  `lastname` VARCHAR(255) NULL,
  `surname` VARCHAR(255) NULL,
  `organization_name` VARCHAR(255) NULL COMMENT 'If the user is an organization',
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
  `belongs_to` BIGINT NULL,
  `promo_code` VARCHAR(45) NULL,
  `two_factor_secret` TEXT NULL,
  `two_factor_recovery_codes` TEXT NULL,
  `two_factor_email_confirmed_at` TIMESTAMP NULL,
  `two_factor_phone_confirmed_at` TIMESTAMP NULL,
  `tips_at_every_login` TINYINT NOT NULL DEFAULT 1,
  `is_online` TINYINT NOT NULL DEFAULT 1,
  `status` ENUM('created', 'activated', 'disabled', 'blocked', 'deleted') NOT NULL DEFAULT 'created',
  `type` ENUM('uncertified', 'certified') NOT NULL DEFAULT 'uncertified',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_users_UNIQUE` (`id` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `phone_UNIQUE` (`phone` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
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
  UNIQUE INDEX `id_roles_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `role_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `role_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `is_selected` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_roleuser_UNIQUE` (`id` ASC),
  INDEX `fk_roleuser_roles_idx` (`role_id` ASC),
  INDEX `fk_roleuser_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_roleuser_roles`
    FOREIGN KEY (`role_id`)
    REFERENCES `roles` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_roleuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `password_resets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(45) NULL,
  `token` VARCHAR(45) NULL,
  `former_password` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_passwordresets_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `personal_access_tokens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
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
  UNIQUE INDEX `id_personalaccesstokens_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `payments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
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
  UNIQUE INDEX `id_payments_UNIQUE` (`id` ASC),
  INDEX `fk_payments_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_payments_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sessions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sessions` (
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
  UNIQUE INDEX `id_sessions_UNIQUE` (`id` ASC),
  INDEX `fk_sessions_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_sessions_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levels`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `levels` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
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
  UNIQUE INDEX `id_levels_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fields`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fields` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
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
  UNIQUE INDEX `id_fields_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `clashs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `clashs` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `clash_code` TEXT NULL,
  `clash_description` LONGTEXT NULL,
  `start_at` DATETIME NULL,
  `end_at` DATETIME NULL,
  `price` DECIMAL(12,2) NULL,
  `type` ENUM('public', 'private') NOT NULL DEFAULT 'public',
  `last_boost_at` TIMESTAMP NULL,
  `boost_type` ENUM('daily', 'weekly', 'monthly', 'yearly') NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `field_id` BIGINT NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_clashs_UNIQUE` (`id` ASC),
  INDEX `fk_clashs_fields_idx` (`field_id` ASC),
  INDEX `fk_clashs_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_clashs_fields`
    FOREIGN KEY (`field_id`)
    REFERENCES `fields` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_clashs_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `subjects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
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
  UNIQUE INDEX `id_subjects_UNIQUE` (`id` ASC),
  INDEX `fk_subjects_levels_idx` (`level_id` ASC),
  INDEX `fk_subjects_clashs_idx` (`clash_id` ASC),
  CONSTRAINT `fk_subjects_levels`
    FOREIGN KEY (`level_id`)
    REFERENCES `levels` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_subjects_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `clashs` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `domains`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `domains` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `domain_name` JSON NOT NULL,
  `domain_description` JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_domains_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `questions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
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
  UNIQUE INDEX `id_questions_UNIQUE` (`id` ASC),
  INDEX `fk_questions_subjects_idx` (`subject_id` ASC),
  INDEX `fk_questions_domains_idx` (`domain_id` ASC),
  CONSTRAINT `fk_questions_subjects`
    FOREIGN KEY (`subject_id`)
    REFERENCES `subjects` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_questions_domains`
    FOREIGN KEY (`domain_id`)
    REFERENCES `domains` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `assertions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `assertions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `assertion_content` LONGTEXT NOT NULL,
  `is_real_answer` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `question_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_assertions_UNIQUE` (`id` ASC),
  INDEX `fk_assertions_questions_idx` (`question_id` ASC),
  CONSTRAINT `fk_assertions_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `questions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `answers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `answers` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `answer_content` LONGTEXT NULL,
  `time_taken` INT NULL COMMENT 'Time in seconds',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `question_id` BIGINT NOT NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_answers_UNIQUE` (`id` ASC),
  INDEX `fk_answers_questions_idx` (`question_id` ASC),
  INDEX `fk_answers_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_answers_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `questions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_answers_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comments` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `comment_content` LONGTEXT NULL,
  `answered_for` BIGINT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `clash_id` BIGINT NOT NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_comments_UNIQUE` (`id` ASC),
  INDEX `fk_comments_clashs_idx` (`clash_id` ASC),
  INDEX `fk_comments_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_comments_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `clashs` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comments_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `circles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `circles` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `circle_name` TEXT NOT NULL,
  `profile_photo` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_circles_UNIQUE` (`id` ASC),
  INDEX `fk_circles_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_circles_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `message_content` LONGTEXT NULL,
  `answered_for` BIGINT NULL,
  `status` ENUM('read', 'unread') NOT NULL DEFAULT 'unread',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NULL,
  `addressee_user_id` BIGINT NULL,
  `addressee_circle_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_messages_UNIQUE` (`id` ASC),
  INDEX `fk_messages_users_1_idx` (`user_id` ASC),
  INDEX `fk_messages_users_2_idx` (`addressee_user_id` ASC),
  INDEX `fk_messages_circles_idx` (`addressee_circle_id` ASC),
  CONSTRAINT `fk_messages_users_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_users_2`
    FOREIGN KEY (`addressee_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_circles`
    FOREIGN KEY (`addressee_circle_id`)
    REFERENCES `circles` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `files` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `file_name` VARCHAR(255) NULL,
  `file_url` TEXT NOT NULL,
  `file_description` LONGTEXT NULL COMMENT 'This might be useful for describing advertisements, for example',
  `file_type` ENUM('video', 'photo', 'audio', 'document', 'id_card', 'ad', 'qr_code') NOT NULL DEFAULT 'photo',
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
  UNIQUE INDEX `id_files_UNIQUE` (`id` ASC),
  INDEX `fk_files_questions_idx` (`question_id` ASC),
  INDEX `fk_files_assertions_idx` (`assertion_id` ASC),
  INDEX `fk_files_answers_idx` (`answer_id` ASC),
  INDEX `fk_files_clashs_idx` (`clash_id` ASC),
  INDEX `fk_files_users_idx` (`user_id` ASC),
  INDEX `fk_files_subjects_idx` (`subject_id` ASC),
  INDEX `fk_files_fields_idx` (`field_id` ASC),
  INDEX `fk_files_comments_idx` (`comment_id` ASC),
  INDEX `fk_files_domains_idx` (`domain_id` ASC),
  INDEX `fk_files_messages_idx` (`message_id` ASC),
  CONSTRAINT `fk_files_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `questions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_assertions`
    FOREIGN KEY (`assertion_id`)
    REFERENCES `assertions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_answers`
    FOREIGN KEY (`answer_id`)
    REFERENCES `answers` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `clashs` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_subjects`
    FOREIGN KEY (`subject_id`)
    REFERENCES `subjects` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_fields`
    FOREIGN KEY (`field_id`)
    REFERENCES `fields` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_comments`
    FOREIGN KEY (`comment_id`)
    REFERENCES `comments` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_domains`
    FOREIGN KEY (`domain_id`)
    REFERENCES `domains` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_files_messages`
    FOREIGN KEY (`message_id`)
    REFERENCES `messages` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `notifications`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
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
  UNIQUE INDEX `id_notifications_UNIQUE` (`id` ASC),
  INDEX `fk_notifications_users1_idx` (`from_user_id` ASC),
  INDEX `fk_notifications_users2_idx` (`to_user_id` ASC),
  INDEX `fk_notifications_clashs_idx` (`clash_id` ASC),
  INDEX `fk_notifications_comments_idx` (`comment_id` ASC),
  INDEX `fk_notifications_messages_idx` (`message_id` ASC),
  INDEX `fk_notifications_questions_idx` (`question_id` ASC),
  INDEX `fk_notifications_assertions_idx` (`assertion_id` ASC),
  INDEX `fk_notifications_answers_idx` (`answer_id` ASC),
  CONSTRAINT `fk_notifications_users1`
    FOREIGN KEY (`from_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_users2`
    FOREIGN KEY (`to_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `clashs` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_comments`
    FOREIGN KEY (`comment_id`)
    REFERENCES `comments` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_messages`
    FOREIGN KEY (`message_id`)
    REFERENCES `messages` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `questions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_assertions`
    FOREIGN KEY (`assertion_id`)
    REFERENCES `assertions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notifications_answers`
    FOREIGN KEY (`answer_id`)
    REFERENCES `answers` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `subject_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `subject_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `subject_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `rating` DECIMAL(12,2) NULL COMMENT 'Save the total rating of the user on a specific subject.',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_subjectuser_UNIQUE` (`id` ASC),
  INDEX `fk_subjectuser_subjects_idx` (`subject_id` ASC),
  INDEX `fk_subjectuser_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_subjectuser_subjects`
    FOREIGN KEY (`subject_id`)
    REFERENCES `subjects` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_subjectuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
COMMENT = 'Display a total rating of a member';


-- -----------------------------------------------------
-- Table `promo_codes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `promo_codes` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(45) NOT NULL,
  `validity` INT NOT NULL,
  `status` ENUM('active', 'expired') NOT NULL DEFAULT 'expired',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_promocodes_UNIQUE` (`id` ASC),
  INDEX `fk_promocodes_users_idx` (`user_id` ASC),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC),
  CONSTRAINT `fk_promocodes_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `about_subjects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `about_subjects` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `subject` JSON NULL,
  `subject_description` JSON NOT NULL,
  `status` ENUM('selected', 'rejected') NOT NULL DEFAULT 'rejected',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_aboutsubjects_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `about_titles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `about_titles` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `title` JSON NOT NULL,
  `alias` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  `about_subject_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_abouttitles_UNIQUE` (`id` ASC),
  INDEX `fk_abouttitles_aboutsubjects_idx` (`about_subject_id` ASC),
  CONSTRAINT `fk_abouttitles_aboutsubjects`
    FOREIGN KEY (`about_subject_id`)
    REFERENCES `about_subjects` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blocked_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blocked_users` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `complaint` LONGTEXT NULL,
  `is_unlocked` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NOT NULL,
  `about_title_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_blockedusers_UNIQUE` (`id` ASC),
  INDEX `fk_blockedusers_users_idx` (`user_id` ASC),
  INDEX `fk_blockedusers_abouttitles_idx` (`about_title_id` ASC),
  CONSTRAINT `fk_blockedusers_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_blockedusers_abouttitles`
    FOREIGN KEY (`about_title_id`)
    REFERENCES `about_titles` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `about_contents`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `about_contents` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `subtitle` JSON NULL,
  `content` JSON NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  `about_title_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_aboutcontents_UNIQUE` (`id` ASC),
  INDEX `fk_aboutcontents_abouttitles_idx` (`about_title_id` ASC),
  CONSTRAINT `fk_aboutcontents_abouttitles`
    FOREIGN KEY (`about_title_id`)
    REFERENCES `about_titles` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `clash_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `clash_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `clash_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `participated` TINYINT NOT NULL DEFAULT 0,
  `reaction` ENUM('like', 'funny', 'difficult', 'informative', 'perfect') NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_clashuser_UNIQUE` (`id` ASC),
  INDEX `fk_clashuser_clashs_idx` (`clash_id` ASC),
  INDEX `fk_clashuser_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_clashuser_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `clashs` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_clashuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `money_transfers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `money_transfers` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `has_commission` TINYINT NOT NULL DEFAULT 0,
  `commission_amount` DECIMAL(12,2) NULL,
  `status` ENUM('done', 'failed') NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `payment_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_moneytransfers_UNIQUE` (`id` ASC),
  INDEX `fk_moneytransfers_payments_idx` (`payment_id` ASC),
  CONSTRAINT `fk_moneytransfers_payments`
    FOREIGN KEY (`payment_id`)
    REFERENCES `payments` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
COMMENT = 'Manage user money transfers';


-- -----------------------------------------------------
-- Table `pricings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pricings` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `pricing_name` JSON NOT NULL,
  `pricing_type` ENUM('money', 'percentage') NOT NULL DEFAULT 'money' COMMENT 'The user must pay directly or pay a commission (percentage) on the payment they receive',
  `reason` ENUM('clash_create', 'clash_participate', 'clash_boost', 'user_certfied', 'ad') NULL,
  `pricing_cost` DECIMAL(12,2) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_pricings_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pricing_descriptions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pricing_descriptions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
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
  UNIQUE INDEX `id_pricingdescriptions_UNIQUE` (`id` ASC),
  INDEX `fk_pricingdescriptions_pricings_idx` (`pricing_id` ASC),
  CONSTRAINT `fk_pricingdescriptions_pricings`
    FOREIGN KEY (`pricing_id`)
    REFERENCES `pricings` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `medals`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `medals` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `medal_type` ENUM('elite', 'prestige', 'ultima') NOT NULL,
  `medal_color` VARCHAR(45) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_medals_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `medal_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `medal_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `medal_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_medaluser_UNIQUE` (`id` ASC),
  INDEX `fk_medaluser_medals_idx` (`medal_id` ASC),
  INDEX `fk_medaluser_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_medaluser_medals`
    FOREIGN KEY (`medal_id`)
    REFERENCES `medals` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_medaluser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `histories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `histories` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `word` TEXT NULL COMMENT 'This refers to a search history of a user',
  `entity` ENUM('clash', 'course', 'subject', 'user') NULL,
  `entity_id` BIGINT NULL,
  `action` ENUM('search', 'view', 'reaction', 'comment', 'report') NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_histories_UNIQUE` (`id` ASC),
  INDEX `fk_histories_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_histories_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `subscriptions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `follower_id` BIGINT NOT NULL,
  `granted` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_subscriptions_UNIQUE` (`id` ASC),
  INDEX `fk_subscriptions_users1_idx` (`user_id` ASC),
  INDEX `fk_subscriptions_users2_idx` (`follower_id` ASC),
  CONSTRAINT `fk_subscriptions_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_subscriptions_users2`
    FOREIGN KEY (`follower_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reasons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reasons` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `reason_content` JSON NOT NULL,
  `entity` ENUM('clash', 'course', 'subject', 'question', 'user') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idreasons_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reports`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reports` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
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
  UNIQUE INDEX `id_reports_UNIQUE` (`id` ASC),
  INDEX `fk_reports_reasons_idx` (`reason_id` ASC),
  INDEX `fk_reports_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_reports_reasons`
    FOREIGN KEY (`reason_id`)
    REFERENCES `reasons` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_reports_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hashtags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hashtags` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `keyword` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtags_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hashtag_clash`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hashtag_clash` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `clash_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagclash_UNIQUE` (`id` ASC),
  INDEX `fk_hashtagclash_hashtags_idx` (`hashtag_id` ASC),
  INDEX `fk_hashtagclash_clashs_idx` (`clash_id` ASC),
  CONSTRAINT `fk_hashtagclash_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtagclash_clashs`
    FOREIGN KEY (`clash_id`)
    REFERENCES `clashs` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `assertion_answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `assertion_answer` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `assertion_id` BIGINT NOT NULL,
  `answer_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_assertionanswer_UNIQUE` (`id` ASC),
  INDEX `fk_assertionanswer_assertions_idx` (`assertion_id` ASC),
  INDEX `fk_assertionanswer_answers_idx` (`answer_id` ASC),
  CONSTRAINT `fk_assertionanswer_assertions`
    FOREIGN KEY (`assertion_id`)
    REFERENCES `assertions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_assertionanswer_answers`
    FOREIGN KEY (`answer_id`)
    REFERENCES `answers` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
COMMENT = 'Some questions may require a single assertion, while others may require several';


-- -----------------------------------------------------
-- Table `competences`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `competences` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `competence_name` JSON NOT NULL,
  `competence_description` JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idcompetences_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `recommendations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `recommendations` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `recommendation_content` LONGTEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `domain_id` BIGINT NULL,
  `competence_id` BIGINT NULL,
  `level_id` BIGINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_recommendations_UNIQUE` (`id` ASC),
  INDEX `fk_recommendations_domains_idx` (`domain_id` ASC),
  INDEX `fk_recommendations_competences_idx` (`competence_id` ASC),
  INDEX `fk_recommendations_levels_idx` (`level_id` ASC),
  CONSTRAINT `fk_recommendations_domains`
    FOREIGN KEY (`domain_id`)
    REFERENCES `domains` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_recommendations_competences`
    FOREIGN KEY (`competence_id`)
    REFERENCES `competences` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_recommendations_levels`
    FOREIGN KEY (`level_id`)
    REFERENCES `levels` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `competence_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `competence_user` (
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
  UNIQUE INDEX `id_competenceuser_UNIQUE` (`id` ASC),
  INDEX `fk_competenceuser_competences_idx` (`competence_id` ASC),
  INDEX `fk_competenceuser_users_idx` (`user_id` ASC),
  INDEX `fk_competenceuser_domains_idx` (`domain_id` ASC),
  CONSTRAINT `fk_competenceuser_competences`
    FOREIGN KEY (`competence_id`)
    REFERENCES `competences` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_competenceuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_competenceuser_domains`
    FOREIGN KEY (`domain_id`)
    REFERENCES `domains` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
COMMENT = 'When a member selects a field, they must indicate their level and competence';


-- -----------------------------------------------------
-- Table `circle_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `circle_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `circle_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `is_admin` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_circleuser_UNIQUE` (`id` ASC),
  INDEX `fk_circleuser_circles_idx` (`circle_id` ASC),
  INDEX `fk_circleuser_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_circleuser_circles`
    FOREIGN KEY (`circle_id`)
    REFERENCES `circles` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_circleuser_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bank_cards`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bank_cards` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `card_name` VARCHAR(255) NULL,
  `card_number` VARCHAR(45) NULL,
  `expiration_date` VARCHAR(45) NULL,
  `cvv_code` VARCHAR(45) NULL,
  `provider` VARCHAR(45) NULL,
  `is_main` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `user_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_bankcards_UNIQUE` (`id` ASC),
  INDEX `fk_bankcards_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_bankcards_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `about_dashes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `about_dashes` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `dash_content` JSON NOT NULL,
  `belongs_to` BIGINT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  `created_by` BIGINT NULL,
  `updated_by` BIGINT NULL,
  `deleted_by` BIGINT NULL,
  `about_content_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_aboutdashes_UNIQUE` (`id` ASC),
  INDEX `fk_aboutdashes_aboutcontents_idx` (`about_content_id` ASC),
  CONSTRAINT `fk_aboutdashes_aboutcontents`
    FOREIGN KEY (`about_content_id`)
    REFERENCES `about_contents` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cache`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  INDEX `cache_expiration_index` (`expiration` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cache_locks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  INDEX `cache_locks_expiration_index` (`expiration` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `failed_jobs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `failed_jobs_uuid_unique` (`uuid` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jobs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `jobs_queue_index` (`queue` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `job_batches`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `job_batches` (
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
-- Table `account_switches`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `account_switches` (
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
  UNIQUE INDEX `id_accountswitches_UNIQUE` (`id` ASC),
  INDEX `fk_accountswitches_users1_idx` (`from_user_id` ASC),
  INDEX `fk_accountswitches_users2_idx` (`to_user_id` ASC),
  CONSTRAINT `fk_accountswitches_users1`
    FOREIGN KEY (`from_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_accountswitches_users2`
    FOREIGN KEY (`to_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hashtag_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hashtag_comment` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `comment_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagcomment_UNIQUE` (`id` ASC),
  INDEX `fk_hashtagcomment_hashtags_idx` (`hashtag_id` ASC),
  INDEX `fk_hashtagcomment_comments_idx` (`comment_id` ASC),
  CONSTRAINT `fk_hashtag_comment_hashtags1`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtag_comment_comments1`
    FOREIGN KEY (`comment_id`)
    REFERENCES `comments` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hashtag_message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hashtag_message` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `message_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagmessage_UNIQUE` (`id` ASC),
  INDEX `fk_hashtagmessage_hashtags_idx` (`hashtag_id` ASC),
  INDEX `fk_hashtagmessage_messages_idx` (`message_id` ASC),
  CONSTRAINT `fk_hashtagmessage_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtagmessage_messages`
    FOREIGN KEY (`message_id`)
    REFERENCES `messages` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hashtag_question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hashtag_question` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `question_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagquestion_UNIQUE` (`id` ASC),
  INDEX `fk_hashtagquestion_hashtags_idx` (`hashtag_id` ASC),
  INDEX `fk_hashtagquestion_questions_idx` (`question_id` ASC),
  CONSTRAINT `fk_hashtagquestion_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtagquestion_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `questions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hashtag_assertion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hashtag_assertion` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `assertion_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_hashtagassertion_UNIQUE` (`id` ASC),
  INDEX `fk_hashtagassertion_hashtags_idx` (`hashtag_id` ASC),
  INDEX `fk_hashtagassertion_assertions_idx` (`assertion_id` ASC),
  CONSTRAINT `fk_hashtagassertion_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtagassertion_assertions`
    FOREIGN KEY (`assertion_id`)
    REFERENCES `assertions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hashtag_answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hashtag_answer` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hashtag_id` BIGINT NOT NULL,
  `answer_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_hashtaganswer_hashtags_idx` (`hashtag_id` ASC),
  INDEX `fk_hashtaganswer_answers_idx` (`answer_id` ASC),
  CONSTRAINT `fk_hashtaganswer_hashtags`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `hashtags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hashtaganswer_answers`
    FOREIGN KEY (`answer_id`)
    REFERENCES `answers` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;
