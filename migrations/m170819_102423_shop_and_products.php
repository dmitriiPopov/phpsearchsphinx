<?php

use yii\db\Migration;

/**
 * Class m170819_102423_shop_and_products
 */
class m170819_102423_shop_and_products extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->execute("
            CREATE TABLE `shop` (
              `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(127) NOT NULL DEFAULT '',
              `filename_real` VARCHAR(511) NOT NULL DEFAULT '',
              `filename` VARCHAR(511) NOT NULL DEFAULT '',
              `file_csv_column_separator` CHAR(4) NOT NULL,
              `status` ENUM('new', 'handled', 'disabled') NOT NULL DEFAULT 'new',
              `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` TIMESTAMP NULL DEFAULT NULL,

              PRIMARY KEY (`id`),
              INDEX `index2` (`status` ASC));
        ");

        $this->execute("
            CREATE TABLE `shop_products` (
              `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              `shop_id` INT(11) UNSIGNED NOT NULL,
              `name` VARCHAR(255) NULL DEFAULT NULL,
              `url` VARCHAR(511) NULL DEFAULT NULL,
              `categoryId` VARCHAR(255) NULL DEFAULT NULL,
              `price` VARCHAR(127) NULL DEFAULT NULL,
              `picture` VARCHAR(511) NULL DEFAULT NULL,
              `description` TEXT NULL DEFAULT NULL,
              PRIMARY KEY (`id`));

            ALTER TABLE `shop_products`
            ADD INDEX `fk_shop_products_1_idx` (`shop_id` ASC);
            ALTER TABLE `deshevshe`.`shop_products`
            ADD CONSTRAINT `fk_shop_products_1`
              FOREIGN KEY (`shop_id`)
              REFERENCES `deshevshe`.`shop` (`id`)
              ON DELETE CASCADE
              ON UPDATE CASCADE;
        ");

//        $this->execute("
//            ALTER TABLE `shop_products`
//            ADD COLUMN `status` ENUM('disabled', 'new', 'handled') NOT NULL DEFAULT 'new' AFTER `description`;
//        ");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS `shop_products`");

        $this->execute("DROP TABLE IF EXISTS  `shop`");
    }

}
