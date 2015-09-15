CREATE TABLE IF NOT EXISTS `bounce` (
  `bounce_id` INT unsigned NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `site_id` INT unsigned NOT NULL,
  `email_id` INT unsigned NOT NULL,
  `user_id` INT unsigned NOT NULL,
  `case` VARCHAR(255) NOT NULL,
  `action_taken` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` INT unsigned NOT NULL,
  PRIMARY KEY (`bounce_id`),
  UNIQUE KEY `EMAIL_UNIQUE` (`email`)
) /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;