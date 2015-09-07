<?php

use yii\db\Schema;

class m150907_040101_init_adminlte_dynagrid extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $sql = <<<SQL
            CREATE TABLE `tbl_dynagrid_dtl` (
              `id` VARCHAR(100) NOT NULL COMMENT 'Unique dynagrid detail setting identifier',
              `category` VARCHAR(10) NOT NULL COMMENT 'Dynagrid detail setting category "filter" or "sort"',
              `name` VARCHAR(150) NOT NULL COMMENT 'Name to identify the dynagrid detail setting',
              `data` VARCHAR(5000) DEFAULT NULL COMMENT 'Json encoded data for the dynagrid detail configuration',
              `dynagrid_id` VARCHAR(100) NOT NULL COMMENT 'Related dynagrid identifier',
              PRIMARY KEY (`id`),
              UNIQUE KEY `tbl_dynagrid_dtl_UK1` (`name`,`category`,`dynagrid_id`)
            ) ENGINE=INNODB;

            CREATE TABLE `tbl_dynagrid` (
              `id` VARCHAR(100) NOT NULL COMMENT 'Unique dynagrid setting identifier',
              `filter_id` VARCHAR(100) DEFAULT NULL COMMENT 'Filter setting identifier',
              `sort_id` VARCHAR(100) DEFAULT NULL COMMENT 'Sort setting identifier',
              `data` VARCHAR(5000) DEFAULT NULL COMMENT 'Json encoded data for the dynagrid configuration',
              PRIMARY KEY (`id`),
              KEY `tbl_dynagrid_FK1` (`filter_id`),
              KEY `tbl_dynagrid_FK2` (`sort_id`),
              CONSTRAINT `tbl_dynagrid_FK1` FOREIGN KEY (`filter_id`) REFERENCES `tbl_dynagrid_dtl` (`id`),
              CONSTRAINT `tbl_dynagrid_FK2` FOREIGN KEY (`sort_id`) REFERENCES `tbl_dynagrid_dtl` (`id`)
            ) ENGINE=INNODB;


            CREATE TABLE `log_upload` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `userId` INT(11) DEFAULT NULL,
              `title` VARCHAR(128) NOT NULL,
              `filename` VARCHAR(255) DEFAULT NULL,
              `fileori` VARCHAR(255) DEFAULT NULL,
              `params` longblob,
              `values` longblob,
              `warning` longblob,
              `keys` TEXT,
              `type` TINYINT(1) DEFAULT NULL,
              `userCreate` INT(11) DEFAULT NULL,
              `userUpdate` INT(11) DEFAULT NULL,
              `updateDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `createDate` DATETIME DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=INNODB;
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $this->dropTable('tbl_dynagrid_dtl');
        $this->dropTable('tbl_dynagrid');
        $this->dropTable('log_upload');
    }
}
