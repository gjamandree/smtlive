SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `users_types`;
CREATE TABLE `esabong`.`users_types`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_type_name` varchar(255) NOT NULL,
  `date_created` datetime(0) DEFAULT NULL,
  `date_modified` datetime(0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `esabong`.`users`  (
  `id` int(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NULL,
  `username` varchar(255) NULL,
  `password` varchar(255) NULL,
  `firstname` varchar(255) NULL,
  `lastname` varchar(255) NULL,
  `user_type_id` int(11) UNSIGNED NOT NULL,
  `date_created` datetime(0) DEFAULT NULL,
  `date_modified` datetime(0) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `ut_user_type_id` FOREIGN KEY (`user_type_id`) REFERENCES `esabong`.`users_types` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
) ENGINE = InnoDB;
SET FOREIGN_KEY_CHECKS = 1;