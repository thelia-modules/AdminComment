
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- admin_comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `admin_comment`;

CREATE TABLE `admin_comment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `admin_id` INTEGER,
    `comment` TEXT,
    `element_key` VARCHAR(255) NOT NULL,
    `element_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `FI_admin_comment_admin_id` (`admin_id`),
    CONSTRAINT `fk_admin_comment_admin_id`
        FOREIGN KEY (`admin_id`)
        REFERENCES `admin` (`id`)
        ON UPDATE RESTRICT
        ON DELETE SET NULL
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
