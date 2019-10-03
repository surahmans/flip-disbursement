<?php

require_once  __DIR__ . '/../bootstrap/app.php';

try {
    $query = $app->conn->prepare("
        SET FOREIGN_KEY_CHECKS = 0;
        DROP TABLE IF EXISTS `disbursements`;
        CREATE TABLE `disbursements` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `bank_code` VARCHAR(25) NOT NULL,
            `account_number` VARCHAR(25) NOT NULL,
            `remark` VARCHAR(100) NOT NULL,
            `created_at` DATETIME NULL DEFAULT NOW(),
            PRIMARY KEY (`id`)
        )
        COLLATE='latin1_swedish_ci';

        DROP TABLE IF EXISTS `disbursement_responses`;
        CREATE TABLE `disbursement_responses` (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `disbursement_id` BIGINT UNSIGNED NOT NULL,
            `transaction_id` BIGINT UNSIGNED NOT NULL,
            `amount` DECIMAL(10,0) UNSIGNED NOT NULL,
            `timestamp` DATETIME NOT NULL,
            `bank_code` VARCHAR(25) NOT NULL,
            `account_number` VARCHAR(25) NOT NULL,
            `beneficiary_name` VARCHAR(50) NOT NULL,
            `remark` VARCHAR(100) NOT NULL,
            `receipt` VARCHAR(255) NULL DEFAULT NULL,
            `time_served` DATETIME NULL DEFAULT NULL,
            `fee` DECIMAL(5,0) NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            CONSTRAINT `disbursement_id` FOREIGN KEY (`disbursement_id`) REFERENCES `disbursements` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        )
        COLLATE='latin1_swedish_ci';
        SET FOREIGN_KEY_CHECKS = 1;
    ");

    $query->execute();
} catch (PDOException $e) {
    die($e->getMessage());
}

echo 'Migration done...';

